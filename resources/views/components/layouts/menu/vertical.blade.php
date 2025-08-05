<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link"><x-app-logo /></a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboards -->
    <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
      <a class="menu-link" href="{{ route('dashboard') }}" wire:navigate>
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div class="text-truncate">{{ __('Dashboard') }}</div>
      </a>
    </li>

    @auth
      @switch(auth()->user()->role)
        @case('admin')
          <li class="menu-item {{ request()->routeIs('monitoring.stock') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('monitoring.stock') }}" wire:navigate>
              <i class="menu-icon tf-icons bx bx-desktop"></i>
              <div class="text-truncate">Monitoring</div>
            </a>
          </li>
          <li class="menu-item {{ request()->is('gudang/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-store-alt"></i>
              <div class="text-truncate">Gudang</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('gudang.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('gudang.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('gudang.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('gudang.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('gudang.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('gudang.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('gudang.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('gudang.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          <li class="menu-item {{ request()->is('pencucian/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-water"></i>
              <div class="text-truncate">Pencucian</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('pencucian.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pencucian.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('pencucian.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pencucian.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('pencucian.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pencucian.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('pencucian.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pencucian.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          <li class="menu-item {{ request()->is('pengeringan/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-sun"></i>
              <div class="text-truncate">Pengeringan</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('pengeringan.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pengeringan.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('pengeringan.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pengeringan.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('pengeringan.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pengeringan.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('pengeringan.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pengeringan.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          <li class="menu-item {{ request()->is('blower/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-wind"></i>
              <div class="text-truncate">Blower/Trap/Sortasi</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('blower.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('blower.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('blower.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('blower.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('blower.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('blower.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('blower.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('blower.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          <li class="menu-item {{ request()->is('mixing/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-shuffle"></i>
              <div class="text-truncate">Mixing</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('mixing.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('mixing.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('mixing.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('mixing.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('mixing.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('mixing.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('mixing.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('mixing.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          <li class="menu-item {{ request()->is('grinding/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-cut"></i>
              <div class="text-truncate">Grinding</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('grinding.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('grinding.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('grinding.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('grinding.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('grinding.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('grinding.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('grinding.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('grinding.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          <li class="menu-item {{ request()->is('packaging/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-package"></i>
              <div class="text-truncate">Packaging</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('packaging.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('packaging.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('packaging.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('packaging.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('packaging.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('packaging.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('packaging.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('packaging.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          <li class="menu-item {{ request()->is('finish_good/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-check-circle"></i>
              <div class="text-truncate">Finish Good</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('finish_good.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('finish_good.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('finish_good.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('finish_good.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('finish_good.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('finish_good.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('finish_good.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('finish_good.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          @break
        @case('gudang')
          <li class="menu-item {{ request()->is('gudang/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-store-alt"></i>
              <div class="text-truncate">Gudang</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('gudang.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('gudang.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('gudang.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('gudang.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('gudang.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('gudang.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('gudang.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('gudang.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          @break
        @case('pencucian')
          <li class="menu-item {{ request()->is('pencucian/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-water"></i>
              <div class="text-truncate">Pencucian</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('pencucian.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pencucian.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('pencucian.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pencucian.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('pencucian.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pencucian.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('pencucian.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pencucian.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          @break
        @case('pengeringan')
          <li class="menu-item {{ request()->is('pengeringan/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-sun"></i>
              <div class="text-truncate">Pengeringan</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('pengeringan.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pengeringan.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('pengeringan.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pengeringan.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('pengeringan.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pengeringan.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('pengeringan.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pengeringan.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          @break
        @case('blower')
          <li class="menu-item {{ request()->is('blower/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-wind"></i>
              <div class="text-truncate">Blower/Trap/Sortasi</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('blower.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('blower.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('blower.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('blower.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('blower.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('blower.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('blower.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('blower.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          @break
        @case('mixing')
          <li class="menu-item {{ request()->is('mixing/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-shuffle"></i>
              <div class="text-truncate">Mixing</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('mixing.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('mixing.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('mixing.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('mixing.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('mixing.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('mixing.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('mixing.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('mixing.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          @break
        @case('grinding')
          <li class="menu-item {{ request()->is('grinding/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-cut"></i>
              <div class="text-truncate">Grinding</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('grinding.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('grinding.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('grinding.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('grinding.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('grinding.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('grinding.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('grinding.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('grinding.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          @break
        @case('packaging')
          <li class="menu-item {{ request()->is('packaging/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-package"></i>
              <div class="text-truncate">Packaging</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('packaging.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('packaging.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('packaging.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('packaging.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('packaging.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('packaging.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('packaging.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('packaging.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          @break
        @case('finish_good')
          <li class="menu-item {{ request()->is('finish_good/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-check-circle"></i>
              <div class="text-truncate">Finish Good</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('finish_good.stock') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('finish_good.stock') }}" wire:navigate>Stock</a>
              </li>
              <li class="menu-item {{ request()->routeIs('finish_good.monitoring') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('finish_good.monitoring') }}" wire:navigate>Monitoring</a>
              </li>
              <li class="menu-item {{ request()->routeIs('finish_good.ttpb') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('finish_good.ttpb') }}" wire:navigate>TTPB</a>
              </li>
              <li class="menu-item {{ request()->routeIs('finish_good.ttpb.preview') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('finish_good.ttpb.preview') }}" wire:navigate>TTPB Preview</a>
              </li>
            </ul>
          </li>
          @break
      @endswitch
    @endauth

    <!-- Settings -->
    <li class="menu-item {{ request()->is('settings/*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-cog"></i>
        <div class="text-truncate">{{ __('Settings') }}</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('settings.profile') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('settings.profile') }}" wire:navigate>{{ __('Profile') }}</a>
        </li>
        <li class="menu-item {{ request()->routeIs('settings.password') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('settings.password') }}" wire:navigate>{{ __('Password') }}</a>
        </li>
      </ul>
    </li>
  </ul>
</aside>
<!-- / Menu -->

<script>
  // Toggle the 'open' class when the menu-toggle is clicked
  document.querySelectorAll('.menu-toggle').forEach(function(menuToggle) {
    menuToggle.addEventListener('click', function() {
      const menuItem = menuToggle.closest('.menu-item');
      // Toggle the 'open' class on the clicked menu-item
      menuItem.classList.toggle('open');
    });
  });
</script>
