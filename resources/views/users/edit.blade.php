<x-app-layout>

    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('user.index') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                    人員管理
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
                                <div class="container py-1 mx-auto flex flex-wrap">
                                    <div class="lg:w-2/3 mx-auto">

                                        <form method="POST" action="{{ route('user.update', ['id' => $user->employee_number]) }}">
                                            @csrf
                                            @method('post')
                                            <x-jet-validation-errors class="mb-4" />

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label">社員番号</p>
                                                <input type="text" id="employee_number" class="Form-Item-Input" name="employee_number" value="{{ $user->employee_number }}" maxlength="5" readonly title="社員番号は変更できません。">
                                            </div>
                                            <hr>

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">編集可</span>社員名</p>
                                                <input type="text" id="name" class="Form-Item-Input" name="name" value="{{ $user->name }}" required>
                                            </div>
                                            <hr>

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">編集可</span>権限</p>
                                                <select class="Form-Item-Input" name="role">
                                                    <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>管理者</option>
                                                    <option value="5" {{ $user->role == 5 ? 'selected' : '' }}>マネージャー</option>
                                                    <option value="9" {{ $user->role == 9 ? 'selected' : '' }}>一般社員</option>
                                                </select>
                                            </div>

                                            <div class="flex justify-between items-center mt-4">
                                                <button type="button" class="text-white bg-yellow-400 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                                <button type="submit" class="text-white bg-indigo-500 hover:bg-indigo-600 border-0 py-2 px-6 focus:outline-none rounded">登録</button>
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

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="bg-opacity-25 mt-4">
                        <div class="p-4">

                            <p class="Form-Item-Label mt-4">パスワード変更：</p>

                            <section class="text-gray-600 body-font">
                                <div class="container py-1 mx-auto flex flex-wrap">
                                    <div class="lg:w-2/3 mx-auto">

                                        <form method="POST" action="{{ route('user.password_update', ['id' => $user->employee_number]) }}">
                                            @csrf
                                            @method('post')
                                            <x-jet-validation-errors class="mb-4" />

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label">パスワード</p>
                                                <input type="password" id="password" class="Form-Item-Input" name="password" :value="" required title="パスワードは8文字以上、英数混合で入力してください。">
                                            </div>
                                            <hr>

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label">パスワード確認</p>
                                                <input type="password" id="password_confirmation" class="Form-Item-Input" name="password_confirmation" value="" required>
                                            </div>

                                            <div class="flex justify-between items-center mt-4">
                                                <button type="button" class="text-white bg-yellow-400 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                                <button type="submit" class="text-white bg-indigo-500 hover:bg-indigo-600 border-0 py-2 px-6 focus:outline-none rounded">登録</button>
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

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="bg-opacity-25 mt-4">
                        <div class="p-4">
                            <p class="Form-Item-Label mt-4">登録情報削除：</p>
                            <section class="text-gray-600 body-font">
                                <div class="container py-1 mx-auto">
                                    @if(Auth::user()->role !== 9)
                                    <form method="POST" action="{{ route('user.destroy', ['id' => $user->employee_number]) }}">
                                        @csrf
                                        @method('delete')
                                        <div class="flex justify-between items-center my-4 lg:w-2/3 mx-auto"> <!-- lg:w-2/3 を追加し、justify-between から items-center に変更 -->
                                            <button type="button" class="flex mb-4 text-white bg-yellow-500 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                            <button onclick="return confirm('選択した社員の登録を削除してもよろしいですか？')" type="submit" class="flex mb-4 ml-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">削除</button>
                                    </form>
                                    @else
                                    削除権限がありません。
                                    @endif
                                </div>

                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>