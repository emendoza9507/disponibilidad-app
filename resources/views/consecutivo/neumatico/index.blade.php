<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Consecutivo de Neumaticos') }}
        </h2>
    </x-slot>


    <x-container>

        @include('partials.messages')

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <form id="form-search-ordenes" class="flex gap-2 justify-between"
                      action="{{route('consecutivo.neumatico.index')}}">
                    <div class="flex gap-2">
                        <div class="relative">
                            <x-input id="input-search" name="matricula" value="{{$matricula}}" class="rounded-none pr-8" placeholder="Buscar"/>
                            <span class="absolute right-2 top-2.5 ">@include('icons.search')</span>
                        </div>

                        <x-input class="rounded-none" name="start_date" type="date"
                                 value="{{$start_date->format('Y-m-d')}}"/>
                        <x-input class="rounded-none" name="end_date" type="date"
                                 value="{{$end_date->format('Y-m-d')}}"/>

                        <x-button class="rounded-none h-full">BUSCAR</x-button>
                    </div>
                    <select id="select-connection" name="connection_id">
                        @foreach($connections as $connection)
                            <option value="{{$connection->id}}" @if($connection->id == $connection_id) selected @endif>
                                {{$connection->name}}
                            </option>
                        @endforeach
                    </select>
                </form>

                <br>

                <div style="max-height: 400px" class="overflow-y-auto">
                    <table class="w-full">
                        <thead class="sticky top-0 bg-white">
                        <tr>
                            <th class="text-start uppercase">OT</th>
                            <th class="text-start uppercase">MATRICULA</th>
                            <th class="text-start uppercase">ENTRADA</th>
                            <th class="text-start uppercase">SALIDA</th>
                            <th class="text-start uppercase">CONSECUTIVOS</th>
                            <th class="text-end uppercase">IMPORTE</th>
                            <th class="px-2 text-end">Cant. Neumaticos</th>
                        </tr>
                        </thead>
                        <tbody id="data-ordenes">
                        @php($importe_total = 0)
                        @php($total_neumaticos = 0)
                        @foreach($ordenes as $ot)
                            @php($importe_total += $ot->IMPORTESERVICIO)
                            @php($total_neumaticos += $ot->cant_neumaticos)
                            <tr class="hover:bg-gray-100">
                                <td class="py-3 text-start">
                                    <a class="" href="{{route('orden.show', $ot->CODIGOOT)}}">
                                        {{$ot->CODIGOOT}}
                                    </a>
                                </td>
                                <td><a href="{{route('autos.show', $ot->CODIGOM)}}">{{$ot->MATRICULA}}</a></td>
                                <td>{{\Carbon\Carbon::create($ot->FECHAENTRADA)->format('d/m/Y')}}</td>
                                <td>{{$ot->FECHASALIDA ? \Carbon\Carbon::create($ot->FECHAENTRADA)->format('d/m/Y') : ''}}</td>
                                <td>
                                    @if($ot->consecutivoNeumaticos->count() == 0)
                                        <form
                                            action="{{route('consecutivo.neumatico.store')}}?connection_id={{$connection_id}}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="codigoot" value="{{$ot->CODIGOOT}}">
                                            <button
                                                class="px-3 py-0 bg-green-700 hover:bg-green-900 text-white font-bold">
                                                GENERAR
                                            </button>
                                        </form>
                                    @else
                                        <div class="flex gap-2">
                                            @foreach($ot->consecutivoNeumaticos as $consecutivo)
                                                <span
                                                    class="border-2 py-0.5 px-2 text-white font-bold bg-gray-500 border-green-500">{{$consecutivo->id}}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end">{{number_format($ot->IMPORTESERVICIO, 2)}}$</td>
                                <td class="px-3 text-end">
                                    {{$ot->cant_neumaticos}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="sticky bottom-0 bg-white">
                        <tr>
                            <th colspan="5" class="text-left uppercase">Total</th>
                            <td class="text-right"><b>Importe:</b> {{number_format($importe_total, 2)}}$</td>
                            <td class="px-3 text-end"><b>NEUMATICOS:</b> {{$total_neumaticos}}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                @if($matricula)
                <div style="max-height: 400px" class="mt-3 overflow-y-auto">
                    <h3 class="text-xl uppercase">Neumaticos Anteriores</h3>
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-start">OT</th>
                                <th class="text-start">MATRICULA</th>
                                <th class="text-start">TALLER</th>
                                <th class="text-start">CONSECUTIVO</th>
                                <th class="text-start">FECHA</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($consecutivos_anteriores as $consecutivo)
                            @php($fecha = \Carbon\Carbon::create($consecutivo->created_at))
                            <tr class="text-start">
                                <td class="text-start">{{$consecutivo->CODIGOOT}}</td>
                                <td>{{$consecutivo->CODIGOM}}</td>
                                <td>{{$consecutivo->TALLER}}</td>
                                <td>
                                    {{$fecha->format('y')}}{{$consecutivo->id}}
                                </td>
                                <td>
                                    {{$fecha->format('d/m/Y')}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </x-container>
</x-app-layout>
<script>
    const $selectConnection = document.querySelector('#select-connection');
    const $formSerchOrdenes = document.querySelector('#form-search-ordenes');
    const $inputSearh = document.querySelector('#input-search');
    const $dataNeumaticos = document.querySelector('#data-ordenes');

    $inputSearh.addEventListener('keyup', () => {
        Array.from($dataNeumaticos.children).forEach(($tr => {
            if ($tr.innerText.toLowerCase().includes($inputSearh.value.toString().toLowerCase())) {
                $tr.classList.remove('hidden')
            } else {
                $tr.classList.add('hidden')
            }
        }))
    })

    $selectConnection.addEventListener('change', () => {
        $formSerchOrdenes.submit();
    })
</script>
