@php
    $maxWidth = '5xl'
@endphp

<div>
    <button wire:click="$set('open', true)">
        <x-icons.settings/>
    </button>

    <x-dialog-modal wire:model="open" :maxWidth="$maxWidth">
        <x-slot name="title" class="flex ">
            <div class="flex gap-1 items-center text-2xl">
                <x-icons.document/>
                <h3 class="uppercase">Orden de Trabajo</h3>
            </div>
        </x-slot>
        <x-slot name="content">
            <div class="text-xl mb-3 flex">
                <div class="mr-4">
                    <span >OT:</span>
                    <b class="underline">{{$ot?->CODIGOOT}}</b>
                </div>
                <div class="mr-4">
                    <span class="uppercase">Matricula:</span>
                    <b class="underline">{{$ot?->MATRICULA}}</b>
                </div>
                <div class="mr-4">
                    <span class="uppercase">Entrada:</span>
                    <b class="underline">{{$entrada ? \Carbon\Carbon::create($entrada)->format('d-m/h:m') : ''}}</b>
                </div>
                <div class="mr-4">
                    <span class="uppercase">Salida:</span>
                    <b class="underline">{{$salida}}</b>
                </div>
            </div>

            <div class="mb-6">
                <h4 class="text-xl">Observaciones:</h4>

                <p>{{$ot?->observaciones}}</p>
            </div>

            <div class="relative" style="max-height: 400px; overflow-y: auto">
                <h4 class="text-xl sticky top-0 bg-white">Materiales:</h4>

                <table class="font-medium w-full">
                    <thead>
                    <tr>
                        <th class="py-2 uppercase text-start">Descripcion</th>
                        <th class="py-2 uppercase text-start">Cantidad</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($materiales as $material)
                        <tr class="h-8">
                            <td class="text-start">{{$material->DESCRIPCION}}</td>
                            <td class="text-start">{{number_format($material->CANTIDAD, 2)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>


        </x-slot>
        <x-slot name="footer">
            <x-button wire:click="$set('open', false)">
                {{__("CERRAR")}}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
