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

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="Form">
                    <x-status class="mb-4" />

                    <p class="Form-Item-Label mt-4">登録作業一覧</p>
                    <button type="button" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" onclick="window.location.href = '{{ route('task.create') }}'">新規登録</button>

                    <section class="text-gray-600 body-font">
                        <div class="container px-5 py-8 mx-auto">
                            <table class="table-auto w-full text-left whitespace-no-wrap">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">作業名</th>
                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">公開設定</th>
                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                                    </tr>
                                </thead>
                                @foreach ($tasks as $task)
                                <tbody>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="font-medium border-t-2 border-gray-200 px-4 py-3">{{ $task->name }}</td>
                                        <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                            @if ($task->is_visible)
                                            表示中
                                            @else
                                            非表示
                                            @endif
                                        </td>
                                        <td class="font-medium border-t-2 border-gray-200 px-4 py-3">
                                            <a href="{{ route('task.edit', ['task' => $task->id]) }}" class="edit-button text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none">編集</a>
                                        </td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </section>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>