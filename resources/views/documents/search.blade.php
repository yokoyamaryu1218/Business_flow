<x-app-layout>

    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('document.index') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                    マニュアル管理
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

                                    <div class="hidden md:block">
                                        <details class="w-30 bg-indigo-500 p-4 rounded-xl shadow-md group mx-auto overflow-hidden max-h-[56px] open:!max-h-[400px] transition-[max-height] duration-500 overflow-hidden hover:bg-indigo-600">
                                            <summary class="outline-none cursor-pointer focus:underline focus:text-indigo-600 font-semibold marker:text-transparent group-open:before:rotate-90 before:origin-center relative before:w-[18px] before:h-[18px] before:transition-transform before:duration-200 before:-left-1 before:top-2/4 before:-translate-y-2/4 before:absolute before:bg-no-repeat before:bg-[length:18px_18px] before:bg-center before:bg-[url('data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20class%3D%22h-6%20w-6%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%20stroke-width%3D%222%22%3E%0A%20%20%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20d%3D%22M9%205l7%207-7%207%22%20%2F%3E%0A%3C%2Fsvg%3E')]">
                                                <span class="text-white">新規登録</span>
                                            </summary>
                                            <hr class="my-2 scale-x-150" />
                                            <div class="text-sm -m-4 -mt-2 p-4 bg-gray-50 hover:bg-gray-300" onclick="window.location.href = '{{ route('document.create') }}'">
                                                <a class="text-black">直接入力</a>
                                            </div>
                                            <hr class="my-2 scale-x-150" />
                                            <div class="text-sm -m-4 -mt-2 p-4 bg-gray-50 hover:bg-gray-300" onclick="window.location.href = '{{ route('document.file') }}'">
                                                <a class="text-black">ファイル読込</a>
                                            </div>
                                        </details>
                                    </div>

                                    <div class="md:hidden">
                                        <button type="button" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" onclick="window.location.href = '{{ route('document.create') }}'">新規登録</button>
                                    </div>
                                </body>
                            </div>

                            <section class="text-gray-600 body-font">
                                <div class="container py-10 mx-auto flex flex-wrap">
                                    <div class="lg:w-4/5 mx-auto">

                                        <div class="my-4 flex items-center justify-end">
                                            <form action="{{ route('document.search') }}" method="GET">
                                                @method('get')
                                                <div class="ml-12 mt-5 flex items-center mobile-ml-0">
                                                    <input type="search" name="search" id="default-search" class="block w-60 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $search }}" placeholder="タイトル、ナンバーで検索可能です。">
                                                    <button type="submit" class="ml-2 py-2 px-4 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-lg small-button">検索</button>
                                                </div>
                                            </form>
                                        </div>

                                        @if (count($search_list) > 0)
                                        <table class="w-full table-auto">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ナンバー</th>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">タイトル</th>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">公開設定</th>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                                                </tr>
                                            </thead>
                                            @foreach ($search_list as $document)
                                            <tbody>
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $document->document_number }}</td>
                                                    <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $document->title }}</td>
                                                    <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                                        @if ($document->is_visible)
                                                        表示中
                                                        @else
                                                        非表示
                                                        @endif
                                                    </td>
                                                    <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                                        <a href="{{ route('document.edit', ['document' => $document->id]) }}" class="edit-button text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none transition-colors duration-300 ease-in-out hover:bg-green-600">編集</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            @endforeach
                                        </table>
                                        {{ $search_list->appends(['search' => $search])->links() }}
                                        @elseif (!empty($search))
                                        <p class="py-2">検索ワードに一致する「マニュアル」は見つかりませんでした。</p>
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