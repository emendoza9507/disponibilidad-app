<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reporte de Neumaticos') }}
        </h2>
    </x-slot>

    <x-container>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <form class="flex gap-2 justify-between" action="{{route('reporte.neumatico.index')}}">

                    <div class="flex gap-2">
                        <div class="relative">
                            <x-input id="input-search" class="rounded-none pr-8" placeholder="Buscar"/>
                            <span class="absolute right-2 top-2.5 ">@include('icons.search')</span>
                        </div>

                        <x-input class="rounded-none" name="start_date" type="date" value="{{$start_date->format('Y-m-d')}}"/>
                        <x-input class="rounded-none" name="end_date" type="date" value="{{$end_date->format('Y-m-d')}}"/>

                        <x-button class="rounded-none h-full">GENERAR</x-button>
                    </div>
                    <select name="connection_id">
                        @foreach($connections as $connection)
                            <option value="{{$connection->id}}" @if($connection->id == $connection_id) selected @endif>
                                {{$connection->name}}
                            </option>
                        @endforeach
                    </select>
                </form>

                <br>

                <div>
                    <table class="w-full">
                        <thead>
                        <tr>
                            <th class="text-start uppercase" style="width: 150px">OT</th>
                            <th class="text-start uppercase">MATRICULA</th>
                            <th class="text-end uppercase" style="width: 150px">Cantidad</th>
                        </tr>
                        </thead>
                    </table>
                </div>

                <div style="max-height: 400px" class="overflow-y-auto">
                    <table class="w-full">
                        <tbody id="data-neumaticos" >
                        @php $total = 0  @endphp
                        @foreach($resumenNeumaticos as $key => $value)
                            @php $total += $value['neumaticos'] @endphp
                            <tr class="@if($value['neumaticos'] > 4) text-red-400 @endif hover:bg-gray-300">
                                <td class="pr-2" style="width: 150px">{{$key}}</td>
                                <td class="pr-2">{{$value['ot']->MATRICULA}}</td>
                                <td class="px-2 text-right" style="width: 150px">{{$value['neumaticos']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <table class="mt-2 sticky bottom-0 bg-white w-full">
                    <tr>
                        <th colspan="5" class="text-left uppercase">Total</th>
                        <td class="text-right px-6">{{$total}}</td>
                    </tr>
                </table>

            </div>
        </div>
    </x-container>
</x-app-layout>
<script>
    const $inputSearh = document.querySelector('#input-search');
    const $dataNeumaticos = document.querySelector('#data-neumaticos');

    $inputSearh.addEventListener('keyup', () => {
        Array.from($dataNeumaticos.children).forEach(($tr => {
            if($tr.innerText.toLowerCase().includes($inputSearh.value)) {
                $tr.classList.remove('hidden')
            } else {
                $tr.classList.add('hidden')
            }
        }))
    })
</script>
