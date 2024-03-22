<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div class="flex items-center">
                        <svg viewBox="0 0 50 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-12">
                            <path d="M11.395 44.428C4.557 40.198 0 32.632 0 24 0 10.745 10.745 0 24 0a23.891 23.891 0 0113.997 4.502c-.2 17.907-11.097 33.245-26.602 39.926z" fill="#6875F5"/>
                            <path d="M14.134 45.885A23.914 23.914 0 0024 48c13.255 0 24-10.745 24-24 0-3.516-.756-6.856-2.115-9.866-4.659 15.143-16.608 27.092-31.75 31.751z" fill="#6875F5"/>
                        </svg>

                        <h1 class="text-5xl uppercase font-bold">
                            {{config('app.name')}}
                        </h1>
                    </div>

                    <h1 class="mt-8 text-2xl font-medium text-gray-900">
                        Bienvenido {{auth()->user()->name}}
                    </h1>

                    <p class="mt-6 text-gray-500 leading-relaxed">
                        <strong>{{config('app.name')}}</strong> Es una herramienta puesta a tu alcance para mejorar tu estilo de trabajo y tengas control sobre las desiciones que necesites
                        tomar en tu dia. Si eres <b><i>Técnico de Taller</i></b>, podras consultar el estado de cualquier Auto en todo momento en tiempo real en los distintos talleres que esten conectados
                        en el momento de la consulta, para hacer que tu trabajo pueda fluir de manera rapida y constante.
                    </p>
                </div>

                <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
                    <div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 0 1-3-3m3 3a3 3 0 1 0 0 6h13.5a3 3 0 1 0 0-6m-16.5-3a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3m-19.5 0a4.5 4.5 0 0 1 .9-2.7L5.737 5.1a3.375 3.375 0 0 1 2.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 0 1 .9 2.7m0 0a3 3 0 0 1-3 3m0 3h.008v.008h-.008v-.008Zm0-6h.008v.008h-.008v-.008Zm-3 6h.008v.008h-.008v-.008Zm0-6h.008v.008h-.008v-.008Z" />
                            </svg>

                            <h2 class="ms-3 text-xl font-semibold text-gray-900">
                                <a href="">Flota</a>
                            </h2>
                        </div>

                        <div class="mt-4" @style(['max-height: 200px', 'overflow: hidden'])>
                            <table is="flota-loader" class="w-full">
                                <thead class="sticky top-0 bg-gray-200">
                                </thead>
                                <tfoot class="sticky bottom-0 bg-gray-200"></tfoot>
                            </table>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.348 14.652a3.75 3.75 0 0 1 0-5.304m5.304 0a3.75 3.75 0 0 1 0 5.304m-7.425 2.121a6.75 6.75 0 0 1 0-9.546m9.546 0a6.75 6.75 0 0 1 0 9.546M5.106 18.894c-3.808-3.807-3.808-9.98 0-13.788m13.788 0c3.808 3.807 3.808 9.98 0 13.788M12 12h.008v.008H12V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>

                            <h2 class="ms-3 text-xl font-semibold text-gray-900">
                                <a href="#">Talleres</a>
                            </h2>
                        </div>

                        <div class="mt-4" @style(['max-height: 200px', 'overflow: auto'])>
                            <table is="connection-state-loader" class="w-full">
                                <thead class="sticky top-0 bg-gray-200">
                                </thead>
                                <tfoot class="sticky bottom-0 bg-gray-200"></tfoot>
                            </table>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-6 h-6 stroke-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <h2 class="ms-3 text-xl font-semibold text-gray-900">
                                <a href="https://tailwindcss.com/">Tailwind</a>
                            </h2>
                        </div>

                        <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                            Laravel Jetstream is built with Tailwind, an amazing utility first CSS framework that doesn't get in your way. You'll be amazed how easily you can build and maintain fresh, modern designs with this wonderful framework at your fingertips.
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-6 h-6 stroke-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            <h2 class="ms-3 text-xl font-semibold text-gray-900">
                                Authentication
                            </h2>
                        </div>

                        <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                            Authentication and registration views are included with Laravel Jetstream, as well as support for user email verification and resetting forgotten passwords. So, you're free to get started with what matters most: building your application.
                        </p>
                    </div>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>

