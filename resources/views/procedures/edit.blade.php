<x-app-layout>
    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('task.index') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                    作業管理
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('task.edit', ['task' => $procedures->task_id]) }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                        作業詳細
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-base font-semibold md:ml-2">
                        {{ $title }}
                    </span>
                </div>
            </li>
        </ol>
    </x-slot>

    <div class="flex" id="section-1">
        <!-- サイドバーを追加 -->
        <div class="flex fixed bottom-0 left-0">
            <div class="hidden md:block w-42 bg-white shadow-lg ml-8 mt-10 mb-20">
                <div class="list-group">
                    <div id="menu-section-1" class="list-group-item text-2xl -m-4 -mt-2 p-4 bg-white cursor-pointer flex items-center" onclick="window.location.href = '#section-1';">
                        <img class="w-4 mr-2" src="data:image/png;base64,{{Config::get('base64.procedure')}}">
                        <a class="text-red-500">手順詳細</a>
                    </div>
                    <div id="menu-section-2" class="list-group-item text-2xl -m-4 -mt-2 p-4 bg-white cursor-pointer flex items-center" onclick="window.location.href = '#section-2';">
                        <img class="w-4 mr-2" src="data:image/png;base64,{{Config::get('base64.routine')}}">
                        <a class="text-red-500">ルーティン一覧</a>
                    </div>
                    <hr class="my-2 scale-x-150" />
                    <div id="menu-section-3" class="list-group-item text-2xl -m-4 -mt-2 p-4 bg-white cursor-pointer flex items-center" onclick="window.location.href = '#section-3';">
                        <img class="w-4 mr-2" src="data:image/png;base64,{{Config::get('base64.dust')}}">
                        <a class="text-red-500">手順削除</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/procedure/sideMenu.js') }}" defer></script>

    <!-- 元のコンテンツエリア -->
    <div class="w-full mx-auto pt-10 pb-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mt-8 text-2xl border-l-4 border-black pl-4">
                        <b>{{ $title }}</b>
                    </div>

                    <div class="bg-opacity-25 mt-4">
                        <div class="p-4">

                            <section class="text-gray-600 body-font">
                                <div class="container py-5 mx-auto flex flex-wrap">
                                    <div class="lg:w-2/3 mx-auto">

                                        <form method="POST" action="{{ route('task.procedure.update', ['id1' => $task->id, 'id2' => $procedures->id]) }}">
                                            @csrf
                                            @method('post')
                                            <x-jet-validation-errors class="mb-4" />

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label">作業名</p>
                                                <input type="text" class="Form-Item-Input" name="name" id="name" value="{{ $task->name }}" readonly>
                                            </div>
                                            <hr>

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">編集可</span>手順名</p>
                                                <input type="text" class="Form-Item-Input" name="name" id="name" value="{{ $procedures->name }}" required>
                                            </div>
                                            <hr>

                                            <div class="previous-procedures">
                                                @if (!empty($previousProcedureIds))
                                                @foreach($previousProcedureIds as $index => $previousProcedureId)
                                                <div class="Form-Item">
                                                    @if ($index === 0)
                                                    <p class="Form-Item-Label">前の手順</p>
                                                    @else
                                                    <p class="Form-Item-Label">&nbsp;</p>
                                                    @endif
                                                    @foreach ($procedure_list as $procedure)
                                                    @if ($procedure->id === $previousProcedureId['id'])
                                                    <input name="previous_procedure_name[]" class="Form-Item-Input" value="{{ $procedure->name }}" readonly>
                                                    <input type="hidden" name="previous_procedure_id[]" class="Form-Item-Input" value="{{ $procedure->id }}" readonly>
                                                    @break
                                                    @endif
                                                    @endforeach
                                                </div>
                                                @endforeach
                                                @else
                                                <div class="Form-Item">
                                                    <p class="Form-Item-Label">前の手順</p>
                                                    <input name="previous_procedure_id[]" class="Form-Item-Input" readonly>
                                                </div>
                                                @endif
                                            </div>
                                            <hr>

                                            <div class="next-procedures">
                                                @if (!empty($nextProcedureIds))
                                                @foreach($nextProcedureIds as $index => $nextProcedureId)
                                                <div class="Form-Item">
                                                    @if ($index === 0)
                                                    <p class="Form-Item-Label">次の手順</p>
                                                    @else
                                                    <p class="Form-Item-Label">&nbsp;</p>
                                                    @endif
                                                    @foreach ($procedure_list as $procedure)
                                                    @if ($procedure->id === $nextProcedureId['id'])
                                                    <input name="next_procedure_name[]" class="Form-Item-Input" value="{{ $procedure->name }}" readonly>
                                                    <input type="hidden" name="next_procedure_id[]" class="Form-Item-Input" value="{{ $procedure->id }}" readonly>
                                                    @break
                                                    @endif
                                                    @endforeach
                                                </div>
                                                @endforeach
                                                @else
                                                <div class="Form-Item">
                                                    <p class="Form-Item-Label">次の手順</p>
                                                    <input name="next_procedure_id[]" class="Form-Item-Input" readonly>
                                                </div>
                                                @endif
                                            </div>
                                            <hr>

                                            <div class="Form-Item">
                                                @if (count($sortedProcedures) === 0)
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">編集可</span>公開設定</p>
                                                <div class="Form-Item-RadioGroup">
                                                    <label>
                                                        <input type="radio" name="is_visible" value="1" @if ($procedures->is_visible === 1) { checked } @endif> 表示
                                                    </label>
                                                    <label class="ml-20-mobile">
                                                        <input type="radio" name="is_visible" value="0" {{ $procedures->is_visible === 0 ? 'checked' : '' }}> 非表示
                                                    </label>
                                                </div>
                                                @else
                                                <p class="Form-Item-Label">公開設定</p>
                                                <div class="Form-Item-RadioGroup">
                                                    <label>
                                                        <input type="radio" name="is_visible" value="1" @if ($procedures->is_visible === 1) { checked } @endif disabled> 表示
                                                    </label>
                                                    <label class="ml-20-mobile">
                                                        <input type="radio" name="is_visible" value="0" @if ($procedures->is_visible === 0) { checked } @endif disabled> 非表示
                                                    </label>
                                                </div>
                                                @endif
                                            </div>
                                            <hr>

                                            <div class="related-manuals-section">
                                                @if (!empty($my_documents))
                                                @foreach($my_documents as $index => $my_document)
                                                <div class="Form-Item">
                                                    @if ($index === 0)
                                                    <p class="Form-Item-Label"><span class="Form-Item-Label-Required">編集可</span>関連するマニュアル</p>
                                                    @else
                                                    <p class="Form-Item-Label hidden md:block">&nbsp;</p>
                                                    @endif
                                                    <select name="document_id[]" class="Form-Item-Input">
                                                        <option value="">マニュアルを選択してください</option>
                                                        @foreach ($documents_list as $document)
                                                        @php
                                                        $selected = ($document['id'] === $my_document['document_id']) ? 'selected' : '';
                                                        @endphp
                                                        <option value="{{ $document['id'] }}" {{ $selected }}>{{ $document['title'] }}</option>
                                                        @endforeach
                                                    </select>

                                                    <div id="add_waku_book" class="ml-2 mt-2 md:mt-0">
                                                        @if ($index === 0)
                                                        <button type="button" id="add_book" class="text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none addButton hover:bg-green-600">行追加</button>
                                                        @else
                                                        <button type="button" id="delete_book" class="text-white rounded-md text-center bg-red-400 py-2 px-4 inline-flex items-center focus:outline-none hover:bg-red-600">行削除</button>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                                @else
                                                <div class="Form-Item">
                                                    <p class="Form-Item-Label">関連するマニュアル</p>
                                                    <select name="document_id[]" class="Form-Item-Input">
                                                        <option value="">マニュアルを選択してください</option>
                                                        @foreach ($procedure_list as $procedure)
                                                        <option>{{ $procedure['title'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div id="add_waku_book" class="ml-2 mt-2 md:mt-0">
                                                        <button id="add_book" class="text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none addButton">行追加</button>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <hr>

                                            <div class="flex justify-between items-center mt-4">
                                                <button type="button" class="text-white bg-yellow-400 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                                <button type="submit" class="text-white bg-indigo-500 hover:bg-indigo-600 border-0 py-2 px-6 focus:outline-none rounded">更新</button>
                                            </div>
                                        </form>

                                        <div style="display: none;">
                                            {* 前の手順用プルダウン *}
                                            <div id="book_select_ary">
                                                <p class="Form-Item-Label"></p>
                                                <select name="document_id[]" class="Form-Item-Input">
                                                    <option value="">マニュアルを選択してください</option>
                                                    @foreach ($documents_list as $procedure)
                                                    <option value="{{ $procedure['id'] }}">{{ $procedure['title'] }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="ml-2 mt-2 md:mt-0">
                                                    <button id="delete_book" class="text-white rounded-md text-center bg-red-400 py-2 px-4 inline-flex items-center focus:outline-none hover:bg-red-600">行削除</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/procedure/create-add-option.js') }}" defer></script>

    <div class="w-full mx-auto py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                <div class="sm:p-6 sm:px-20 bg-white border-b border-gray-200" id="section-2">
                    <div class="sm:p-4">

                        <body>
                            <p class="Form-Item-Label mt-4">ルーティン一覧</p>
                        </body>

                        @if (count($sortedProcedures) > 0)
                        @foreach ($sortedProcedures as $groupIndex => $procedureGroup)
                        @php
                        $flowNumber = $groupIndex + 1;
                        $routine = $matchingRoutines[$groupIndex];
                        @endphp

                        <body>
                            <p class="flex items-center text-lg font-extrabold dark:text-white">【ルーティン{{ $flowNumber }}】
                                {{ $routine->is_visible ? '表示中' : '非表示' }}
                            </p>
                            <button type="button" class="flex mb-4 ml-auto text-white bg-green-400 border-0 py-2 px-6 focus:outline-none hover:bg-green-600 rounded" onclick="window.location.href = '{{ route('task.procedure.routine_edit', ['id1' => $task->id, 'id2' => $routine->id]) }}'">編集</button>
                        </body>
                        <div class="relative m-3 flex flex-wrap mx-auto justify-left">
                            <style>
                                .routine:hover p.text-base {
                                    color: white;
                                }
                            </style>
                            @foreach ($procedureGroup as $index => $procedure)
                            <div class="routine max-w-[320px] bg-white border border-gray-400 shadow-md rounded-3xl p-2 mx-1 my-3 cursor-pointer transition-colors duration-300 hover:bg-gray-500" onclick="window.location.href = '{{ route('task.procedure.edit', ['id1' => $task->id, 'id2' => $procedure->id]) }}'">
                                <div class="mt-2 pl-1 mb-1 flex items-start">
                                    <div class="mt-2 pl-1 mb-1 hover-red-text">
                                        <div>
                                            <p class="text-base font-semibold text-gray-900 mb-0">手順{{ $index + 1 }}</p>
                                            @if ($procedure)
                                            <p class="text-base font-semibold text-gray-900 mb-0">{{ $procedure->name }}</p>
                                            @else
                                            <p class="text-base font-semibold text-gray-900 mb-0">手順が存在しません</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($index !== count($procedureGroup) - 1)
                            <div class="flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @if ($groupIndex !== count($sortedProcedures) - 1 && !empty($sortedProcedures[$groupIndex + 1]))
                        <hr>
                        @endif
                        @endforeach
                        {{ $sortedProcedures->links() }}
                        @else
                        <section class="text-gray-600 body-font">
                            <div class="container px-5 mx-auto mt-4">
                                現在、ルーティンの登録はありません。
                            </div>
                        </section>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- マニュアルを削除できるのはマネージャ以上 -->
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 sm:px-20 bg-white border-b border-gray-200" id="section-3">
                    <div class="bg-opacity-25 mt-4">
                        <div class="p-4">
                            <p class="Form-Item-Label mt-4">手順削除：</p>
                            <section class="text-gray-600 body-font">
                                <div class="container py-1 mx-auto">
                                    @if(Auth::user()->role !== 9)
                                    @if (count($sortedProcedures) === 0)
                                    <form method="POST" action="{{ route('task.procedure.destroy', ['id1' => $procedures->task_id, 'id2' => $procedures->id]) }}">
                                        @csrf
                                        @method('delete')
                                        <div class="flex justify-between items-center my-4 lg:w-2/3 mx-auto"> <!-- lg:w-2/3 を追加し、justify-between から items-center に変更 -->
                                            <button type="button" class="flex mb-4 text-white bg-yellow-500 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                            <button onclick="return confirm('選択した手順を削除してもよろしいですか？')" type="submit" class="flex mb-4 ml-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">削除</button>
                                        </div>
                                    </form>
                                    @else
                                    現在登録中のルーティンがあるため削除できません。
                                    @endif
                                    @else
                                    削除権限がありません。
                                    @endif
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>