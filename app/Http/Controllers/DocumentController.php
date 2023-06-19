<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use App\Models\Document;
use App\Models\ProcedureDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "マニュアル管理";
        $documents = Document::paginate(10);
        return view('documents.index', compact('documents', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "マニュアル新規登録";
        return view('documents.create', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function file()
    {
        $title = "マニュアル新規登録";
        return view('documents.file', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDocumentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumentRequest $request)
    {
        DB::beginTransaction();

        try {
            $last_number = Document::max('document_number');

            $documentdb = new DocumentService;
            $document_number = $documentdb->numbering($last_number);

            $approver_id = (Auth::user()->role !== 9) ? Auth::user()->employee_number : null;
            $is_visible = ($approver_id !== null) ? 0 : $request->input('is_visible');

            $documentData = [
                'document_number' => $document_number,
                'title' => $request->input('document_title'),
                'file_name' => $document_number . '.txt',
                'is_visible' => $is_visible,
                'approver_id' => $approver_id,
                'creator_id' => Auth::user()->employee_number,
            ];

            $document = Document::create($documentData);

            // テキストファイルに保存する
            $documentSV = new DocumentService;
            $documentSV->savaDocument($document_number, $request->input('document_details'));

            session()->flash('status', '登録完了');

            if (Auth::user()->role == 9) {
                $document->approvals()->create([
                    'creator_id' => Auth::user()->employee_number,
                    'approved' => 0, // 0：申請中、1：承認、2：否認、3：取り下げ
                    'approval_at' => null,
                ]);
                session()->flash('status', '登録が完了しました。承認までお待ちください。');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            session()->flash('alert', '登録エラー');
        }

        return (Auth::user()->role !== 9)
            ? redirect()->route('document.index')
            : redirect()->route('approval.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDocumentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function file_store(Request $request)
    {
        try {
            // ファイルがアップロードされたかどうかをチェックする
            if (!$request->hasFile('file')) {
                return redirect()->back()->withErrors(['file' => 'ファイルが選択されていません']);
            }

            $files = $request->file('file');
            $successCount = 0; // 成功したファイルの件数をカウントする変数

            foreach ($files as $file) {
                // 各ファイルを処理する
                $filenameWithExtension = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                // ファイルが .txt 拡張子を持っているかどうかをチェックする
                if (strtolower($extension) !== 'txt') {
                    // ファイルが .txt ファイルでない場合は、エラーメッセージを含めて元のページにリダイレクトする
                    return redirect()->back()->withErrors(['file' => 'テキストファイル以外のファイルが選択されています。']);
                }

                $content = file_get_contents($file->getRealPath());
                $filenameWithoutExtension = pathinfo($filenameWithExtension, PATHINFO_FILENAME);

                // テキストファイルの中身が空の場合はエラーメッセージを表示してリダイレクトする
                if (empty($content)) {
                    return redirect()->back()->withErrors(['file' => 'テキストファイルの中身が空白のファイルがあります。']);
                }

                $documentSV = new DocumentService;
                $last_number = Document::orderBy('id', 'desc')->value('document_number');

                $document_number = $documentSV->numbering($last_number);

                if (Auth::user()->role !== 9) {
                    $approver_id = Auth::user()->employee_number;
                    $request['is_visible'] = 0;
                } else {
                    $approver_id = null;
                }

                $documentData = [
                    'document_number' => $document_number,
                    'title' => $filenameWithoutExtension,
                    'file_name' => $filenameWithExtension,
                    'is_visible' => $request['is_visible'],
                    'approver_id' => $approver_id,
                    'creator_id' => Auth::user()->employee_number,
                ];

                // テキストファイルに保存する
                $documentSV->savaDocument($document_number, $content);

                if (Document::create($documentData)) {
                    $successCount++; // 成功したファイルの件数をカウントする
                }
            }

            if ($successCount > 0) {
                session()->flash('status', $successCount . '件のファイルが登録されました。');
            }
        } catch (\Exception $e) {
            session()->flash('alert', '登録エラー');
        }

        return redirect()->route('document.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $title = "検索結果";
        $search = $request->input('search');

        $search_list = [];
        $documentPage = $request->query('document_page', 1);

        if (!empty($search)) {
            $documents = Document::where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('document_number', 'like', '%' . $search . '%');
            })->paginate(10, ['*'], 'document_page', $documentPage);
            $documents->appends(['document_search' => $search]); // 検索条件をページネーションリンクに追加
            $search_list = $documents;
        }

        return view('documents.search', compact('title', 'search', 'search_list'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        $title = "マニュアル編集";

        // テキストファイルの内容を取得する
        $documentSV = new DocumentService;
        $fileContents = $documentSV->getContents($document->file_name);

        $procedures = Procedure::join('procedure_documents', 'procedures.id', '=', 'procedure_documents.procedure_id')
            ->where('procedure_documents.document_id', $document->id)
            ->select('procedures.id', 'procedures.name')
            ->get();

        return view('documents.edit', compact('document', 'fileContents', 'procedures', 'title'));
    }

    public function file_download($document)
    {
        $document = Document::findOrFail($document);
        $documentSV = new DocumentService;

        return $documentSV->downloadFile($document->file_name);
    }

    public function all_download()
    {
        $documents = Document::all();

        // ZIPファイルを作成
        $zip = new ZipArchive();
        $zipFileName = tempnam(sys_get_temp_dir(), 'documents') . '.zip';

        // ZIPファイルを開く
        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return response()->json(['error' => 'ZIPファイルを作成できませんでした。']);
        }

        // documentsディレクトリ内のテキストファイルをZIPに追加
        foreach ($documents as $document) {
            $filePath = storage_path("app/documents/{$document->file_name}"); // Notice 'app' added to the path
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $document->file_name);
            }
        }

        // ZIPファイルを閉じる
        $zip->close();

        // 作成したZIPファイルが存在するか確認
        if (!file_exists($zipFileName)) {
            return response()->json(['error' => 'ZIPファイルの作成に失敗しました。']);
        }

        // ZIPファイルをクライアントに送信
        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDocumentRequest  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        DB::beginTransaction();

        try {
            $fileName = $document->file_name;
            $filePath = 'documents/' . $fileName;

            // テキストファイルの内容を書き換える
            Storage::disk('local')->put($filePath, $request['document_details']);

            $document->title = $request['document_title'];
            $document->is_visible = $request['is_visible'];
            $document->updated_at = Carbon::now();
            $document->save();

            DB::commit();

            session()->flash('status', '更新完了');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', '更新エラー');
        }

        return redirect()->route('document.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        DB::beginTransaction();

        try {
            // ファイルの存在を確認してから削除する
            $fileName = $document->file_name;
            $filePath = 'documents/' . $fileName;
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            $procedureIds = $document->procedureDocument()->pluck('id');

            // 関連するテーブルのレコードを一括削除
            $document->procedureDocument()->delete();
            $document->delete();

            DB::commit();

            session()->flash('status', '削除完了');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', '削除エラー');
        }


        return redirect()->route('document.index');
    }
}
