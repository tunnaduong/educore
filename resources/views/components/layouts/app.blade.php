<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tiếng Trung Hanxian - EduCore</title>
    @livewireStyles
    {{-- Bootstrap Icons \\ DO NOT REMOVE --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    {{-- Bootstrap 4 & AdminLTE & Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    {{ $slot }}

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Đảm bảo dropdown hoạt động với Bootstrap 4
        $(document).ready(function() {
            $('.dropdown-toggle').dropdown();
        });
    </script>
    @stack('scripts')
</body>

</html>
