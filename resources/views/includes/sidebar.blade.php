<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  {{-- <a href="index3.html" class="brand-link">
    <img src="{{ asset("")}}dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">ML & AI</span>
  </a> --}}

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset("")}}dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Administrator</a>
      </div>
    </div> --}}

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
        <li class="nav-header">Menu</li>
        <li class="nav-item">
          <a href="{{route('anomali')}}" class="nav-link">
            <i class="nav-icon fas fa-folder"></i>
            <p>Deteksi Anomali</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('logistic')}}" class="nav-link">
            <i class="nav-icon fas fa-folder"></i>
            <p>Logistik</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('log-system')}}" class="nav-link">
            <i class="nav-icon fas fa-folder"></i>
            <p>Log Sistem</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('operational')}}" class="nav-link">
            <i class="nav-icon fas fa-folder"></i>
            <p>Operasional</p>
          </a>
        </li>
        


        
        
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>