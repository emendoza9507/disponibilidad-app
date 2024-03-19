import './bootstrap';
import moment from "moment-with-locales-es6";


function reportPrint($btnPrint, $areaPrint, callback) {
    const hostame = location.hostname

    $btnPrint.addEventListener('click', () => {
        const $printer = window.open('', 'ReporteOrden', "width=800,height=600,menubar=NO")

        $printer.document.body.innerHTML = `
            <div class="min-h-screen bg-gray-100">
                <main>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200 relative">
                            ${$areaPrint.innerHTML}
                    </div>
                    </div>
                </div>
                </main>
            </div>
        `

        $printer.addEventListener('focusout', () => $printer.close())
        $printer.addEventListener('afterprint', () => $printer.close())

        $printer.document.head.innerHTML = `<link rel="stylesheet" href="http://${hostame}/build/assets/app.css?${Date.now()}">
            <script src="http://${hostame}/build/assets/app.js"></script>
        `

        // Array.from($printer.document.body.getElementsByClassName('print:hidden')).forEach(node => node.classList.add('hidden'))
        Array.from($printer.document.body.getElementsByClassName('print:block')).forEach(node => node.classList.remove('hidden'))
        Array.from($printer.document.getElementsByClassName('overflow-y-auto')).forEach(node => node.style = '')

        $printer.document.querySelector('#btn-print').addEventListener('click', function () {
            $printer.print();
        } )

        $printer.document.body.querySelectorAll('a').forEach(a => a.href = '#')

        callback($printer.document)
        // $printer.print()
    })
}

window.reportPrint = reportPrint
window.moment = moment
