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

    <div class="pt-10 pb-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mt-8 text-2xl border-l-4 border-black pl-4">
                        <b>{{ $title }}</b>
                    </div>

                    <div class="bg-opacity-25 mt-4">
                        <div class="p-4">

                            <div class="flex items-center justify-end">
                                <div class="hidden md:block">
                                    <details class="w-30 bg-indigo-500 p-4 rounded-xl shadow-md group mx-auto overflow-hidden max-h-[56px] open:!max-h-[400px] transition-[max-height] duration-500 overflow-hidden hover:bg-indigo-600">
                                        <summary class="outline-none cursor-pointer focus:underline focus:text-indigo-600 font-semibold marker:text-transparent group-open:before:rotate-90 before:origin-center relative before:w-[18px] before:h-[18px] before:transition-transform before:duration-200 before:-left-1 before:top-2/4 before:-translate-y-2/4 before:absolute before:bg-no-repeat before:bg-[length:18px_18px] before:bg-center before:bg-[url('data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20class%3D%22h-6%20w-6%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%20stroke-width%3D%222%22%3E%0A%20%20%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20d%3D%22M9%205l7%207-7%207%22%20%2F%3E%0A%3C%2Fsvg%3E')]">
                                            <span class="text-white">確認</span>
                                        </summary>
                                        @if($document->is_visible === 1)
                                        <hr class="my-2 scale-x-150" />
                                        <div class="text-sm -m-4 -mt-2 p-4 bg-gray-50 hover:bg-gray-300" onclick="window.open('{{ route('dashboard.documents_details', ['id' => $document->id]) }}', '_blank')">
                                            <a class="text-black">表示確認</a>
                                        </div>
                                        @endif
                                        <hr class="my-2 scale-x-150" />
                                        <div class="text-sm -m-4 -mt-2 p-4 bg-gray-50 hover:bg-gray-300" onclick="window.location.href = '{{ route('document.file_download', ['document' => $document->id]) }}'">
                                            <a class="text-black">マニュアルダウンロード</a>
                                        </div>
                                    </details>
                                </div>

                                @if($document->is_visible === 1)
                                <div class="md:hidden">
                                    <button type="button" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" onclick="window.open('{{ route('dashboard.documents_details', ['id' => $document->id]) }}', '_blank')">表示確認</button>
                                </div>
                                @endif
                            </div>

                            <section class="text-gray-600 body-font">
                                <div class="container py-5 mx-auto flex flex-wrap">
                                    <div class="lg:w-2/3 mx-auto">

                                        <form method="POST" action="{{ route('document.update', ['document' => $document->id]) }}">
                                            @csrf
                                            @method('post')
                                            <x-jet-validation-errors class="mb-4" />

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">編集可</span>マニュアル名</p>
                                                <input type="text" id="document_title" class="Form-Item-Input" name="document_title" value="{{ $document->title }}" required>
                                            </div>
                                            <hr>

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">編集可</span>マニュアル内容</p>
                                            </div>
                                            <div class="flex flex-wrap w-full px-10 mb-4" style="background-color: #efefef; position: relative; height: 200px; overflow: hidden;">
                                                <textarea id="document_details" name="document_details" class="absolute inset-0 w-full resize-none outline-none border-none bg-transparent" style="resize: none; background-color: #efefef; height: 100%;" required>{{ $fileContents }}</textarea>
                                            </div>
                                            <hr>

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">編集可</span>公開設定</p>
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

    <!-- マニュアルを削除できるのはマネージャ以上 -->
    @if(Auth::user()->role !== 9)
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="bg-opacity-25 mt-4">
                        <div class="p-4">

                            <p class="Form-Item-Label mt-4">マニュアル削除：</p>

                            <section class="text-gray-600 body-font">
                                <div class="container py-1 mx-auto flex flex-wrap">
                                    <div class="lg:w-2/3 mx-auto">
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
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>