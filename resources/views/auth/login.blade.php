@extends('layouts.app')

@section('content')
<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="px-4 py-8 bg-gray-50 shadow-xl sm:rounded-lg sm:px-10">
        <div class="text-center text-gray-800">{{ __('Login') }}</div>

        <div>
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

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
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="text-gray-700 font-light" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-md">
                    {{ __('Login') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
