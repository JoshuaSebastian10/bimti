        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
              <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
                
                <span class="app-brand-text demo menu-text fw-bold ms-2">Bimbingan</span>
              </a>
  
              <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
              </a>
            </div>
  
            <div class="menu-divider mt-0"></div>
  
            <div class="menu-inner-shadow"></div>
  
            <ul class="menu-inner py-1">
              <!-- Dashboards -->
              <li class="menu-item open {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="menu-link ">
                  <i class="menu-icon tf-icons bx bx-home-smile"></i>
                  <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
                </a>
              </li>

              <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Bimbingan</span>
              </li>
              <li class="menu-item {{ request()->routeIs('admin.manajemenBimbingan*') ? 'active' : '' }}">
                <a
                  href="{{ route('admin.manajemenBimbingan') }}"
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bxs-data"></i>
                  <div class="text-truncate" data-i18n="Email">Manajemen Bimbingan</div>
                </a>
              </li>

              <li class="menu-item {{ request()->routeIs('admin.jadwalDosen*') ? 'active' : '' }}">
                <a
                  href="{{ route('admin.jadwalDosen') }}"
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bxs-time"></i>
                  <div class="text-truncate" data-i18n="Email">Manajemen Jadwal Dosen</div>
                </a>
              </li>

                            {{-- <li class="menu-item">
                <a
                  href=""
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bxs-data"></i>
                  <div class="text-truncate" data-i18n="Email">Laporan Dan Rekap Bimbingan</div>
                </a>
              </li> --}}  
  
              <!-- Apps & Pages -->
              <li class="menu-header small text-uppercase">
                <span class="menu-header-text">User</span>
              </li>

              <li class="menu-item {{ request()->routeIs('admin.dataMahasiswa.index*') ? 'active' : '' }}">
                <a
                  href="{{route('admin.dataMahasiswa.index')}}"
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bxs-user"></i>  
                  <div class="text-truncate" data-i18n="Email">Data User</div>
                </a>
              </li>

              
              
              <!-- Components -->
              <li class="menu-header small text-uppercase"><span class="menu-header-text">Import Data Mahasiswa</span></li>
              <li class="menu-item {{ request()->routeIs('admin.mahasiswa.import.create*') ? 'active' : '' }}">
                <a
                  href="{{route('admin.mahasiswa.import.create')}}"
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bx-import"></i>
                  <div class="text-truncate" data-i18n="Email">Import Data Mahasiswa</div>
                </a>
              </li>

              
              <l class="menu-header small text-uppercase"><span class="menu-header-text">Akun</span></l>
              
                <li class="menu-item {{ request()->routeIs('profile.edit*') ? 'active' : '' }}">
                <a
                  href="{{route('profile.edit')}}"
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bx-user-circle"></i>  
                  <div class="text-truncate" data-i18n="Email">Profil</div>
                </a>
              </li>

                <li class="menu-item">
                  <form method="POST" action="{{ route('logout') }}" style="margin: 0;"> @csrf
                      
                      <button type="submit" class="menu-link" style="border: none; background: transparent;">
                          <i class="menu-icon tf-icons bx bx-power-off"></i>  
                          <div class="text-truncate" data-i18n="Logout">Logout</div>
                      </button>
                  </form>
              </li>
      
            </ul>
          </aside>
