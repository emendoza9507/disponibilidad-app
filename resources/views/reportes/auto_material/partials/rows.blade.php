@if($ordenes->count() > 0)
    @php($importe_total = 0)
    <tr>
        <td colspan="4" class="text-center bg-gray-100 uppercase font-bold border-b-2">{{$connection->name}}</td>
    </tr>
    @foreach($ordenes as $ot)
        <tr class="border-b-2 border-gray-300 hover:bg-gray-100">
            <td class="py-2">
                <a href="{{route('orden.show', [$ot->CODIGOOT, 'connection_id' => $connection_id])}}">
                    {{$ot->CODIGOOT}}
                </a>
            </td>
            <td>{{\Carbon\Carbon::create($ot->FECHASALIDA)->format('d/m/Y')}}</td>
            <td class="flex flex-col">
                @php($importe_material = 0)
                @foreach($ot->materials as $material)
                    @php($importe_material += $material->IMPORTELINEA)
                    <div class="flex justify-between hover:bg-gray-300">
                        <span>{{$material->DESCRIPCION}}</span>
                        <span>{{number_format($material->CANTIDAD, 2)}}</span>
                    </div>
                @endforeach
            </td>
            <td class="text-end">{{number_format($importe_material, 2)}}$</td>
        </tr>
    @endforeach
@endif

