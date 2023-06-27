<div x-data="{ showModal1: false, documents: [], procedureId: null }">
    <x-app-layout>

        @section('title', $title . ' / ' . config('app.name', 'Laravel'))

        <x-slot name="header">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white small-text">
                        業務サポート情報
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('dashboard.tasks') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white small-text">
                            作業一覧
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-base font-semibold md:ml-2 small-text">
                            {{ $title }}
                        </span>
                    </div>
                </li>
            </ol>
        </x-slot>

        <div class="pt-10 pb-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div class="mt-8 text-2xl border-l-4 border-red-500 pl-4">
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
                                <form action="{{ route('dashboard.procedures_search', ['id' => $task->id] ) }}" method="GET">
                                    @method('get')
                                    <div class="ml-12 mt-5 flex items-center mobile-ml-0">
                                        <input type="search" name="search" id="default-search" class="block w-60 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="ここに文字を入力してください。">
                                        <button type="submit" class="ml-2 py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg small-button">検索</button>
                                    </div>
                                </form>
                                <div class="ml-12 mobile-ml-0">
                                    <div class="mt-2 text-gray-500">
                                        検索対象：手順（{{ $task->name }}）
                                    </div>
                                </div>
                            </div>

                            <div class="p-4">
                                <div class="ml-12 mobile-ml-0">
                                    @if (count($procedures) > 0)
                                    <div class="mt-2 text-gray-500">
                                        ご覧になりたい手順をクリックしてください。
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        @foreach ($procedures as $index => $procedure)
                                        <div>
                                            <div class="my-4 inline-flex items-center justify-start w-210 px-4 py-2 mb-2 text-sm font-bold text-black border border-gray-400 rounded-md hover:bg-red-500 hover:text-white hover:border-transparent sm:w-auto sm:mb-0 navLink" x-data="{ taskId: '{{ $procedure->task_id }}', procedureId: '{{ $procedure->id }}' }" @click.stop="showModal1 = !showModal1; fetchDocuments('{{ $procedure->id }}'); procedureId = '{{ $procedure->id }}'">
                                                <svg class="h-5 w-5 -ml-1 mr-2 fill-current text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path d="M15 10l-5-5v10l5-5z" />
                                                </svg>
                                                <span class="flex-shrink-0 self-start">{{ $procedure->name }}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <section class="text-gray-600 body-font">
                                        <div class="container px-5 mx-auto mt-4">
                                            現在、手順の登録はありません。
                                        </div>
                                    </section>
                                    @endif
                                </div>
                                {{ $procedures->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="md:p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div class="mt-8 text-2xl border-l-4 border-red-500 pl-4">
                            <b>ルーティン一覧</b>
                        </div>

                        <div class="bg-opacity-25 mt-4">
                            <div class="md:p-4">
                                @if (count($sortedProcedures) > 0)
                                @foreach ($sortedProcedures as $groupIndex => $procedureGroup)
                                @php
                                $flowNumber = $groupIndex + 1;
                                @endphp
                                <div class="flex items-center">
                                    <img class="mr-2" src="data:image/png;base64,{{Config::get('base64.routine')}}">
                                    <h2 class="mt-4 flex items-center text-2xl font-extrabold dark:text-white" style="display: flex; align-items: center; border-bottom: 2px solid #ba2636; padding: 7px 0 6px; flex-grow: 1;">
                                        ルーティン{{ $flowNumber }}
                                    </h2>
                                </div>
                                <div class="ml-12 mobile-ml-0">
                                    <div class="mt-2 text-gray-500">
                                        ご覧になりたい手順名をクリックしてください。
                                    </div>
                                    <div class="relative m-3 flex flex-wrap mx-auto justify-left">
                                        @if ($procedureGroup !== null)
                                        @foreach ($procedureGroup as $index => $procedure)
                                        <style>
                                            .routine:hover p.text-base {
                                                color: white;
                                            }
                                        </style>
                                        <div class="routine max-w-[320px] bg-white border border-gray-400 shadow-md rounded-3xl p-2 mx-1 my-3 cursor-pointer transition-colors duration-300 hover:bg-red-500 small-element" x-data="{ taskId: '{{ $procedure->task_id }}', procedureId: '{{ $procedure->id }}' }" @click.stop="showModal1 = !showModal1; fetchDocuments('{{ $procedure->id }}'); procedureId = '{{ $procedure->id }}'">
                                            <div class="mt-2 pl-1 mb-1 flex items-start">
                                                <div class="mt-2 pl-1 mb-1 hover-red-text">
                                                    <div>
                                                        <p class="text-base font-semibold text-gray-900 mb-0">手順{{ $loop->iteration }}</p>
                                                        <p class="text-base font-semibold text-gray-900 mb-0">{{ $procedure->name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if (!$loop->last)
                                        <div class="flex justify-center items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                        @endif
                                        @endforeach
                                        @endif
                                    </div>
                                    @if (!$loop->last && !empty($sortedProcedures[$groupIndex + 1]))
                                    <hr>
                                    @endif
                                </div>
                                @endforeach
                                @else
                                <section class="text-gray-600 body-font">
                                    <div class="container px-5 mx-auto mt-4">
                                        現在、ルーティンの登録はありません。
                                    </div>
                                </section>
                                @endif
                            </div>
                        </div>
                        {{ $routines->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal1 -->
        <div class="fixed inset-0 flex items-center justify-center z-20 bg-black bg-opacity-50 duration-300" x-show="showModal1" x-transition:enter="transition duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
            <div class="relative sm:w-3/4 md:w-1/2 lg:w-1/3 mx-2 sm:mx-auto my-10 opacity-100" @click.away="showModal1 = false">
                <livewire:documents :key="$task->id" />
            </div>
        </div>

        <script>
            function fetchDocuments(procedureId) {
                Livewire.emit('fetchDocuments', procedureId);
            }
        </script>
    </x-app-layout>
</div>