<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de Auto') }}
        </h2>
    </x-slot>

    <x-container>
        @include('partials.messages')

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
                                <select id="select-connection" class="py-0 ml-1 m-0" name="connection_id" is="select-connection">
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
                        <form id="form-select-connection" class="flex mb-2 text-xl items-center" action="{{route('autos.show', $auto->CODIGOM)}}">
                            <h3 class="uppercase text-red-400">Sin Ordenes de Trabajo en el taller:</h3>
                            <select id="select-connection" class="py-0 ml-1 m-0" name="connection_id" is="select-connection">
                                @foreach($connections as $connection)
                                    <option value="{{$connection->id}}" @if($connection->id == $connection_id) selected @endif>
                                        {{$connection->name}}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endisset

                <div class="mt-5">
                    <h3 class="uppercase inline-block border-2 border-b-0 bg-gray-300 border-gray-300">Ordenes en Taller</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2">
                        <table class="border-2 border-gray-300">
                            <thead>
                            <tr>
                                <th class="w-1 uppercase">Taller</th>
                                <th class="uppercase">Abiertas</th>
                                <th class="uppercase">Cerradas</th>
                                <th class="uppercase">Total</th>
                            </tr>
                            </thead>
                            <tbody id="ordenes-por-taller" class="text-center">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-container>
</x-app-layout>
<script >
    const $selectConnection = document.querySelector('#select-connection');
    const $formSelectConnection = document.querySelector('#form-select-connection');

    const $ordenesPorTaller = document.querySelector('#ordenes-por-taller')

    function loadOrdenerPorTaller($connection, $parent) {
        return fetch('{{route('auto.show.ordenes', $auto->CODIGOM)}}' + `?connection_id=${$connection.id}`)
            .then(res => res.json())
            .then(ordenes => {
                return ordenes
            })
    }

    const promises = Array.from(connections).map(connection => loadOrdenerPorTaller(connection, $ordenesPorTaller))

    Promise.all(promises).then((ordenes) => {
        let abiertas = 0, cerradas = 0, total = 0

        $ordenesPorTaller.innerHTML = '';
        ordenes.forEach((taller, index) => {
            abiertas    += taller.abiertas;
            cerradas    += taller.cerradas;
            total       += taller.total;

            let conecion_estado = taller.conectado ? taller.conectado : false

            const tr = document.createElement('tr');

            if(conecion_estado) {
                tr.innerHTML = `
                    <td>
                        <a href="{{route('orden.index', [null, 'maestro' => $auto->CODIGOM])}}&connection_id=${connections[index].id}">
                            ${taller.taller}
                        </a>
                    </td>
                    <td>${taller.abiertas}</td>
                    <td>${taller.cerradas}</td>
                    <td>${taller.total}</td>
                `
            } else {
                tr.innerHTML = `
                    <td>
                        <a class="text-red-300 text-bold" href="{{route('orden.index', [null, 'maestro' => $auto->CODIGOM])}}&connection_id=${connections[index].id}">
                            ${taller.taller}
                        </a>
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <span class="py-0 px-2 bg-red-400 text-white font-bold">desconectado</span>
                    </td>
                `
            }



            $ordenesPorTaller.append(tr)
        })

        const tr = document.createElement('tr');
        tr.classList.add('bg-gray-300')
        tr.innerHTML = `
                <th>TOTAL</th>
                <td>${abiertas}</td>
                <td>${cerradas}</td>
                <td>${total}</td>
            `
        $ordenesPorTaller.append(tr)
    })

</script>
