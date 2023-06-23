<div x-data="{ showModal1: false, procedures: [], procedureId: null }">

    <x-app-layout>
        @section('title', $title . ' / ' . config('app.name', 'Laravel'))

        <x-slot name="header">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white pankuzu-text">
                        業務サポート情報
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('dashboard.tasks') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white pankuzu-text">
                            作業一覧
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('dashboard.task_details', ['id' => $procedure->task_id]) }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white pankuzu-text">
                            {{ $procedure->task_name }}手順一覧
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-base font-semibold md:ml-2 pankuzu-text">
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
                        <div class="mt-8 text-2xl border-l-4 border-black pl-4 small-text">
                            <b>{{ $title }}</b>
                        </div>

                        <section class="text-gray-600 body-font">
                            <div class="container py-10 mx-auto flex flex-wrap">
                                <div class="lg:w-4/5 mx-auto">
                                    <div class="flex flex-wrap w-full py-10 px-10 relative mb-4" style="background-color: #efefef;">
                                        <div class="lg:w-4/5 mx-auto">
                                            <h2 class="mt-4 mb-8 text-xl text-gray-900 font-medium title-font">
                                                <span class="md:hidden block">■マニュアル名：<br>{{ $documents[0]->title }}</span>
                                                <span class="hidden md:block">■マニュアル名：{{ $documents[0]->title }}</span>
                                            </h2>
                                            <p class="mb-8 leading-relaxed">{!! nl2br(e($fileContents)) !!}</p>
                                            <div class="font-semibold text-right text-sm">最終更新日：{{ date('Y-m-d', strtotime($documents[0]->updated_at)) }}</div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between">
                                        @if ($previousProcedureIds)
                                        <button class="my-4 ml-4 inline-flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg small-text" x-data="{ taskId: '{{ $procedure->task_id }}' }" @click.stop="showModal1 = !showModal1; fetchProcedures('{{ $procedure->previous_procedure_id }}'); procedureId = '{{ $procedure->previous_procedure_id }}'">
                                            ← 前の手順
                                        </button>
                                        @else
                                        <a class="my-4 ml-4 inline-flex text-gray-700 bg-gray-100 border-0 py-2 px-6 focus:outline-none rounded text-lg small-text">← 前の手順</a>
                                        @endif

                                        @if ($nextProcedureIds)
                                        <button class="my-4 ml-4 inline-flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg small-text" x-data="{ taskId: '{{ $procedure->task_id }}' }" @click.stop="showModal1 = !showModal1; fetchProcedures('{{ $procedure->next_procedure_ids }}'); procedureId = '{{ $procedure->next_procedure_ids }}'">
                                            次の手順 →
                                        </button>
                                        @else
                                        <a class="my-4 ml-4 inline-flex text-gray-700 bg-gray-100 border-0 py-2 px-6 focus:outline-none rounded text-lg small-text">次の手順 →</a>
                                        @endif
                                    </div>
                                    <div class="flex mt-4 items-center">
                                        <img class="w-5 mr-1" src="data:image/png;base64,{{Config::get('base64.link')}}">
                                        <h1 class="mt-4 flex items-center text-1xl font-extrabold dark:text-white" style="display: flex; align-items: center; border-bottom: 2px solid #3e3a39; padding: 7px 0 6px; flex-grow: 1;">
                                            関連マニュアル
                                        </h1>
                                    </div>
                                    @foreach($manuals as $manual)
                                    <div class="flex mt-4 ml-5 items-center">
                                        <img class="w-5 mr-1" src="data:image/png;base64,{{Config::get('base64.comment')}}">
                                        <a href="{{ route('dashboard.procedures', ['id1' => $manual->procedure_id, 'id2' => $manual->document_id]) }}" class="text-gray-500 hover:text-red-500 hover:underline">
                                            {{ $manual->title }}
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal1 -->
        <div class="fixed inset-0 flex items-center justify-center z-20 bg-black bg-opacity-50 duration-300" x-show="showModal1" x-transition:enter="transition duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
            <div class="relative sm:w-3/4 md:w-1/2 lg:w-1/3 mx-2 sm:mx-auto my-10 opacity-100" @click.away="showModal1 = false">
                <livewire:procedures :key="$procedure->next_procedure_ids" />
            </div>
        </div>

        <script>
            function fetchProcedures(procedureId) {
                Livewire.emit('fetchProcedures', procedureId);
            }
        </script>
    </x-app-layout>
</div>