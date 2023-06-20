<x-app-layout>
    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

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

    <div class="pt-10 pb-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mt-8 text-2xl border-l-4 border-black pl-4">
                        <b>{{ $title }}</b>
                    </div>

                    <div class="bg-opacity-25 mt-4">
                        <div class="p-4">

                            <section class="text-gray-600 body-font">
                                <div class="container py-5 mx-auto flex flex-wrap">
                                    <div class="lg:w-2/3 mx-auto">

                                        <form method="POST" action="{{ route('task.procedure.routine_update', ['id' => $task_id]) }}">
                                            @csrf
                                            @method('post')
                                            <x-jet-validation-errors class="mb-4" />

                                            <div class="previous-procedures" id="previous_procedures">
                                                @foreach ($procedures as $index => $procedure)
                                                @php
                                                $stepNumber = $index + 1;
                                                $isFirstStep = ($index === 0);
                                                $isLastStep = ($index === count($procedures) - 1);
                                                $stepName = ($isFirstStep) ? '最初の手順' : ($isLastStep ? '<span class="Form-Item-Label-Required">編集可</span> 最後の手順' : '<span class="Form-Item-Label-Required">編集可</span> 手順' . $stepNumber);
                                                @endphp

                                                <div class="Form-Item">
                                                    <p class="Form-Item-Label">
                                                        {!! $stepName !!}
                                                    </p>

                                                    @if ($isFirstStep)
                                                    <input type="text" name="previous_procedure_id[{{ $index }}]" class="Form-Item-Input" value="{{ $procedure['name'] }}" readonly>
                                                    @else
                                                    @if ($isLastStep)
                                                    <select name="previous_procedure_id[{{ $index }}]" class="Form-Item-Input">
                                                        <option value="">手順を選択してください</option>
                                                        @foreach ($procedure_list as $procedureItem)
                                                        @php
                                                        $selected = ($procedureItem['id'] === $procedure['id']) ? 'selected' : '';
                                                        @endphp
                                                        <option value="{{ $procedureItem['id'] }}" {{ $selected }}>{{ $procedureItem['name'] }}</option>
                                                        @endforeach
                                                    </select>
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
                                                    @endif

                                                    <div class="ml-2">
                                                        @if ($isFirstStep)
                                                        <button id="add_previous" type="button" class="text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none addButton hover:bg-green-600">行追加</button>
                                                        @else
                                                        <button type="button" class="deleteButton text-white rounded-md text-center bg-red-400 py-2 px-4 inline-flex items-center focus:outline-none hover:bg-red-600">行削除</button>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <hr>

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">編集可</span>公開設定</p>
                                                <div class="Form-Item-RadioGroup">
                                                    <label>
                                                        <input type="radio" name="is_visible" value="1" @if ($routines[0]->is_visible === 1) { checked } @endif> 表示
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="is_visible" value="0" @if ($routines[0]->is_visible === 0) { checked } @endif> 非表示
                                                    </label>
                                                </div>
                                            </div>
                                            <hr>

                                            <div style="display: none;">
                                                {{-- 前の手順用プルダウン --}}
                                                <div id="previous_select_ary">
                                                    <p class="Form-Item-Label">手順<span x-text="stepNumber"></span></p>
                                                    <select name="previous_procedure_id[]" id="previous_procedure_id[]" class="Form-Item-Input">
                                                        <option value="">手順を選択してください</option>
                                                        @foreach ($procedure_list as $procedure)
                                                        <option value="{{ $procedure['id'] }}">{{ $procedure['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="ml-2">
                                                        <button type="button" class="deleteButton text-white rounded-md text-center bg-red-400 py-2 px-4 inline-flex items-center focus:outline-none hover:bg-red-600">行削除</button>
                                                    </div>
                                                </div>
                                                <div id="next_procedures_select_ary">
                                                    <p class="Form-Item-Label">手順<span x-text="stepNumber"></span></p>
                                                    <select name="previous_procedure_id[]" id="previous_procedure_id[]" class="Form-Item-Input">
                                                        <option value="">手順を選択してください</option>
                                                        @foreach ($next_procedures_list as $procedure)
                                                        <option value="{{ $procedure['id'] }}">{{ $procedure['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="ml-2">
                                                        <button type="button" class="deleteButton text-white rounded-md text-center bg-red-400 py-2 px-4 inline-flex items-center focus:outline-none hover:bg-red-600">行削除</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex justify-between items-center mt-4">
                                                <button type="button" class="text-white bg-yellow-400 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                                <button type="submit" class="text-white bg-indigo-500 hover:bg-indigo-600 border-0 py-2 px-6 focus:outline-none rounded">更新</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="pageType" value="edit">
            <script>
                const pageType = "edit";
            </script>
            <script src="{{ asset('js/routine-option.js') }}" defer></script>
        </div>
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
                                        @if(Auth::user()->role !== 9)
                                        <form method="POST" action="{{ route('task.procedure.routine_delete', ['id' => $task_id]) }}">
                                            @csrf
                                            @method('delete')
                                            <div class="flex justify-between my-4">
                                                <button type="button" class="flex mb-4 text-white bg-yellow-500 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                                <button onclick="return confirm('選択したルーティンを削除してもよろしいですか？')" type="submit" class="flex mb-4 ml-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">削除</button>
                                            </div>
                                        </form>
                                        @else
                                        削除権限がありません。
                                        @endif
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