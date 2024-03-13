<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ordenes de Trabajo') }}
        </h2>
    </x-slot>

    <x-container>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                @if($ot)
                    <div class="card lg:w-4/12 flex flex-col">
                        <div class="flex text-xl justify-between">
                            <h3 class="font-bold">OT:</h3>
                            <span>{{$ot->CODIGOOT}}</span>
                        </div>

                        <div class="flex text-xl  justify-between">
                            <h3 class="font-bold">ENTRADA:</h3>
                            <span>{{\Carbon\Carbon::create($ot->FECHAENTRADA)->format('(h:m) d-m-Y')}}</span>
                        </div>

                        <div class="flex text-xl  justify-between">
                            <h3 class="font-bold">SALIDA:</h3>
                            <span>{{$ot->FECHASALIDA ?: \Carbon\Carbon::create($ot->FECHASALIDA)->format('(h:m) d-m-Y')}}</span>
                        </div>

                        <div class="flex text-xl  justify-between">
                            <h3 class="font-bold">ESTADO:</h3>
                            <span>{{$ot->FECHACIERRE == null ? 'Abierta' : 'Cerrada'}}</span>
                        </div>
                    </div>

                    <br>

                    <h3 class="text-xl font-bold uppercase">Materiales</h3>
                    <div style="max-height: 400px" class="overflow-y-auto">
                        <table class="w-full">
                            <thead>
                            <tr>
                                <th class="text-start">DESCRIPCION</th>
                                <th class="text-end">CANTIDAD</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ot->materials as $material)
                                <tr>
                                    <td>{{$material->DESCRIPCION}}</td>
                                    <td class="text-end">{{number_format($material->CANTIDAD, 2)}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h3 class="mt-2 text-xl font-bold uppercase">Mano de Obra</h3>
                    <div style="max-height: 400px" class="overflow-y-auto">
                        <table class="w-full">
                            <thead>
                            <tr>
                                <th class="text-start">DESCRIPCION</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ot->manoObras as $mano_obra)
                                <tr>
                                    <td>{{$mano_obra->DESCRIPCION}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <table class="mt-2 sticky bottom-0 bg-white w-full">
                        <tr>
                            <th colspan="5" class="text-left uppercase">IMPORTE</th>
                            <td class="text-right px-2">{{number_format($ot->IMPORTESERVICIO, 2)}}$</td>
                        </tr>
                    </table>
                @endif

            </div>
        </div>
    </x-container>
</x-app-layout>
