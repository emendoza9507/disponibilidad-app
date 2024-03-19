<x-app-layout>
    <x-container>

        @include('partials.messages')

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div  id="reporte-neumatico" class="p-6 lg:p-8 bg-white">


                <div class="mt-3 text-center">
                    <h3 class="text-xl uppercase font-bold inline-block relative">
                        Ultimas baterias cargados en Talleres ({{$maestro->MATRICULA}})
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
                            <tr>
                                <td colspan="4" class="text-center">
                                    <span class="text-red-300"> Cargando datos por favor espere...</span>
                                </td>
                            </tr>
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
            return fetch(`{{route('consecutivo.bateria.json_last_ot_with_bateria', $maestro->CODIGOM)}}?connection_id=${connection.id}`)
                .then(res => res.json())
        })

        let desconectados = [];
        Promise
            .all(promises).then(promises => {
                desconectados = promises.filter((v) => !v.status)

                promises.filter(v => v.status && v.data).sort((t1, t2) => {
                    return new Date(t2.data.FECHACIERRE).getTime() - new Date(t1.data.FECHACIERRE).getTime()
                }).forEach(promise => {
                    const tr = document.createElement('tr');
                    $dataOrdenes.append(tr);

                    tr.classList.add('hover:bg-gray-300')

                    if(promise.status) {
                        if(promise.data) {
                            [
                                // Mostrar Nombre del Taller
                                (td, promise) => {
                                    td.classList.add('py-2')
                                    td.append(promise.taller)
                                },
                                // Mostar ID de la OT
                                (td, promise) => {
                                    const ot = promise.data.CODIGOOT
                                    const span = document.createElement('span');
                                    td.append(span);

                                    span.classList.add('cursor-pointer')
                                    span.append(ot)
                                    span.addEventListener('click', () => {
                                        location.href = `${location.origin}/orden/${ot}?connection_id=${promise.connection_id}`
                                    })
                                },
                                (td, promise) => {
                                    td.append(promise.data?.KMENTRADA)
                                },
                                // Mostrar fecha
                                (td, promise) => {
                                    let flag = false
                                    moment.locale('ES')
                                    const span = document.createElement('span')
                                    span.classList.add('cursor-pointer')


                                    let str_date = span.innerHTML = moment(new Date(promise.data.FECHACIERRE)).fromNow();

                                    // Alternar entre fecha y string
                                    span.addEventListener('click', () => {
                                        if(flag) {
                                            span.innerHTML = str_date
                                        } else {
                                            span.innerHTML = moment(new Date(promise.data.FECHACIERRE)).calendar()
                                        }
                                        flag = !flag
                                    })
                                    td.append(span)
                                }
                            ].forEach(callback => {
                                const td = document.createElement('td');
                                td.classList.add('text-center', 'border-b-2')
                                callback(td, promise)
                                tr.append(td)
                            })
                        }
                    } else {
                        [
                            (td, promise) => td.append(promise.taller),
                            (td) => {
                                td.colSpan = 2
                                td.append("Taller actualmente desconectado")
                            }
                        ].forEach(callback => {
                            const td = document.createElement('td')
                            td.classList.add('text-center')
                            callback(td, promise)
                            tr.append(td)
                            tr.classList.add('bg-red-400','text-white', 'font-bold')
                        })
                    }
                })
            })
            .finally(() => {
                $dataOrdenes.removeChild($dataOrdenes.children[0]);

                // mostrar talleres desconectados
                const tr = document.createElement('tr');
                $dataOrdenes.append(tr)

                const tdIndex = document.createElement('th');
                tdIndex.classList.add('text-center')
                tdIndex.colSpan = desconectados.length
                tdIndex.append('DESCONECTADOS')

                desconectados.forEach((taller, index) => {
                    const tr = document.createElement('tr');
                    $dataOrdenes.append(tr);

                    if(index == 0) tr.append(tdIndex)

                    const td = document.createElement('td');
                    td.classList.add('text-center','text-red-400')
                    const span = document.createElement('span')
                    span.append(taller.taller)
                    td.append(span)

                    tr.append(td);
                })

            })

    })
</script>
