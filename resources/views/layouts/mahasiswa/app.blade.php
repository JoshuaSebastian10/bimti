<!doctype html>
<html
  lang="en"
  class="layout-menu-fixed layout-compact"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}"><!-- penting untuk /broadcasting/auth -->

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logoInformatika.png') }}" />

    <!-- SweetAlert2 (opsional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('template/assets/vendor/fonts/iconify-icons.css') }}" />
    @stack('styles')

    <!-- Core CSS Sneat -->
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Helpers & Config -->
    <script src="{{ asset('template/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('template/assets/js/config.js') }}"></script>

    {{-- Vite: load app.js (di dalamnya import ./bootstrap yang inisialisasi Echo/Pusher) --}}
    @vite(['resources/js/app.js'])

    {{-- Livewire styles (jika pakai Livewire) --}}
    @livewireStyles
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Sidebar -->
        @include('layouts.mahasiswa.sidebar')
        <!-- /Sidebar -->

        <!-- Layout container -->
        <div class="layout-page">
          {{-- Header --}}
          @include('layouts.mahasiswa.header')
          {{-- /Header --}}

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            @yield('content')
            <!-- /Content -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- /Content wrapper -->
        </div>
        <!-- /Layout page -->
      </div>

      <!-- Overlay (opsional) -->
      {{-- <div class="layout-overlay layout-menu-toggle"></div> --}}
    </div>
    <!-- /Layout wrapper -->

    <!-- Core JS Sneat -->
    <script src="{{ asset('template/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/js/menu.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('template/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS Sneat -->
    <script src="{{ asset('template/assets/js/main.js') }}"></script>
    <script src="{{ asset('template/assets/js/dashboards-analytics.js') }}"></script>

    <!-- Github buttons (opsional) -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    {{-- Livewire scripts --}}
    @livewireScripts

    {{-- === Realtime subscribe ke private channel user (Pusher/Echo) === --}}
    @auth

    <script>
document.addEventListener('DOMContentLoaded', () => {
  const kanal = @json(optional(auth()->user()->mahasiswa)->id ? ('mahasiswa.' . auth()->user()->mahasiswa->id) : null);
  if (!kanal || !window.Echo) return;

  if (!window.__notifMahasiswaSub) {
    window.__notifMahasiswaSub = window.Echo.private(kanal)
      .listen('.perubahan-diminta',  () => Livewire.dispatch('notifMasuk'))
      .listen('.perubahan-otomatis', () => Livewire.dispatch('notifMasuk'));
  }
});
</script>

    @endauth
  </body>
</html>
