<x-app-layout>
    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('task.index') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                    作業管理
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

    <div class="pt-10 pb-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="Form">
                    <x-status class="mb-4" />

                    <p class="Form-Item-Label mt-4">「{{ $task->name }}」を編集中</p>

                    <form method="POST" action="{{ route('task.update', ['task' => $task->id]) }}">
                        @csrf
                        @method('post')
                        <div class="Form-Item">
                            <p class="Form-Item-Label">作業名</p>
                            <input type="text" class="Form-Item-Input" name="task" id="task" value="{{ $task->name }}">
                        </div>
                        <hr>

                        <div class="Form-Item">
                            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>公開設定</p>
                            <div class="Form-Item-RadioGroup">
                                <label>
                                    <input type="radio" name="is_visible" value="1" @if ($task->is_visible === 1) { checked } @endif> 表示
                                </label>
                                <label>
                                    <input type="radio" name="is_visible" value="0" @if ($task->is_visible === 0) { checked } @endif> 非表示
                                </label>
                            </div>
                        </div>
                        <hr>

                        <div class="flex justify-between mt-4 mb-8">
                            <button type="button" class="text-white bg-yellow-400 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                            <button type="submit" class="text-white bg-indigo-500 hover:bg-indigo-600 border-0 py-2 px-6 focus:outline-none rounded">更新</button>
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
                    <p class="Form-Item-Label mt-4">関連する手順</p>
                    <button type="button" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" onclick="#">手順登録</button>

                    @foreach ($sortedProcedures as $groupIndex => $procedureGroup)
                    @php
                    $flowNumber = $groupIndex + 1;
                    @endphp
                    <p class="Form-Item-Label">【業務フロー{{ $flowNumber }}】</p>
                    @foreach ($procedureGroup as $index => $procedure)
                    <div class="Form-Item">
                        <p class="Form-Item-Label">
                            手順{{ $loop->iteration }}
                        </p>
                        <input type="text" name="procedure_name[{{$groupIndex}}][{{$procedure->id}}]" id="procedure_name_{{ $groupIndex }}_{{ $index }}" class="delete-input Form-Item-Input" value="{{ $procedure->name }}" readonly>
                        <div class="ml-2">
                            <a href="#" class="edit-button text-white rounded-md text-center bg-green-400 py-2 px-4 inline-flex items-center focus:outline-none">編集する</a>
                        </div>
                    </div>
                    @if ($loop->last && !empty($sortedProcedures[$groupIndex + 1]))
                    <hr>
                    @endif
                    @endforeach
                    @endforeach
                    <hr>

                </div>

            </div>

        </div>
    </div>


    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="Form">
                    <p class="Form-Item-Label mt-4">手順削除：</p>

                    <form method="POST" action="{{ route('task.destroy', ['task' => $task->id]) }}">
                        @csrf
                        @method('delete')
                        <div class="flex justify-between my-4">
                            <button type="button" class="flex mb-4 text-white bg-yellow-500 hover:bg-yellow-500 border-0 py-2 px-6 focus:outline-none rounded" onclick="history.back()">戻る</button>
                            <button onclick="return confirm('作業に関連する手順も削除されます。\n本当に削除してもよろしいですか？')" type="submit" class="flex mb-4 ml-auto text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded">削除</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

</x-app-layout>