<div>
    <button class="inline-block rounded rounded-full px-2 py-2 text-gray-700 hover:bg-gray-50 focus:relative" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        </svg>
    </button>

    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <h2 class="text-2xl font-bold">Editar Usuario</h2>
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

            <div class="mt-4 flex gap-3">
            <fieldset>Roles</fieldset>
            @foreach($roles as $rol)
                <div class="mb-4">

                    <x-label>
                        <input type="checkbox" wire:model.live="user_roles" value="{{$rol->id}}" @if($this->user->hasRole($rol)) checked @endif class="mr-1">
                        {{$rol->name}}
                    </x-label>
                </div>
            @endforeach
            </div>
        </x-slot>
        <x-slot name="footer">
            <div>
                <x-button wire:click="update">{{__('GUARDAR')}}</x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
