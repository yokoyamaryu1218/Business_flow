@props(['work_list'])

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <div class="w-10">
                            <img class="mr-1" src="data:image/png;base64,{{Config::get('base64.logo1')}}">
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-jet-nav-link href="/" :active="request()->is('/')">
                        トップページ
                    </x-jet-nav-link>
                </div>

                <!-- Dropdown container -->
                <div x-data="{ open: false }" class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <!-- Dropdown toggle link -->
                    <div @mouseover="open = true" class="flex">
                        <x-jet-nav-link :active="request()->routeIs('dashboard.*')">
                            業務サポート情報
                        </x-jet-nav-link>
                    </div>

                    <!-- Dropdown menu -->
                    <div x-show="open" @mouseleave="open = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90" class="absolute w-48 py-2 mt-12 bg-white rounded-md shadow-xl z-20">
                        <span class="block px-4 py-2 text-sm text-gray-700 bg-white">
                            <b>作業一覧</b>
                        </span>

                        <div class="grid grid-cols-1 md:grid-cols-2">
                            @foreach ($work_list as $index => $works)
                            @if ($index < 9) <div>
                                <a href="{{ route('dashboard.task_details', ['id' => $works->id]) }}" class="block px-4 py-2 text-sm text-gray-700 bg-white hover:bg-gray-400 hover:text-white">
                                    {{ $works->name }}
                                </a>
                        </div>
                        @elseif ($index == 9)
                        <div>
                            <a href="{{ route('dashboard.tasks') }}" class="block px-4 py-2 text-sm text-gray-700 bg-white hover:bg-gray-400 hover:text-white">
                                一覧へ
                            </a>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        @if (Route::has('login'))
        <div class="hidden sm:flex sm:items-center sm:ml-6">
            @auth
            <a href="{{ url('/dashboard') }}" class=" inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                ダッシュボード</a>
            @else
            <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                ログイン
            </a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}" class=" inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                会員登録
            </a>
            @endif
            @endauth
        </div>
        @endif

        <!-- Hamburger -->
        <div class="-mr-2 flex items-center sm:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Authentication -->
            <x-jet-responsive-nav-link href="/" :active="request()->is('/')">
                トップページ
            </x-jet-responsive-nav-link>

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <x-jet-responsive-nav-link href="{{ route('login') }}">
                    ログイン
                </x-jet-responsive-nav-link>
            </div>
        </div>
    </div>
</nav>