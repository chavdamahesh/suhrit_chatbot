<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="../../index3.html" class="brand-link">
    <img
      src="../../dist/img/AdminLTELogo.png"
      alt="AdminLTE Logo"
      class="brand-image img-circle elevation-3"
      style="opacity: 0.8"
    />
    <span class="brand-text font-weight-light">ChatBot</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
  <div class="image">
    <img
      src="../../dist/img/user2-160x160.jpg"
      class="img-circle elevation-2"
      alt="User Image"
    />
  </div>
  <div class="info">
    <a href="#" class="d-block">SoftTechies</a>
    <a href="authentication/logout.php" class="d-block text-danger" style="font-size: 0.9rem;">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>
</div>


    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input
          class="form-control form-control-sidebar"
          type="search"
          placeholder="Search"
          aria-label="Search"
        />
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
  
        <!-- Manage Customer -->
        <li class="nav-item">
          <a href="editCategory.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'editCategory.php' ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-user"></i>
            <p>Manage ChatBots</p>
          </a>
        </li>
        <!-- Manage Customer -->
        <li class="nav-item">
          <a href="manageCustomer.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manageCustomer.php' ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-user"></i>
            <p>Manage Customer</p>
          </a>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
