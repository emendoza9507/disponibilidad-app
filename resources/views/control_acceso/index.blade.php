
@role($connection_id, 'tecnico')
@php($isTecnico = true)
@elserole
@php($isTecnico = false)
@endrole

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mantenimientos en Taller') }}
        </h2>
    </x-slot>


    <x-container>

        @include('partials.messages')

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <form class="flex gap-2 justify-between" action="{{route('control_acceso.index')}}">
                    <div class="flex gap-2">
                        <div class="relative">
                            <x-input id="input-search" name="matricula" class="rounded-none pr-8" placeholder="Buscar"/>
                            <span class="absolute right-2 top-2.5 ">@include('icons.search')</span>
                        </div>

                        <x-button is="buttom-submit" class="rounded-none h-full">BUSCAR</x-button>
                    </div>
                    <select id="select-connection" name="connection_id" is="select-connection">
                        @foreach($connections as $connection)
                            <option value="{{$connection->id}}" @if($connection->id == $connection_id) selected @endif>
                                {{$connection->name}}
                            </option>
                        @endforeach
                    </select>
                </form>

                <br>

                <section>
                    @error('codigoot')
                        <div class="mb-3 p-2 bg-red-400 text-white" onclick="removeMessage(this)">
                            {{$message}}
                        </div>
                    @enderror
                    <div class="grid grid-cols-2 gap-3">
                        <article>
                            <div class="scrollable">
                                <video src="" id="video"></video>
                                <canvas id="canvas"></canvas>
                                <img src="http://placekitten.com/g/320/261" id="photo" alt="photo">
                                <button id="escanear">Escanear</button>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </div>
    </x-container>
</x-app-layout>
<script>
    const $inputSearh = document.querySelector('#input-search');

    (function(){
        let streaming = false,
            video = document.querySelector('#video'),
            canvas = document.querySelector('#canvas'),
            photo = document.querySelector('#photo'),
            escanear = document.querySelector('#escanear'),
            width = 320,
            height = 0;

        navigator.mediaDevices.getUserMedia({video: true, audio: false}).then(stream => {
            if(navigator.mozGetUserMedia) {
                video.mozSrcObject = stream;
            } else {
                let vendorUrl = window.URL || window.webkitURL;
                video.src = vendorUrl.srcObject (stream);
            }
            video.play()
        }).catch(error => {
            console.log('An error occured! ' + error)
        });

        video.addEventListener(
            "canplay",
            function (ev) {
            if (!streaming) {
                height = video.videoHeight / (video.videoWidth / width);
                video.setAttribute("width", width);
                video.setAttribute("height", height);
                canvas.setAttribute("width", width);
                canvas.setAttribute("height", height);
                streaming = true;
            }
            },
            false,
        );

        function takepicture() {
            canvas.width = width;
            canvas.height = height;
            canvas.getContext("2d").drawImage(video, 0, 0, width, height);
            var data = canvas.toDataURL("image/png");
            photo.setAttribute("src", data);
        }

        escanear.addEventListener(
            "click",
            function (ev) {
            takepicture();
            ev.preventDefault();
            },
            false,
        );
    })()
</script>
