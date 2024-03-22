<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orden de Trabajo') }}
        </h2>
    </x-slot>

    <x-container>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div id="reporte-ot" class="p-6 lg:p-8 bg-white border-b border-gray-200 relative">
                @if($ot)
                    <div class="hidden print:block mb-3 border-b-2 overflow-hidden">
                        <h3 class="text-2xl float-start clear-both font-bold uppercase">Orden de Trabajo</h3>
                        <span class="text-xl float-end">{{$ot->CODIGOOT}}</span>
                    </div>

                    <div class="grid lg:gap-12 lg:grid-cols-2 bg-gray-100">
                        <div class="flex flex-col">
                            <div class="flex print:hidden text-xl justify-between">
                                <h3 class="font-bold">OT:</h3>
                                <span>{{$ot->CODIGOOT}}</span>
                            </div>

                            <div class="flex text-xl justify-between">
                                <h3 class="font-bold">MATRICULA:</h3>
                                <a href="{{route('autos.show', [$ot->CODIGOM, 'connection_id' => $connection_id])}}" class="underline">{{$ot->MATRICULA}}</a>
                            </div>

                            <div class="flex text-xl  justify-between">
                                <h3 class="font-bold">ENTRADA:</h3>
                                <span>{{\Carbon\Carbon::create($ot->FECHAENTRADA)->format('(h:m) d/m/Y')}}</span>
                            </div>

                            <div class="flex text-xl  justify-between">
                                <h3 class="font-bold">SALIDA:</h3>
                                <span>{{$ot->FECHASALIDA != null ? \Carbon\Carbon::create($ot->FECHASALIDA)->format('(h:m) d/m/Y') : ''}}</span>
                            </div>

                            <div class="flex text-xl  justify-between">
                                <h3 class="font-bold">ESTADO:</h3>
                                <span class="uppercase @if($ot->FECHACIERRE == null) text-green-500 @else text-red-500 @endif">{{$ot->FECHACIERRE == null ? 'Abierta' : 'Cerrada'}}</span>
                            </div>
                        </div>

                        <div class="card flex flex-col">
                            <div class="flex text-xl justify-between">
                                <h3 class="font-bold">KM/E:</h3>
                                <span>{{number_format($ot->KMENTRADA,2)}}</span>
                            </div>

                            <div class="flex text-xl justify-between">
                                <h3 class="font-bold">KM/S:</h3>
                                <span>{{number_format($ot->kmsalida,2)}}</span>
                            </div>

                            <div class="flex text-xl justify-between">
                                <h3 class="font-bold">DEPOSITO/E:</h3>
                                <span>{{number_format($ot->DEPOSITOENTRADA,2)}}</span>
                            </div>

                            <div class="flex text-xl justify-between">
                                <h3 class="font-bold">DEPOSITO/S:</h3>
                                <span>{{number_format($ot->DEPOSITOSALIDA,2)}}</span>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-xl mt-3 font-bold uppercase">Diagnostico</h3>
                    <div>
                        <p>{{$ot->DIAGNOSTICO}}</p>
                    </div>

                    <br>

                    <h3 class="text-xl font-bold uppercase">Observaciones</h3>
                    <div>
                        <p>{{$ot->observaciones}}</p>
                    </div>
                    <br>

                    <h3 class="text-xl bg-gray-300 border-gray-300 font-bold inline-block uppercase">Materiales</h3>
                    <div  class="overflow-y-auto mb-5">
                        <table class="w-full border-2 border-gray-300">
                            <thead class="">
                            <tr>
                                <th class="text-start">DESCRIPCION</th>
                                <th class="text-end">CANTIDAD</th>
                                <th class="text-end">IMPORTE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($importe_material = 0)
                            @foreach($ot->materials as $material)
                                @php($importe_material += $material->IMPORTELINEA)
                                <tr class="hover:bg-gray-300">
                                    <td>{{$material->DESCRIPCION}}</td>
                                    <td class="text-end">{{number_format($material->CANTIDAD, 2)}}</td>
                                    <td class="text-end">{{number_format($material->IMPORTELINEA)}}$</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="sticky bottom-0 bg-gray-300">
                            <tr>
                                <th colspan="2" class="text-start">IMPORTE MATERIAL</th>
                                <td class="text-end font-bold">{{number_format($importe_material)}}$</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="">
                        <h3 class="text-xl font-bold inline-block border-2 border-b-0 bg-gray-300 border-gray-300 uppercase">Mano de Obra</h3>
                        <table class="w-full border-2 border-gray-300">
                            <thead class="">
                            <tr>
                                <th class="text-start">DESCRIPCION</th>
                                <th class="text-start">OPERARIO</th>
                                <th class="text-end">IMPORTE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($importe_obra = 0)
                            @foreach($ot->manoObras as $mano_obra)
                                @php($importe_obra += $mano_obra->IMPORTELINEA)
                                <tr  class="hover:bg-gray-300">
                                    <td>{{$mano_obra->DESCRIPCION}}</td>
                                    <td>{{$mano_obra->operario->NOMBRE}} {{$mano_obra->operario->APELLIDOS}}</td>
                                    <td class="text-end">{{number_format($mano_obra->IMPORTELINEA, 2)}}$</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="sticky bottom-0 bg-gray-300">
                            <tr>
                                <th colspan="2" class="text-start">IMPORTE OBRA</th>
                                <td class="text-end font-bold">{{number_format($importe_obra)}}$</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <table class="mt-2 sticky bottom-0 bg-white w-full">
                        <tr>
                            <th colspan="5" class="text-left uppercase">IMPORTE</th>
                            <td class="text-right">{{number_format($ot->IMPORTESERVICIO, 2)}}$</td>
                        </tr>
                    </table>

                    <div class="mt-5 text-end">
                        <x-button onclick="history.back()" class="print:hidden bg-gray-600 text-black hover:text-white rounded-none">
                            @include('icons.back')
                        </x-button>
                        <x-button id="btn-print" class="print:hidden bg-yellow-600 text-black hover:text-white rounded-none">
                            @include('icons.printer')
                            IMPRIMIR
                        </x-button>
                    </div>
                @endif
            </div>
        </div>
    </x-container>
</x-app-layout>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const $btnPrint = document.querySelector('#btn-print');
        const $areaPrint = document.querySelector('#reporte-ot');

        reportPrint($btnPrint, $areaPrint, ($printer) => {
            const $classes = ['border-gray-300', 'bg-gray-300', 'border-2']

            console.log($printer.getElementsByClassName(...$classes))

            Array.from($printer.getElementsByClassName(...$classes)).forEach(node => node.classList.remove(...$classes))
        })
    })
</script>
