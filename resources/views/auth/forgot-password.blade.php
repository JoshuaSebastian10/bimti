

<!doctype html>

<html
  lang="en"
  class="layout-wide customizer-hide"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Lupa Password</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('template/assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('template/assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('template/assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('template/assets/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Forgot Password -->
          <div class="card px-sm-6 px-0">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center mb-6">
                <a href="index.html" class="app-brand-link gap-2">
                    
                  <span class="app-brand-text demo text-heading fw-bold">Teknik Informatika</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-1">Lupa Password? 🔒</h4>
              <p class="mb-6">Link Perubahan Password Akan Dikirimkan Secara Otomatis Ke Email</p>
              
                        @if (session('status'))
                            <div class="alert alert-success mb-6" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger mb-6" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-6" method="POST" action="{{ route('password.email') }}">
                            @csrf {{-- <-- Tambahkan @csrf di sini --}}

                            <div class="mb-6">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    placeholder="Masukkan email Anda"
                                    value="{{ old('email') }}" {{-- Tambahkan old('email') --}}
                                    required autofocus />
                            </div>
                            <button class="btn btn-primary d-grid w-100">Kirim Link Reset Password</button>
                        </form>
                        
                     

                        <div class="text-center">
                            {{-- ... (kode link kembali ke login sudah benar) ... --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- / Content -->



    <!-- Core JS -->

    <script src="{{ asset('template/assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('template/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/js/bootstrap.js') }}"></script>

    <script src="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('template/assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->
    
    <!-- Vendors JS -->

    <!-- Main JS -->

    <script src="{{ asset('template/assets/js/main.js') }}"></script>

    <!-- Page JS -->

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>

