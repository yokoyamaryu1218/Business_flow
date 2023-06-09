<?php

namespace App\Http\Controllers;

use App\Models\ProcedureDocument;
use App\Http\Requests\StoreProcedureDocumentRequest;
use App\Http\Requests\UpdateProcedureDocumentRequest;

class ProcedureDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProcedureDocumentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProcedureDocumentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProcedureDocument  $procedureDocument
     * @return \Illuminate\Http\Response
     */
    public function show(ProcedureDocument $procedureDocument)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProcedureDocument  $procedureDocument
     * @return \Illuminate\Http\Response
     */
    public function edit(ProcedureDocument $procedureDocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProcedureDocumentRequest  $request
     * @param  \App\Models\ProcedureDocument  $procedureDocument
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProcedureDocumentRequest $request, ProcedureDocument $procedureDocument)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProcedureDocument  $procedureDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProcedureDocument $procedureDocument)
    {
        //
    }
}
