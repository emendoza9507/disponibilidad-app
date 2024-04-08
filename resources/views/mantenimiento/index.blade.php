<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mantenimientos en Taller') }}
        </h2>
    </x-slot>


    <x-container>

        @include('partials.messages')

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <form class="flex gap-2 justify-between" action="{{route('mantenimiento.index')}}">
                    <div class="flex gap-2">
                        <div class="relative">
                            <x-input id="input-search" name="matricula" class="rounded-none pr-8" placeholder="Buscar"/>
                            <span class="absolute right-2 top-2.5 ">@include('icons.search')</span>
                        </div>

                        <x-input class="rounded-none" name="start_date" type="date" value="{{$start_date?->format('Y-m-d')}}"/>
                        <x-input class="rounded-none" name="end_date" type="date" value="{{$end_date?->format('Y-m-d')}}"/>

                        <x-button is="buttom-submit" class="rounded-none h-full">BUSCAR</x-button>
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

                <section>
                    @error('codigoot')
                        <div class="mb-3 p-2 bg-red-400 text-white" onclick="removeMessage(this)">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="grid grid-cols-2 gap-3">
                        <article>
                            <header class="pb-2">
                                <h3 class="uppercase p-2 text-xl inline bg-gray-300">Mantenimientos</h3>
                            </header>
                            <div class="scrollable" style="height: 400px; overflow:auto">
                                <table class="w-full">
                                    <thead class="sticky top-0 bg-gray-300">
                                        <th class="w-0 py-1 px-2 text-start">OT</th>
                                        <th class="text-start px-2">MATRICULA</th>
                                        <th></th>
                                    </thead>
                                    <tbody class=" bg-gray-50">
                                        @foreach ($mantenimientos as $mantenimiento)
                                            <tr class="hover:bg-gray-300">
                                                <td class="py-1 px-2">
                                                    <a href="{{route('orden.show', [$mantenimiento->codigoot, 'connection_id' => $connection_id])}}">
                                                        {{$mantenimiento->codigoot}}
                                                    </a>
                                                </td>
                                                <td class="px-2">
                                                    {{$mantenimiento->matricula}}
                                                </td>
                                                <td>
                                                    <div>
                                                        <form onsubmit="confirmEliminarMantenimiento(event)" action="{{route('mantenimiento.destroy', [$mantenimiento->id, 'connection_id' => $connection_id])}}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button>
                                                                @include('icons.trash')
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="sticky bottom-0 bg-white">
                                        <tr>
                                            <td colspan="3">
                                                {{$mantenimientos->links()}}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </article>
                        <article>
                            <header class="pb-2 text-end">
                                <h3 class="uppercase p-2 text-xl inline bg-gray-300">Ordenes de Trabajo</h3>
                            </header>
                            <div class="scrollable" style="height: 400px; overflow-y: auto">
                                <table class="w-full">
                                    <thead class="sticky top-0 bg-gray-300">
                                        <tr>
                                            <th class="w-0 py-1 px-2 text-start">OT</th>
                                            <th class="text-start px-2">MATRICUAL</th>
                                            <th class="text-center">ESTADO</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-ordenes" class=" bg-gray-50">
                                        @foreach ($ordenes as $ot)
                                            <tr class="hover:bg-gray-300">
                                                <td class="py-1 px-2">
                                                    <a href="{{route('orden.show', [$ot->CODIGOOT, 'connection_id' => $connection_id])}}">{{$ot->CODIGOOT}}</a>
                                                </td>
                                                <td class="px-2">
                                                    <a href="{{route('autos.show', [$ot->CODIGOM, 'connection_id' => $connection_id])}}">{{$ot->MATRICULA}}</a>
                                                </td>
                                                <td class="text-center">{{$ot->estado->estado_nombre}}</td>
                                                <td>
                                                    <form action="{{route('mantenimiento.store', ['connection_id'=>$connection_id])}}" method="POST">
                                                        @csrf()
                                                        <input type="hidden" name="codigoot" value="{{$ot->CODIGOOT}}">
                                                        <input type="hidden" name="matricula" value="{{$ot->MATRICULA}}">
                                                        <input type="hidden" name="created_at" value="{{$ot->FECHAENTRADA}}">
                                                        <button>
                                                            @include('icons.plus')
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </div>
    </x-container>
</x-app-layout>
<script>
    const $inputSearh = document.querySelector('#input-search');
    const $dataOrdenes = document.querySelector('#data-ordenes');

    $inputSearh.addEventListener('keyup', () => {
        Array.from($dataOrdenes.children).forEach(($tr => {
            if($tr.innerText.toLowerCase().includes($inputSearh.value.toString().trim().toLowerCase())) {
                $tr.classList.remove('hidden')
            } else {
                $tr.classList.add('hidden')
            }
        }))
    })

    function confirmEliminarMantenimiento(e) {
        let result = confirm('Seguro que desea eliminar este mantenimiento');

        if(result == true) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }
    }
</script>
