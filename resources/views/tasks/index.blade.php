<x-app-layout>
    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <span class="ml-1 text-base font-semibold md:ml-2">
                    {{ $title }}
                </span>
            </li>
        </ol>
    </x-slot>

    <div class="flex" id="section-1">
        <!-- サイドバーを追加 -->
        <div class="hidden md:block w-32 bg-white shadow-lg ml-8 mt-10 fixed bottom-0 left-0 mb-20">
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
                        <div class="sm:p-4">

                            <div class="flex items-center">

                                <body>
                                    <h2 class="flex items-center text-xl sm:text-2xl font-extrabold dark:text-white" style="display: flex; align-items: center; padding: 7px 0 6px; flex-grow: 1;">
                                        登録作業一覧
                                    </h2>
                                    <button type="button" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" onclick="window.location.href = '{{ route('task.create') }}'">作業登録</button>
                                </body>
                            </div>

                            <section class="text-gray-600 body-font">
                                <div class="container py-10 mx-auto flex flex-wrap">
                                    <div class="lg:w-4/5 mx-auto">

                                        <div class="mb-8">
                                            <x-status />
                                        </div>

                                        @if(count($tasks) > 0)
                                        <div class="my-4 flex items-center justify-end">
                                            <form action="{{ route('task.search') }}" method="GET">
                                                @method('get')
                                                <div class="ml-12 mt-5 flex items-center mobile-ml-0">
                                                    <input type="search" name="task_search" id="default-search" class="block w-60 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('task_search') }}" placeholder="作業名で検索可能です。">
                                                    <button type="submit" class="ml-2 py-2 px-4 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-lg small-button small-button">検索</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="overflow-x-auto">
                                            <table class="w-full table-auto">
                                                <thead>
                                                    <tr>
                                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">作業名</th>
                                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">登録手順数</th>
                                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">公開設定</th>
                                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                                                    </tr>
                                                </thead>
                                                @foreach ($tasks as $task)
                                                <tbody>
                                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                        <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $task->name }}</td>
                                                        <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $task->procedure_count }}</td>
                                                        <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                                            @if ($task->is_visible)
                                                            表示中
                                                            @else
                                                            非表示
                                                            @endif
                                                        </td>
                                                        <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                                            <a href="{{ route('task.edit', ['task' => $task->id]) }}" class="edit-button text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none transition-colors duration-300 ease-in-out hover:bg-green-600 small-button">編集</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                @endforeach
                                            </table>
                                            {{ $tasks->appends(['task_page' => $taskPage, 'procedure_page' => $procedurePage])->links() }}
                                        </div>
                                        @else
                                        現在登録の作業はありません。
                                        @endif
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full mx-auto py-4" id="section-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                <div class="bg-opacity-25 mt-4">
                    <div class="sm:p-4">

                        <div class="flex items-center">

                            <body>
                                <h2 class="flex items-center text-xl sm:text-2xl font-extrabold dark:text-white" style="display: flex; align-items: center; padding: 7px 0 6px; flex-grow: 1;">
                                    登録手順一覧
                                </h2>
                                <button type="button" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" onclick="window.location.href = '{{ route('task.procedure.procedure_create') }}'">手順登録</button>
                            </body>
                        </div>

                        <section class="text-gray-600 body-font">
                            <div class="container py-10 mx-auto flex flex-wrap">
                                <div class="lg:w-4/5 mx-auto">

                                    @if(count($procedures) > 0)
                                    <div class="my-4 flex items-center justify-end">
                                        <form action="{{ route('task.procedure.search') }}" method="GET">
                                            @method('get')
                                            <div class="ml-12 mt-5 flex items-center mobile-ml-0">
                                                <input type="search" name="procedure_search" id="default-search" class="block w-60 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('procedure_search') }}" placeholder="手順名で検索可能です。">
                                                <button type="submit" class="ml-2 py-2 px-4 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-lg small-button">検索</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="w-full table-auto">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">手順名</th>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">登録作業先</th>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">公開設定</th>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                                                </tr>
                                            </thead>
                                            @foreach ($procedures as $procedure)
                                            <tbody>
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $procedure->name }}</td>
                                                    <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $procedure->task_name }}</td>
                                                    <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                                        @if ($procedure->is_visible)
                                                        表示中
                                                        @else
                                                        非表示
                                                        @endif
                                                    </td>
                                                    <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                                        <a href="{{ route('task.procedure.edit',['id1' => $procedure->task_id, 'id2' => $procedure->id]) }}'" class="edit-button text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none transition-colors duration-300 ease-in-out hover:bg-green-600 small-button">編集</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            @endforeach
                                        </table>
                                        {{ $procedures->appends(['task_page' => $taskPage, 'procedure_page' => $procedurePage])->links() }}
                                    </div>
                                    @else
                                    現在登録の手順はありません。
                                    @endif
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>