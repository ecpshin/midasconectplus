<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="w-screen h-screen antialiased bg-gradient-to-br from-15% from-black via-[#6D303B] to-[#443C78]">
    <!-- component -->
    <div class="bg-transparent flex justify-center items-center h-screen">
        <!-- Left: Image -->
        <div class="w-1/2 h-screen hidden lg:block">
            <div class="my-auto bg-transparent w-full h-full" class="image-logo" style="background-image: url({{ asset('img/logo-midas.png')}}); background-repeat: no-repeat; background-size: 450px; background-position: center;"></div>
        </div>
        <!-- Right: Login Form -->
        <div class="h-screen lg:p-36 md:p-52 sm:20 p-8 w-full lg:w-1/2 flex flex-col justify-center items-center">
            <h1 class="text-3xl font-semibold mb-4 text-center text-slate-50">Bem-vindo(a)!</h1>
            <p class="text-xl font-semibold mb-4 text-center text-slate-50 opacity">Selecione o painel de acesso ao sistema</p>
            <!-- Sign up  Link -->
            <div class="mt-6 text-amber-700 text-2xl text-center font-semibold bg-slate-50 rounded-full w-[350px] px-5 py-2">
                    <a href="{{ route('filament.midas.auth.login') }}" class="hover:text-blue-700">Painel de Usuário</a>
            </div>
            <div class="mt-6 text-amber-700 text-2xl text-center font-semibold bg-slate-50 rounded-full w-[350px] px-5 py-2">
                    <a href="{{ route('filament.admin.auth.login') }}" class="hover:text-blue-700">Painel Administrativo</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
