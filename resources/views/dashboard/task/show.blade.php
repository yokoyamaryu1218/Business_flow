<div x-data="{ showModal1: false, documents: [], procedureId: null }">
    <x-app-layout>

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
                        <a href="{{ route('dashboard.tasks') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                            作業一覧
                        </a>
                    </div>
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
                                        ルーティン{{ $flowNumber }}
                                    </h2>
                                </div>
                                <div class="ml-12">
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
                                        <div class="routine max-w-[320px] bg-white border border-gray-400 shadow-md rounded-3xl p-2 mx-1 my-3 cursor-pointer transition-colors duration-300 hover:bg-red-500" x-data="{ taskId: '{{ $procedure->task_id }}', procedureId: '{{ $procedure->id }}' }" @click.stop="showModal1 = !showModal1; fetchDocuments('{{ $procedure->id }}'); procedureId = '{{ $procedure->id }}'">
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
                            </div>
                        </div>
                        {{ $sortedProcedures->links() }}
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