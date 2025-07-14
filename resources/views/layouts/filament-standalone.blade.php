<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Verifikasi NCAGE' }}</title>
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
    @livewireStyles
    <style>
        body {
            background-color: #f5f5f5;
            color: #333;
        }
        .content-wrapper {
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        @yield('content')
    </div>

    @livewireScripts
</body>
</html>