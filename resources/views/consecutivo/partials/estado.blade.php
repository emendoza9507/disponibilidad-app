
{{-- @php($estado = !!$ot->FECHACIERRE) --}}
{{-- <span @class([
    'text-white font-bold px-2 py-0.5 rounded rounded-full',
    'bg-red-400' => $estado,
    'bg-green-500' => !$estado]
)>
    {{$ot->FECHACIERRE ? 'cerrada' : 'abierta'}}
</span> --}}
<span class="px-2 select-none rounded-2xl text-white @if($ot->ESTADO == 1) bg-green-500 @elseif ($ot->ESTADO == 9) bg-yellow-400 @else  bg-red-400  @endif">
    {{$ot->estado->estado_nombre}}
</span>
