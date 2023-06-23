<x-app-layout>
    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('task.index') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white small-text">
                    作業管理
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('task.edit', ['task' => $task_id]) }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white small-text">
                        作業詳細
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

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
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

                                        <form method="POST" action="{{ route('task.procedure.routine_store', ['id' => $task_id]) }}">
                                            @csrf
                                            @method('post')
                                            <input type="hidden" id="task_id" name="task_id" value="{{ $task_id }}">
                                            <x-jet-validation-errors class="mb-4" />

                                            <div class="previous-procedures" id="previous_procedures">
                                                <div class="Form-Item">
                                                    <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>最初の手順</p>
                                                    <select name="procedure_id[]" class="Form-Item-Input">
                                                        <option value="">手順を選択してください</option>
                                                        @foreach ($previous_procedures_list as $procedure)
                                                        <option value="{{ $procedure['id'] }}">{{ $procedure['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div id="add_waku_previous" class="ml-2 mt-2 md:mt-0">
                                                        <button type="button" id="add_previous" class="text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none addButton hover:bg-green-600">行追加</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>公開設定</p>
                                                <div class="Form-Item-RadioGroup">
                                                    <label>
                                                        <input type="radio" name="is_visible" value="1" checked /> 表示
                                                    </label>
                                                    <label class="hidden md:block">
                                                        <input type="radio" name="is_visible" name="is_visible" value="0" /> 非表示
                                                    </label>
                                                    <label class="md:hidden ml-20">
                                                        <input type="radio" name="is_visible" name="is_visible" value="0" /> 非表示
                                                    </label>
                                                </div>
                                            </div>
                                            <hr>

                                            <div style="display: none;">
                                                {{-- 前の手順用プルダウン --}}
                                                <div id="previous_select_ary">
                                                    <p class="Form-Item-Label">手順<span x-text="stepNumber"></span></p>
                                                    <select name="procedure_id[]" id="procedure_id[]" class="Form-Item-Input">
                                                        <option value="">手順を選択してください</option>
                                                        @foreach ($procedure_list as $procedure)
                                                        <option value="{{ $procedure['id'] }}">{{ $procedure['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="ml-2 mt-2 md:mt-0">
                                                        <button type="button" class="deleteButton text-white rounded-md text-center bg-red-400 py-2 px-4 inline-flex items-center focus:outline-none hover:bg-red-600">行削除</button>
                                                    </div>
                                                </div>
                                                <div id="next_procedures_select_ary">
                                                    <p class="Form-Item-Label">手順<span x-text="stepNumber"></span></p>
                                                    <select name="procedure_id[]" id="procedure_id[]" class="Form-Item-Input">
                                                        <option value="">手順を選択してください</option>
                                                        @foreach ($next_procedures_list as $procedure)
                                                        <option value="{{ $procedure['id'] }}">{{ $procedure['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="ml-2 mt-2 md:mt-0">
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
        </div>
    </div>
    <input type="hidden" id="pageType" value="create">
    <script>
        const pageType = "create";
        var next_procedures_list = @json($next_procedures_list);
        var procedures_list = @json($procedure_list);
    </script>
    <script src="{{ asset('js/routine/option.js') }}" defer></script>
</x-app-layout>