<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title inertia>@yield('title')</title>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        law: {
                            dark: '#1e272e',
                            gold: '#d4af37',
                            soft: '#f8f9fa'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .dark ::-webkit-scrollbar { width: 5px; }
        .dark ::-webkit-scrollbar-track { background: #1e272e; }
        .dark ::-webkit-scrollbar-thumb { background: #d4af37; border-radius: 10px; }
    </style>
    {{-- @routes --}}
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
