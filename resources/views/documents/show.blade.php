<div x-data="{ showModal1: false, procedures: [], procedureId: null }">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                マニュアル
            </h2>
        </x-slot>

        <link rel="stylesheet" href="{{ asset('/css/self.css')  }}">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">


                    <div class="Form">
                        <p class="mt-4">{{ $procedure->name }}</p>
                        <p class="mt-4">{{ $documents[0]->title }}</p>

                        <div class="Form-Item">
                            <div class="mb-8 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" style="margin: 0 auto; display: block; width: 80%;">
                                {!! nl2br(e($fileContents)) !!}
                            </div>
                        </div>
                        <hr>

                        <div class="Form-Item">
                            <p class="Form-Item-Label">関連マニュアル</p>
                            <table>
                                <tbody>
                                    @foreach($manuals as $manual)
                                    <tr>
                                        <th>
                                            <a href="{{ route('document.show', ['id1' => $manual->procedure_id, 'id2' => $manual->document_id]) }}" style="color: blue;">
                                                {{ $manual->title }}
                                            </a>
                                        </th>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>

                        <div class="flex justify-between">
                            @if ($previousProcedureId)
                            <button class="my-4 ml-4 inline-flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg" x-data="{ taskId: '{{ $procedure->task_id }}' }" @click.stop="showModal1 = !showModal1; fetchProcedures('{{ $procedure->previous_procedure_id }}'); procedureId = '{{ $procedure->previous_procedure_id }}'">
                                前の手順
                            </button>
                            @else
                            <button class="my-4 ml-4 inline-flex text-gray-700 bg-gray-100 border-0 py-2 px-6 focus:outline-none hover:bg-gray-200 rounded text-lg">後の手順</button>
                            @endif

                            @if ($nextProcedures)
                            <button class="my-4 ml-4 inline-flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg" x-data="{ taskId: '{{ $procedure->task_id }}' }" @click.stop="showModal1 = !showModal1; fetchProcedures('{{ $procedure->next_procedure_ids }}'); procedureId = '{{ $procedure->next_procedure_ids }}'">
                                後の手順
                            </button>
                            @else
                            <button class="my-4 ml-4 inline-flex text-gray-700 bg-gray-100 border-0 py-2 px-6 focus:outline-none hover:bg-gray-200 rounded text-lg">後の手順</button>
                            @endif
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