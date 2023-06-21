<x-app-layout>

    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('approval.index') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                    申請一覧
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
                                <div class="container py-5 mx-auto flex flex-wrap">
                                    <div class="lg:w-2/3 mx-auto">

                                        <form method="POST" action="{{ route('approval.document_update', ['documents' => $document->id]) }}">
                                            @csrf
                                            @method('post')
                                            <x-jet-validation-errors class="mb-4" />

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>マニュアル名</p>
                                                <input type="text" id="document_title" class="Form-Item-Input" name="document_title" value="{{ $document->title }}" readonly>
                                            </div>
                                            <hr>

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>マニュアル内容</p>
                                            </div>
                                            <div class="flex flex-wrap w-full px-10 mb-4" style="background-color: #efefef; position: relative; height: 200px;">
                                                <textarea id="document_details" name="document_details" class="absolute inset-0 w-full resize-none outline-none border-none bg-transparent" style="resize: vertical; background-color: #efefef;" readonly>{{ $fileContents }}</textarea>
                                            </div>
                                            <hr>

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>ステータス</p>
                                                <div class="select-wrapper">
                                                    <!-- 0：申請中（承認待ち）、1：承認、2：否認、3：取り下げ -->
                                                    <select class="Form-Item-Input" name="approved">
                                                        <option value="0" {{ $document->approved == 0 ? 'selected' : '' }}>承認待ち</option>
                                                        <option value="3" {{ $document->approved == 3 ? 'selected' : '' }}>取り下げ</option>
                                                    </select>
                                                </div>
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
                            <p class="Form-Item-Label mt-4">申請削除：</p>
                            <section class="text-gray-600 body-font">
                                <div class="container py-1 mx-auto">
                                    <form method="POST" action="{{ route('document.destroy', ['document' => $document->document_id]) }}">
                                        @csrf
                                        @method('delete')
                                        <div class="flex justify-between items-center my-4 lg:w-2/3 mx-auto"> <!-- lg:w-2/3 を追加し、justify-between から items-center に変更 -->
                                            <button type="button" class="flex mb-4 text-white bg-yellow-500 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                                            <button onclick="return confirm('選択した申請を削除してもよろしいですか？')" type="submit" class="flex mb-4 ml-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">削除</button>
                                        </div>
                                    </form>
                                </div>

                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>