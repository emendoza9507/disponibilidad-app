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
                    <div class="hidden print:block mb-3 border-b-2">
                        <h3 class="text-2xl font-bold uppercase">Orden Trabajo</h3>
                    </div>

                    <div class="card lg:w-4/12 flex flex-col">
                        <div class="flex text-xl justify-between">
                            <h3 class="font-bold">OT:</h3>
                            <span>{{$ot->CODIGOOT}}</span>
                        </div>

                        <div class="flex text-xl justify-between">
                            <h3 class="font-bold">MATRICULA:</h3>
                            <a href="{{route('autos.show', $ot->CODIGOM)}}" class="underline">{{$ot->MATRICULA}}</a>
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

                    <br>

                    <h3 class="text-xl font-bold uppercase">Diagnostico</h3>
                    <div>
                        <p>{{$ot->DIAGNOSTICO}}</p>
                    </div>

                    <br>

                    <h3 class="text-xl font-bold uppercase">Observaciones</h3>
                    <div>
                        <p>{{$ot->observaciones}}</p>
                    </div>
                    <br>

                    <h3 class="text-xl font-bold uppercase">Materiales</h3>
                    <div style="max-height: 400px" class="overflow-y-auto">
                        <table class="w-full">
                            <thead>
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
                            <tfoot>
                            <tr>
                                <th colspan="2" class="text-start">IMPORTE MATERIAL</th>
                                <td class="text-end font-bold">{{number_format($importe_material)}}$</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                    <h3 class="mt-2 text-xl font-bold uppercase">Mano de Obra</h3>
                    <div style="max-height: 400px" class="overflow-y-auto">
                        <table class="w-full">
                            <thead>
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
                            <tfoot>
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

                    <div class="mt-5 absolute top-0 right-6">
                        <x-button id="btn-print" class="print:hidden bg-yellow-300 text-black hover:text-white rounded-none">
                            @include('icons.printer')
                            IMPRIMIR
                        </x-button>
                    </div>

                    <p class="print:block hidden">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium alias aliquam amet at aut consectetur culpa distinctio dolore eum explicabo harum laudantium libero magni nisi nobis praesentium, recusandae repellat repudiandae.
                    </p>
                @endif
            </div>
        </div>
    </x-container>
</x-app-layout>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const $btnPrint = document.querySelector('#btn-print');
        const $areaPrint = document.querySelector('#reporte-ot');

        reportPrint($btnPrint, $areaPrint)
    })
</script>
