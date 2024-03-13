<div>
    <button class="inline-block rounded rounded-full px-4 py-2 text-gray-700 hover:bg-gray-50 focus:relative" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
        </svg>
    </button>

    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <h2 class="text-2xl font-bold">Crear Departamento</h2>
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
