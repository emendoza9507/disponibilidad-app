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
                                @foreach($connections as $con)
                                    <option value="{{$con->id}}" @if($con->id == $connection_id) selected @endif>
                                        {{$con->name}}
                                    </option>
                                @endforeach
                            </select>

                            <x-button is="buttom-submit" class="rounded-none h-full">GENERAR</x-button>
                        </div>

                        <select name="area" >
                            <option value="*">TODAS</option>
                            @foreach($areas as $a)
                                <option @selected($area == $a->CODIGO) value="{{$a->CODIGO}}">{{$a->DESCRIPCION}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <br>


                <div style="max-height: 400px" class="overflow-y-auto scrollable">
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
                            @include('reportes.auto_material.partials.rows')
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
        const $selectConnection = document.querySelector('#select-connection')
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

        connections.filter(c => c.id !== {{$connection_id}}).forEach(connection => {

            const tr = document.createElement('tr')
            tr.id = connection.id
            tr.innerHTML = `<td colspan="4" class="text-red-400 text-center">Cargando datos de ${connection.name}...</td>`
            $data.append(tr)

            axios
                .get(location.href, {
                    params: { connection_id: connection.id }
                })
                .then(({data}) => data)
                .then(({status, html}) => {
                    if(status) {
                        $data.innerHTML += html
                    } else {

                    }
                })
                .catch(err => console.log(err.message))
                .finally(() => {
                    $data.removeChild(document.getElementById(connection.id))
                })
        })

    })
</script>
