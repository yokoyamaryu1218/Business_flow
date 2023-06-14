<div class="relative bg-white shadow-lg rounded-md text-gray-900 z-20">
    <header class="flex items-center justify-between p-2">
        <h2 class="font-semibold">手順一覧<span class="ml-2 text-gray-500 text-sm">手順名を選択してください。表示に時間がかかる場合があります。</span></h2>
        <button class="focus:outline-none p-2" @click="showModal1 = false">
            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
            </svg>
        </button>
    </header>

    <main class="p-2 text-center flex flex-col items-start">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <tbody>
                @if (count($procedures) > 0)
                @foreach ($procedures as $procedure)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-blue-500 dark:hover:bg-blue-500">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="cursor-pointer flex items-center" wire:click="fetchDocuments({{ $procedure['id'] }})">
                            <svg class="h-5 w-5 -ml-1 mr-2 fill-current text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M15 10l-5-5v10l5-5z" />
                            </svg>
                            <span class="flex-shrink-0 self-start">
                                手順{{ $procedure['id'] }}:{{ $procedure['name'] }}
                            </span>
                        </div>
                    </th>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="1" class="px-6 py-4 text-gray-900 dark:text-white text-center">
                        @if (empty($procedures))
                        検索中です...
                        @else
                        関連する手順はありません。
                        @endif
                    </td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- 関連するマニュアルの表示 -->
        @if (!empty($documents))
        <h2 class="font-semibold mt-4">関連マニュアル<span class="ml-2 text-gray-500 text-sm">ご覧になりたいマニュアル名を選択してください。</span></h2>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <tbody>
                @if (count($documents) > 0)
                @foreach ($documents as $document)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-blue-500 dark:hover:bg-blue-500" onclick="window.location='{{ route('dashboard.procedures', ['id1' => $document->procedure_id, 'id2' => $document->document_id]) }}'">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="cursor-pointer flex items-center">
                            <svg class="h-5 w-5 -ml-1 mr-2 fill-current text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M15 10l-5-5v10l5-5z" />
                            </svg>
                            <span class="flex-shrink-0 self-start">
                                {{ $document->title }}
                            </span>
                        </div>
                    </th>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="1" class="px-6 py-4 text-gray-900 dark:text-white text-center">
                        @if (empty($documents))
                        検索中です...
                        @else
                        関連するマニュアルはありません。
                        @endif
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
        @endif
    </main>

    <footer class="flex justify-center p-2">
        <button class="bg-red-600 font-semibold text-white p-2 w-32 rounded-full hover:bg-red-700 focus:outline-none focus:ring shadow-lg hover:shadow-none transition-all duration-300" @click="showModal1 = false">
            閉じる
        </button>
    </footer>
</div>