<div>
    <form action="{{ route('new_connection') }}">
        @csrf

        <div>
            <x-label for="name" value="{{ __('Nombre') }}"/>
            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"/>
        </div>
    </form>

</div>
