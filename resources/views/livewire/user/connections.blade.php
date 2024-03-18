<div>
    <button class="inline-block rounded rounded-full px-2 py-2 text-gray-700 hover:bg-gray-50 focus:relative" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
        </svg>
    </button>

    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <h2 class="text-2xl font-bold">Persmisos en Talleres</h2>
        </x-slot>
        <x-slot name="content">
{{--            @dump($user)--}}
{{--            @dump($connections)--}}
{{--            @dump($roles)--}}
            <div>
                <x-label for="name" value="{{ __('Nombre') }}"/>
                <x-input id="name" class="block mt-1 w-full rounded-none" disabled type="text" name="name" value="{{$user->name}}" autofocus autocomplete="name"/>
                <div class="text-red-500">@error('name') {{ $message }} @enderror</div>
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Corrreo') }}"/>
                <x-input id="email" class="block mt-1 w-full rounded-none" disabled type="email"  name="email" value="{{$user->email}}" required autocomplete="email"/>
                <div class="text-red-500">@error('email') {{ $message }} @enderror</div>
            </div>

            {{$role_to_remove}}

            <div class="mt-6">
                <table class="w-full">
                    @foreach($user_connections as $key => $value)
                        <tr>
                            <th class="text-start py-2">{{$value['connection']->name}}</th>

                            <td>
                            @foreach($value['roles'] as $role)
                                <div class="rounded-full inline-block px-2 py-1 border-2 border-gray-600 bg-gray-300">
                                    {{$role->name}}

                                    <span class="cursor-pointer" wire:click="dettachRoleOfTaller({{$value['id']}})">&cross;</span>
                                </div>
                            @endforeach
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="mt-6 flex justify-between gap-2">
                <select wire:model.live="connection">
                    @foreach($connections as $connection)
                        <option value="{{$connection->id}}">{{$connection->name}}</option>
                    @endforeach
                </select>
                <select wire:model.live="role">
                    @foreach($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                </select>
                <x-button wire:click="assignRoleToTaller" class="rounded-none">AGREGAR</x-button>
            </div>

        </x-slot>
        <x-slot name="footer">
            <div>
                <x-button>{{__('GUARDAR')}}</x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>

