<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Departamentos') }}
        </h2>
    </x-slot>

    <x-container>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                <livewire:departamento.index />


            </div>
        </div>
    </x-container>
</x-app-layout>
