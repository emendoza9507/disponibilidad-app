<div class="md:grid md:grid-cols-3">
    <div class="md:col-span-12">
        <div class="mt-1 flex flex-col">
            <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
                <div class="py-2 inline-block min-w-full md:px-4 lg:px-8">
                    <div class="overflow-hidden">
                        <div  class="flex justify-between align-middle mb-5">
                            <h1 class="my-auto text-xl font-bold"> Neumaticos:</h1>
                            <livewire:auto.select-taller :connectionId="$connectionId" :route="'neumatico.index'"/>
                        </div>

                        @isset($error)
                            <p class="text-red-400 mb-6">El Taller se encuentra desconectado en estos momentos, llamar al Jefe de Taller.</p>
                            <a class="text-blue-600 " href="/neumatico">Salir</a>
                        @else

                            <div class="mb-5 flex gap-2">
                                <x-input id="search" wire:model.live.debounce.250ms="matricula" class="block mt-1" type="search" name="search" :value="old('name')" required autofocus autocomplete="search"
                                         placeholder="Matricula"
                                />
                            </div>

                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="text-start">OT</th>
                                        <th class="px-2">ENTRADA</th>
                                        <th class="px-2">NEUMATICOS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ots as $current_ot)
                                        <tr class="cursor-pointer @if($ot?->CODIGOOT == $current_ot->CODIGOOT) bg-gray-300 @endif" wire:click="setOt({{$current_ot->CODIGOOT}})">
                                            <td class="py-6">{{$current_ot->CODIGOOT}}</td>
                                            <td class="px-2 text-center">{{\Carbon\Carbon::create($current_ot->FECHAENTRADA)->format('d-m/h:m')}}</td>
                                            <td class="px-2 text-center">
                                                @if(count($current_ot->neumaticos) == 0)
                                                    @if($this->checkMaterialsOfNeumaticos($current_ot))
                                                        <button class="bg-emerald-500 px-2 rounded-md text-white hover:bg-emerald-700" wire:click="generarNeumaticos({{$current_ot->CODIGOOT}})">GENERAR</button>
                                                    @endif
                                                @else
                                                    <div class="">
                                                    <span class="bg-gray-500 px-1.5 py-0.5 text-white border-green-600 border-2">{{count($current_ot->neumaticos)}}</span>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if($ot)
                                <div class="mt-4 mb-2 flex justify-between">
                                    <span class="text-blue-400">
                                        Neumaticos cargados en Mistral:
                                        <b>{{$this->checkNeumaticosCargados()}}</b>
                                    </span>


                                    @if(count($ot->neumaticos) < $this->checkNeumaticosCargados())
                                        <div>
                                            <span class="text-blue-400">
                                            Consecutivos generados:
                                            <b>{{count($ot->neumaticos)}}</b>
                                        </span>
                                            <button class="bg-emerald-500 px-2 rounded-md text-white hover:bg-emerald-700" wire:click="generarNeumaticos({{$current_ot->CODIGOOT}})">GENERAR</button>
                                        </div>
                                    @else
                                        <span class="text-blue-400">
                                            Consecutivos generados:
                                            <b>{{count($ot->neumaticos)}}</b>
                                        </span>
                                    @endif
                                </div>

                                <table >
                                    <tbody>
                                    @foreach($ot->neumaticos as $neumatico)
                                        <tr>
                                            <th class="py-2">Consecutivo: </th>
                                            <td class="pr-3"><span class="bg-gray-500 px-1.5 py-0.5 text-white border-green-600 border-2">{{$neumatico->id}}</span></td>

                                            <th>Taller: </th>
                                            <td class="pr-3">{{$neumatico->TALLER}}</td>

                                            <th>Generado por: </th>
                                            <td>{{$neumatico->user->name}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
