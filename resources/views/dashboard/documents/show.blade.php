    <x-app-layout>
        @section('title', $title . ' / ' . config('app.name', 'Laravel'))

        <x-slot name="header">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                        業務サポート情報
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('dashboard.documents') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                            マニュアル一覧
                        </a>
                    </div>
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

                        <section class="text-gray-600 body-font">
                            <div class="container py-10 mx-auto flex flex-wrap">
                                <div class="lg:w-4/5 mx-auto">
                                    <div class="flex flex-wrap w-full py-10 px-10 relative mb-4" style="background-color: #efefef;">
                                        <div class="lg:w-4/5 mx-auto">
                                            <h2 class="mt-4 mb-8 text-xl text-gray-900 font-medium title-font">■マニュアル名：{{ $documents[0]->title }}</h2>
                                            <p class="mb-8 leading-relaxed">{!! nl2br(e($fileContents)) !!}</p>
                                        </div>
                                    </div>

                                    <div class="flex mt-4 items-center">
                                        <img class="w-5 mr-1" src="data:image/png;base64,{{Config::get('base64.link')}}">
                                        <h1 class="mt-4 flex items-center text-1xl font-extrabold dark:text-white" style="display: flex; align-items: center; border-bottom: 2px solid #3e3a39; padding: 7px 0 6px; flex-grow: 1;">
                                            関連する作業・手順について
                                        </h1>
                                    </div>
                                    @foreach($procedures as $procedure)
                                    <div class="flex mt-4 ml-5 items-center">
                                        <img class="w-5 mr-1" src="data:image/png;base64,{{Config::get('base64.comment')}}">
                                        <a href="{{ route('dashboard.procedures', ['id1' => $procedure->id, 'id2' => $documents[0]->document_id]) }}'" class="text-gray-500 hover:text-red-500 hover:underline">
                                            {{ $procedure->task_name}}・{{ $procedure->name }}
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
    </x-app-layout>