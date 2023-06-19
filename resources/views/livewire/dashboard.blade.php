<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    <div>
        <div class="block h-12" style="display: flex;">
            <img class="w-15 mr-2" src="data:image/png;base64,{{Config::get('base64.logo1')}}">
            <img class="w-30" alt="Logo 2" src="data:image/png;base64,{{Config::get('base64.logo2')}}">
        </div>
    </div>

    <div class="mt-8 text-2xl border-l-4 border-black pl-4">
        業務サポート情報
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

            <div class="bg-opacity-25 mt-4">
                <form action="{{ route('dashboard.search') }}" method="GET">
                    @method('get')
                    <div class="ml-12 mt-5 flex items-center">
                        <input type="search" name="search" id="default-search" class="block w-60 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('search') }}" placeholder="ここに文字を入力してください。">
                        <button type="submit" class="ml-2 py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg">検索</button>
                    </div>
                    <div class="ml-12 mt-2">
                        <span class="text-gray-700">検索対象:</span>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="search_target[]" value="task" class="form-checkbox">
                            <span class="ml-2">作業</span>
                        </label>
                        <label class="inline-flex items-center ml-4">
                            <input type="checkbox" name="search_target[]" value="procedure" class="form-checkbox">
                            <span class="ml-2">手順</span>
                        </label>
                        <label class="inline-flex items-center ml-4">
                            <input type="checkbox" name="search_target[]" value="document" class="form-checkbox">
                            <span class="ml-2">マニュアル</span>
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="bg-opacity-25 mt-4">
        <div class="p-4">
            <div class="flex items-center">

                <body>
                    <img class="mr-1" src="data:image/png;base64,{{Config::get('base64.task')}}">
                    <h2 class="flex items-center text-2xl font-extrabold dark:text-white" style="display: flex; align-items: center; border-bottom: 2px solid #ba2636; padding: 7px 0 6px; flex-grow: 1;">
                        作業一覧
                    </h2>
                </body>
            </div>
            <div class="ml-12">
                <div class="mt-2 text-gray-500">
                    ご覧になりたい作業をクリックしてください。
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2">
                    @foreach ($work_list as $index => $works)
                    @if ($index < 9) <div>
                        <a href="{{ route('dashboard.task_details', ['id' => $works->id]) }}" class="my-4 inline-flex items-center justify-start w-210 px-4 py-2 mb-2 text-sm font-bold text-black border border-gray-400 rounded-md hover:bg-red-500 hover:text-white hover:border-transparent sm:w-auto sm:mb-0 navLink">
                            <svg class="h-5 w-5 -ml-1 mr-2 fill-current text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M15 10l-5-5v10l5-5z" />
                            </svg>
                            <span class="flex-shrink-0 self-start">{{ $works->name }}</span>
                        </a>
                </div>
                @elseif ($index == 9)
                <div>
                    <a href="{{ route('dashboard.tasks') }}" class="my-4 inline-flex items-center justify-start w-210 px-4 py-2 mb-2 text-sm font-bold text-black border border-gray-400 rounded-md hover:bg-red-500 hover:text-white hover:border-transparent sm:w-auto sm:mb-0 navLink">
                        <svg class="h-5 w-5 -ml-1 mr-2 fill-current text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M19 11H13V5C13 4.45 12.55 4 12 4C11.45 4 11 4.45 11 5V11H5C4.45 11 4 11.45 4 12C4 12.55 4.45 13 5 13H11V19C11 19.55 11.45 20 12 20C12.55 20 13 19.55 13 19V13H19C19.55 13 20 12.55 20 12C20 11.45 19.55 11 19 11Z" />
                        </svg>
                        <span class="flex-shrink-0 self-start">全作業一覧へ</span>
                    </a>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-opacity-25 mt-4">
        <div class="p-4">
            <div class="flex items-center">

                <body>
                    <img class="mr-1" src="data:image/png;base64,{{Config::get('base64.book')}}">
                    <h2 class="flex items-center text-2xl font-extrabold dark:text-white" style="display: flex; align-items: center; border-bottom: 2px solid #00a3af; padding: 7px 0 6px; flex-grow: 1;">
                        マニュアル一覧
                    </h2>
                </body>
            </div>
            <!-- ここで表示位置を変えられる -->
            <div class="ml-12"> 
                <div class="mt-2 text-gray-500">
                    ご覧になりたいマニュアルをクリックしてください。
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2">
                    @foreach ($document_list as $index => $document)
                    @if ($index < 9) <div>
                        <a href="{{ route('dashboard.documents_details', ['id' => $document->id]) }}" class="my-4 inline-flex items-center justify-start w-210 px-4 py-2 mb-2 text-sm font-bold text-black border border-gray-400 rounded-md hover:bg-blue-500 hover:text-white hover:border-transparent sm:w-auto sm:mb-0 navLink">
                            <svg class="h-5 w-5 -ml-1 mr-2 fill-current text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M15 10l-5-5v10l5-5z" />
                            </svg>
                            <span class="flex-shrink-0 self-start">{{ $document->title }}</span>
                        </a>
                </div>
                @elseif ($index == 9)
                <div>
                    <a href="{{ route('dashboard.documents') }}" class="my-4 inline-flex items-center justify-start w-210 px-4 py-2 mb-2 text-sm font-bold text-black border border-gray-400 rounded-md hover:bg-blue-500 hover:text-white hover:border-transparent sm:w-auto sm:mb-0 navLink">
                        <svg class="h-5 w-5 -ml-1 mr-2 fill-current text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M19 11H13V5C13 4.45 12.55 4 12 4C11.45 4 11 4.45 11 5V11H5C4.45 11 4 11.45 4 12C4 12.55 4.45 13 5 13H11V19C11 19.55 11.45 20 12 20C12.55 20 13 19.55 13 19V13H19C19.55 13 20 12.55 20 12C20 11.45 19.55 11 19 11Z" />
                        </svg>
                        <span class="flex-shrink-0 self-start">全マニュアル一覧へ</span>
                    </a>
                </div>
                @break
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>