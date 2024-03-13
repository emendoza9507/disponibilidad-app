<div class="md:grid md:grid-cols-3">
    <div class="md:col-span-12">
        <div class="mt-1 flex flex-col">
            <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
                <div class="py-2 inline-block min-w-full md:px-4 lg:px-8">
                    <div class="overflow-hidden">
                        <div  class="flex justify-between align-middle mb-5">
                            <h1 class="my-auto text-xl font-bold"> Autos: {{$connection->database}}</h1>
                            <livewire:auto.select-taller :connectionId="$connectionId" :route="'autos.index'"/>
                        </div>


                        @isset($error)
                            <p class="text-red-400 mb-6">El Taller se encuentra desconectado en estos momentos, llamar al Jefe de Taller.</p>
                            <a class="text-blue-600 " href="/autos">Salir</a>
                        @else

                        <div class="mb-5 flex gap-2">
                            <x-input id="search" wire:model.live.debounce.250ms="search" class="block mt-1" type="search" name="search" :value="old('name')" required autofocus autocomplete="search"
                            placeholder="Matricula"
                            />
                            <x-button wire:click="search">
                                @include('icons.search')
                            </x-button>
                        </div>
                        <table class="w-full">
                            <thead class="max-w">
                            <tr>
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
                            <tbody>
                            @foreach($autos as $auto)
                                <tr wire:key="{{uuid_create().$auto->MATRUCULA}}">
                                    <td class="px-1 text-center"></td>
                                    <td class="px-4 text-center">{{$auto->MATRICULA}}</td>
                                    <td class="px-4 text-center">{{$auto->supermaestro?->TIPO}}</td>
                                    <td class="px-4 text-center">{{$auto->supermaestro?->MARCA}}</td>
                                    <td class="px-4 text-center">{{$auto->supermaestro?->MODELO}}</td>
                                    <td class="px-4 text-center">{{$auto->NOMOTOR}}</td>
                                    <td class="px-4 text-center">{{$auto->MATRICULAANT}}</td>
                                    <td>
                                        <livewire:orden-trabajo.index :maestro="$auto" :key="uuid_create().$auto->MATRICULA"/>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
{{--                        {{$autos->links()}}--}}

                        <br class="mb-6">


                        <h3 class="text-xl mb-4 font-bold">Resumen Neumaticos y Baterias</h3>

                        <div class="mb-2">
                            <input type="date" wire:model.live.debounce.500ms="startDateResumen">
                            <input type="date" wire:model.live.debounce.500ms="endDateResumen">
                        </div>

                        <div wire:loading wire:target="startDateResumen" class="text-red-400">
                            Cargando datos...
                        </div>
                        <section wire:loading.remove wire:target="startDateResumen" class="flex justify-between relative gap-3">

                            <div class="flex-1 " style="height: 200px; overflow-y: auto">
                                {{-- NEUMATICOS --}}
                                <h4 class="text-xl font-bold mb-2 sticky top-0 bg-white">Neumaticos:</h4>

                                <table class="w-full">
                                    <tbody id="data-neumaticos" >
                                    @php $total = 0  @endphp
                                    @foreach($this->resumenNeumaticos as $key => $value)
                                        @php $total += $value['neumaticos'] @endphp
                                        <tr class="@if($value['neumaticos'] > 4) text-red-400 @endif">
                                            <th>OT:</th>
                                            <td class="pr-2">{{$key}}</td>

                                            <th>MATRICULA:</th>
                                            <td class="pr-2">{{$value['ot']->MATRICULA}}</td>

                                            <th>Cant.:</th>
                                            <td class="px-2 min-w-10 text-right">{{$value['neumaticos']}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <table class="sticky bottom-0 bg-white w-full">
                                    <tr>
                                        <th colspan="5" class="text-left">Total</th>
                                        <td class="text-right px-2">{{$total}}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="flex-1" style="height: 200px; overflow-y: auto">
                                {{-- BATERIAS --}}
                                <h4 class="text-xl font-bold mb-2 sticky top-0 bg-white">Baterias:</h4>

                                <table class="w-full" >
                                    <tbody>
                                    @php $total = 0  @endphp
                                    @foreach($this->resumenBaterias as $key => $value)
                                        @php $total += $value['baterias'] @endphp
                                        <tr class="@if($value['baterias'] > 1) text-red-400 @endif">
                                            <th>OT:</th>
                                            <td class="pr-2">{{$key}}</td>

                                            <th>MATRICULA:</th>
                                            <td class="pr-2">{{$value['ot']->MATRICULA}}</td>

                                            <th>Cant.:</th>
                                            <td class="px-2 min-w-10 text-right">{{$value['baterias']}}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                <table class="sticky bottom-0 bg-white w-full">
                                    <tr class="">
                                        <th colspan="5" class="text-left">Total</th>
                                        <td class="text-right px-2 ">{{$total}}</td>
                                    </tr>
                                </table>
                            </div>
                        </section>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const $data = document.querySelector('#data-neumaticos');

    const $rows = Array.from($data.querySelectorAll('tr'));
    const $total = $rows.pop();


</script>
