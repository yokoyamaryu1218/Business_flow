<x-app-layout>

    <style>
        tr:hover .flex-shrink-0 {
            color: white;
        }
    </style>

    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                    業務サポート情報
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
                    <div class="mt-8 text-2xl border-l-4 border-blue-500 pl-4">
                        <b>{{ $title }}</b>
                    </div>
                    <div class="bg-opacity-25 mt-4">

                        <div class="p-4">
                            <div class="flex items-center">

                                <body>
                                    <img class="mr-2" src="data:image/png;base64,{{Config::get('base64.search')}}">
                                    <h2 class="flex items-center text-2xl font-extrabold dark:text-white" style="display: flex; align-items: center; border-bottom: 2px solid #3e3a39; padding: 7px 0 6px; flex-grow: 1;">
                                        キーワードで検索する
                                    </h2>
                                </body>
                            </div>
                            <div class="ml-12">
                                <div class="mt-2 text-gray-500">
                                    検索対象：マニュアル
                                </div>
                            </div>
                            <form action="{{ route('dashboard.search') }}" method="GET">
                                @method('get')
                                <div class="ml-12 mt-5 flex items-center">
                                    <input type="search" name="search" id="default-search" class="block w-60 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="ここに文字を入力してください。">
                                    <input type="hidden" name="search_target" value="document"> <!-- 検索対象をtaskに絞るためのhidden要素 -->
                                    <button type="submit" class="ml-2 py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg">検索</button>
                                </div>
                            </form>
                        </div>

                        <div class="p-4">
                            <div class="ml-12">
                                <div class="mt-2 text-gray-500">
                                    ご覧になりたい作業をクリックしてください。
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2">
                                    @foreach ($documents as $document)
                                    <div>
                                        <a href="{{ route('dashboard.documents_details', ['id' => $document->id]) }}" class="my-4 inline-flex items-center justify-start w-210 px-4 py-2 mb-2 text-sm font-bold text-black border border-gray-400 rounded-md hover:bg-blue-500 hover:text-white hover:border-transparent sm:w-auto sm:mb-0" style="width: 250px;">
                                            <svg class="h-5 w-5 -ml-1 mr-2 fill-current text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path d="M15 10l-5-5v10l5-5z" />
                                            </svg>
                                            <span class="flex-shrink-0 self-start">{{ $document->title }}</span>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            {{ $documents->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>