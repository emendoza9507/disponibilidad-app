<form method="POST" action="{{ route('new_connection') }}">
    @csrf

    <x-validation-errors class="mb-4" />

    <div>
        <x-label for="name" value="{{ __('Nombre') }}"/>
        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" autofocus autocomplete="name"/>
    </div>

    <div class="mt-4">
        <x-label for="database" value="{{ __('Nombre de la Base de Datos') }}"/>
        <x-input id="database" class="block mt-1 w-full" type="text" name="database" :value="old('database')" required autocomplete="database"/>
    </div>

    <div class="mt-4">
        <x-label for="hostname" value="{{ __('Direccion del Servidor') }}"/>
        <x-input id="hostname" class="block mt-1 w-full" type="text" name="hostname" :value="old('hostname')" required autocomplete="hostname"/>
    </div>

    <div class="mt-4">
        <x-label for="username" value="{{ __('Usuario') }}"/>
        <x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autocomplete="username"/>
    </div>

    <div class="mt-4">
        <x-label for="password" value="{{ __('contraseña') }}"/>
        <x-input id="password" class="block mt-1 w-full" type="password" name="password" :value="old('password')" required autocomplete="password"/>
    </div>

    <div class="mt-4">
        <x-label for="description" value="{{ __('Descripcion') }}"/>
        <x-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" required autocomplete="description"/>
    </div>

    <div class="mt-4">
        <x-button >{{__('GUARDAR')}}</x-button>
    </div>
</form>
