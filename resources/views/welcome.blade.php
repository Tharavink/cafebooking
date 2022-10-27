<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('app.name') }}</title>
    {{-- <link rel="stylesheet" href="https://unpkg.com/tailwindcss@next/dist/tailwind.min.css" /> --}}
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="text-gray-700 bg-white" style="font-family: 'Source Sans Pro', sans-serif">
    <!-- component -->
    <!-- This is an example component -->
    <div>
        <div class="px-4 py-4 bg-pink-700">
            <div class="md:max-w-6xl md:mx-auto md:flex md:items-center md:justify-between">
                <div class="flex items-center justify-between">
                    <a href="#" class="inline-block py-2 text-xl font-bold text-white">{{ config('app.name') }}</a>
                    <div class="inline-block cursor-pointer md:hidden">
                        <div class="w-8 mb-2 bg-gray-400" style="height: 2px;"></div>
                        <div class="w-8 mb-2 bg-gray-400" style="height: 2px;"></div>
                        <div class="w-8 bg-gray-400" style="height: 2px;"></div>
                    </div>
                </div>

                @if (Route::has('login'))
                    <div class="fixed top-0 right-0 hidden px-6 py-4 sm:block">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-block py-1 mr-6 font-bold text-gray-100 md:py-4">Dashboard</a>
                        @else
                            <div class="hidden md:block">
                                <a href="{{ route('login') }}"
                                    class="inline-block py-1 mr-6 text-pink-200 md:py-4 hover:text-gray-100">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="inline-block px-4 py-2 text-gray-700 bg-white rounded-lg hover:bg-gray-100">Register</a>
                                @endif
                            </div>
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-pink-700 md:overflow-hidden">
            <div class="px-4 py-20 md:py-4">
                <div class="md:max-w-6xl md:mx-auto">
                    <div class="md:flex md:flex-wrap">
                        <div class="text-center md:w-1/2 md:text-left md:pt-16">
                            <h1 class="mb-4 text-2xl font-bold leading-tight text-white md:text-5xl">
                                Simplest way to book a spot in you favourite cafes
                            </h1>

                            <p class="text-pink-200 md:text-xl md:pr-48">
                                Always have to wait for a spot in famous cafes?
                                Well, now you can book a spot even before you
                                leave your place.
                            </p>

                            @if (Route::has('register'))
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                        class="inline-block px-8 py-3 mt-6 mb-12 text-white bg-gray-700 rounded-lg shadow md:mb-0 md:mt-10 hover:bg-gray-800">Get
                                        Started</a>
                                @else
                                    <a href="{{ route('register') }}"
                                        class="inline-block px-8 py-3 mt-6 mb-12 text-white bg-gray-700 rounded-lg shadow md:mb-0 md:mt-10 hover:bg-gray-800">Get
                                        Started</a>
                                @endauth
                            @endif

                        </div>
                        <div class="relative md:w-1/2">
                            <div class="hidden md:block">
                                <div class="absolute bottom-0 left-0 w-40 px-6 py-8 -mb-40 -ml-24 bg-white rounded-lg shadow-lg"
                                    style="transform: rotate(-8deg);">
                                    <img src="{{ asset('images/cafe.svg') }}" class="mb-3" alt="">

                                    <div class="text-center text-gray-800">
                                        Cafe <br />Management
                                    </div>
                                </div>

                                <div class="absolute bottom-0 left-0 w-40 px-6 py-8 mb-16 ml-24 bg-white rounded-lg shadow-lg"
                                    style="transform: rotate(-8deg); z-index: 2;">
                                    <img src="{{ asset('images/booking.svg') }}" class="mb-3" alt="">

                                    <div class="text-center text-gray-800">
                                        Booking Services
                                    </div>
                                </div>

                                <div class="absolute bottom-0 left-0 w-48 px-10 py-8 ml-32 bg-white rounded-lg shadow-lg"
                                    style="transform: rotate(-8deg); z-index: 2; margin-bottom: -220px;">
                                    <img src="{{ asset('images/table.svg') }}" class="mb-3" alt="">

                                    <div class="text-center text-gray-800">
                                        Auto Table <br />Assigning
                                    </div>
                                </div>

                                <div class="absolute top-0 right-0 flex w-full mt-10 overflow-hidden bg-white rounded-lg shadow-lg"
                                    style="transform: rotate(-8deg); margin-right: -250px; z-index: 1;">
                                    <div class="w-32 bg-gray-200" style="height: 560px;"></div>
                                    <div class="flex-1 p-6">
                                        <h2 class="mb-3 text-lg font-bold text-gray-700">
                                            Cafe List
                                        </h2>
                                        <div class="flex mb-5">
                                            <div class="w-16 px-4 py-2 mr-2 bg-gray-100 rounded-full">
                                                <div class="p-1 bg-gray-300 rounded-full"></div>
                                            </div>
                                            <div class="w-16 px-4 py-2 mr-2 bg-gray-100 rounded-full">
                                                <div class="p-1 bg-gray-300 rounded-full"></div>
                                            </div>
                                            <div class="w-16 px-4 py-2 mr-2 bg-gray-100 rounded-full">
                                                <div class="p-1 bg-gray-300 rounded-full"></div>
                                            </div>
                                            <div class="w-16 px-4 py-2 bg-gray-100 rounded-full">
                                                <div class="p-1 bg-gray-300 rounded-full"></div>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap mb-5 -mx-4">
                                            <div class="w-1/3 px-4">
                                                <div class="h-40 p-6 bg-white rounded-lg shadow-lg">
                                                    <div class="w-16 h-16 mb-6 bg-gray-200 rounded-full"></div>
                                                    <div class="w-16 h-2 mb-2 bg-gray-200 rounded-full"></div>
                                                    <div class="w-10 h-2 bg-gray-200 rounded-full"></div>
                                                </div>
                                            </div>
                                            <div class="w-1/3 px-4">
                                                <div class="h-40 p-6 bg-white rounded-lg shadow-lg">
                                                    <div class="w-16 h-16 mb-6 bg-gray-200 rounded-full"></div>
                                                    <div class="w-16 h-2 mb-2 bg-gray-200 rounded-full"></div>
                                                    <div class="w-10 h-2 bg-gray-200 rounded-full"></div>
                                                </div>
                                            </div>
                                            <div class="w-1/3 px-4">
                                                <div class="h-40 p-6 bg-white rounded-lg shadow-lg">
                                                    <div class="w-16 h-16 mb-6 bg-gray-200 rounded-full"></div>
                                                    <div class="w-16 h-2 mb-2 bg-gray-200 rounded-full"></div>
                                                    <div class="w-10 h-2 bg-gray-200 rounded-full"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <h2 class="mb-3 text-lg font-bold text-gray-700">
                                            Booking Info
                                        </h2>

                                        <div
                                            class="flex flex-wrap items-center justify-between w-full py-3 border-b-2 border-gray-100">
                                            <div class="w-1/3">
                                                <div class="flex">
                                                    <div class="w-8 h-8 mr-4 bg-gray-200 rounded"></div>
                                                    <div>
                                                        <div class="w-16 h-2 mb-1 bg-gray-200 rounded-full"></div>
                                                        <div class="w-10 h-2 bg-gray-100 rounded-full"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-1/3">
                                                <div class="w-16 px-4 py-2 mx-auto bg-green-100 rounded-full">
                                                    <div class="p-1 bg-green-200 rounded-full"></div>
                                                </div>
                                            </div>
                                            <div class="w-1/3">
                                                <div class="w-10 h-2 mx-auto bg-gray-100 rounded-full"></div>
                                            </div>
                                        </div>

                                        <div
                                            class="flex flex-wrap items-center justify-between py-3 border-b-2 border-gray-100">
                                            <div class="w-1/3">
                                                <div class="flex">
                                                    <div class="w-8 h-8 mr-4 bg-gray-200 rounded"></div>
                                                    <div>
                                                        <div class="w-16 h-2 mb-1 bg-gray-200 rounded-full"></div>
                                                        <div class="w-10 h-2 bg-gray-100 rounded-full"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-1/3">
                                                <div class="w-16 px-4 py-2 mx-auto bg-orange-100 rounded-full">
                                                    <div class="p-1 bg-orange-200 rounded-full"></div>
                                                </div>
                                            </div>
                                            <div class="w-1/3">
                                                <div class="w-16 h-2 mx-auto bg-gray-100 rounded-full"></div>
                                            </div>
                                        </div>

                                        <div
                                            class="flex flex-wrap items-center justify-between py-3 border-b-2 border-gray-100">
                                            <div class="w-1/3">
                                                <div class="flex">
                                                    <div class="w-8 h-8 mr-4 bg-gray-200 rounded"></div>
                                                    <div>
                                                        <div class="w-16 h-2 mb-1 bg-gray-200 rounded-full"></div>
                                                        <div class="w-10 h-2 bg-gray-100 rounded-full"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-1/3">
                                                <div class="w-16 px-4 py-2 mx-auto bg-pink-100 rounded-full">
                                                    <div class="p-1 bg-pink-200 rounded-full"></div>
                                                </div>
                                            </div>
                                            <div class="w-1/3">
                                                <div class="w-8 h-2 mx-auto bg-gray-100 rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="absolute bottom-0 left-0 w-full ml-1"
                                    style="transform: rotate(-8deg); z-index: 1; margin-bottom: -360px;">
                                    <div class="w-48 h-48 grid--gray"></div>
                                </div>
                            </div>

                            <div
                                class="absolute top-0 right-0 flex w-full overflow-hidden bg-white rounded-lg shadow md:hidden">
                                <div
                                    class="absolute top-0 left-0 right-0 flex items-center h-4 bg-gray-200 rounded-t-lg">
                                    <span class="inline-block w-2 h-2 ml-2 mr-1 bg-red-500 rounded-full"></span>
                                    <span class="inline-block w-2 h-2 mr-1 bg-orange-400 rounded-full"></span>
                                    <span class="inline-block w-2 h-2 mr-1 bg-green-500 rounded-full"></span>
                                </div>
                                <div class="w-32 px-2 py-8 bg-gray-100" style="height: 340px;">
                                    <div class="w-16 h-2 mb-4 bg-gray-300 rounded-full"></div>
                                    <div class="flex items-center mb-4">
                                        <div class="flex-shrink-0 w-5 h-5 mr-3 bg-gray-300 rounded-full"></div>
                                        <div>
                                            <div class="w-10 h-2 bg-gray-300 rounded-full"></div>
                                        </div>
                                    </div>

                                    <div class="w-16 h-2 mb-2 bg-gray-200 rounded-full"></div>
                                    <div class="w-10 h-2 mb-2 bg-gray-200 rounded-full"></div>
                                    <div class="w-20 h-2 mb-2 bg-gray-200 rounded-full"></div>
                                    <div class="w-6 h-2 mb-2 bg-gray-200 rounded-full"></div>
                                    <div class="w-16 h-2 mb-2 bg-gray-200 rounded-full"></div>
                                    <div class="w-10 h-2 mb-2 bg-gray-200 rounded-full"></div>
                                    <div class="w-20 h-2 mb-2 bg-gray-200 rounded-full"></div>
                                    <div class="w-6 h-2 mb-2 bg-gray-200 rounded-full"></div>
                                </div>
                                <div class="flex-1 px-4 py-8">
                                    <h2 class="mb-1 text-xs font-bold text-gray-700">
                                        Popular Payments
                                    </h2>
                                    <div class="flex mb-5">
                                        <div class="w-12 p-2 mr-2 bg-gray-100 rounded-full"></div>
                                        <div class="w-12 p-2 mr-2 bg-gray-100 rounded-full"></div>
                                        <div class="w-12 p-2 mr-2 bg-gray-100 rounded-full"></div>
                                        <div class="w-12 p-2 mr-2 bg-gray-100 rounded-full"></div>
                                    </div>

                                    <div class="flex flex-wrap mb-5 -mx-2">
                                        <div class="w-1/3 px-2">
                                            <div class="p-3 bg-white rounded-lg shadow">
                                                <div class="w-6 h-6 mb-2 bg-gray-200 rounded-full"></div>
                                                <div class="w-10 h-2 mb-1 bg-gray-200 rounded-full"></div>
                                                <div class="w-6 h-2 bg-gray-200 rounded-full"></div>
                                            </div>
                                        </div>
                                        <div class="w-1/3 px-2">
                                            <div class="p-3 bg-white rounded-lg shadow">
                                                <div class="w-6 h-6 mb-2 bg-gray-200 rounded-full"></div>
                                                <div class="w-10 h-2 mb-1 bg-gray-200 rounded-full"></div>
                                                <div class="w-6 h-2 bg-gray-200 rounded-full"></div>
                                            </div>
                                        </div>
                                        <div class="w-1/3 px-2">
                                            <div class="p-3 bg-white rounded-lg shadow">
                                                <div class="w-6 h-6 mb-2 bg-gray-200 rounded-full"></div>
                                                <div class="w-10 h-2 mb-1 bg-gray-200 rounded-full"></div>
                                                <div class="w-6 h-2 bg-gray-200 rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <h2 class="mb-1 text-xs font-bold text-gray-700">
                                        Popular Payments
                                    </h2>

                                    <div
                                        class="flex flex-wrap items-center justify-between w-full py-3 border-b-2 border-gray-100">
                                        <div class="w-1/3">
                                            <div class="flex">
                                                <div class="flex-shrink-0 w-5 h-5 mr-3 bg-gray-200 rounded-full"></div>
                                                <div>
                                                    <div class="w-16 h-2 mb-1 bg-gray-200 rounded-full"></div>
                                                    <div class="w-10 h-2 bg-gray-100 rounded-full"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-1/3">
                                            <div class="w-16 px-4 py-2 mx-auto bg-green-100 rounded-full">
                                                <div class="p-1 bg-green-200 rounded-full"></div>
                                            </div>
                                        </div>
                                        <div class="w-1/3">
                                            <div class="w-10 h-2 mx-auto bg-gray-100 rounded-full"></div>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center justify-between py-3">
                                        <div class="w-1/3">
                                            <div class="flex">
                                                <div class="flex-shrink-0 w-5 h-5 mr-3 bg-gray-200 rounded-full"></div>
                                                <div>
                                                    <div class="w-16 h-2 mb-1 bg-gray-200 rounded-full"></div>
                                                    <div class="w-10 h-2 bg-gray-100 rounded-full"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-1/3">
                                            <div class="w-16 px-4 py-2 mx-auto bg-orange-100 rounded-full">
                                                <div class="p-1 bg-orange-200 rounded-full"></div>
                                            </div>
                                        </div>
                                        <div class="w-1/3">
                                            <div class="w-16 h-2 mx-auto bg-gray-100 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute bottom-0 right-0 w-40 px-10 py-6 mr-3 bg-white rounded-lg shadow-lg md:hidden"
                                style="z-index: 2; margin-bottom: -380px;">
                                <div class="relative w-20 pt-3 mx-auto mb-12 bg-indigo-800 rounded-lg">
                                    <div class="h-3 bg-white"></div>

                                    <div class="my-2 text-right">
                                        <div class="inline-flex w-3 h-3 -mr-2 bg-white rounded-full"></div>
                                        <div
                                            class="inline-flex w-3 h-3 mr-2 bg-indigo-800 border-2 border-white rounded-full">
                                        </div>
                                    </div>

                                    <div
                                        class="absolute bottom-0 left-0 w-16 pt-3 pb-2 mx-auto -mb-6 -ml-4 bg-green-700 rounded-lg">
                                        <div class="h-2 mb-2 bg-white"></div>
                                        <div class="w-6 h-2 ml-auto mr-2 bg-white rounded"></div>
                                    </div>
                                </div>

                                <div class="text-sm text-center text-gray-800">
                                    Payment for <br />Internet
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <svg class="hidden text-white fill-current md:block" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 1440 320">
                <path fill-opacity="1" d="M0,224L1440,32L1440,320L0,320Z"></path>
            </svg>
        </div>
    </div>
</body>
</html>
