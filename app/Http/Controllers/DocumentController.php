<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Models\User;
use App\Models\DocumentApprovals;
use App\Models\DocumentHistory;
use App\Models\Procedure;
use App\Models\ProcedureDocument;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $documents = Document::whereNotNull('approver_id')->paginate(10);
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
            $documentSV->saveText($document_number, $request->input('document_details'));

            // JSON形式に直す
            $changes = json_encode([
                'title' => $document->title,
                'fileContents' => $request->input('document_details')
            ]);

            // DBに保存する
            DocumentHistory::create([
                'document_id' => $document->id,
                'process' => "新規作成",
                'changes' => $changes,
                'creator_id' => Auth::user()->employee_number,
                'changes_id' => Auth::user()->employee_number,
                'befored_at' =>  Carbon::now(),
            ]);

            session()->flash('status', '登録完了');

            if (Auth::user()->role == 9) {
                DocumentApprovals::create([
                    'document_id' => $document->id,
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
        DB::beginTransaction();

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

                $approver_id = Auth::user()->role !== 9 ? Auth::user()->employee_number : null;
                $request['is_visible'] = Auth::user()->role !== 9 ? 0 : $request['is_visible'];

                $documentData = [
                    'document_number' => $document_number,
                    'title' => $filenameWithoutExtension,
                    'file_name' => $filenameWithExtension,
                    'is_visible' => $request['is_visible'],
                    'approver_id' => $approver_id,
                    'creator_id' => Auth::user()->employee_number,
                ];

                // テキストファイルに保存する
                $documentSV->saveText($document_number, $content);

                if ($document = Document::create($documentData)) {
                    $successCount++; // 成功したファイルの件数をカウントする
                }

                // JSON形式に直す
                $changes = json_encode([
                    'title' => $filenameWithoutExtension,
                    'fileContents' => $content
                ]);

                // DBに保存する
                DocumentHistory::create([
                    'document_id' => $document->id,
                    'process' => "新規作成",
                    'changes' => $changes,
                    'creator_id' => Auth::user()->employee_number,
                    'changes_id' => Auth::user()->employee_number,
                    'befored_at' =>  Carbon::now(),
                ]);

                if (Auth::user()->role === 9) {
                    DocumentApprovals::create([
                        'document_id' => $document->id,
                        'creator_id' => Auth::user()->employee_number,
                        'approved' => 0, // 0：申請中、1：承認、2：否認、3：取り下げ
                        'approval_at' => null,
                    ]);
                    session()->flash('status', '登録が完了しました。承認までお待ちください。');
                }
            }

            if (Auth::user()->role !== 9) {
                if ($successCount > 0) {
                    session()->flash('status', $successCount . '件のファイルが登録されました。');
                }
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

        $histories = DocumentHistory::where('document_id', $document->id)
            ->leftJoin('users', 'document_histories.changes_id', '=', 'users.employee_number')
            ->select('document_histories.*', 'users.name AS employee_name')
            ->get();

        $procedures = Procedure::join('procedure_documents', 'procedures.id', '=', 'procedure_documents.procedure_id')
            ->where('procedure_documents.document_id', $document->id)
            ->select('procedures.id', 'procedures.name')
            ->get();

        return view('documents.edit', compact('document', 'fileContents', 'histories', 'procedures', 'title'));
    }

    public function history_download($document, $id)
    {
        $document = Document::findOrFail($document);
        $file_title = $document->document_number;
        $history = DocumentHistory::findOrFail($id);
        $change = $history->changes;
        $title = "マニュアル名：" . json_decode($change)->title;
        $fileContents = "マニュアル内容：\n" . json_decode($change)->fileContents;
    
        // ファイルを作成せずに一時的なファイルとして保存する
        $tempFile = tempnam(sys_get_temp_dir(), 'manual_');
        file_put_contents($tempFile, $title . "\n" . $fileContents);
    
        // ダウンロードするファイル名を設定
        $downloadFileName = $file_title . '.txt';
    
        // ファイルをダウンロードする
        return response()->download($tempFile, $downloadFileName)->deleteFileAfterSend(true);
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
            // 履歴の保存　まずは現在のテキストファイルの内容を取得する
            $documentSV = new DocumentService;
            $fileContents = $documentSV->getContents($document->file_name);

            // JSON形式に直す
            $changes = json_encode([
                'title' => $document->title,
                'fileContents' => $fileContents
            ]);

            // 直前の変更者のIDを取得する
            $changesId = DocumentHistory::where('document_id', $document->id)->latest('id')->value('creator_id');

            // DBに保存する
            DocumentHistory::create([
                'document_id' => $document->id,
                'process' => "更新",
                'changes' => $changes,
                'creator_id' => Auth::user()->employee_number,
                'changes_id' => $changesId,
                'befored_at' => $document->updated_at, //直前の変更日時
            ]);

            // 5件以上の変更履歴がある場合、古い情報を削除する
            $historyCount = DocumentHistory::where('document_id', $document->id)->count();
            if ($historyCount > 5) {
                $oldestHistories = DocumentHistory::where('document_id', $document->id)
                    ->orderBy('created_at')
                    ->limit($historyCount - 5)
                    ->get();

                foreach ($oldestHistories as $history) {
                    $history->delete();
                }
            }

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
            ProcedureDocument::whereIn('procedure_id', $procedureIds)->delete();
            DocumentApprovals::where('document_id', $document->id)->delete();
            DocumentHistory::where('document_id', $document->id)->delete();
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
