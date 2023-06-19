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
                            <div class="p-4">

                                <section class="text-gray-600 body-font">
                                    <div class="container py-5 mx-auto flex flex-wrap">
                                        <div class="lg:w-2/3 mx-auto">

                                            <form method="POST" action="{{ route('task.procedure.procedure_store') }}">
                                                @csrf
                                                @method('post')
                                                <x-jet-validation-errors class="mb-4" />

                                                <div class="Form-Item">
                                                    <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>手順名</p>
                                                    <input type="text" class="Form-Item-Input" name="name" id="name" value="{{ old('name') }}" required>
                                                </div>
                                                <hr>

                                                <div class="Form-Item">
                                                    <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>作業名</p>
                                                    <select name="task_id" class="Form-Item-Input">
                                                        @foreach ($task_list as $task)
                                                        <option value="{{ $task['id'] }}">{{ $task['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <hr>


                                                <div class="Form-Item">
                                                    <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>公開設定</p>
                                                    <div class="Form-Item-RadioGroup mx-2">
                                                        <label>
                                                            <input type="radio" name="is_visible" value="1"> 表示
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="is_visible" value="0" checked /> 非表示
                                                        </label>
                                                    </div>
                                                </div>
                                                <hr>

                                                <div class="Form-Item">
                                                    <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>関連するマニュアル</p>
                                                    <select name="document_id[]" class="Form-Item-Input">
                                                        <option value="">マニュアルを選択してください</option>
                                                        @foreach ($documents_list as $procedure)
                                                        <option value="{{ $procedure['id'] }}">{{ $procedure['title'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div id="add_waku_book" class="ml-2">
                                                        <button type="button" id="add_book" class="text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none addButton hover:bg-green-600">行追加</button>
                                                    </div>
                                                </div>
                                                <hr>

                                                <div class="flex justify-between items-center mt-4">
                                                    <button type="button" class="text-white bg-yellow-400 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                                    <button type="submit" class="text-white bg-indigo-500 hover:bg-indigo-600 border-0 py-2 px-6 focus:outline-none rounded">登録</button>
                                                </div>
                                            </form>

                                            <div style="display: none;">
                                                {* 前の手順用プルダウン *}
                                                <div id="book_select_ary">
                                                    <p class="Form-Item-Label"></p>
                                                    <select name="document_id[]" class="Form-Item-Input">
                                                        <option value="">マニュアルを選択してください</option>
                                                        @foreach ($documents_list as $procedure)
                                                        <option value="{{ $procedure['id'] }}">{{ $procedure['title'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="ml-2">
                                                        <button id="delete_book" class="text-white rounded-md text-center bg-red-400 py-2 px-4 inline-flex items-center focus:outline-none hover:bg-red-600">行削除</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/create-add-option.js') }}" defer></script>

    </x-app-layout>