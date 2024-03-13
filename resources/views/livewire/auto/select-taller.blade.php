<div>
    <div>
        <select wire:model="connectionId" wire:change="import">
            @foreach($connections as $connection)
                <option value="{{$connection->id}}">
                    {{$connection->name}}
                </option>
            @endforeach
        </select>
    </div>
</div>
