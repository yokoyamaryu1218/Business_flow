<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            マニュアル 新規登録
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('/css/self.css')  }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-2xl py-4 mx-auto">

                    <form method="POST" action="{{ route('document.store') }}">
                        @csrf
                        @method('post')
                        <div class="Form">
                            <div class="Form-Item">
                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>マニュアルタイトル</p>
                                <input type="text" id="document_title" class="Form-Item-Input" name="document_title" :value="old('document_title')" required>
                            </div>
                            <hr>

                            <div class="Form-Item">
                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>マニュアル内容</p>
                                <textarea class="Form-Item-Textarea" id="document_details" name="document_details">{{ old('document_details') }}</textarea>
                            </div>

                            <input type="submit" class="Form-Btn" value="登録">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>