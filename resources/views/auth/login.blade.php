<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <div class="w-20">
                <img class="mr-1" src="data:image/png;base64,{{Config::get('base64.loginLogo')}}">
            </div>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label for="employee_number" value="社員番号" />
                <x-jet-input id="employee_number" class="block mt-1 w-full" type="text" name="employee_number" :value="old('employee_number')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="パスワード" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">ログイン状態を保持する</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button class="ml-4">
                    ログイン
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>