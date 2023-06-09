<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Procedure;
use App\Models\ProcedureDocument;
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
        $documents = Document::all();

        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('documents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDocumentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumentRequest $request)
    {
        $last_number = Document::orderBy('id', 'desc')->value('document_number');

        $documentdb = new DocumentService;
        $document_number = $documentdb->numbering($last_number);

        Document::create([
            'document_number' => $document_number,
            'title' => $request['document_title'],
            'file_name' => $document_number . '.txt',
        ]);

        // ファイルの保存先パスを生成
        $filePath = 'documents/' . $document_number . '.txt';

        // ファイルに内容を保存
        Storage::put($filePath, $request['document_details']);

        session()->flash('status', '登録完了');
        $documents = Document::all();

        return view('documents.index', compact('documents'));
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
        $fileName = $document->file_name;
        $filePath = 'documents/' . $fileName;

        // テキストファイルの内容を取得する
        $fileContents = Storage::disk('local')->get($filePath);

        $procedures = Procedure::join('procedure_documents', 'procedures.id', '=', 'procedure_documents.procedure_id')
            ->where('procedure_documents.document_id', $document->id)
            ->select('procedures.id', 'procedures.name')
            ->get();

        return view('documents.edit', compact('document', 'fileContents', 'procedures'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $document = Document::findOrFail($document->id);

        $procedureIds = $document->procedureDocument()->pluck('id');

        // procedure_documentsテーブルから関連するレコードを削除
        ProcedureDocument::whereIn('procedure_id', $procedureIds)->delete();

        // proceduresテーブルから関連するレコードを削除
        $document->procedureDocument()->delete();

        $document->delete();

        session()->flash('status', '削除完了');
        $documents = Document::all();

        return view('documents.index', compact('documents'));
    }
}
