<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Neumatico') }}
        </h2>
    </x-slot>


    <x-container>

        @include('partials.messages')

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div  id="reporte-neumatico" class="p-6 lg:p-8 bg-white">


                <div class="mt-3">
                    <h3 class="text-xl uppercase font-bold inline-block relative">
                        Neumaticos cargados en Talleres ({{$maestro->MATRICULA}})
                    </h3>
                </div>


                <div class="mt-3">
                    <table class="w-full">
                        <thead>
                            <th class="w-1">TALLER</th>
                            <th>OT</th>
                            <th>FECHA</th>
                        </thead>
                        <tbody id="data-ordenes">

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
    window.addEventListener('DOMContentLoaded', () => {
        const $btnPrint = document.querySelector('#btn-print');
        const $areaPrint = document.querySelector('#reporte-neumatico');
        const $dataOrdenes = document.querySelector('#data-ordenes');

        reportPrint($btnPrint, $areaPrint)

        const connections = @json($connections).data

        const promises = Array.from(connections).map(connection => {
            return fetch(`{{route('consecutivo.neumatico.json_last_ot_with_neumatico', $maestro->CODIGOM)}}?connection_id=${connection.id}`)
                .then(res => res.json())
        })

        Promise.all(promises).then(promises => {
            promises.sort((t1, t2) => {
                return t1.data?.FECHACIERRE < t2.data?.FECHACIERRE ? 1 : -1
            }).forEach(promise => {
                console.log(promise)
                const tr = document.createElement('tr');
                $dataOrdenes.append(tr);

                tr.classList.add('hover:bg-gray-300')

                if(promise.status) {
                    if(promise.data) {
                        [
                            (td) => {
                                td.append(promise.taller)
                            },
                            (td) => {
                                const ot = promise.data.CODIGOOT
                                td.classList.add('cursor-pointer')
                                td.append(ot)

                                td.addEventListener('click', () => {
                                    location.href = `${location.origin}/orden/${ot}?connection_id=${promise.connection_id}`
                                })
                            },
                            (td) => td.append(promise.data.FECHACIERRE)
                        ].forEach(callback => {
                            const td = document.createElement('td');
                            td.classList.add('text-center')
                            callback(td)
                            tr.append(td)
                        })
                    }
                } else {
                    [
                        (td) => td.append(promise.taller),
                        (td) => {
                            td.colSpan = 2
                            td.append("Taller actualmente desconectado")
                        }
                    ].forEach(callback => {
                        const td = document.createElement('td')
                        td.classList.add('text-center')
                        callback(td)
                        tr.append(td)
                        tr.classList.add('bg-red-400','text-white', 'font-bold')
                    })
                }
            })
        })
    })
</script>
