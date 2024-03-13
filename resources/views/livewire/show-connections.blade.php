<div>
    <table class="min-w-full">
        <thead class="bg-gray-200 border-b">
            <tr>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                    Nombre
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                    HOST
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                    DATABASE
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                    USER
                </th>
                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($connections as $key => $con)
                <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100" wire:key="{{$con->pluck('id')->join(uniqid())}}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{$con->name}}
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                        {{$con->hostname}}
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                        {{$con->database}}
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                        {{$con->username}}
                    </td>
                    <td id="{{$con->name}}" class="text-sm flex text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                        <livewire:update-connection :connection="$con" :key="'update-'.$con->id.uniqid()"/>
                        <livewire:delete-connection :connection="$con" :key="'delete-'.$con->id.uniqid()"/>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="py-4 px-4">
        {{$connections->links()}}
    </div>
</div>
