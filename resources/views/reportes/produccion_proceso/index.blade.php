<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Produccion en Proceso') }}
        </h2>
    </x-slot>


    <x-container>

        @include('partials.messages')

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div id="reporte" class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <form class="flex gap-2 justify-between" action="{{route('reporte.proceso.index')}}">
                    <div class="flex gap-2">
                        <h3 class="uppercase text-xl font-bold">Producción en Proceso {{$connection->name}} ({{$connection->codigo_taller}})</h3>
                    </div>
                    <select id="select-connection" name="connection_id" is="select-connection" class="print:hidden">
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
                            <th class="text-start uppercase">KM/E</th>
                            <th class="text-end uppercase w-1">DEPOSITO</th>
                            <th class="text-end uppercase">IMPORTE</th>
                        </tr>
                        </thead>
                        <tbody id="data-ordenes" >
                        @php($importe_total = 0)
                        @foreach($ordenes as $ot)
                            @php($importe_total += $ot->IMPORTESERVICIO)
                            <tr class="hover:bg-gray-100">
                                <td class="py-3">
                                    <a href="{{route('orden.show', $ot->CODIGOOT)}}">{{$ot->CODIGOOT}}</a>
                                </td>
                                <td><a href="{{route('autos.show', $ot->CODIGOM)}}">{{$ot->MATRICULA}}</a></td>
                                <td>{{\Carbon\Carbon::create($ot->FECHAENTRADA)->format('d/m/Y')}}</td>
                                <td>{{$ot->KMENTRADA}}</td>
                                <td class="text-center">{{$ot->DEPOSITOENTRADA}}</td>

                                <td class="text-end w-1">{{number_format($ot->IMPORTESERVICIO, 2)}}$</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="sticky bottom-0 bg-gray-300">
                        <tr>
                            <th colspan="3" class="text-left uppercase">Total</th>
                            <td class="px-2 text-end"><b>OTS:</b> {{$ordenes->count()}}</td>
                            <td class="px-2 text-center">{{$combustible_tanque}}</td>
                            <td class="text-right">{{number_format($importe_total, 2)}}$</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-5 flex">
                    <div class="flex flex-col text-center">
                        <div class="border-b border-black mt-10"></div>
                        <span>{{auth()->user()->name}}</span>
                        <b>Supervisor</b>
                    </div>
                </div>

                <div class="mt-5 text-end">
                    <x-button onclick="history.back()" class="print:hidden bg-gray-600 text-black hover:text-white rounded-none">
                        @include('icons.back')
                    </x-button>
                    <x-button id="btn-print" class="print:hidden bg-yellow-600 text-black hover:text-white rounded-none">
                        @include('icons.printer')
                        IMPRIMIR
                    </x-button>
                </div>
             </div>
        </div>
    </x-container>
</x-app-layout>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const $inputSearh = document.querySelector('#input-search');
        const $dataNeumaticos = document.querySelector('#data-ordenes');
        const $btnPrint = document.querySelector('#btn-print');
        const $areaPrint = document.querySelector('#reporte');

        reportPrint($btnPrint, $areaPrint, ($printer) => {
            const $classes = ['border-gray-300', 'bg-gray-300', 'border-2']

            console.log($printer.getElementsByClassName(...$classes))

            Array.from($printer.getElementsByClassName(...$classes)).forEach(node => node.classList.remove(...$classes))
        })
    })
</script>
