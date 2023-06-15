<x-app-layout>

    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <span class="ml-1 text-base font-semibold md:ml-2">
                    {{ $title }}
                </span>
            </li>
        </ol>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('/css/self.css')  }}">

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mt-8 text-2xl border-l-4 border-black pl-4">
                        <b>{{ $title }}</b>
                    </div>

                    <div class="bg-opacity-25 mt-4">
                        <div class="p-4">

                            <div class="flex items-center">
                                <body>
                                    <h2 class="flex items-center text-2xl  font-extrabold dark:text-white" style="display: flex; align-items: center; padding: 7px 0 6px; flex-grow: 1;">
                                        登録マニュアル一覧
                                    </h2>
                                    <button type="button" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" onclick="window.location.href = '{{ route('document.create') }}'">新規登録</button>
                                </body>
                            </div>

                            <section class="text-gray-600 body-font">
                                <div class="container py-10 mx-auto flex flex-wrap">
                                    <div class="lg:w-4/5 mx-auto">

                                        <div class="mb-8">
                                            <x-status />
                                        </div>

                                        <table class="table-auto w-full text-left whitespace-no-wrap">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ナンバー</th>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">タイトル</th>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">公開設定</th>
                                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                                                </tr>
                                            </thead>
                                            @foreach ($documents as $document)
                                            <tbody>
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $document->document_number }}</td>
                                                    <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $document->title }}</td>
                                                    <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                                        @if ($document->is_visible)
                                                        表示中
                                                        @else
                                                        非表示
                                                        @endif
                                                    </td>
                                                    <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                                        <a href="{{ route('document.edit', ['document' => $document->id]) }}" class="edit-button text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none transition-colors duration-300 ease-in-out hover:bg-green-600">編集</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            @endforeach
                                        </table>
                                        {{ $documents->links() }}
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>