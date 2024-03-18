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
                    <select name="connection_id">
                        @foreach($connections as $connection)
                        <option value="{{$connection->id}}" @if($connection->id == $connection_id) selected @endif>
                            {{$connection->name}}
                        </option>
                        @endforeach
                    </select>
                </form>

                <br>

                <div style="max-height: 400px" class="relative overflow-y-auto">
                    <table class="w-full relative">
                        <thead class="sticky top-0 bg-white">
                        <tr class="uppercase">
                            <th class="px-1">#</th>
                            <th class="px-4">Matricula</th>
                            <th class="px-4">Tipo</th>
                            <th class="px-4">Marca</th>
                            <th class="px-4">Modelo</th>
                            <th class="px-4">Num. Motor</th>
                            <th class="px-4">Mat. Anterior</th>
                            <th colspan="2"></th>
                        </tr>
                        </thead>
                        <tbody id="data-autos">
                        @foreach($autos as $auto)
                            <tr class="hover:bg-gray-300">
                                <td class="px-1 py-2 text-center">{{$loop->index + 1}}</td>
                                <td class="px-4 text-center">{{$auto->MATRICULA}}</td>
                                <td class="px-4 text-center">{{$auto->supermaestro?->TIPO}}</td>
                                <td class="px-4 text-center">{{$auto->supermaestro?->MARCA}}</td>
                                <td class="px-4 text-center">{{$auto->supermaestro?->MODELO}}</td>
                                <td class="px-4 text-center">{{$auto->NOMOTOR}}</td>
                                <td class="px-4 text-center">{{$auto->MATRICULAANT}}</td>
                                <td>
                                    <a href="{{route('autos.show', $auto->CODIGOM)}}">
                                        @include('icons.documents')
                                    </a>
                                </td>
                                <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-container>
</x-app-layout>
<script>
    const $inputSearh = document.querySelector('#input-search');
    const $dataNeumaticos = document.querySelector('#data-autos');

    $inputSearh.addEventListener('keyup', () => {
        Array.from($dataNeumaticos.children).forEach(($tr => {
            if($tr.innerText.toLowerCase().includes($inputSearh.value.toString().trim().toLowerCase())) {
                $tr.classList.remove('hidden')
            } else {
                $tr.classList.add('hidden')
            }
        }))
    })
</script>
