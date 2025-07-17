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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .text-dark-red { color: #660000; }
        .border-dark-red { border-color: #660000 !important; }
        .btn-dark-red { background-color: #660000; color: white; }
        .btn-dark-red:hover { background-color: #4d0000; }
        .btn-outline-dark-red {
            color: #660000;
            border: 2px solid #660000;
        }
        .btn-outline-dark-red:hover {
            background-color: #660000;
            color: white;
        }
        .equal-height {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .match-height-container {
        display: flex;
        flex-wrap: wrap;
    }

    .match-height-container > div {
        display: flex;
        flex: 1;
    }
    .tab-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .tab-scroll::-webkit-scrollbar {
        height: 6px;
    }
    .tab-scroll::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
    </style>
</head>
<body>
    <div class="content-wrapper">
        @yield('content')
    </div>

    @livewireScripts
    @yield('scripts')
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>