<x-app-layout>

    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('document.index') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                    マニュアル管理
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

    <link rel="stylesheet" href="{{ asset('/css/self.css')  }}">

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-2xl py-4 mx-auto">

                    <form method="POST" action="{{ route('document.store') }}">
                        @csrf
                        @method('post')
                        <div class="Form">
                            <x-jet-validation-errors class="mb-4" />

                            <p class="Form-Item-Label mt-4">マニュアル新規登録</p>

                            <div class="Form-Item">
                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>マニュアルタイトル</p>
                                <input type="text" id="document_title" class="Form-Item-Input" name="document_title" :value="old('document_title')" required>
                            </div>
                            <hr>

                            <div class="Form-Item">
                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>マニュアル内容</p>
                                <textarea class="Form-Item-Textarea" id="document_details" name="document_details">{{ old('document_details') }}</textarea>
                            </div>
                            <hr>

                            <div class="Form-Item">
                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>公開設定</p>
                                <div class="Form-Item-RadioGroup">
                                    <label>
                                        <input type="radio" name="is_visible" value="1"> 表示
                                    </label>
                                    <label>
                                        <input type="radio" name="is_visible" value="0" checked /> 非表示
                                    </label>
                                </div>
                            </div>
                            <hr>

                            <div class="flex justify-between my-4">
                                <button type="button" class="text-white bg-yellow-400 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                <button type="submit" class="text-white bg-indigo-500 hover:bg-indigo-600 border-0 py-2 px-6 focus:outline-none rounded">登録</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>