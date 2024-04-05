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


                        <a role="button" class="float-end clear-both inline-flex items-center px-4 py-2 bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 rounded-none h-full"
                           href="{{route('consecutivo.neumatico.all')}}">
                            @include('icons.eye') &nbsp;
                            TODOS
                        </a>
                    </div>
                    <select id="select-connection" name="connection_id" is="select-connection">
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
                            <th class="text-start uppercase">ESTADO</th>
                            <th class="text-start uppercase">CONSECUTIVOS</th>
                            <th class="text-end uppercase">IMPORTE</th>
                            <th class="px-2 text-end uppercase">Cant. Neumaticos</th>
                        </tr>
                        </thead>
                        <tbody id="data-ordenes">
                        @php($importe_total = 0)
                        @php($total_neumaticos = 0)
                        @foreach($ordenes as $ot)
                            @php($importe_total += $ot->IMPORTESERVICIO)
                            @php($total_neumaticos += $ot->cant_neumaticos)
                            <tr class="hover:bg-gray-100" onclick="chequearConsecutivo('{{$ot->MATRICULA}}')">
                                <td class="py-3 text-start">
                                    <a class="" href="{{route('orden.show', [$ot->CODIGOOT, 'connection_id' => $connection_id])}}">
                                        {{$ot->CODIGOOT}}
                                    </a>
                                </td>
                                <td><a href="{{route('consecutivo.neumatico.show_maestro', $ot->CODIGOM)}}">{{$ot->MATRICULA}}</a></td>
                                <td>{{\Carbon\Carbon::create($ot->FECHAENTRADA)->format('d/m/Y')}}</td>
                                <td>{{$ot->FECHASALIDA ? \Carbon\Carbon::create($ot->FECHASALIDA)->format('d/m/Y') : ''}}</td>
                                <td>
                                    @include('consecutivo.partials.estado')
                                </td>
                                <td>
                                    @if($ot->consecutivoNeumaticos->count() == 0)
                                        @role($connection_id, 'tecnico')
                                        <div class="flex">
                                            <form
                                                action="{{route('consecutivo.neumatico.store', [null, 'connection_id' => $connection_id])}}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="codigoot" value="{{$ot->CODIGOOT}}">
                                                <button
                                                    class="px-3 py-0 bg-red-700 hover:bg-red-900 text-white font-bold">
                                                    GENERAR
                                                </button>
                                            </form>
                                        </div>
                                        @endrole
                                    @else
                                        <div class="flex gap-2" onclick="chequearConsecutivo('{{$ot->MATRICULA}}')" >
                                            @foreach($ot->consecutivoNeumaticos as $consecutivo)
                                                <span class="border-2 py-0.5 px-2 text-white font-bold bg-gray-500 border-green-500">{{$consecutivo->id}}</span>
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
                        @if (count($ordenes) == 0 && isset($auto))
                            <tr>
                                <td colspan="8" class="text-center underline">
                                    <a href="{{route('consecutivo.neumatico.show_maestro', $auto->CODIGOM)}}">
                                        Ver ultimos neumaticos cargadas
                                    </a>
                                </td>
                            </tr>
                        @endif
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

                <div class="mt-3  overflow-hidden">
                    <h3 class="text-xl clear-both inline-block top-1 relative uppercase">Consecutivos Anteriores</h3>

                </div>
                <div style="max-height: 400px" class="overflow-y-auto">
                    <table class="mt-2 w-full">
                        <thead class="sticky top-0 bg-white">
                            <tr>
                                <th class="text-start">OT</th>
                                <th class="text-start">TALLER</th>
                                <th class="text-center">CONSECUTIVO</th>
                                <th class="text-end">FECHA</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($consecutivos_anteriores as $consecutivo)
                            @php($fecha = \Carbon\Carbon::create($consecutivo->created_at))
                            <tr class="text-start hover:bg-gray-300">
                                <td class="text-start">
                                    <a @isset($consecutivo->connection)href="{{route('orden.show', $consecutivo->CODIGOOT)}}?connection_id={{$consecutivo->connection->id}}"@endisset>
                                        {{$consecutivo->CODIGOOT}}
                                    </a>
                                </td>
                                <td>
                                   {{$consecutivo->TALLER}}
                                </td>
                                <td class="text-center">
                                    <a href="{{route('consecutivo.neumatico.show', [$consecutivo->id, 'connection_id' => $connection_id])}}">
                                        {{$consecutivo->consecutivo()}}
                                    </a>
                                </td>
                                <td class="text-end">
                                    {{$fecha->format('d/m/Y')}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if($consecutivos_anteriores->count() > 0)
                <div class="mt-2">
                    {{$consecutivos_anteriores->links()}}
                </div>
                @endif
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

    function chequearConsecutivo(matricula) {
        $inputSearh.value = matricula;
        $inputSearh.form.submit()
    }

    $inputSearh.addEventListener('keyup', () => {
        Array.from($dataNeumaticos.children).forEach(($tr => {
            if ($tr.innerText.toLowerCase().includes($inputSearh.value.toString().trim().toLowerCase())) {
                $tr.classList.remove('hidden')
            } else {
                $tr.classList.add('hidden')
            }
        }))
    })

    // $selectConnection.addEventListener('change', () => {
    //     $formSerchOrdenes.submit();
    // })
</script>
