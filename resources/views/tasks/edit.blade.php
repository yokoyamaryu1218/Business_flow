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
                    <span class="ml-1 text-base font-semibold md:ml-2">
                        {{ $title }}
                    </span>
                </div>
            </li>
        </ol>
    </x-slot>

    <div class="flex" id="section-1">
        <!-- サイドバーを追加 -->
        <div class="flex fixed">
            <div class="hidden md:block w-42 bg-white shadow-lg ml-8 mt-10 fixed bottom-0 left-0 mb-20">
                <div class="list-group">
                    <div id="menu-section-1" class="list-group-item text-2xl -m-4 -mt-2 p-4 bg-white cursor-pointer flex items-center" onclick="window.location.href = '#section-1';">
                        <img class="w-4 mr-2" src="data:image/png;base64,{{Config::get('base64.task')}}">
                        <a class="text-red-500">作業一覧</a>
                    </div>
                    <hr class="my-2 scale-x-150" />
                    <div id="menu-section-2" class="list-group-item text-2xl -m-4 -mt-2 p-4 bg-white cursor-pointer flex items-center" onclick="window.location.href = '#section-2';">
                        <img class="w-4 mr-2" src="data:image/png;base64,{{Config::get('base64.procedure')}}">
                        <a class="text-red-500">手順一覧</a>
                    </div>
                    <div id="menu-section-3" class="list-group-item text-2xl -m-4 -mt-2 p-4 bg-white cursor-pointer flex items-center" onclick="window.location.href = '#section-3';">
                        <img class="w-4 mr-2" src="data:image/png;base64,{{Config::get('base64.routine')}}">
                        <a class="text-red-500">ルーティン一覧</a>
                    </div>
                    <hr class="my-2 scale-x-150" />
                    <div id="menu-section-4" class="list-group-item text-2xl -m-4 -mt-2 p-4 bg-white cursor-pointer flex items-center" onclick="window.location.href = '#section-4';">
                        <img class="w-4 mr-2" src="data:image/png;base64,{{Config::get('base64.dust')}}">
                        <a class="text-red-500">手順削除</a>
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
                            <b>{{ $task->name }} {{ $title }}</b>
                        </div>

                        <div class="bg-opacity-25 mt-4">
                            <div class="p-4">

                                <div class="mb-8">
                                    <x-status />
                                </div>

                                <section class="text-gray-600 body-font">
                                    <div class="container py-5 mx-auto flex flex-wrap">
                                        <div class="lg:w-2/3 mx-auto">

                                            <form method="POST" action="{{ route('task.update', ['task' => $task->id]) }}">
                                                @csrf
                                                @method('post')
                                                <x-jet-validation-errors class="mb-4" />

                                                <div class="Form-Item">
                                                    <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>作業名</p>
                                                    <input type="text" class="Form-Item-Input" name="task" id="task" value="{{ $task->name }}">
                                                </div>
                                                <hr>

                                                <div class="Form-Item">
                                                    <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>公開設定</p>
                                                    <div class="Form-Item-RadioGroup">
                                                        <label>
                                                            <input type="radio" name="is_visible" value="1" @if ($task->is_visible === 1) { checked } @endif> 表示
                                                        </label>
                                                        <label class="ml-20-mobile">
                                                            <input type="radio" name="is_visible" value="0" @if ($task->is_visible === 0) { checked } @endif> 非表示
                                                        </label>
                                                    </div>
                                                </div>
                                                <hr>

                                                <div class="flex justify-between items-center mt-4">
                                                    <button type="button" class="text-white bg-yellow-400 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                                    <button type="submit" class="text-white bg-indigo-500 hover:bg-indigo-600 border-0 py-2 px-6 focus:outline-none rounded">更新</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full mx-auto py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                <div class="sm:p-6 sm:px-20 bg-white border-b border-gray-200" id="section-2">
                    <div class="sm:p-4">

                        <body>
                            <p class="Form-Item-Label mt-4">{{ $task->name }} に紐づいている手順</p>
                            <button type="button" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" onclick="window.location.href = '{{ route('task.procedure.create', ['id' => $task->id]) }}'">手順登録</button>
                        </body>

                        <section class="text-gray-600 body-font">
                            <div class="container py-10 mx-auto">
                                <div class="overflow-x-auto">

                                    @if (count($procedures) > 0)
                                    <div class="my-4 flex items-center justify-end">
                                        <form action="{{ route('task.procedure.procedure_search', ['id' => $task->id] ) }}" method="GET">
                                            @method('get')
                                            <div class="ml-12 mt-5 flex items-center">
                                                <input type="search" name="procedure_search" id="default-search" class="block w-60 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('procedure_search') }}" placeholder="手順名で検索可能です。">
                                                <button type="submit" class="ml-2 py-2 px-4 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-lg small-button">検索</button>
                                            </div>
                                        </form>
                                    </div>

                                    <table class="min-w-full table-auto">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 bg-gray-100">手順名</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 bg-gray-100">公開設定</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 bg-gray-100"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($procedures as $procedure)
                                            <tr class="bg-white border-b">
                                                <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $procedure->name}}</td>
                                                <td class="font-medium border-t-2 border-gray-200 px-4 py-3"> @if ($procedure->is_visible)
                                                    表示中
                                                    @else
                                                    非表示
                                                    @endif
                                                </td>
                                                <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                                    <a href="{{ route('task.procedure.edit', ['id1' => $procedure->task_id, 'id2' => $procedure->id]) }}'" class="text-white bg-green-400 border-0 py-2 px-4 rounded hover:bg-green-500 focus:outline-none small-button">編集</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $procedures->appends(['product_page' => $procedurePage, 'routine_page' => $routinePage])->links() }}
                                </div>
                                @else
                                <section class="text-gray-600 body-font">
                                    <div class="container px-5 mx-auto">
                                        現在、手順の登録はありません。
                                    </div>
                                </section>
                                @endif
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full mx-auto py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                <div class="sm:p-6 sm:px-20 bg-white border-b border-gray-200" id="section-3">
                    <div class="sm:p-4">

                        <body>
                            <p class="Form-Item-Label mt-4">ルーティン一覧</p>
                            <button type="button" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded small-button" onclick="window.location.href = '{{ route('task.procedure.routine_create', ['id' => $task->id]) }}'">新規登録</button>
                        </body>

                        @if (count($sortedProcedures) > 0)
                        @foreach ($sortedProcedures as $groupIndex => $procedureGroup)
                        @php
                        $flowNumber = $groupIndex + 1;
                        $routine = $routines[$groupIndex];
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
                                            <p class="text-base font-semibold text-gray-900 mb-0">{{ $procedure->name }}</p>
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
                        @endif
                        @endforeach
                        @else
                        <section class="text-gray-600 body-font">
                            <div class="container px-5 mx-auto mt-4">
                                現在、ルーティンの登録はありません。
                            </div>
                        </section>
                        @endif
                        {{ $sortedProcedures->appends(['routine_page' => $routinePage, 'product_page' => $procedurePage])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200" id="section-4">
                    <div class="bg-opacity-25 mt-4">
                        <div class="p-4">
                            <p class="Form-Item-Label mt-4">作業削除：</p>
                            <section class="text-gray-600 body-font">
                                <div class="container py-1 mx-auto">
                                    @if(Auth::user()->role !== 9)
                                    @if (count($sortedProcedures) === 0)
                                    <form method="POST" action="{{ route('task.destroy', ['task' => $task->id]) }}">
                                        @csrf
                                        @method('delete')
                                        <div class="flex justify-between items-center my-4 lg:w-2/3 mx-auto"> <!-- lg:w-2/3 を追加し、justify-between から items-center に変更 -->
                                            <button type="button" class="flex mb-4 text-white bg-yellow-500 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                            <button onclick="return confirm('作業に関連する手順も削除されます。\n本当に削除してもよろしいですか？')" type="submit" class="flex mb-4 ml-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">削除</button>
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