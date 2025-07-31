<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide">
<head>
    <meta charset="utf-8" />
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    
    {{-- Sisipkan semua link CSS dan script <head> dari layout utama Anda di sini --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('template/assets/img/favicon/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/pages/page-auth.css') }}" />
    <script src="{{ asset('template/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('template/assets/js/config.js') }}"></script>
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4">
                            <a href="/" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-heading fw-bold">Bimbingan Unima</span>
                            </a>
                        </div>
                        
                        <h4 class="mb-2">Atur Ulang Password Anda 🔑</h4>
                        <p class="mb-4">Pastikan password baru Anda kuat dan mudah diingat.</p>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-3" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $request->email) }}" readonly />
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">Password Baru</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required autocomplete="new-password" autofocus/>
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required autocomplete="new-password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary d-grid w-100">
                                Simpan Password Baru
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Sisipkan semua tag <script> dari akhir <body> layout utama Anda di sini --}}
    <script src="{{ asset('template/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('template/assets/js/main.js') }}"></script>
</body>
</html>