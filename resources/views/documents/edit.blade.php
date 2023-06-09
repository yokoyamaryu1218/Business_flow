<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            マニュアル 編集
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('/css/self.css')  }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-2xl py-4 mx-auto">
                    @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('document.update', ['document' => $document->id]) }}">
                        @csrf
                        @method('post')
                        <div class="Form">
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

                            @if (!empty($procedures))
                            @foreach ($procedures as $index => $procedure)
                            <div class="Form-Item">
                                @if ($index === 0)
                                <p class="Form-Item-Label">紐づいている手順</p>
                                @else
                                <p class="Form-Item-Label">&nbsp;</p>
                                @endif
                                <input type="text" name="procedure_name" id="procedure_name" class="delete-input Form-Item-Input" value="{{ $procedure['name'] }}" readonly>
                            </div>
                            @endforeach
                            @else
                            <div class="Form-Item">
                                <p class="Form-Item-Label">紐づいている手順</p>
                                <input type="text" name="procedure_name" id="procedure_name" class="delete-input Form-Item-Input" readonly>
                            </div>
                            @endif

                            <div class="flex justify-between my-4">
                                <input type="submit" class="Form-Btn" value="更新">
                                <form method="POST" action="{{ route('document.destroy', ['document' => $document->id]) }}">
                                    @csrf
                                    @method('delete')
                                    <button onclick="return confirm('本当に削除してもよろしいですか？')" class="text-white rounded-md text-center bg-red-500 py-2 px-4 inline-flex items-center focus:outline-none" style="border-radius: 6px; margin-top: 32px; margin-left: auto; margin-right: auto; padding-top: 20px; padding-bottom: 20px; width: 280px; display: block; letter-spacing: 0.05em; background: #ff5454; color: #fff; font-weight: bold; font-size: 20px;">削除</button>
                                </form>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>