<x-jet-action-section>
    <x-slot name="title">
        アカウント情報
    </x-slot>

    <x-slot name="description">
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            アカウントを削除します。
        </div>

        <div class="mt-5">
            <x-jet-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                アカウント削除
            </x-jet-danger-button>
        </div>

        <!-- Delete User Confirmation Modal -->
        <x-jet-dialog-modal wire:model="confirmingUserDeletion">
            <x-slot name="title">
                アカウント削除
            </x-slot>

            <x-slot name="content">
                アカウントを削除してよろしいですか？もし予約している最新のレッスンがある場合はキャンセル後、アカウントを削除できます。アカウントを削除するには、パスワードを入力してください。

                <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-jet-input type="password" class="mt-1 block w-3/4" placeholder="{{ __('Password') }}" x-ref="password" wire:model.defer="password" wire:keydown.enter="deleteUser" />

                    <x-jet-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                    キャンセル
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-3" wire:click="deleteUser" wire:loading.attr="disabled">
                    アカウント削除
                </x-jet-danger-button>
            </x-slot>
        </x-jet-dialog-modal>
    </x-slot>
</x-jet-action-section>