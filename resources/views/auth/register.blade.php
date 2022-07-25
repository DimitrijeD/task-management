@extends('layouts.app')

@section('content')
<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="px-4 py-8 bg-gray-50 shadow-xl sm:rounded-lg sm:px-10">
        <div class="text-center text-gray-800">{{ __('Register') }}</div>
        <div>
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <input 
                        id="name" 
                        placeholder="Name"
                        type="text" 
                        class="w-full p-2 bg-gray-50 border-2 border-gray-300 focus:outline-none focus:ring focus:border-blue-300" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autocomplete="name" 
                        autofocus
                    >

                    @error('name')
                        <span class="text-red-500" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div>
                    <input 
                        id="email" 
                        placeholder="Email Address"
                        type="email" 
                        class="w-full p-2 bg-gray-50 border-2 border-gray-300 focus:outline-none focus:ring focus:border-blue-300" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email" 
                        autofocus
                    >

                    @error('email')
                        <span class="text-red-500" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div>
                    <input 
                        id="password" 
                        type="password" 
                        placeholder="Password"
                        class="w-full p-2 bg-gray-50 border-2 border-gray-300 focus:outline-none focus:ring focus:border-blue-300" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                    >

                    @error('password')
                        <span class="text-red-500" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div>
                    <input 
                        id="password-confirm" 
                        type="password" 
                        placeholder="Confirm Password"
                        class="w-full p-2 bg-gray-50 border-2 border-gray-300 focus:outline-none focus:ring focus:border-blue-300" 
                        name="password_confirmation" 
                        required 
                        autocomplete="current-password"
                    >
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-md">
                    {{ __('Register') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
