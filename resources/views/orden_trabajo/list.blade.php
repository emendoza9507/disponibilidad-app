<div style="max-height: 400px" class="overflow-y-auto">
    <table class="w-full">
        <thead class="sticky top-0 bg-white">
        <tr>
            <th class="text-start uppercase">OT</th>
            <th class="text-start uppercase">MATRICULA</th>
            <th class="text-start uppercase">ENTRADA</th>
            <th class="text-start uppercase">SALIDA</th>
            <th class="text-start uppercase">ESTADO</th>
            <th class="text-end uppercase">IMPORTE</th>
            <th class="px-2"></th>
        </tr>
        </thead>
        <tbody id="data-ordenes" >
        @php($importe_total = 0)
        @foreach($ordenes as $ot)
            @php($importe_total += $ot->IMPORTESERVICIO)
            <tr class="hover:bg-gray-100">
                <td class="py-3">
                    <a href="{{route('orden.show', [$ot->CODIGOOT, 'connection_id' => $connection_id])}}">{{$ot->CODIGOOT}}</a>
                </td>
                <td><a href="{{route('autos.show', $ot->CODIGOM)}}">{{$ot->MATRICULA}}</a></td>
                <td>{{\Carbon\Carbon::create($ot->FECHAENTRADA)->format('d/m/Y | h:m')}}</td>
                <td>{{$ot->FECHASALIDA ? \Carbon\Carbon::create($ot->FECHAENTRADA)->format('d/m/Y | h:m') : ''}}</td>
                <td>
                    <span class="px-2 select-none rounded-2xl text-white @if($ot->FECHACIERRE == null) bg-green-500 @else  bg-red-400  @endif">
                        {{$ot->FECHACIERRE == null ? 'abierta' : 'cerrada'}}
                    </span>
                </td>
                <td class="text-end">{{number_format($ot->IMPORTESERVICIO, 2)}}$</td>
                <td class="px-2 w-1">
                    <div class="flex gap-2 items-center">
                        <a href="{{route('reporte.auto.material', [$ot->CODIGOM, 'connection_id' => $connection_id, 'start_date' => $ot->FECHAENTRADA])}}">
                            @include('icons.box')
                        </a>
                        <a class="flex print:hidden justify-end" href="{{route('orden.show', [$ot->CODIGOOT, 'connection_id'=>$connection_id])}}">
                            @include('icons.settings')
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot class="sticky bottom-0 bg-white">
        <tr>
            <th colspan="5" class="text-left uppercase">Total</th>
            <td class="text-right"><b>Importe:</b> {{number_format($importe_total, 2)}}$</td>
            <td class="px-2 text-end"><b>OTS:</b> {{$ordenes->count()}}</td>
        </tr>
        </tfoot>
    </table>
</div>
{{--<table class="mt-2 sticky bottom-0 bg-white w-full">--}}
{{--    <tr>--}}
{{--        <th colspan="5" class="text-left uppercase">Total</th>--}}
{{--        <td class="text-right px-16">{{$importe_total}}$</td>--}}
{{--    </tr>--}}
{{--</table>--}}
