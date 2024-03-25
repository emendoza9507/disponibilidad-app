@isset($marca)
    <tr>
        <td>
            MARCA || MODELO
        </td>
    </tr>
@endisset
@foreach($autos as $auto)
    <tr class="hover:bg-gray-300">
        <td class="px-1 py-2 text-center">{{$loop->index + 1}}</td>
        <td class="px-4 text-center">{{$auto->MATRICULA}}</td>
        <td class="px-4 text-center">{{$auto->supermaestro?->TIPO}}</td>
        <td class="px-4 text-center">{{$auto->supermaestro?->MARCA}}</td>
        <td class="px-4 text-center">{{$auto->supermaestro?->MODELO}}</td>
        <td class="px-4 text-center">{{$auto->MATRICULAANT}}</td>
        <td class="">
            <div class="flex gap-2 items-center">
                <a title="Consumo" href="{{route('reporte.auto.material', [$auto->CODIGOM, 'connection_id' => $connection_id, 'start_date' => $auto->FECHAALTA])}}">
                    @include('icons.box')
                </a>
                <a title="Ordenes de Trabajo" href="{{route('autos.show', [$auto->CODIGOM, 'connection_id' => $connection_id])}}">
                    @include('icons.documents')
                </a>
            </div>
        </td>
        <td></td>
    </tr>
@endforeach
