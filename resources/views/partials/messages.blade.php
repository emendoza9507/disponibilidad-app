@php

$bg_colors = [
    'info' => 'bg-blue-400',
    'success' => 'bg-green-500',
    'alert' => 'bg-orange-400',
    'warning' => 'bg-yellow-300',
    'danger' => 'bg-red-400'
]

@endphp
@if (count($messages))
    <div class="my-2" onclick="removeMessage(this)">
        <div class="bg-ye-400 shadow-xl sm:rounded-lg">
            @foreach ($messages as $message)
                <div class="py-5 px-2 text-white {{ $bg_colors[$message['level']] }}">{!! $message['message'] !!}</div>
            @endforeach
        </div>
    </div>
@endif

<script>
    function removeMessage($msg) {
        setTimeout(function (){ $msg.remove() }, 1000)
    }
</script>
