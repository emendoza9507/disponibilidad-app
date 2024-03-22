<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Disponibilidad') }}</title>

        <!-- Fonts -->
{{--        <link rel="preconnect" href="https://fonts.bunny.net">--}}
{{--        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />--}}

        <link rel="stylesheet" href="{{asset('/build/assets/app.css')}}">
        <script src="{{asset('/build/assets/app.js')}}"></script>

        <!-- Scripts -->
{{--        @vite(['resources/css/app.css', 'resources/js/app.js'])--}}

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu', ['menu' => $menu])
            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        @stack('scripts')

        <template id="iconDiconnected">
            <svg  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.412 15.655 9.75 21.75l3.745-4.012M9.257 13.5H3.75l2.659-2.849m2.048-2.194L14.25 2.25 12 10.5h8.25l-4.707 5.043M8.457 8.457 3 3m5.457 5.457 7.086 7.086m0 0L21 21" />
            </svg>
        </template>

        <script>
            let connections = @json($connections).data;
            function checkConnectaionsState(callback) {
                Promise.all(
                    connections.map((connection, index) => {
                        return axios.get(`{{route('connections.check')}}?connection_id=${connection.id}`)
                            .then(({data}) => data)
                    })
                ).then(connections => {
                    connections.forEach(({data, connection_id, status}, index) => {
                        callback(connection_id, status, data, index === connections.length - 1)
                    })
                }).catch(() => {})
            }

            //Lo pongo aki para no compilar si necesito agregar algo
            class ConnectionSelect extends HTMLElement {
                /**
                 * Bandera para comprobar automaticamente el estado de las conexiones
                 * @type {boolean}
                 */
                _check = true

                /**
                 * Desactiva el envio del formulario al cambiar el valor del select
                 * @type {boolean}
                 */
                _nosubmit = false

                get check() {
                    return this._check
                }

                set check(val) {
                    this._check = val
                    this.setAttribute('check', this._check)
                }

                get nosubmit() {
                    return this._nosubmit
                }

                set notsubmit(val) {
                    this._nosubmit = val
                    this.setAttribute('nosubmit', this._nosubmit)
                }

                static get observedAttributes() { return ['check', 'nosubmit'] }

                constructor() {
                    super();
                }

                initAttrs() {
                    if(this.getAttribute('check')) {
                        switch (this.getAttribute('check')) {
                            case 'false' :
                                this.check = false
                                break
                            default:
                                this.check = true
                        }
                    }

                    if(this.hasAttribute('nosubmit')) {
                        this.nosubmit = true
                    }
                }

                autoSubmitEvent(e) {
                    let form = this.parentNode;

                    while(form !== null && form.nodeName !== 'FORM') {
                        form = form.parentNode
                    }

                    if(form !== null && form.nodeName === 'FORM') {
                        form.submit()
                    }
                }

                callbackCheckConnectaionsState(id, status, connection, isLast) {
                    if(status === false) {
                        Array.from(this.children).filter(option => option.value === id).forEach(option => {
                            option.disabled = true

                            // option.classList.add('text-sm','text-white','bg-red-400', 'font-bold')
                        })
                    }

                    if(isLast) {
                        console.log(isLast)
                        this.assignEventChangeAutoSubmit()
                    }
                }

                attributeChangedCallback(name, oldValue, newValue) {
                    switch (name) {
                        case 'check':
                            if(oldValue !== newValue) {
                                newValue && checkConnectaionsState(this.callbackCheckConnectaionsState);
                            }
                            break
                        case 'nosubmit': {
                            if(newValue !== oldValue) {
                                if(newValue !== false) {
                                    this.removeEventListener('change', this.autoSubmitEvent)
                                } else {
                                    this.removeEventListener('change', this.autoSubmitEvent)
                                    // this.addEventListener('change', this.autoSubmitEvent.bind(this));
                                }
                            }
                            break
                        }
                    }
                }

                assignEventChangeAutoSubmit() {
                    !this._nosubmit && this.addEventListener('change', this.autoSubmitEvent.bind(this))
                }

                connectedCallback() {
                    this.initAttrs()

                    // this.assignEventChangeAutoSubmit()
                    //
                    this._check && checkConnectaionsState(this.callbackCheckConnectaionsState.bind(this));
                }
            }
            customElements.define('select-connection', ConnectionSelect);

            class SelectMaterialArea extends HTMLSelectElement {
                constructor() {
                    super()

                }

                connectedCallback() {

                }
            }
            customElements.define('select-material-area', SelectMaterialArea, {extends: 'select'})

            class LaravelLink extends HTMLElement {
                constructor() {
                    super()
                    this.attachShadow({ mode: "open" });
                }

                connectedCallback() {
                    const href = this.getAttribute("href") || "#";
                    this.shadowRoot.innerHTML = `
                      <a href="${href}">
                        <slot></slot>
                      </a>
                    `;
                }
            }
            customElements.define('laravel-link', LaravelLink);

        </script>
    </body>
</html>
