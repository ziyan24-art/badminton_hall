<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

   
    <style>

        body {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
            url('{{ asset("images/background/bg4.jpeg") }}') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
   

    @yield('css')
    <title>HALL PEMDA - @yield('title')</title>
</head>

<body>

    {{-- Navbar --}}
    @include('theme.navbar')
    {{-- End Navbar --}}

    {{-- Container utama --}}
    <div class="max-w-3xl mx-auto bg-white bg-opacity-30 rounded-md min-h-screen overflow-hidden shadow-lg backdrop-blur-md">
        <div id="content" class="px-8 py-4 mb-20 relative">
            {{-- Header --}}
            <div class="flex justify-between align-middle items-center relative z-50">
                <div id="logo" class="flex-shrink-0">
                    <a href="/">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Hal"
                            class="object-cover max-h-16 max-w-26">
                    </a>
                </div>
                @auth
                <div class="flex-1 flex flex-col">
                    <div class="flex justify-end">
                        <a href="#" id="btn-notification" class="text-3xl text-gray-400">
                            <i class="fas fa-xs fa-bell"></i>
                        </a>
                    </div>
                    <div style="z-index: 100"
                        class="bg-white px-3 py-4 rounded-md shadow-2xl absolute right-0 top-12 w-full transition duration-500 hidden"
                        id="notification-card">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <p class="text-dark font-medium">Notification</p>
                            <a href="#" class="text-xs text-gray-500 font-medium py-3">Show All</a>
                        </div>
                        <div class="notification-content border-b pb-3">
                            <a href="#">
                                <p class="font-medium text-gray-600 py-2">
                                    Welcome to Hall Badminton PEMDA!
                                </p>
                                <p class="text-sm text-gray-500 py-2">
                                    Thank you for register on our website
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
            {{-- End Header --}}

            {{-- Content --}}
            @yield('content')
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"
        integrity="sha512-RXf+QSDCUQs5uwRKaDoXt55jygZZm2V++WUZduaU/Ui/9EGp3f/2KZVahFZBKGH0s774sd3HmrhUy+SgOFQLVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let toastr = (type, msg, btn = null) => {
            Swal.fire({
                icon: type,
                html: msg,
                showConfirmButton: (btn == null ? false : true),
                confirmButtonText: btn,
            });
        };

        $(document).ready(function() {
            let isNotifOpen = false;
            $('#btn-notification').click(function() {
                $(this).toggleClass('text-primary text-gray-400');
                $('#notification-card').toggleClass('hidden');
                isNotifOpen = !isNotifOpen;
            });
        });
    </script>

    @yield('js')

    @if ($msg = session()->get('success'))
    <script>
        toastr('success', `{{ $msg }}`);
    </script>
    @elseif ($msg = session()->get('errors'))
    <script>
        toastr('error', `{!! $msg !!}`, `Saya Paham`);
    </script>
    @endif
</body>

</html>