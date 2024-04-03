<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Consecutivo de Neumatico') }}
        </h2>
    </x-slot>


    <x-container>

        @include('partials.messages')

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <form action="{{route('consecutivo.neumatico.update', [$neumatico->id, 'connection_id' => $connection_id])}}" method="POST" id="reporte-neumatico" class="p-6 lg:p-8 bg-white">
                @csrf
                @method('PUT')

                <div class="mt-3 text-center">
                    <h3 class="text-xl uppercase font-bold inline-block right-2 relative">Neumatico</h3>
                </div>

                <div class="mt-2 grid grid-cols-1 gap-3 px-0 sm:px-30">
                    <div class="grid grid-cols-2">
                        <div>
                            <b class="relative left-10">CONSECUTIVO:</b>
                        </div>
                        <div class="text-end">
                            <span class="relative right-10">{{$neumatico->consecutivo()}}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6 items-center">
                        <div>
                            <b class="relative left-10">CONSECUTIVO ANTERIOR:</b>
                        </div>
                        <div class="text-end" is="consecutive-loader">
                            <x-input id="anterior" @class(['relative right-10 text-end', 'border-2 border-red-300' => $errors->has('anterior')])  name="anterior" value="{{old('anterior')}}"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div>
                            <b class="relative left-10">TALLER:</b>
                        </div>
                        <div class="text-end">
                            <span class="relative right-10" title="">
                                {{$neumatico->TALLER}}
                                <bold class="font-bold">({{$neumatico->connection->name}})</bold>
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div>
                            <b class="relative left-10">OT:</b>
                        </div>
                        <div class="text-end">
                            <span class="relative right-10">
                                <a href="{{route('orden.show', [$neumatico->CODIGOOT, 'connection_id' => $neumatico->connection_id])}}" class="underline">
                                    {{$neumatico->CODIGOOT}}
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div>
                            <b class="relative left-10">CREADA POR:</b>
                        </div>
                        <div class="text-end">
                            <span class="relative right-10">{{$neumatico->user->name}} </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div>
                            <b class="relative left-10">FECHA CREADA:</b>
                        </div>
                        <div class="text-end">
                            <span class="relative right-10">{{$neumatico->created_at->format('d/m/Y')}}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 items-center">
                        <div>
                            <b class="relative left-10">CONSECUTIVO MANUAL:</b>
                        </div>
                        <div class="text-end">
                            <x-input @class(['relative right-10 text-end', 'border-2 border-red-300' => $errors->has('cons_manual')]) name="cons_manual" value="{{old('cons_manual')}}"/>
                        </div>
                    </div>
                    @if($neumatico->OBSERVACIONES)
                        <div class="grid grid-cols-2">
                            <div>
                                <b class="relative left-10">OBSERVACIONES:</b>
                            </div>
                            <div class="text-end">
                                <span class="relative right-10">{{$neumatico->created_at->format('d/m/Y')}}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-5 sm:px-30 text-end">
                    <x-button onclick="history.back()" class="print:hidden relative right-10 bg-gray-600 text-black hover:text-white rounded-none">
                       Cancelar
                    </x-button>
                    <x-button class="print:hidden relative right-10 bg-green-600 text-black hover:text-white rounded-none">
{{--                        @include('icons.save')--}}
                        GUARDAR
                    </x-button>
                </div>
            </form>
        </div>
    </x-container>
</x-app-layout>
<script>
    class ConsecutiveLoader extends HTMLElement {
        data = []
        currentFocus = -1

        constructor() {
            super();
            this.attachShadow({mode: "open"})

            this.shadowRoot.innerHTML = `
                <slot></slot>
            `
        }

        setData(data) {
            this.data = data
            //Comprobar que existe el id del consecutivo anteriror si no existe mostrar el input bordeado de reojo

            let exists = this.data.filter(neumatico => neumatico.id.toString() === this.input.value.toString())

            if(exists.length === 0) {
                this.input.classList.add('border-2','border-red-300')
            } else {
                this.input.classList.remove('border-2', 'border-red-300')
            }
        }

        connectedCallback() {
            const input = this.input = this.querySelector('input');

            if(input) {

                let interval = null

                input.addEventListener('input', () => {

                    if(interval != null) clearTimeout(interval)

                    interval = setTimeout(() => {
                        axios
                            .get(`{{route('consecutivo.neumatico.json_all')}}?query=${input.value}`)
                            .then(({data}) => {
                                this.setData(data)
                            })
                    }, 500)
                })




            }

        }
    }
    customElements.define('consecutive-loader', ConsecutiveLoader);

    window.addEventListener('DOMContentLoaded', () => {


    })
</script>
