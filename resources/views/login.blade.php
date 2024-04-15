@extends('shared.layout')

@section('content')
    <!-- component -->
    <section class="min-h-screen flex items-stretch text-white ">
        <div class="lg:flex w-1/2 hidden bg-cover relative items-center">
            <div class="h-screen w-full flex items-center justify-center">
                <img src="" alt="" class="h-[80%]">
            </div>
        </div>
        <div class="lg:w-1/2 w-full flex items-center justify-center text-center md:px-16 px-0 z-0 bg-red-500">
            <div class="absolute lg:hidden z-10 inset-0 bg-gray-500 bg-no-repeat bg-cover items-center"
            ">
            <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
        </div>
        <div class="w-full py-6 z-20">

            <div class="py-6 space-x-2">
                <a href="#"
                   class="w-10 h-10 items-center justify-center inline-flex rounded-full font-bold text-lg border-2 border-white">f</a>
                <a href="#"
                   class="w-10 h-10 items-center justify-center inline-flex rounded-full font-bold text-lg border-2 border-white">G+</a>
                <a href="#"
                   class="w-10 h-10 items-center justify-center inline-flex rounded-full font-bold text-lg border-2 border-white">in</a>
            </div>
            <p class="text-gray-100">
                or use email your account
            </p>

            <form id="login-form" class="sm:w-2/3 w-full px-4 lg:px-0 mx-auto text-gray-800">
                <div class="pb-2 pt-4">
                    <input type="email" name="email" id="email" placeholder="Email" class="block w-full p-4 text-lg rounded-sm">
                    <div class="text-red-500" id="emailError"></div>
                </div>
                <div class="pb-2 pt-4">
                    <input class="block w-full p-4 text-lg rounded-sm" type="password" name="password" id="password" placeholder="Password">
                    <div class="text-red-500" id="passwordError"></div>
                </div>
                <div class="px-4 pb-2 pt-4">
                    <button type="submit" class="uppercase block w-full p-4 text-lg rounded-full bg-green-500 hover:bg-green-400 text-white focus:outline-none">
                        Sign In
                    </button>
                </div>
            </form>

        </div>
        </div>
    </section>

    {{--  login script  --}}
    <script src="{{asset('auth/js/login.js')}}"></script>

@endsection
