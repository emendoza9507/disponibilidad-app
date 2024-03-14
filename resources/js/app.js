import './bootstrap';



function reportPrint($btnPrint, $areaPrint) {
    $btnPrint.addEventListener('click', () => {
        const $printer = window.open('', this, "width=800,height=600,menubar=NO")

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

        $printer.document.head.innerHTML = `<link rel="stylesheet" href="http://disponibilidad.local/app.css">`

        // Array.from($printer.document.body.getElementsByClassName('print:hidden')).forEach(node => node.classList.add('hidden'))
        Array.from($printer.document.body.getElementsByClassName('print:block')).forEach(node => node.classList.remove('hidden'))

        $printer.document.querySelector('#btn-print').addEventListener('click', function () {
            $printer.print();
        } )

        $printer.document.body.querySelectorAll('a').forEach(a => a.href = '#')

        // $printer.print()
    })
}

window.reportPrint = reportPrint
