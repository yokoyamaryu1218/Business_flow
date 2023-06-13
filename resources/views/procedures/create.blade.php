<div x-data="{ showModal1: false }">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                手順 新規作成
            </h2>
        </x-slot>

        <link rel="stylesheet" href="{{ asset('/css/self.css')  }}">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <form method="POST" action="{{ route('procedure.create', ['id' => $task->id]) }}">
                        @csrf
                        @method('post')
                        <div class="Form">
                            <button type="button" @click="showModal1 = true" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">業務フローを確認する</button>

                            <div class="Form-Item">
                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>手順名</p>
                                <input type="text" class="Form-Item-Input" name="name" id="name" value="">
                                <input type="hidden" name="id" id="id" value="{{ $task->id }}">
                            </div>
                            <hr>

                            <div class="Form-Item">
                                <p class="Form-Item-Label">前の手順</p>
                                <select name="previous_procedure_id[]" class="Form-Item-Input">
                                    <option value="">手順を選択してください</option>
                                    @foreach ($procedure_list as $procedure)
                                    <option value="{{ $procedure['id'] }}">{{ $procedure['name'] }}</option>
                                    @endforeach
                                </select>
                                <div id="add_waku_procedure" class="ml-2">
                                    <button id="add_previous" type="button" class="text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none addButton">ADD</button>
                                </div>
                            </div>
                            <hr>

                            <div class="Form-Item">
                                <p class="Form-Item-Label">後の手順</p>
                                <select name="next_procedure_id[]" class="Form-Item-Input">
                                    <option value="">手順を選択してください</option>
                                    @foreach ($procedure_list as $procedure)
                                    <option value="{{ $procedure['id'] }}">{{ $procedure['name'] }}</option>
                                    @endforeach
                                </select>
                                <div id="add_waku_next" class="ml-2">
                                    <button id="add_next" class="text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none addButton">ADD</button>
                                </div>
                            </div>
                            <hr>

                            <div class="Form-Item">
                                <p class="Form-Item-Label">関連するマニュアル</p>
                                <select name="document_id[]" class="Form-Item-Input">
                                    <option value="">マニュアルを選択してください</option>
                                    @foreach ($documents as $procedure)
                                    <option value="{{ $procedure['id'] }}">{{ $procedure['title'] }}</option>
                                    @endforeach
                                </select>
                                <div id="add_waku_book" class="ml-2">
                                    <button id="add_book" class="text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none addButton">ADD</button>
                                </div>
                            </div>
                            <hr>

                            <div id="additional-inputs"></div>

                            <input type="submit" class="Form-Btn my-4" value="作成する">
                        </div>
                    </form>



                    <div style="display: none;">
                        {* 前の手順用プルダウン *}
                        <div id="previous_select_ary">
                            <p class="Form-Item-Label"></p>
                            <select name="procedure_id[]" id="procedure_id" class="Form-Item-Input">
                                <option value="">手順を選択してください</option>
                                @foreach ($procedure_list as $procedure)
                                <option value="{{ $procedure['id'] }}">{{ $procedure['name'] }}</option>
                                @endforeach
                            </select>
                            <div class="ml-2">
                                <button id="delete_previous" class="text-white rounded-md text-center bg-red-400 py-2 px-4 inline-flex items-center focus:outline-none">DELETE</button>
                            </div>
                        </div>
                        <div id="next_select_ary">
                            <p class="Form-Item-Label"></p>
                            <select name="next_procedure_id[]" class="Form-Item-Input">
                                <option value="">手順を選択してください</option>
                                @foreach ($procedure_list as $procedure)
                                <option value="{{ $procedure['id'] }}">{{ $procedure['name'] }}</option>
                                @endforeach
                            </select>
                            <div class="ml-2">
                                <button id="delete_next" class="text-white rounded-md text-center bg-red-400 py-2 px-4 inline-flex items-center focus:outline-none">DELETE</button>
                            </div>
                        </div>
                        <div id="book_select_ary">
                            <p class="Form-Item-Label"></p>
                            <select name="document_id[]" class="Form-Item-Input">
                                <option value="">マニュアルを選択してください</option>
                                @foreach ($documents as $procedure)
                                <option value="{{ $procedure['id'] }}">{{ $procedure['title'] }}</option>
                                @endforeach
                            </select>
                            <div class="ml-2">
                                <button id="delete_book" class="text-white rounded-md text-center bg-red-400 py-2 px-4 inline-flex items-center focus:outline-none">DELETE</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed inset-0 flex items-center justify-center z-20 bg-black bg-opacity-50 duration-300" x-show="showModal1" x-transition:enter="transition duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
            <div class="relative sm:w-3/4 md:w-1/2 mx-2 sm:mx-auto my-10 opacity-100" @click.away="showModal1 = false">
                <div class="relative bg-white shadow-lg rounded-md text-gray-900 z-20">
                    <header class="flex items-center justify-between p-2">
                        <h2 class="font-semibold">業務フロー</h2>
                        <button class="focus:outline-none p-2" @click="showModal1 = false">
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                            </svg>
                        </button>
                    </header>
                    <div class="text-center"> {{-- 中央配置 --}}
                        @foreach ($sortedProcedures as $groupIndex => $procedureGroup)
                        @php
                        $flowNumber = $groupIndex + 1;
                        @endphp
                        <p class="Form-Item-Label mt-4">【業務フロー{{ $flowNumber }}】</p>
                        @foreach ($procedureGroup as $index => $procedure)
                        <div class="Form-Item">
                            <p class="Form-Item-Label">
                                手順{{ $loop->iteration }}
                            </p>
                            <input type="text" name="procedure_name[{{$groupIndex}}][{{$procedure->id}}]" id="procedure_name_{{ $groupIndex }}_{{ $index }}" class="delete-input Form-Item-Input" value="{{ $procedure->name }}" readonly>
                            <div class="ml-2">
                                <a href="#" class="edit-button text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none">編集する</a>
                            </div>
                        </div>
                        @if ($loop->last && !empty($sortedProcedures[$groupIndex + 1])) {{-- 次の配列が存在する場合 --}}
                        <hr>
                        @endif
                        @endforeach
                        @endforeach
                    </div>
                    <footer class="flex justify-center p-2">
                        <button class="bg-red-600 font-semibold text-white p-2 w-32 rounded-full hover:bg-red-700 focus:outline-none focus:ring shadow-lg hover:shadow-none transition-all duration-300" @click="showModal1 = false">
                            閉じる
                        </button>
                    </footer>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // イベント設定
                $(document).on('click', '#add_previous', addOptionRow); // フォーカス追加
                $(document).on('click', '#delete_previous', clickDeleteItem) // 著者削除

                $(document).on('click', '#add_next', addOptionRow); // フォーカス追加
                $(document).on('click', '#delete_next', clickDeleteItem) // 著者削除

                $(document).on('click', '#add_book', addOptionRow); // フォーカス追加
                $(document).on('click', '#delete_book', clickDeleteItem) // 著者削除

            });

            function addOptionRow(e) {
                e.preventDefault(); // イベントのデフォルトの動作を防止

                let targetName = '';
                switch (e.target.id) {
                    case 'add_previous':
                        targetName = 'previous';
                        break;
                    case 'add_next':
                        targetName = 'next';
                        break;
                    case 'add_book':
                        targetName = 'book';
                        break;
                    default:
                        return;
                }

                // オプションエレメントをクローンする
                let $clone = getCloneOption(targetName + '_select_ary');

                // ADDボタンを押した行の直後に追加する
                $(e.target).closest('.Form-Item').after($clone);
            }

            function getCloneOption(id) {
                // クローンするオプションエレメントを取得
                let $optionRow = $('#' + id).html();

                // クローンを作成
                let $clone = $('<div class="Form-Item">' + $optionRow + '</div>');

                return $clone;
            }

            function clickDeleteItem(e) {
                $(e.target).closest('.Form-Item').remove();
            }
        </script>

    </x-app-layout>
</div>