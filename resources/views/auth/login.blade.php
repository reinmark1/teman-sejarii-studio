@extends('layouts.auth')

@section('title', 'Masuk Studio — teman sejarii')
@section('subtitle', 'Akses Internal')

@section('content')
    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="label-editorial">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" maxlength="255" required autofocus
                   placeholder="owner@temansejarii.com"
                   class="input-editorial">
        </div>

        <div>
            <label for="password" class="label-editorial">Kata Sandi</label>
            <div class="relative">
                <input type="password" id="password" name="password" maxlength="30" required
                       placeholder="••••••••"
                       class="input-editorial pr-10">
                <button type="button"
                        data-toggle-password="password"
                        aria-label="Tampilkan atau sembunyikan kata sandi"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-taupe hover-text-espresso transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 icon-eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 icon-eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between pt-1">
            <label class="inline-flex items-center gap-2 text-xs text-taupe cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-sand text-espresso focus:ring-0">
                <span>Ingat sesi saya</span>
            </label>
        </div>

        <button type="submit" class="btn-espresso w-full mt-2">
            Masuk ke Studio
        </button>
    </form>

    <div class="mt-6 pt-5 border-t border-sand text-center">
        <p class="text-xs text-taupe">
            Belum memiliki akun pengelola?
            <a href="{{ route('register') }}" class="text-espresso font-medium underline hover-text-blush">
                Daftar di sini
            </a>
        </p>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/password-toggle.js') }}"></script>
@endpush