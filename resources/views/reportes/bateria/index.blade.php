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

                <table class="w-full">
                    <thead>
                    <tr>
                        <th class="text-start">OT</th>
                        <th class="text-start">MATRICULA</th>
                        <th class="text-end">Cant.</th>
                    </tr>
                    </thead>
                    <tbody id="data-neumaticos" >
                    @php $total = 0  @endphp
                    @foreach($resumenBaterias as $key => $value)
                        @php $total += $value['baterias'] @endphp
                        <tr class="@if($value['baterias'] > 4) text-red-400 @endif">
                            <td class="pr-2">{{$key}}</td>
                            <td class="pr-2">{{$value['ot']->MATRICULA}}</td>
                            <td class="px-2 min-w-10 text-right">{{$value['baterias']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <table class="sticky bottom-0 bg-white w-full">
                    <tr>
                        <th colspan="5" class="text-left">Total</th>
                        <td class="text-right px-2">{{$total}}</td>
                    </tr>
                </table>

            </div>
        </div>
    </x-container>
</x-app-layout>
