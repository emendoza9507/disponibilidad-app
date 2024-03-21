<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Consecutivo de Bateria') }}
        </h2>
    </x-slot>


    <x-container>

        @include('partials.messages')

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <form  id="reporte-neumatico" class="p-6 lg:p-8 bg-white">


                <div class="mt-3 text-center">
                    <h3 class="text-xl uppercase font-bold inline-block right-2 relative">BATERIA</h3>
                </div>

                <div class="mt-2 grid grid-cols-1 gap-3 px-0 sm:px-30">
                    <div class="grid grid-cols-2">
                        <div>
                            <b class="relative left-10">CONSECUTIVO:</b>
                        </div>
                        <div class="text-end">
                            <span class="relative right-10">{{$bateria->consecutivo()}}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <b class="relative left-10">CONSECUTIVO ANTERIOR:</b>
                        </div>
                        <div class="text-end">
                            <x-input name="anterior" value="{{old('anterior')}}"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div>
                            <b class="relative left-10">TALLER:</b>
                        </div>
                        <div class="text-end">
                            <span class="relative right-10">{{$bateria->TALLER}}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div>
                            <b class="relative left-10">OT:</b>
                        </div>
                        <div class="text-end">
                            <span class="relative right-10">
                                <a href="{{route('orden.show', [$bateria->CODIGOOT, 'connection_id' => $bateria->connection_id])}}" class="underline">
                                    {{$bateria->CODIGOOT}}
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div>
                            <b class="relative left-10">CREADA POR:</b>
                        </div>
                        <div class="text-end">
                            <span class="relative right-10">{{$bateria->user->name}} </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div>
                            <b class="relative left-10">FECHA CREADA:</b>
                        </div>
                        <div class="text-end">
                            <span class="relative right-10">{{$bateria->created_at->format('d/m/Y')}}</span>
                        </div>
                    </div>
                    @if($neumatico->cons_manual)
                        <div class="grid grid-cols-2">
                            <div>
                                <b class="relative left-10">CONSECUTIVO MANUAL:</b>
                            </div>
                            <div class="text-end">
                                <span class="relative right-10">{{$neumatico->cons_manual}}</span>
                            </div>
                        </div>
                    @endif
                    @if($bateria->OBSERVACIONES)
                        <div class="grid grid-cols-2">
                            <div>
                                <b class="relative left-10">OBSERVACIONES:</b>
                            </div>
                            <div class="text-end">
                                <span class="relative right-10">{{$bateria->created_at->format('d/m/Y')}}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-5 sm:px-30 text-end">
                    <x-button onclick="history.back()" class="print:hidden relative right-10 bg-gray-600 text-black hover:text-white rounded-none">
                       @include('icons.back')
                    </x-button>
                    <x-button id="btn-print" class="print:hidden relative right-10 bg-yellow-600 text-black hover:text-white rounded-none">
                        @include('icons.printer')
                        IMPRIMIR
                    </x-button>
                </div>
            </form>
        </div>
    </x-container>
</x-app-layout>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const $btnPrint = document.querySelector('#btn-print');
        const $areaPrint = document.querySelector('#reporte-neumatico');

        reportPrint($btnPrint, $areaPrint)
    })
</script>
