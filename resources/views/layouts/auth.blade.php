<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'teman sejarii')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md card-editorial p-8 sm:p-10">
        <div class="text-center mb-8">
            <p class="text-[11px] uppercase tracking-[0.25em] text-taupe mb-2">@yield('subtitle', 'trinkets & jewelry')</p>
            <h1 class="font-editorial italic text-4xl text-espresso">teman sejarii </h1>
        </div>

        @if($errors->any())
            <div class="mb-5 p-3 rounded-md bg-red-50 border border-red-200 text-xs text-red-800 space-y-1">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>