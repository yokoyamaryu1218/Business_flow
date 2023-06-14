<div x-data="{ showModal1: false, procedures: [], procedureId: null }">
    <x-app-layout>
        @section('title', $title . ' / ' . config('app.name', 'Laravel'))

        <style>
            hr.dashed {
                border: none;
                border-top: 1px dashed rgba(0, 0, 0, 0.5);
                /* 例えば、半透明の灰色に設定 */
                height: 0;
            }
        </style>

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
                        <div class="mt-8 text-2xl border-l-4 border-black pl-4">
                            <b>{{ $title }}</b>
                        </div>

                        <div class="bg-opacity-25 mt-4">
                            <form action="{{ route('dashboard.search') }}" method="GET">
                                @method('get')
                                <div class="ml-12 mt-5 flex items-center">
                                    <input type="search" name="search" id="default-search" class="block w-60 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $search }}" placeholder="ここに文字を入力してください。">
                                    <button type="submit" class="ml-2 py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg">検索</button>
                                </div>
                            </form>
                        </div>

                        <div class="flex items-center">
                            <h2 class="mt-4 flex items-center text-2xl font-extrabold dark:text-white" style="display: flex; align-items: center; border-bottom: 2px solid #3e3a39; padding: 7px 0 6px; flex-grow: 1;">
                                "{{ $search }}"の検索結果：
                            </h2>
                        </div>
                        <div class="my-4">
                            @if (!empty($search_list['task']) || !empty($search_list['procedure']) || !empty($search_list['document']))
                            <div class="my-4">
                                @if (!empty($search_list['task']) && ($search_target == 'task' || $search_target == null))
                                <h3 class="font-bold">作業 検索結果:{{ count($search_list['task'])}}</h3>
                                <ul class="my-2 ml-4">
                                    @foreach ($search_list['task'] as $task)
                                    <li>
                                        <a href="{{ route('dashboard.task_details', ['id' => $task['id']]) }}" class="text-blue-700 hover:text-red-500">
                                            {{ $task['name'] }}
                                        </a>
                                    </li>
                                    <!-- 他のタスクの情報を表示する場合は、必要なプロパティを追加します -->
                                    @endforeach
                                </ul>
                                @else
                                @if ($search_target != 'task')
                                <h3 class="font-bold">作業 検索結果:</h3>
                                <ul class="my-2 ml-4">
                                    <p>検索ワードに一致する「作業」は見つかりませんでした。</p>
                                </ul>
                                @endif
                                @endif
                                <hr class="dashed">
                            </div>

                            @if (!empty($search_list['procedure']) && ($search_target == 'procedure' || $search_target == null))
                            <h3 class="font-bold">手順 検索結果:</h3>
                            <ul class="my-2 ml-4">
                                @foreach ($search_list['procedure'] as $procedure)
                                <li class="my-2">
                                    <a class="text-blue-700 hover:text-red-500" x-data="{ taskId: '{{ $procedure['task_id'] }}', procedureId: '{{ $procedure['id'] }}' }" @click.stop="showModal1 = !showModal1; fetchDocuments('{{ $procedure['id'] }}'); procedureId = '{{ $procedure['id'] }}'">
                                        {{ $procedure['name'] }}
                                    </a>
                                </li>
                                <!-- 他の手順の情報を表示する場合は、必要なプロパティを追加します -->
                                @endforeach
                            </ul>
                            @else
                            @if ($search_target != 'procedure')
                            <h3 class="font-bold">手順 検索結果:</h3>
                            <ul class="my-2 ml-4">
                                <p>検索ワードに一致する「手順」は見つかりませんでした。</p>
                            </ul>
                            @endif
                            @endif
                            <hr class="dashed">

                            @if (!empty($search_list['document']) && ($search_target == 'document' || $search_target == null))
                            <h3 class="font-bold">マニュアル 検索結果:</h3>
                            <ul class="my-2 ml-4">
                                @foreach ($search_list['document'] as $document)
                                <li class="my-2">
                                    <a href="{{ route('dashboard.documents_details', ['id' => $document['id']]) }}" class="text-blue-700 hover:text-red-500">
                                        {{ $document['title'] }}
                                    </a>
                                </li>
                                <!-- 他のドキュメントの情報を表示する場合は、必要なプロパティを追加します -->
                                @endforeach
                            </ul>
                            @else
                            @if ($search_target != 'document')
                            <h3 class="font-bold">マニュアル 検索結果:</h3>
                            <ul class="my-2 ml-4">
                                <p>検索ワードに一致する「マニュアル」は見つかりませんでした。</p>
                            </ul>
                            @endif
                            @endif
                            <hr class="dashed">
                        </div>
                        @else
                        "{{ $search }}"の検索結果は0件です。</br>
                        あなたがお探しの検索ワードに一致する情報は見つかりませんでした。
                        @endif
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