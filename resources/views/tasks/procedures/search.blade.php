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

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mt-8 text-2xl border-l-4 border-black pl-4">
                        <b>{{ $title }}</b>
                    </div>

                    <div class="bg-opacity-25 mt-4">
                    <div class="sm:p-4">

                            <div class="flex items-center">

                                <body>
                                    <h2 class="flex items-center text-2xl  font-extrabold dark:text-white" style="display: flex; align-items: center; padding: 7px 0 6px; flex-grow: 1;">
                                        検索結果一覧
                                    </h2>
                                    <button type="button" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" onclick="window.location.href = '{{ route('task.procedure.procedure_create') }}'">新規登録</button>
                                </body>
                            </div>

                            <section class="text-gray-600 body-font">
                                <div class="container py-10 mx-auto flex flex-wrap">
                                    <div class="lg:w-4/5 mx-auto">

                                        <div class="my-4 flex items-center justify-end">
                                            <form action="{{ route('task.search') }}" method="GET">
                                                @method('get')
                                                <div class="ml-12 mt-5 flex items-center mobile-ml-0">
                                                    <input type="search" name="procedure_search" id="default-search" class="block w-48 sm:w-60 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $search }}" placeholder="手順名で検索可能です。">
                                                    <button type="submit" class="ml-2 py-2 px-4 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-lg small-button">検索</button>
                                                </div>
                                            </form>
                                        </div>

                                        @if (count($search_list) > 0)
                                        <table class="w-full table-auto">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">手順名</th>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">登録作業先</th>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">公開設定</th>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                                                </tr>
                                            </thead>
                                            @foreach ($search_list as $procedure)
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
                                                        <a href="{{ route('task.procedure.edit', ['id1' => $procedure->task_id, 'id2' => $procedure->id]) }}'" class="text-white bg-green-400 border-0 py-2 px-4 rounded hover:bg-green-500 focus:outline-none small-button">編集</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            @endforeach
                                        </table>
                                        {{ $search_list->links() }}
                                        @elseif (!empty($search))
                                        <p class="py-2">検索ワードに一致する「作業」は見つかりませんでした。</p>
                                        @else
                                        <p>"{{ $search }}"の検索結果は0件です。</p>
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
</x-app-layout>