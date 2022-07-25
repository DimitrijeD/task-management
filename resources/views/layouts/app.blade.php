<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="">
        <nav class="bg-gray-200 border-b-2 border-gray-300 px-2 sm:px-4 py-2.5 rounded dark:bg-gray-900">
            <div class="container flex flex-wrap justify-between items-center mx-auto">
                <a href="{{ url('/') }}" class="flex items-center">
                    <span class="text-xl font-semibold">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                    <ul class="flex flex-col mt-4 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium">
                        @guest
                            @if (Route::has('login'))
                                <li class="">
                                    <a class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-gray-400 md:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700" 
                                        href="{{ route('login') }}"
                                    >{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="">
                                    <a class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-gray-400 md:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700" 
                                        href="{{ route('register') }}"
                                    >{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="">
                                <a 
                                    class="" 
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"
                                >{{ __('Logout') }}</a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4 container mx-auto">
            @yield('content')
        </main>
    </div>
</body>
</html>