<script>
    class FlotaLoader extends HTMLTableElement {
        _route = `{{route('auto.json_flota')}}`
        _next_page_url = null

        _flota = []
        _tbody = null

        _page = 1
        _total = 10

        get flota() {
            return this._flota
        }

        set flota(val) {
            this._flota = val
        }

        get tbody() {
            if(!this._tbody) {
                this._tbody = document.createElement('tbody')
                this.append(this._tbody)
            }

            return this._tbody
        }

        constructor() {
            super();
        }

        loadFlota(callback, route) {
            axios.get(route)
                .then(({data}) => {
                    if(data.status) {
                        this.flota = data.data
                        callback(this.flota)
                    }
                })
        }

        renderFlota(flota) {
            flota.forEach(({MARCA, MODELO, total}) => {
                const tr = document.createElement('tr');
                this.tbody.append(tr);

                [
                    (td) => {
                        td.append(MARCA)
                    },
                    (td) => {
                        td.append(MODELO)
                    },
                    (td) => {
                        td.append(total)
                    }
                ].forEach((callback) => {
                    const td = document.createElement('td')
                    callback(td)

                    tr.append(td)
                })
            })
        }

        connectedCallback() {
            this.tHead.append(...['Marca', 'modelo', 'cant.'].map(head => {
                const th = document.createElement('th')
                th.classList.add('text-start','uppercase')

                th.append(head)

                return th
            }))

            this.loadFlota(() => {
                this.renderFlota(this.flota)
            }, this._route)

            const trArrotTop = document.createElement('tr')
            const td = document.createElement('td')
            td.colSpan = 3
            td.innerHTML = `
                <svg style="margin: 0 auto"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 18.75 7.5-7.5 7.5 7.5" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 7.5-7.5 7.5 7.5" />
                </svg>
            `

            td.onclick = () => {
                this.parentElement.scrollTo({top: 0, behavior: 'smooth'});

                trArrotTop.remove()
            }

            trArrotTop.append(td)

            this.tFoot.append(...Array.from({length: 1}).map(() => {
                const tr = document.createElement('tr')

                const td = document.createElement('td')
                td.colSpan = 3
                td.innerHTML = `
                    <svg style="margin: 0 auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 5.25 7.5 7.5 7.5-7.5m-15 6 7.5 7.5 7.5-7.5" />
                    </svg>
                `

                td.onclick = () => {
                    this.parentElement.scrollTo({top: this.parentElement.scrollTop + 100, behavior: 'smooth'})
                    // this.parentElement.scrollTop = this.parentElement.scrollTop + 100

                    if(this.parentElement.scrollTop !== 0) {
                        this.tHead.append(trArrotTop)
                    }

                    if(this.parentElement.scrollTop === this.parentElement.scrollHeight) {
                        tr.remove()
                    }
                }

                tr.append(td)
                return tr
            }))
        }
    }
    customElements.define('flota-loader',  FlotaLoader, { extends: 'table' })

    class ConnectionStateLoader extends HTMLTableElement {

        _tbody = null;

        get tbody() {
            if(!this._tbody) {
                this._tbody = document.createElement('tbody')
                this.append(this._tbody)
            }

            return this._tbody
        }

        constructor() {
            super();
        }

        connectedCallback() {
            connections.forEach(({id, name, codigo_taller}) => {
                axios.get(`{{route('connections.check')}}?connection_id=${id}`)
                    .then(({data}) => {
                        const tr = document.createElement('tr');

                        [
                            (td) => {
                                td.append(name)
                            },
                            (td) => {
                                td.append(codigo_taller)

                                if(data.status) {
                                    td.classList.add('text-green-400')
                                }
                            }
                        ].forEach(callback => {
                            const td = document.createElement('td');

                            callback(td);

                            td.classList.add('font-bold')

                            if(!data.status) {
                                td.classList.add('text-red-300')
                            }

                            tr.append(td)
                        })

                        this.tbody.append(tr);
                    })
                    .catch(() => {
                        console.log('asd')
                    })
            })
        }
    }
    customElements.define('connection-state-loader',  ConnectionStateLoader, { extends: 'table' })

</script>
