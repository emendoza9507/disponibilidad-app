<x-app-layout>
    <x-container>

        @include('partials.messages')

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div  id="reporte-neumatico" class="p-6 lg:p-8 bg-white">


                <div class="mt-3 text-center">
                    <h3 class="text-xl uppercase font-bold inline-block relative">
                        Rastreando auto ({{$maestro->MATRICULA}})
                    </h3>
                </div>


                <div class="mt-3">
                    <table class="w-full">
                        <thead>
                        <th>TALLER</th>
                        <th>OT</th>
                        <th>KILOMETROS</th>
                        <th>FECHA</th>
                        </thead>
                        <tbody id="data-ordenes">
                        @isset($ot)
                            <tr>
                                <td class="text-center">{{$connection->name}}</td>
                                <td class="text-center">{{$ot->CODIGOOT}}</td>
                                <td class="text-center">{{$ot->KILOMETROENTRADA}}</td>
                                <td class="text-center">{{$ot->FECHAENTRADA}}</td>
                            </tr>
                        @endisset
                        </tbody>
                    </table>
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
            </div>
        </div>
    </x-container>
</x-app-layout>
<script>

    class Track extends HTMLTableElement {
        connectedCallback() {
            console.log(this.tHead)
        }
    }
    customElements.define('auto-tack', Track, { extends: 'table' })

    window.addEventListener('DOMContentLoaded', () => {

    })
</script>
