<div x-data="{ showModal1: false, documents: [], procedureId: null }">
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
                    <div class="mt-8 text-2xl border-l-4 border-red-500 pl-4">
                            <b>{{ $title }}</b>
                        </div>

                        <div class="bg-opacity-25 mt-4">
                            <div class="p-4">
                                @foreach ($sortedProcedures as $groupIndex => $procedureGroup)
                                @php
                                $flowNumber = $groupIndex + 1;
                                @endphp
                                <div class="flex items-center">
                                    <img class="mr-2" src="data:image/png;base64,{{Config::get('base64.task')}}">
                                    <h2 class="mt-4 flex items-center text-2xl font-extrabold dark:text-white" style="display: flex; align-items: center; border-bottom: 2px solid #ba2636; padding: 7px 0 6px; flex-grow: 1;">
                                        作業{{ $flowNumber }} 一覧
                                    </h2>
                                </div>
                                <div class="ml-12">
                                    <div class="mt-2 text-gray-500">
                                        ご覧になりたい手順をクリックしてください。
                                    </div>
                                    @php
                                    $count = 1;
                                    @endphp
                                    @foreach ($procedureGroup as $works)
                                    <div>
                                        <div class="my-4 inline-flex items-center justify-start w-210 px-4 py-2 mb-2 text-sm font-bold text-black border border-gray-400 rounded-md hover:bg-red-500 hover:text-white hover:border-transparent sm:w-auto sm:mb-0" style="width: 250px;" x-data="{ taskId: '{{ $works->task_id }}', procedureId: '{{ $works->id }}' }" @click.stop="showModal1 = !showModal1; fetchDocuments('{{ $works->id }}'); procedureId = '{{ $works->id }}'">
                                            <svg class="h-5 w-5 -ml-1 mr-2 fill-current text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path d="M15 10l-5-5v10l5-5z" />
                                            </svg>
                                            <span class="flex-shrink-0 self-start">
                                                手順{{ $count }}: {{ $works->name }}
                                            </span>
                                        </div>
                                    </div>
                                    @php
                                    $count++;
                                    @endphp
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                        </div>

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