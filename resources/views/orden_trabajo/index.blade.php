<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ordenes de Trabajo') }}
        </h2>
    </x-slot>

    <x-container>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <form class="flex gap-2 justify-between" action="{{route('orden.index')}}">
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

                <div style="max-height: 400px" class="overflow-y-auto">
                    <table class="w-full">
                        <thead>
                        <tr>
                            <th class="text-start uppercase">OT</th>
                            <th class="text-start uppercase">MATRICULA</th>
                            <th class="text-start uppercase">ENTRADA</th>
                            <th class="text-start uppercase">SALIDA</th>
                            <th class="text-start uppercase">ESTADO</th>
                            <th class="text-start uppercase">IMPORTE</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="data-ordenes" >
                            @foreach($ordenes as $ot)
                                <tr class="hover:bg-gray-400">
                                    <td>{{$ot->CODIGOOT}}</td>
                                    <td>{{$ot->MATRICULA}}</td>
                                    <td>{{\Carbon\Carbon::create($ot->FECHAENTRADA)->format('d/m/Y | h:m')}}</td>
                                    <td>{{$ot->FECHASALIDA ? \Carbon\Carbon::create($ot->FECHAENTRADA)->format('d/m/Y | h:m') : ''}}</td>
                                    <td>
                                        <span class="px-2 select-none rounded-2xl text-white @if($ot->FECHACIERRE == null) bg-green-500 @else  bg-red-400  @endif">
                                            {{$ot->FECHACIERRE == null ? 'abierta' : 'cerrada'}}
                                        </span>
                                    </td>
                                    <td>{{number_format($ot->IMPORTESERVICIO, 2)}}</td>
                                    <td>
                                        <a href="{{route('orden.show', $ot->CODIGOOT)}}">
                                            @include('icons.settings')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <table class="mt-2 sticky bottom-0 bg-white w-full">
                    <tr>
                        <th colspan="5" class="text-left uppercase">Total</th>
{{--                        <td class="text-right px-2">{{$total}}</td>--}}
                    </tr>
                </table>

            </div>
        </div>
    </x-container>
</x-app-layout>
