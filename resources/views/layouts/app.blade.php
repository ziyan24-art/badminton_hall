<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Bdm - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon and Touch Icons -->
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" crossorigin="anonymous">

    <!-- Core CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/now-ui-dashboard.css?v=1.3.0') }}" rel="stylesheet">

    <!-- Demo CSS (optional) -->
    <link href="{{ asset('assets/demo/demo.css') }}" rel="stylesheet">

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

    @stack('css')
</head>

<body class="{{ $class ?? '' }}">
    <div class="wrapper">
        @auth
        @include('layouts.page_template.auth')
        @endauth
        @guest
        @include('layouts.page_template.guest')
        @endguest
    </div>

    @stack('modal')

    <!-- Core JS -->
    <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/now-ui-dashboard.min.js?v=1.3.0') }}"></script>
    <script src="{{ asset('assets/demo/demo.js') }}"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Setup CSRF token untuk semua AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        /**
         * Gunakan ini untuk menampilkan alert SweetAlert dengan tombol
         * Tanpa menimpa toastr asli
         */
        const showToastSwal = (type, msg, btn = null) => {
            Swal.fire({
                icon: type,
                html: msg,
                showConfirmButton: btn !== null,
                confirmButtonText: btn ?? ''
            });
        };

        const swallConfirm = (formElement, html, confirmText = 'Ya') => {
            Swal.fire({
                html,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    formElement.submit();
                }
            });
        };

        const confirmDelete = (html, callback) => {
            Swal.fire({
                html,
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        };

        const showErrors = (errors) => {
            if (errors && Object.keys(errors).length > 0) {
                let html = '<ul>';
                Object.entries(errors).forEach(([key, msgs]) => {
                    msgs.forEach(msg => html += `<li>${msg}</li>`);
                });
                html += '</ul>';
                showToastSwal('error', html, 'Tutup');
            }
        };

        const submitForm = (req) => {
            const originalBtn = req?.button?.html();
            req?.button?.html(`Menunggu <i class='fas fa-spin fa-spinner'></i>`).attr('disabled', true);

            const resetButton = () => {
                req?.button?.attr('disabled', false).html(originalBtn);
            };

            $.ajax({
                url: req?.url,
                type: req?.type ?? 'POST',
                data: req?.data,
                dataType: 'json',
                success: (res) => {
                    resetButton();
                    if (res?.success) {
                        req?.successCallback?.();
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.message ?? 'Terjadi kesalahan');
                        showErrors(res.errors);
                    }
                },
                error: (xhr) => {
                    resetButton();
                    toastr.error(xhr.statusText ?? 'Terjadi kesalahan');
                    console.error(xhr.responseText);
                }
            });
        };
    </script>

    @stack('js')

    {{-- Flash Messages --}}
    @if ($msg = session('success'))
    <script>
        toastr.success(`{!! $msg !!}`);
    </script>
    @elseif($msg = session('errors'))
    <script>
        toastr.error(`{!! $msg !!}`);
    </script>
    @endif
</body>

</html>