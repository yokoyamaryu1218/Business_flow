<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Procedure;
use App\Models\ProcedureDocument;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Services\DocumentService;

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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDocumentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumentRequest $request)
    {
        DB::beginTransaction();

        try {
            $last_number = Document::orderBy('id', 'desc')->value('document_number');

            $documentdb = new DocumentService;
            $document_number = $documentdb->numbering($last_number);

            $documentData = [
                'document_number' => $document_number,
                'title' => $request['document_title'],
                'file_name' => $document_number . '.txt',
                'is_visible' => $request['is_visible'],
            ];

            Document::create($documentData);

            $documentSV = new DocumentService;
            // テキストファイルに保存する
            $documentSV->savaDocument($document_number, $request['document_details']);

            session()->flash('status', '登録完了');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
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
    public function show(Document $document)
    {
        //
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

            // テキストファイルの内容を取得する
            $fileContents = Storage::disk('local')->get($filePath);

            // テキストファイルの内容を書き換える
            $fileContents = $request['document_details'];

            // 書き換えた内容をテキストファイルに保存する
            Storage::disk('local')->put($filePath, $fileContents);

            $document = Document::findOrFail($document->id);
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
            $document = Document::findOrFail($document->id);

            // ファイルの存在を確認してから削除する
            $fileName = $document->file_name;
            $filePath = 'documents/' . $fileName;
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            $procedureIds = $document->procedureDocument()->pluck('id');

            // procedure_documentsテーブルから関連するレコードを削除
            ProcedureDocument::whereIn('procedure_id', $procedureIds)->delete();

            // proceduresテーブルから関連するレコードを削除
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
