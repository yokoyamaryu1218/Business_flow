<x-app-layout>

    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                    {{ $title}}
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-base font-semibold md:ml-2">
                        承認待ち一覧
                    </span>
                </div>
            </li>
        </ol>
    </x-slot>

    <div class="flex" id="section-1">
        <!-- サイドバーを追加 -->
        <div class="flex fixed left-0 bottom-0 mb-20">
            <div class="hidden md:block w-42 bg-white shadow-lg ml-8 mt-10 fixed bottom-0 left-0 mb-20">
                <div class="list-group">
                    <div id="menu-section-1" class="list-group-item text-2xl -m-4 -mt-2 p-4 bg-white cursor-pointer flex items-center" onclick="window.location.href = '#section-1';">
                        <img class="w-4 mr-2" src="data:image/png;base64,{{Config::get('base64.procedure')}}">
                        <a class="text-red-500">手順</a>
                    </div>
                    <div id="menu-section-2" class="list-group-item text-2xl -m-4 -mt-2 p-4 bg-white cursor-pointer flex items-center" onclick="window.location.href = '#section-2';">
                        <img class="w-4 mr-2" src="data:image/png;base64,{{Config::get('base64.routine')}}">
                        <a class="text-red-500">ルーティン</a>
                    </div>
                    <hr class="my-2 scale-x-150" />
                    <div id="menu-section-3" class="list-group-item text-2xl -m-4 -mt-2 p-4 bg-white cursor-pointer flex items-center" onclick="window.location.href = '#section-3';">
                        <img class="w-4 mr-2" src="data:image/png;base64,{{Config::get('base64.book_red')}}">
                        <a class="text-red-500">マニュアル</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/procedure/sideMenu.js') }}" defer></script>

    <div class="w-full mx-auto pt-10 pb-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="sm:p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mt-8 text-2xl border-l-4 border-black pl-4">
                        <b>{{ $title }}</b>
                    </div>

                    <div class="bg-opacity-25 mt-4">
                        <div class="sm:p-4">

                            <div class="flex items-center">

                                <body>
                                    <h2 class="flex items-center text-lg sm:text-2xl font-extrabold dark:text-white" style="display: flex; align-items: center; padding: 7px 0 6px; flex-grow: 1;">
                                        承認待ち一覧：手順
                                    </h2>
                                    <button type="button" class="flex my-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded small-button" onclick="window.location.href = '{{ route('approval.approved') }}'">承認済み一覧</button>
                                </body>
                            </div>

                            <section class="text-gray-600 body-font">
                                <div class="container py-1 mx-auto flex flex-wrap">
                                    <div class="lg:w-4/5 mx-auto">

                                        <div class="mb-8">
                                            <x-status />
                                        </div>

                                        @if (count($procedures) > 0)
                                        <section class="text-gray-600 body-font">
                                            <div class="container px-5 py-1 mx-auto">
                                                <table class="w-full table-auto">
                                                    <thead>
                                                        <tr>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">手順名</th>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">申請日</th>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">申請者</th>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ステータス</th>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($procedures as $procedure)
                                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $procedure->name }}</td>
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ date('Y-m-d', strtotime($procedure->updated_at)) }}</td>
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $procedure->user_name }}</td>
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3 text-red-500">
                                                                @if ($procedure->approved === 0)
                                                                承認待ち
                                                                @endif
                                                            </td>
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                                                <a href="{{ route('approval.procedure_edit', ['procedures' => $procedure->id]) }}" class="edit-button text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none hover:bg-green-600">開く</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </section>
                                        @else
                                        <section class="text-gray-600 body-font">
                                            <div class="container px-5 py-1 mx-auto">
                                                現在承認待ちのマニュアルはありません。
                                            </div>
                                        </section>
                                        @endif
                                        {{ $procedures->appends(['document_page' => $documentPage, 'routine_page' => $routinePage, 'procedure_page' => $procedurePage])->links() }}
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full mx-auto py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" id="section-2">

                <div class="sm:p-6 sm:px-20 bg-white border-b border-gray-200" id="section-2">
                    <div class="bg-opacity-25 mt-4">
                        <div class="sm:p-4">

                            <div class="flex items-center">

                                <body>
                                    <h2 class="flex items-center text-lg sm:text-2xl font-extrabold dark:text-white" style="display: flex; align-items: center; padding: 7px 0 6px; flex-grow: 1;">
                                        承認待ち一覧：ルーティン
                                    </h2>
                                </body>
                            </div>

                            <section class="text-gray-600 body-font">
                                <div class="container py-1 mx-auto flex flex-wrap">
                                    <div class="lg:w-4/5 mx-auto">

                                        @if (count($routines) > 0)
                                        <section class="text-gray-600 body-font">
                                            <div class="container px-5 py-1 mx-auto">
                                                <table class="w-full table-auto">
                                                    <thead>
                                                        <tr>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">申請日</th>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">申請者</th>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ステータス</th>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($routines as $routine)
                                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ date('Y-m-d', strtotime($routine->updated_at)) }}</td>
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $routine->user_name }}</td>
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3 text-red-500">
                                                                @if ($routine->approved === 0)
                                                                承認待ち
                                                                @endif
                                                            </td>
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                                                <a href="{{ route('approval.routine_edit', ['routines' => $routine->id]) }}" class="edit-button text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none hover:bg-green-600">開く</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </section>
                                        @else
                                        <section class="text-gray-600 body-font">
                                            <div class="container px-5 py-1 mx-auto">
                                                現在承認待ちのルーティンはありません。
                                            </div>
                                        </section>
                                        @endif
                                        {{ $routines->appends(['document_page' => $documentPage, 'routine_page' => $routinePage, 'procedure_page' => $procedurePage])->links() }}
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full mx-auto py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" id="section-3">

                <div class="sm:p-6 sm:px-20 bg-white border-b border-gray-200">

                    <div class="bg-opacity-25 mt-4">
                        <div class="sm:p-4">

                            <div class="flex items-center">

                                <body>
                                    <h2 class="flex items-center text-lg sm:text-2xl font-extrabold dark:text-white" style="display: flex; align-items: center; padding: 7px 0 6px; flex-grow: 1;">
                                        承認待ち一覧：マニュアル
                                    </h2>
                                </body>
                            </div>

                            <section class="text-gray-600 body-font">
                                <div class="container py-1 mx-auto flex flex-wrap">
                                    <div class="lg:w-4/5 mx-auto">

                                        @if (count($documents) > 0)
                                        <section class="text-gray-600 body-font">
                                            <div class="container px-5 py-1 mx-auto">
                                                <table class="w-full table-auto">
                                                    <thead>
                                                        <tr>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">タイトル</th>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">申請日</th>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">申請者</th>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ステータス</th>
                                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($documents as $document)
                                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $document->title }}</td>
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ date('Y-m-d', strtotime($document->updated_at)) }}</td>
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $document->user_name }}</td>
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3 text-red-500">
                                                                @if ($document->approved === 0)
                                                                承認待ち
                                                                @endif
                                                            </td>
                                                            <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                                                <a href="{{ route('approval.document_edit', ['documents' => $document->id]) }}" class="edit-button text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none hover:bg-green-600">開く</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </section>
                                        @else
                                        <section class="text-gray-600 body-font">
                                            <div class="container px-5 py-1 mx-auto">
                                                現在承認待ちの手順はありません。
                                            </div>
                                        </section>
                                        @endif
                                        {{ $documents->appends(['document_page' => $documentPage, 'routine_page' => $routinePage, 'procedure_page' => $procedurePage])->links() }}
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