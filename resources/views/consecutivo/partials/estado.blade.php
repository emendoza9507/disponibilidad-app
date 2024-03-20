
@php($estado = !!$ot->FECHACIERRE)
<span @class([
    'text-white font-bold px-2 py-0.5 rounded rounded-full',
    'bg-red-400' => $estado,
    'bg-green-500' => !$estado]
)>
    {{$ot->FECHACIERRE ? 'cerrada' : 'abierta'}}
</span>
