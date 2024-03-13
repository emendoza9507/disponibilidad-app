<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conecciones') }}
        </h2>
    </x-slot>

    <x-container>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                <div class="md:grid md:grid-cols-3">
                    <div class="mt-3">
                        <h1 class="mb-3 text-xl font-bold"> Nueva Coneccion </h1>

                        @include('connections.new-connection-form')
                    </div>

                    <div class="md:col-span-2">
                        <div class="mt-1 flex flex-col">
                            <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
                                <div class="py-2 inline-block min-w-full md:px-4 lg:px-8">
                                    <div class="overflow-hidden">
                                        <h1 class="mb-9 text-xl font-bold"> Conecciones </h1>
                                        @livewire('show-connections')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </x-container>
</x-app-layout>
