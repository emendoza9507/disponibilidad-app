<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de Auto') }}
        </h2>
    </x-slot>

    <x-container>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                <div class="flex gap-4 flex-wrap">
                    <div class="flex flex-col w-full md:w-5/12 gap-2 ">
                        <div class="flex text-xl gap-2">
                            <h3 class="font-bold">MATRICULA:</h3>
                            <span>{{$auto->MATRICULA}}</span>
                        </div>

                        <div class="flex text-xl   gap-2">
                            <h3 class="font-bold">ANTERIOR:</h3>
                            <span>{{$auto->MATRICULAANT}}</span>
                        </div>

                        <div class="flex text-xl   gap-2">
                            <h3 class="font-bold">NO.MOTOR:</h3>
                            <span>{{$auto->NOMOTOR}}</span>
                        </div>

                        <div class="flex text-xl   gap-2">
                            <h3 class="font-bold">MAESTRO:</h3>
                            <span>{{$auto->CODIGOM}}</span>
                        </div>

                        <div>
                            @if($auto->FECHABAJA != null)
                                <span class="px-3 text-white font-bold bg-red-400 rounded-xl">BAJA</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col w-full md:w-5/12 gap-2 ">
                        <div class="flex text-xl  gap-2">
                            <h3 class="font-bold">TIPO:</h3>
                            <span>{{$auto->supermaestro->TIPO}}</span>
                        </div>

                        <div class="flex text-xl  gap-2">
                            <h3 class="font-bold">MARCA:</h3>
                            <span>{{$auto->supermaestro->MARCA}}</span>
                        </div>

                        <div class="flex text-xl  gap-2">
                            <h3 class="font-bold">MODELO:</h3>
                            <span>{{$auto->supermaestro->MODELO}}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <h3 class="text-xl font-bold">COSTOS</h3>

                    <table class="w-full">
                        <tr>
                            <th class="text-start">PRECIO COMPRA:</th>
                            <td class="pr-2">{{number_format($auto->PRECIOCOMPRA, 2)}}$</td>

                            <th class="text-start">DEPRECIACION MENSUAL:</th>
                            <td class="pr-2">{{number_format($auto->DEPREMENSUAL, 2)}}$</td>

                            <th>VALOR RESIDUAL:</th>
                            <td class="pr-2">{{number_format($auto->VALORRESIDUAL, 2)}}$</td>

                            <th>VALOR AMORTIZADO:</th>
                            <td class="pr-2">{{number_format($auto->VALORRESIDUAL, 2)}}$</td>
                        </tr>
                    </table>
                </div>



                @isset($ordenes[0])
                <div class="mt-5">
                    <form id="form-select-connection" class="flex mb-2 text-xl items-center" action="{{route('autos.show', $auto->CODIGOM)}}">
                            <h3 class="font-bold">ORDENES DE TRABAJO:</h3>
                            <label class="pl-2 flex">TALLER
                                <select id="select-connection" class="py-0 ml-1 m-0" name="connection_id">
                                    @foreach($connections as $connection)
                                            <option value="{{$connection->id}}" @if($connection->id == $connection_id) selected @endif>
                                            {{$connection->name}}
                                        </option>
                                        @endforeach
                                </select>
                            </label>
                    </form>

                    @include('orden_trabajo.list')
                </div>
                @else
                    <div class="mt-5">
                        <h3 class="uppercase text-red-400">Sin Ordenes de Trabajo en el taller: {{$connection->name}}</h3>
                    </div>
                @endisset
            </div>
        </div>
    </x-container>
</x-app-layout>
<script >
    const $selectConnection = document.querySelector('#select-connection');
    const $formSelectConnection = document.querySelector('#form-select-connection');

    $selectConnection.addEventListener('change', () => {
        $formSelectConnection.submit();
    })
</script>
