<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reporte de Baterias') }}
        </h2>
    </x-slot>

    <x-container>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <form class="flex gap-2 justify-between" action="{{route('reporte.bateria.index')}}">
                    <div class="flex gap-2">
                        <div class="relative">
                            <x-input id="input-search" class="rounded-none pr-8" placeholder="Buscar"/>
                            <span class="absolute right-2 top-2.5 ">@include('icons.search')</span>
                        </div>

                        <x-input class="rounded-none" name="start_date" type="date" value="{{$start_date->format('Y-m-d')}}"/>
                        <x-input class="rounded-none" name="end_date" type="date" value="{{$end_date->format('Y-m-d')}}"/>

                        <x-button is="buttom-submit" class="rounded-none h-full">GENERAR</x-button>

                        <x-button id="btn-alerta" type="button" class="rounded-none h-full bg-red-400">ALERTAS</x-button>
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

                <div style="max-height: 400px" class="overflow-y-auto scrollable">
                    <table class="w-full">
                        <thead>
                        <tr>
                            <th class="text-start uppercase">OT</th>
                            <th class="text-start uppercase">ENTRADA</th>
                            <th class="text-start uppercase">MATRICULA</th>
                            <th class="text-end uppercase">Cant. Baterias</th>
                        </tr>
                        </thead>
                        <tbody id="data-baterias" >
                        @php $total = 0  @endphp
                        @foreach($resumenBaterias as $key => $value)
                            @php $total += $value['baterias'] @endphp
                            <tr class="@if($value['baterias'] > 1) alert text-red-300 @endif hover:bg-gray-100">
                                <td class="pr-2 py-2" style="width: 150px">
                                    <a href="{{route('orden.show', $key)}}">{{$key}}</a>
                                </td>
                                <td class="pr-2">{{\Carbon\Carbon::create($value['ot']->FECHAENTRADA)->format('d/m/Y')}}</td>
                                <td class="pr-2">{{$value['ot']->MATRICULA}}</td>
                                <td class="px-2 min-w-10 text-right">{{$value['baterias']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="sticky bottom-0 bg-white">
                        <tr>
                            <th class="text-left uppercase" style="width: 150px">Total</th>
                            <td colspan="2"></td>
                            <td class="px-2 text-right">{{$total}}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </x-container>
</x-app-layout>
<script>
    let showAlertas = false;
    const $inputSearh = document.querySelector('#input-search');
    const $dataBaterias = document.querySelector('#data-baterias');
    const $btnAlerta = document.querySelector('#btn-alerta');

    $inputSearh.addEventListener('keyup', () => {
        Array.from($dataBaterias.children).forEach(($tr => {
            if($tr.innerText.toLowerCase().includes($inputSearh.value)) {
                $tr.classList.remove('hidden')
            } else {
                $tr.classList.add('hidden')
            }
        }))
    })

    $btnAlerta.addEventListener('click', () => {
        showAlertas  = !showAlertas

        Array.from($dataBaterias.children).forEach(($tr) => {
            if(showAlertas) {
                if(!$tr.classList.contains('alert')) {
                    $tr.classList.add('hidden')
                } else {
                    $tr.classList.remove('hidden')
                }
            } else {
                $tr.classList.remove('hidden')
            }
        })
    })
</script>
