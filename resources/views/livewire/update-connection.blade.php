<div>

    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <button class="py-0.5" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        </svg>
    </button>
    <form wire:submit="save" >
        <x-dialog-modal wire:model="open">
            <x-slot name="title">
                ACTUALIZAR CONECCION
            </x-slot>

            <x-slot name="content">
                @csrf
                <x-validation-errors class="mb-4" />

                <div>
                    <x-label for="name" value="{{ __('Nombre') }}" />
                    <x-input id="name" wire:model.live="name" class="block mt-1 w-full" type="text" />
                </div>

                <div class="mt-4">
                    <x-label for="database"  value="{{ __('Nombre de la Base de Datos') }}" />
                    <x-input id="database" wire:model.live="database" class="block mt-1 w-full" type="text" name="database"
                        required autocomplete="database" />
                </div>

                <div class="mt-4">
                    <x-label for="hostname" value="{{ __('Direccion del Servidor') }}" />
                    <x-input id="hostname" wire:model.live="hostname" class="block mt-1 w-full" type="text" name="hostname"
                        required autocomplete="hostname" />
                </div>

                <div class="mt-4">
                    <x-label for="username" value="{{ __('Usuario') }}" />
                    <x-input id="username" wire:model.live="username" class="block mt-1 w-full"  type="text" name="username"
                        required autocomplete="username" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('contraseña') }}" />
                    <x-input id="password" wire:model.live="password" class="block mt-1 w-full"  type="password" name="password"
                        required autocomplete="password" />
                </div>

                <div class="mt-4">
                    <x-label for="description" value="{{ __('Descripcion') }}" />
                    <x-input id="description" wire:model.live="description" class="block mt-1 w-full" type="text" name="description"
                         required autocomplete="description" />
                </div>


            </x-slot>

            <x-slot name="footer">
                <div class="mt-4 mx-6">
                    <x-button  wire:click="$set('open', false)">{{ __('CANCELAR') }}</x-button>
                </div>
                <div class="mt-4">
                    <x-button  class="bg-red-800">{{ __('ACTUALIZAR') }}</x-button>
                </div>
            </x-slot>
        </x-dialog-modal>

    </form>
</div>
