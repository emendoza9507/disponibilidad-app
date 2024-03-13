@php
    $maxWidth = '5xl'
@endphp

<div>
    <button wire:click="$set('open', true)">
        <x-icons.documents/>
    </button>

    <x-dialog-modal wire:model="open" :maxWidth="$maxWidth">
        <x-slot name="title" class="flex ">
            <div class="flex gap-1 items-center text-2xl">
                <x-icons.documents/>
                <h3 class="uppercase">Ordenes de Trabajo</h3>
            </div>
        </x-slot>
        <x-slot name="content">
            <div class="text-xl mb-3">
                <span>Matricula:</span>
                <b class="underline">{{$maestro->MATRICULA}}</b>
            </div>

            <div style="height: 60vh">
                <table class="font-medium w-full">
                    <thead>
                    <tr>
                        <th class="py-2 uppercase text-start">COD. OT</th>
                        <th class="px-4 py-2 uppercase text-start">Fecha Entrada</th>
                        <th class="px-4 py-2 uppercase text-start">Fecha Salida</th>
                        <th class="px-4 py-2 uppercase">Importe</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ots as $ot)
                        <tr class="h-8" wire:key="{{uuid_create().$ot->CODIGOOT}}">
                            <td class="text-start">{{$ot->CODIGOOT}}</td>
                            <td class="px-4 text-start">{{$ot->FECHAENTRADA}}</td>
                            <td class="px-4 text-start">{{$ot->FECHASALIDA}}</td>
                            <td class="px-4 text-center">{{number_format($ot->IMPORTESERVICIO, 2, '.', ',')}}</td>
                            <td>
                                <livewire:orden-trabajo.materiales :codigoot="$ot->CODIGOOT" :key="uuid_create().$ot->CODIGOOT"/>
                            </td>
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
