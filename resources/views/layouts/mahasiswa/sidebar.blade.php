        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="{{ route('mahasiswa.dashboard') }}" class="app-brand-link">
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
            <li class="menu-item {{ request()->routeIs('mahasiswa.dashboard*') ? 'active' : '' }}">
              <a href="{{ route('mahasiswa.dashboard') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Bimbingan</span>
            </li>

            <li class="menu-item {{ request()->routeIs('mahasiswa.bimbinganSaya*') ? 'active' : '' }}">
              <a
                href="{{ route('mahasiswa.bimbinganSaya') }}"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-task"></i>
                <div class="text-truncate" data-i18n="Email">Bimbingan Saya</div>
              </a>
            </li>

              <li class="menu-item {{ request()->routeIs('mahasiswa.bimbinganAkademik*') || request()->routeIs('mahasiswa.bimbinganSkripsi.create*') ? 'open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-book-content"></i>
                <div class="text-truncate" data-i18n="Layouts">Buat Bimbingan</div>
              </a>

              <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('mahasiswa.bimbinganAkademik*') ? 'active' : '' }}">
                  <a href="{{ route('mahasiswa.bimbinganAkademik') }}" class="menu-link">
                    <div class="text-truncate">Akademik</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->routeIs('mahasiswa.bimbinganAkademik*') || request()->routeIs('mahasiswa.bimbinganProposal.create*') ? 'open' : '' }}">
                  <a href="{{ route('mahasiswa.bimbinganProposal.create') }}" class="menu-link">
                    <div class="text-truncate">Proposal</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->routeIs('mahasiswa.bimbinganSkripsi.create*') ? 'active' : '' }}">
                  <a href="{{ route('mahasiswa.bimbinganSkripsi.create') }}" class="menu-link">
                    <div class="text-truncate">Skripsi</div>
                  </a>
                </li>
              </ul>
            </li>

                <l class="menu-header small text-uppercase"><span class="menu-header-text">Akun</span></l>
              
                <li class="menu-item {{ request()->routeIs('profile.edit*') ? 'active' : '' }}">
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

            
        
            
    
          </ul>
        </aside>
        <!-- / Menu -->