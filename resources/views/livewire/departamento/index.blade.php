<div class="md:grid md:grid-cols-3">
    <div class="md:col-span-12">
        <div class="mt-1 flex flex-col">
            <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
                <div class="py-2 inline-block min-w-full md:px-4 lg:px-8">
                    <div class="overflow-hidden">
                        <div  class="flex justify-between align-middle">
                            <h1 class="mb-9 text-xl font-bold"> Departamentos </h1>
                            <livewire:departamento.create />
                        </div>

                        <table class="min-w-full">
                            <thead class="bg-gray-200 border-b">
                            <tr>
                                <th>#</th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Nombre
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Descripcion
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Direccion
                                </th>
                                <th scope="col" class="w-32 text-sm font-medium text-gray-900 px-6 py-4 text-left"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($departamentos as $key => $departamento)
                                <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                    <td class="px-6 text-center py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{$departamento->codigodp}}
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        {{$departamento->nombre}}
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        {{$departamento->descripcion}}
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        {{$departamento->direccion}}
                                    </td>
                                    <td id="{{$departamento->id}}" class="w-32 text-sm flex text-gray-900 font-light px-0 py-4 whitespace-nowrap justify-end">
                                        <livewire:departamento.update :departamento="$departamento" :key="uniqid()"/>
                                        <livewire:departamento.delete :departamento="$departamento" :key="uniqid()"/>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="py-4 px-4">
                            {{$departamentos->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
