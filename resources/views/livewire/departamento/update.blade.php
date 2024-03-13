<div>
    <button class="inline-block rounded rounded-full px-4 py-2 text-gray-700 hover:bg-gray-50 focus:relative" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        </svg>
    </button>

    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <h2 class="text-2xl font-bold">Editar Departamento</h2>
        </x-slot>
        <x-slot name="content">
            <div>
                <x-label for="codigodp" value="{{ __('Codigo') }}"/>
                <x-input id="codigodp" class="block mt-1 w-full" type="text" wire:model="codigodp" name="name" :value="old('codigodp')" autofocus autocomplete="codigodp"/>
                <div class="text-red-500">@error('codigodp') {{ $message }} @enderror</div>
            </div>

            <div class="mt-4">
                <x-label for="nombre" value="{{ __('Nombre') }}"/>
                <x-input id="nombre" class="block mt-1 w-full" type="email" wire:model="nombre" name="nombre" :value="old('nombre')" required autocomplete="nombre"/>
                <div class="text-red-500">@error('nombre') {{ $message }} @enderror</div>
            </div>

            <div class="mt-4">
                <x-label for="descripcion" value="{{ __('Descripcion') }}"/>
                <x-input id="descripcion" class="block mt-1 w-full" type="text" wire:model="descripcion" name="descripcion" :value="old('descripcion')" required autocomplete="descripcion"/>
                <div class="text-red-500">@error('descripcion') {{ $message }} @enderror</div>
            </div>
            <div class="mt-4">
                <x-label for="direccion" value="{{ __('Direccion') }}"/>
                <x-input id="direccion" class="block mt-1 w-full" type="text" wire:model="direccion" name="direccion" :value="old('direccion')" required autocomplete="direccion"/>
                <div class="text-red-500">@error('direccion') {{ $message }} @enderror</div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <div>
                <x-button wire:click="save">{{__('GUARDAR')}}</x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
