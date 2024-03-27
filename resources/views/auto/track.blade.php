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
                    <table class="w-full" is="auto-track">
                        <thead>
                        <th>TALLER</th>
                        <th>OT</th>
                        <th>KILOMETROS</th>
                        <th>FECHA</th>
                        </thead>
                        <tbody id="data-ordenes">
{{--                        @isset($ot)--}}
{{--                            <tr>--}}
{{--                                <td class="text-center">{{$connection->name}}</td>--}}
{{--                                <td class="text-center">{{$ot->CODIGOOT}}</td>--}}
{{--                                <td class="text-center">{{$ot->KMENTRADA}}</td>--}}
{{--                                <td class="text-center">{{$ot->FECHAENTRADA}}</td>--}}
{{--                            </tr>--}}
{{--                        @endisset--}}
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
            moment.locale('es')
            const otrasConneciones = connections

            Array.from(otrasConneciones).forEach((connection) => {
                const tr = document.createElement('tr')
                tr.id = connection.id
                tr.classList.add('hover:bg-gray-100')

                const td = document.createElement('td')
                td.colSpan = 4
                td.classList.add('text-center', 'text-green-400')
                td.append(`Buscando en ${connection.name} porfavor espere`)
                tr.append(td);

                function track(tr, con) {
                    axios
                        .get(location.origin + location.pathname, { params: { connection_id: con.id } })
                        .then(({data}) => data)
                        .then(({status, ot}) => {
                            if(!status || !ot) {
                                tr.remove()
                                return;
                            }

                            if(ot) {
                                td.remove();
                                [
                                    (td) => td.append(con.name),
                                    (td) => {
                                        const a = document.createElement('a')
                                        a.href=`${location.origin}/orden/${ot.CODIGOOT}?connection_id=${con.id}`;
                                        a.append(ot.CODIGOOT)

                                        if(ot.FECHACIERRE) {
                                            a.classList.add('text-red-300')
                                        } else {
                                            a.classList.add('text-green-400')
                                        }

                                        td.append(a)
                                    },
                                    (td) => td.append(ot.KMENTRADA),
                                    (td) => td.append(moment(ot.FECHAENTRADA).fromNow())
                                ].forEach(callback => {
                                    const td = document.createElement('td')
                                    td.classList.add('text-center', 'py-2')
                                    callback(td)
                                    tr.append(td)
                                })
                            }
                        })
                }


                track.bind(this)(tr, connection)


                this.tBodies.item(0).append(tr)
            })
        }
    }
    customElements.define('auto-track', Track, { extends: 'table' })

    window.addEventListener('DOMContentLoaded', () => {

    })
</script>
