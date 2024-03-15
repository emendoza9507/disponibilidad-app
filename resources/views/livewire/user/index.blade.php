<div class="md:grid md:grid-cols-3">
    <div class="md:col-span-12">
        <div class="mt-1 flex flex-col">
            <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
                <div class="py-2 inline-block min-w-full md:px-4 lg:px-8">
                    <div class="overflow-hidden">
                        <div  class="flex justify-between align-middle">
                            <h1 class="mb-9 text-xl font-bold"> Usuarios </h1>
                            <livewire:user.create />
                        </div>

                            <table class="min-w-full">
                                <thead class="bg-gray-200 border-b">
                                <tr>
                                    <th>#</th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        Image
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        Nombre
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                        Email
                                    </th>
                                    <th scope="col" class="w-32 text-sm font-medium text-gray-900 px-6 py-4 text-left"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $key => $user)
                                    <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100" wire:key="{{$user->pluck('id')->join(uniqid())}}">
                                        <td class="px-6 text-center py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{$user->id}}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{$user->image}}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{$user->name}}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{$user->email}}
                                        </td>
                                        <td id="{{$user->id}}" class="w-32 text-sm flex text-gray-900 font-light px-0 py-4 whitespace-nowrap justify-end">
                                            <livewire:user.connections :user="$user" :key="'connection-'.$user->id.uniqid()"/>
                                            <livewire:user.update :user="$user" :key="'update-'.$user->id.uniqid()"/>
                                            <livewire:user.delete :user="$user" :key="'delete-'.$user->id.uniqid()"/>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="py-4 px-4">
                                {{$users->links()}}
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
