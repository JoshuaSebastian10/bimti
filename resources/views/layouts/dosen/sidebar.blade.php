        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.html" class="app-brand-link">
              
              <span class="app-brand-text demo menu-text fw-bold ms-2">BIMTI</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
            </a>
          </div>

          <div class="menu-divider mt-0"></div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboards -->
            <li class="menu-item open  {{ request()->routeIs('dosen.dashboard*') ? 'active' : '' }}">
              <a href="{{ route('dosen.dashboard') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Bimbingan</span>
            </li>
            <li class="menu-item mb-2 {{ request()->routeIs('dosen.daftarBimbingan*') ? 'active' : '' }}">
              <a
                href="{{ route('dosen.daftarBimbingan') }}"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-task"></i>
                <div class="text-truncate" data-i18n="Email">Daftar Bimbingan</div>
              </a>
            </li>

            <li class="menu-item mb-2 {{ request()->routeIs('dosen.mahasiswaBimbingan*') ? 'active' : '' }}" >
              <a
                href="{{ route('dosen.mahasiswaBimbingan') }}"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div class="text-truncate" data-i18n="Email">Mahasiswa Bimbingan</div>
              </a>
            </li>

            <li class="menu-item {{ request()->routeIs('dosen.jadwalBimbingan*') ? 'active' : '' }}">
              <a
                href="{{route('dosen.jadwalBimbingan')}}"
                class="menu-link">
                <i class="menu-icon tf-icons bx bxs-time"></i>  
                <div class="text-truncate" data-i18n="Email">Jadwal Bimbingan</div>
              </a>
            </li>

             <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Akun</span>
            </li>

            <li class="menu-item ">
              <a
                href="{{ route('profile.edit') }}"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>  
                <div class="text-truncate" data-i18n="Email">Profil</div>
              </a>
            </li>

                      <li class="menu-item">
                  <form method="POST" action="{{ route('logout') }}" style="margin: 0;"> @csrf
                      
                      <button type="submit" class="menu-link" style="border: none; background: transparent;">
                    <i class="menu-icon tf-icons bx bx-log-out"></i>  
                  <div class="text-truncate" data-i18n="Email">Logout</div>
                      </button>
                  </form>
              </li>
            
            
            <!-- Components -->
            {{-- <li class="menu-header small text-uppercase"><span class="menu-header-text">Import</span></li>
            <li class="menu-item">
              <a
                href="{{route('admin.mahasiswa.import.create')}}"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-import"></i>
                <div class="text-truncate" data-i18n="Email">Import Data Mahasiswa</div>
              </a>
            </li> --}}
            
        
            
    
          </ul>
        </aside>
        <!-- / Menu -->