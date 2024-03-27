<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Autos') }}
        </h2>
    </x-slot>

   @isset($error)
        <x-container>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    {{$error}}
                </div>
            </div>
        </x-container>
   @endisset

    <x-container>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <form class="flex gap-2 justify-between" action="{{route('autos.index')}}">
                    <div class="flex gap-2">
                        <div class="relative">
                            <x-input id="input-search" value="{{$matricula}}" name="matricula" class="rounded-none pr-8" placeholder="Buscar"/>
                            <span class="absolute right-2 top-2.5 ">@include('icons.search')</span>
                        </div>

                        <x-button class="rounded-none h-full">BUSCAR</x-button>
                    </div>
                    <select id="select-connection" name="connection_id" is="select-connection">
                        @foreach($connections as $connection)
                        <option value="{{$connection->id}}" @if($connection->id == $connection_id) selected @endif>
                            {{$connection->name}}
                        </option>
                        @endforeach
                    </select>
                </form>

                <br>

                <div style="max-height: 400px" class="relative overflow-y-auto overflow-x-hidden">
                    <table class="w-full relative">
                        <thead class="sticky top-0 bg-white">
                        <tr class="uppercase">
                            <th class="px-4">Matricula</th>
                            <th class="px-4">Tipo</th>
                            <th class="px-4">Marca</th>
                            <th class="px-4">Modelo</th>
                            <th class="px-4">Mat. Anterior</th>
                            <th colspan="2"></th>
                        </tr>
                        <tbody id="data-autos">
                        @foreach($autos as $auto)
                            <td class="text-center">
                                <a href="{{route('autos.show', [$auto->CODIGOM, 'connection_id' => $connection_id])}}">
                                    {{$auto->MATRICULA}}
                                </a>
                            </td>
                            <td class="text-center">{{$auto->supermaestro->TIPO}}</td>
                            <td class="text-center">{{$auto->supermaestro->MARCA}}</td>
                            <td class="text-center">{{$auto->supermaestro->MODELO}}</td>
                            <td class="text-center">{{$auto->MATRICULAANT}}</td>
                            <td class="text-center">
                                <div class="flex justify-center">
                                    <a title="Ordenes de Trabajo" href="{{route('auto.track', [$auto->CODIGOM, 'connection_id' => $connection_id])}}">
                                        @include('icons.location')
                                    </a>
                                    <a title="Ordenes de Trabajo" href="{{route('autos.show', [$auto->CODIGOM, 'connection_id' => $connection_id])}}">
                                        @include('icons.documents')
                                    </a>
                                </div>
                            </td>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <span class="hover:font-bold hover:underline top-5 z-10"></span>
    </x-container>
</x-app-layout>
<script>
    const $inputSearh = document.querySelector('#input-search');
    const $data = document.querySelector('#data-autos');

    $inputSearh.addEventListener('keyup', () => {
        Array.from($data.children).forEach(($tr => {
            if($tr.classList.contains('header')) return
            if($tr.innerText.toLowerCase().includes($inputSearh.value.toString().trim().toLowerCase())) {
                $tr.classList.remove('hidden')
            } else {
                $tr.classList.add('hidden')
            }
        }))
    })

    let flota = @json($flota);

    @if(!$matricula)

        function getLoteAndRender(lote, index) {
            if(!lote) return
            axios
                .get(location.href, { params: { marca: lote.MARCA, modelo: lote.MODELO, connection_id: {{$connection_id}} } })
                .then(({data}) => data)
                .then(({status, data}) => {
                    const tr = document.getElementById(`${lote.MARCA}${lote.MODELO}`);
                    const fragment = document.createDocumentFragment();

                    const trHeader = document.createElement('tr');
                    const tdMarcaModelo = document.createElement('td');
                    tdMarcaModelo.classList.add('text-center','bg-gray-300', 'cursor-pointer')
                    tdMarcaModelo.innerHTML = `${lote.MARCA}  ${lote.MODELO}`;

                    const tdSeparador = document.createElement('td');
                    tdSeparador.colSpan = 4;
                    tdSeparador.classList.add('border-b-2', 'text-end')

                    const tdTotalAutos = document.createElement('td');
                    tdTotalAutos.append(data.length);
                    tdTotalAutos.classList.add('text-center', 'border-b-2')

                    trHeader.classList.add('header', 'bg-gray-50', 'hover:bg-gray-100', 'sticky', 'top-5')
                    trHeader.append(tdMarcaModelo, tdSeparador, tdTotalAutos);
                    fragment.append(trHeader);

                    const rows = []
                    let showFlag = true

                    tdMarcaModelo.addEventListener('click', () => {
                        showFlag = !showFlag
                        rows.forEach(tr => {
                            if(!showFlag) {
                                tr.hidden = true
                            } else {
                                tr.removeAttribute('hidden')
                            }
                        })
                    })

                    Array.from(data).forEach(auto => {
                        const autoRow = document.createElement('tr');
                        rows.push(autoRow);
                        [
                            (td) => {
                                td.innerHTML = `
                                    <a href="${location.pathname}/${auto.CODIGOM}${location.search}">
                                        ${auto.MATRICULA}
                                    </a>
                                `
                            },
                            (td) => {
                                td.append(auto.TIPO)
                            },
                            (td) => {
                                td.append(lote.MARCA)
                            },
                            (td) => {
                                td.append(lote.MODELO)
                            },
                            (td) => {
                                td.append(auto.MATRICULAANT ? auto.MATRICULAANT : '' )
                            },
                            (td) => {
                                td.innerHTML = `
                                <div class="flex justify-center">
                                    <a title="Ordenes de Trabajo" href="${location.pathname}/${auto.CODIGOM}/track${location.search}">
                                        @include('icons.location')
                                    </a>
                                    <a title="Ordenes de Trabajo" href="${location.pathname}/${auto.CODIGOM}${location.search}">
                                        @include('icons.documents')
                                    </a>
                                </div>
                                `
                            }
                        ].forEach(callback => {
                            const td = document.createElement('td')
                            td.classList.add('text-center')
                            callback(td)
                            autoRow.append(td)
                        })
                        fragment.append(autoRow);
                    });
                    $data.replaceChild(fragment, tr)
                })
        }

        flota.forEach((lote, index) => {
            const tr = document.createElement('tr')
            tr.id = `${lote.MARCA}${lote.MODELO}`;
            const td = document.createElement('td')
            td.colSpan = 6;
            td.classList.add('text-center','text-red-300', 'cursor-pointer');
            td.innerHTML = `Cargar lote ${lote.MARCA} ${lote.MODELO}`;


            tr.classList.add('hover:bg-gray-100', 'hover:font-bold', 'hover:underline')
            tr.append(td);
            $data.append(tr)

            td.onclick = () => {
                td.onclick = null;
                td.innerHTML = 'Cargando lote porfavor estere...'
                td.classList.remove('text-red-300')
                td.classList.add('text-green-400')

                setTimeout(() => {
                    getLoteAndRender(lote, index)
                }, 1000)
            }
        })

        // getLoteAndRender(flota[0], 0)
    @endif
</script>
