@extends('layouts.app')

@section('content')
<div class="flex justify-center mt-32">
    <div class="w-full lg:w-1/2 shadow-lg rounded border border-gray-100 p-6">
        <div class="text-3xl mb-4">
            {{ __('auth.login') }}
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label 
                    for="email" 
                    class="block text-gray-700 font-bold mb-2"
                >{{ __('auth.email') }}</label>

                <input 
                    id="email" 
                    type="email" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="email" 
                    autofocus
                >

                @error('email')
                    <span class="text-red-500 text-sm mb-2" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="mb-4">
                <label 
                    for="password" 
                    class="block text-gray-700 font-bold mb-2"
                >{{ __('auth.password') }}</label>

                <input 
                    id="password" 
                    type="password" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                >

                @error('password')
                    <span class="text-red-500 text-sm mb-2" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="mb-4">
                <input  type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label  for="remember">
                    {{ __('auth.rememberme') }}
                </label>
            </div>

            <div>
                <button type="submit" class="btn btn-blue">
                    {{ __('auth.login') }}
                </button>

                @if (Route::has('password.request'))
                    <a class="btn btn-text" href="{{ route('password.request') }}">
                        {{ __('auth.forgotyourpassword') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection