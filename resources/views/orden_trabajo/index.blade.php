<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ordenes de Trabajo') }}
        </h2>
    </x-slot>


    <x-container>

        @include('partials.messages')

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <form class="flex gap-2 justify-between" action="{{route('orden.index')}}">
                    <div class="flex gap-2">
                        <div class="relative">
                            <x-input id="input-search" class="rounded-none pr-8" placeholder="Buscar"/>
                            <span class="absolute right-2 top-2.5 ">@include('icons.search')</span>
                        </div>

                        <x-input class="rounded-none" name="start_date" type="date" value="{{$start_date->format('Y-m-d')}}"/>
                        <x-input class="rounded-none" name="end_date" type="date" value="{{$end_date->format('Y-m-d')}}"/>

                        <x-button class="rounded-none h-full">GENERAR</x-button>
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

                @include('orden_trabajo.list')
            </div>
        </div>
    </x-container>
</x-app-layout>
<script>
    const $inputSearh = document.querySelector('#input-search');
    const $dataNeumaticos = document.querySelector('#data-ordenes');

    $inputSearh.addEventListener('keyup', () => {
        Array.from($dataNeumaticos.children).forEach(($tr => {
            if($tr.innerText.toLowerCase().includes($inputSearh.value.toString().toLowerCase())) {
                $tr.classList.remove('hidden')
            } else {
                $tr.classList.add('hidden')
            }
        }))
    })
</script>
