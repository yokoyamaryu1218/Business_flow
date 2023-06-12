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

    <style>
        input:checked+label {
            border-color: black;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>

    <div class="pt-10 pb-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-2xl py-4 mx-auto">

                    <form method="POST" action="{{ route('document.update', ['document' => $document->id]) }}">
                        @csrf
                        @method('post')
                        <div class="Form">
                            <x-jet-validation-errors class="mb-4" />

                            <p class="Form-Item-Label mt-4">マニュアル編集：</p>

                            <div class="Form-Item">
                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>マニュアルタイトル</p>
                                <input type="text" id="document_title" class="Form-Item-Input" name="document_title" value="{{ $document->title }}" required>
                            </div>
                            <hr>

                            <div class="Form-Item">
                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>マニュアル内容</p>
                                <textarea class="Form-Item-Textarea" id="document_details" name="document_details">{{ $fileContents }}</textarea>
                            </div>
                            <hr>

                            <div class="Form-Item">
                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>公開設定</p>
                                <div class="Form-Item-RadioGroup">
                                    <label>
                                        <input type="radio" name="is_visible" value="1" @if ($document->is_visible === 1) { checked } @endif> 表示
                                    </label>
                                    <label>
                                        <input type="radio" name="is_visible" value="0" @if ($document->is_visible === 0) { checked } @endif> 非表示
                                    </label>
                                </div>
                            </div>
                            <hr>

                            @if (!empty($procedures))
                            @foreach ($procedures as $index => $procedure)
                            <div class="Form-Item">
                                @if ($index === 0)
                                <p class="Form-Item-Label">紐づいている手順</p>
                                @else
                                <p class="Form-Item-Label">&nbsp;</p>
                                @endif
                                <input type="text" name="procedure_name" id="procedure_name" class="delete-input Form-Item-Input" value="{{ $procedure['name'] }}" readonly title="この画面では変更できません。">
                            </div>
                            @endforeach
                            @else
                            <div class="Form-Item">
                                <p class="Form-Item-Label">紐づいている手順</p>
                                <input type="text" ame="procedure_name" id="procedure_name" class="delete-input Form-Item-Input" readonly title="この画面では変更できません。">
                            </div>
                            @endif

                            <div class="flex justify-between my-4">
                                <button type="button" class="flex mb-4 text-white bg-yellow-500 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                <button type="submit" class="flex mb-4 text-white bg-indigo-500 hover:bg-indigo-500 border-0 py-2 px-6 focus:outline-none rounded">更新</button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="Form">
                    <p class="Form-Item-Label mt-4">マニュアル削除：</p>

                    <form method="POST" action="{{ route('document.destroy', ['document' => $document->id]) }}">
                        @csrf
                        @method('delete')
                        <div class="flex justify-between my-4">
                            <button type="button" class="flex mb-4 text-white bg-yellow-500 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                            <button onclick="return confirm('選択したマニュアルを削除してもよろしいですか？')" type="submit" class="flex mb-4 ml-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">削除</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>