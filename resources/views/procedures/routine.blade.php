<x-app-layout>
    @section('title', $title . ' / ' . 'businessflow')

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('task.index') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                    作業管理
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('task.edit', ['task' => $task_id]) }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                        作業詳細
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

    <link rel="stylesheet" href="{{ asset('/css/self.css') }}">

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mt-8 text-2xl border-l-4 border-black pl-4">
                        <b>{{ $title }}</b>
                    </div>
                </div>

                <div class="bg-opacity-25 mt-4">
                    <div class="p-4">


                        <section class="text-gray-600 body-font">
                            <div class="bg-opacity-25 mt-4">
                                <div class="p-4">

                                    <section class="text-gray-600 body-font">
                                        <div class="container py-5 mx-auto flex flex-wrap">
                                            <div class="lg:w-2/3 mx-auto">

                                                <form method="POST" action="{{ route('procedure.routine_update', ['id' => $task_id]) }}">
                                                    @csrf
                                                    @method('post')

                                                    <div class="previous-procedures" id="previous_procedures">
                                                        @foreach ($procedures as $index => $procedure)
                                                        @php
                                                        $stepNumber = $index + 1;
                                                        $isFirstStep = ($index === 0);
                                                        $isLastStep = ($index === count($procedures) - 1);
                                                        $stepName = ($isFirstStep) ? '最初の手順' : (($isLastStep) ? '最後の手順' : '手順' . $stepNumber);
                                                        @endphp
                                                        <div class="Form-Item">
                                                            <p class="Form-Item-Label">{{ $stepName }}</p>
                                                            @if ($isFirstStep || $isLastStep)
                                                            <input type="text" name="previous_procedure_id[{{ $index }}]" class="Form-Item-Input" value="{{ $procedure['name'] }}" readonly>
                                                            @else
                                                            <select name="previous_procedure_id[{{ $index }}]" class="Form-Item-Input">
                                                                <option value="">手順を選択してください</option>
                                                                @foreach ($procedure_list as $procedureItem)
                                                                @php
                                                                $selected = ($procedureItem['id'] === $procedure['id']) ? 'selected' : '';
                                                                @endphp
                                                                <option value="{{ $procedureItem['id'] }}" {{ $selected }}>{{ $procedureItem['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                            @endif
                                                            <div class="ml-2">
                                                                @if ($isFirstStep)
                                                                <button id="add_previous" type="button" class="text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none addButton hover:bg-green-600">行追加</button>
                                                                @else
                                                                @if ($isLastStep)
                                                                <button class="deleteButton text-white rounded-md text-center bg-gray-400 py-2 px-4 inline-flex items-center focus:outline-none cursor-not-allowed" disabled>行削除</button>
                                                                @else
                                                                <button class="deleteButton text-white rounded-md text-center bg-red-400 py-2 px-4 inline-flex items-center focus:outline-none hover:bg-red-600">行削除</button>
                                                                @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>

                                                    <div style="display: none;">
                                                        {{-- 前の手順用プルダウン --}}
                                                        <div id="previous_select_ary">
                                                            <p class="Form-Item-Label">手順<span x-text="stepNumber"></span></p>
                                                            <select name="previous_procedure_id[]" id="previous_procedure_id" class="Form-Item-Input">
                                                                <option value="">手順を選択してください</option>
                                                                @foreach ($procedure_list as $procedure)
                                                                <option value="{{ $procedure['id'] }}">{{ $procedure['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="ml-2">
                                                                <button class="deleteButton text-white rounded-md text-center bg-red-400 py-2 px-4 inline-flex items-center focus:outline-none hover:bg-red-600">行削除</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex justify-between items-center mt-4">
                                                        <button type="button" class="text-white bg-yellow-400 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                                        <button type="submit" class="text-white bg-indigo-500 hover:bg-indigo-600 border-0 py-2 px-6 focus:outline-none rounded">登録</button>
                                                    </div>
                                                </form>
                                            </div>
                                    </section>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/routine-option.js') }}" defer></script>
    </div>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="bg-opacity-25 mt-4">
                        <div class="p-4">

                            <p class="Form-Item-Label mt-4">ルーティン削除：</p>

                            <section class="text-gray-600 body-font">
                                <div class="container py-1 mx-auto flex flex-wrap">
                                    <div class="lg:w-2/3 mx-auto">
                                        <form method="POST" action="{{ route('procedure.routine_delete', ['id' => $task_id]) }}">
                                            @csrf
                                            @method('delete')
                                            <div class="flex justify-between my-4">
                                                <button type="button" class="flex mb-4 text-white bg-yellow-500 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                                <button onclick="return confirm('選択したルーティンを削除してもよろしいですか？')" type="submit" class="flex mb-4 ml-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">削除</button>
                                            </div>
                                        </form>
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