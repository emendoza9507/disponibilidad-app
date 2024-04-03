<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Consecutivo de Neumaticos') }}
        </h2>
    </x-slot>


    <x-container>

        @include('partials.messages')

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <form id="form-search-ordenes" class="flex gap-2 justify-between"
                      action="{{route('consecutivo.neumatico.all')}}">
                    <div class="flex gap-2">
                        <div class="relative">
                            <x-input id="input-search" name="query" value="{{$query}}" class="rounded-none pr-8" placeholder="Buscar"/>
                            <span class="absolute right-2 top-2.5 ">@include('icons.search')</span>
                        </div>

                        <x-button class="rounded-none h-full">BUSCAR</x-button>
                    </div>
                </form>


                <div class="mt-3 flex justify-between">
                    <h3 class="text-xl font-bold uppercase">Neumaticos</h3>
                    <div class="text-xl">
                        <b>TOTAL:</b>
                        <span>{{$consecutivos->total()}}</span>
                    </div>
                </div>

                <div style="max-height: 400px" class="overflow-y-auto">
                    <table class="mt-1 w-full">
                        <thead class="sticky top-0 bg-white">
                            <tr class="">
                                <th class="text-start py-2 pr-2 w-1">CONSECUTIVO</th>
                                <th class="text-start">OT</th>
                                <th class="text-start">TALLER</th>
                                <th class="text-end">FECHA</th>
                            </tr>
                        </thead>
                        <tbody id="data-consecutivos">
                        @foreach($consecutivos as $consecutivo)
                            @php($fecha = \Carbon\Carbon::create($consecutivo->created_at))
                            <tr class="text-start hover:bg-gray-300">
                                <td class="text-start py-2">
                                    <a href="{{route('consecutivo.neumatico.show', $consecutivo->id)}}">
                                    {{$consecutivo->id}}
                                    </a>
                                </td>
                                <td class="text-start">
                                    <a @isset($consecutivo->connection)href="{{route('orden.show', $consecutivo->CODIGOOT)}}?connection_id={{$consecutivo->connection->id}}"@endisset>
                                        {{$consecutivo->CODIGOOT}}
                                    </a>
                                </td>
                                <td class="pl-2">
                                    <span title="{{$consecutivo->connection->name}}">
                                        {{$consecutivo->TALLER}}
                                    </span>
                                </td>
                                <td class="text-end">
                                    {{$fecha->format('d/m/Y')}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">
                    {{$consecutivos->links()}}
                </div>
            </div>
        </div>
    </x-container>
</x-app-layout>
<script>
    const $formSerchOrdenes = document.querySelector('#form-search-ordenes');
    const $inputSearh = document.querySelector('#input-search');
    const $consecutivos = document.querySelector('#data-consecutivos');

    $inputSearh.addEventListener('keyup', () => {
        Array.from($consecutivos.children).forEach(($tr => {
            if ($tr.innerText.toLowerCase().includes($inputSearh.value.toString().trim().toLowerCase())) {
                $tr.classList.remove('hidden')
            } else {
                $tr.classList.add('hidden')
            }
        }))
    })
</script>
