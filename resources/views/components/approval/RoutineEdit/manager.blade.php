<x-app-layout>

    @section('title', $title . ' / ' . config('app.name', 'Laravel'))

    <x-slot name="header">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('approval.index') }}" class="inline-flex items-center text-base font-medium text-blue-700 hover:text-blue-600 dark:text-blue-400 dark:hover:text-white">
                    承認管理
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

                                        <form method="POST" action="{{ route('approval.routine_update', ['routines' => $routines->id]) }}">
                                            @csrf
                                            @method('post')
                                            <x-jet-validation-errors class="mb-4" />
                                            <div class="previous-procedures" id="previous_procedures">
                                                @foreach ($procedures as $index => $procedure)
                                                @php
                                                $stepNumber = $index + 1;
                                                $isFirstStep = ($index === 0);
                                                $isLastStep = ($index === count($procedures) - 1);
                                                $stepName = ($isFirstStep) ? '最初の手順' : ($isLastStep ? '最後の手順' : '手順' . $stepNumber);
                                                @endphp
                                                <div class="Form-Item">
                                                    <p class="Form-Item-Label">
                                                        {!! $stepName !!}
                                                    </p>
                                                    <input type="text" name="previous_procedure_id[{{ $index }}]" class="Form-Item-Input" value="{{ $procedure['name'] }}" readonly>
                                                </div>
                                                @endforeach
                                            </div>
                                            <hr>

                                            <div class="Form-Item">
                                                <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>ステータス</p>
                                                <!-- 0：申請中（承認待ち）、1：承認、2：否認、3：取り下げ -->
                                                <select class="Form-Item-Input" name="approved">
                                                    <option value="0" {{ $routines->approved == 0 ? 'selected' : '' }}>承認待ち</option>
                                                    <option value="1" {{ $routines->approved == 1 ? 'selected' : '' }}>承認</option>
                                                    <option value="2" {{ $routines->approved == 2 ? 'selected' : '' }}>否認</option>
                                                </select>
                                            </div>
                                            <hr>

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
</x-app-layout>