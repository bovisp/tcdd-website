@extends('layouts.app')

@section('content')
<div class="flex justify-center mt-32">
    <div class="w-full lg:w-1/2 shadow-lg rounded border border-gray-100 p-6">
        <div class="text-3xl mb-4">
            {{ __('Reset Password') }}
        </div>

        @if (session('status'))
            <div class="alert alert-blue mb-4" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label 
                    for="email" 
                    class="block text-gray-700 font-bold mb-2"
                >{{ __('E-Mail Address') }}</label>

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

            <button type="submit" class="btn btn-blue">
                {{ __('Send Password Reset Link') }}
            </button>
        </form>
    </div>
</div>
@endsection