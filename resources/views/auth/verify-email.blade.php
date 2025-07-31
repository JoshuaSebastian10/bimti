<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide">
<head>
    <meta charset="utf-8" />
    <title>Verifikasi Email</title>
    {{-- Sisipkan <head> konten dari template Sneat Anda di sini (CSS, fonts, dll.) --}}
    @include('layouts.partials.head') {{-- Contoh jika Anda punya partial --}}
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/pages/page-auth.css') }}" />
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4">
                            <a href="/" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-heading fw-bold">Bimbingan Unima</span>
                            </a>
                        </div>
                        
                        <h4 class="mb-2">Verifikasi Email Anda ✉️</h4>
                        <p class="mb-4">
                            Terima kasih telah mendaftar! Sebelum melanjutkan, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerimanya, kami akan dengan senang hati mengirimkannya kembali.
                        </p>

                        {{-- Pesan Status Jika Email Baru Terkirim --}}
                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success" role="alert">
                                Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan.
                            </div>
                        @endif

                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            {{-- Form untuk Kirim Ulang Email --}}
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    Kirim Ulang Email Verifikasi
                                </button>
                            </form>

                            {{-- Form untuk Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-label-secondary">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Sisipkan JS dari template Sneat Anda di sini --}}
    @include('layouts.partials.scripts') {{-- Contoh jika Anda punya partial --}}
</body>
</html>