<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Control por Materiales') }}
        </h2>
    </x-slot>


    <x-container>

        @include('partials.messages')

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div id="reporte" class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <form class="flex flex-col" action="{{route('reporte.auto.material', [$maestro->CODIGOM])}}">
                    <div class="flex justify-between gap-2 items-end">
                        <h3 class="uppercase text-xl font-bold">Ordenes {{$maestro->MATRICULA}}</h3>
                        <div class="flex gap-2 text-sm">
                            <span class="uppercase"><b>Desde:</b> {{$start_date->format('d/m/Y')}}</span>
                            <span class="uppercase"><b>Hasta:</b> {{$end_date->format('d/m/Y')}}</span>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-rows-2 gap-2 print:hidden">
                        <div class="flex gap-2">
                            <div class="relative">
                                <x-input id="input-search" name="matricula" class="rounded-none pr-8" placeholder="Buscar"/>
                                <span class="absolute right-2 top-2.5 ">@include('icons.search')</span>
                            </div>

                            <x-input class="rounded-none" name="start_date" type="date" value="{{$start_date?->format('Y-m-d')}}"/>
                            <x-input class="rounded-none" name="end_date" type="date" value="{{$end_date?->format('Y-m-d')}}"/>

                            <select id="select-connection" name="connection_id" is="select-connection" class="print:hidden">
                                @foreach($connections as $connection)
                                    <option value="{{$connection->id}}" @if($connection->id == $connection_id) selected @endif>
                                        {{$connection->name}}
                                    </option>
                                @endforeach
                            </select>

                            <x-button class="rounded-none h-full">GENERAR</x-button>
                        </div>

                        <select name="area" >
                            @foreach($areas as $a)
                                <option @selected($area == $a->CODIGO) value="{{$a->CODIGO}}">{{$a->DESCRIPCION}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <br>


                <div style="max-height: 400px" class="overflow-y-auto">
                    <table class="w-full">
                        <thead class="sticky top-0 bg-gray-300">
                        <tr>
                            <th class="text-start uppercase py-2    ">OT</th>
                            <th class="text-start uppercase">FECHA</th>
                            <th class="text-end uppercase">
                                <div class="flex justify-between">
                                    <span>MATERIALES</span>
                                    <span>CANTIDAD</span>
                                </div>
                            </th>
                            <th class="text-end uppercase pl-3">IMPORTE EN MATERIAL</th>
                        </tr>
                        </thead>
                        <tbody id="data-ordenes" >
                        @php($importe_total = 0)
                        @foreach($ordenes as $ot)
                            <tr class="border-b-2 border-gray-300 hover:bg-gray-100">
                                <td class="py-2">
                                    <a href="{{route('orden.show', [$ot->CODIGOOT, 'connection_id' => $connection_id])}}">
                                        {{$ot->CODIGOOT}}
                                    </a>
                                </td>
                                <td>{{\Carbon\Carbon::create($ot->FECHACIERRE)->format('d/m/Y')}}</td>
                                <td class="flex flex-col">
                                    @php($importe_material = 0)
                                    @foreach($ot->materials as $material)
                                        @php($importe_material += $material->IMPORTELINEA)
                                        <div class="flex justify-between hover:bg-gray-300">
                                            <span>{{$material->DESCRIPCION}}</span>
                                            <span>{{$material->CANTIDAD}}</span>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="text-end">{{number_format($importe_material, 2)}}$</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="sticky bottom-0 bg-gray-300">

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
        const $data = document.querySelector('#data-ordenes');
        const $btnPrint = document.querySelector('#btn-print');
        const $areaPrint = document.querySelector('#reporte');

        reportPrint($btnPrint, $areaPrint, ($printer) => {
            const $classes = ['border-gray-300', 'bg-gray-300', 'border-2']

            console.log($printer.getElementsByClassName(...$classes))

            Array.from($printer.getElementsByClassName(...$classes)).forEach(node => node.classList.remove(...$classes))
        })

        $inputSearh.addEventListener('keyup', () => {
            Array.from($data.children).forEach(($tr => {
                if($tr.innerText.toLowerCase().includes($inputSearh.value.toString().trim().toLowerCase())) {
                    $tr.classList.remove('hidden')
                } else {
                    $tr.classList.add('hidden')
                }
            }))
        })
    })
</script>
