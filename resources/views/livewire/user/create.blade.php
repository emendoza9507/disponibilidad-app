<div>
    <button class="inline-block rounded rounded-full px-4 py-2 text-gray-700 hover:bg-gray-50 focus:relative" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
        </svg>
    </button>

    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <h2 class="text-2xl font-bold">Crear Usuario</h2>
        </x-slot>
        <x-slot name="content">
            <div>
                <x-label for="name" value="{{ __('Nombre') }}"/>
                <x-input id="name" class="block mt-1 w-full" type="text" wire:model="name" name="name" :value="old('name')" autofocus autocomplete="name"/>
                <div class="text-red-500">@error('name') {{ $message }} @enderror</div>
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}"/>
                <x-input id="email" class="block mt-1 w-full" type="email" wire:model="email" name="email" :value="old('email')" required autocomplete="email"/>
                <div class="text-red-500">@error('email') {{ $message }} @enderror</div>
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}"/>
                <x-input id="password" class="block mt-1 w-full" type="password" wire:model="password" name="pasword" :value="old('password')" required autocomplete="password"/>
                <div class="text-red-500">@error('password') {{ $message }} @enderror</div>
            </div>
            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" wire:model="password_confirmation" required autocomplete="new-password" />
                <div class="text-red-500">@error('password') {{ $message }} @enderror</div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <div>
                <x-button wire:click="save">{{__('GUARDAR')}}</x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
