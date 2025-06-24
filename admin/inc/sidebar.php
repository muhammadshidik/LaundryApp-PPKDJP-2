 <?php
$navbarID = $_SESSION['id'];
$queryNavbar = mysqli_query($connection, "SELECT * FROM user WHERE id = '$navbarID'");
$dataNavbar = mysqli_fetch_assoc($queryNavbar);

?>
 
 <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
              <img
                src="template/assets/img/kaiadmin/logo_light.svg"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item active <?= !isset($_GET['page']) || ($_GET['page'] == 'dashboard') ? 'active' : '' ?>   ">
                <a
                  data-bs-toggle="collapse"
                  href="?page=dashboard"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                  <span class="caret"></span>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Master Data</h4>
              </li>
              <?php if ($dataNavbar['id_level'] == 1) : ?>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#base">
                  <i class="fas fa-layer-group"></i>
                  <p>Admin</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="base">
                  <ul class="nav nav-collapse">
                    <li <?= (isset($_GET['page']) && ($_GET['page'] == 'user' || $_GET['page'] == 'add-user')) ? 'active' : '' ?>>
                      <a href="?page=user">
                        <span class="sub-item">User</span>
                      </a>
                    </li>
                    <li <?= (isset($_GET['page']) && ($_GET['page'] == 'level' || $_GET['page'] == 'add-level')) ? 'active' : '' ?> > 
                      <a href="?page=level">
                        <span class="sub-item">Level</span>
                      </a>
                    </li>
                    <li <?= (isset($_GET['page']) && ($_GET['page'] == 'customer' || $_GET['page'] == 'add-customer')) ? 'active' : '' ?>>
                      <a href="?page=customer">
                        <span class="sub-item">Customer</span>
                      </a>
                    </li>
                    <li <?= (isset($_GET['page']) && ($_GET['page'] == 'service' || $_GET['page'] == 'add-service')) ? 'active' : '' ?>>
                      <a href="?page=service">
                        <span class="sub-item">Service</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              <?php elseif ($dataNavbar['id_level'] == 2) : ?>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#sidebarLayouts">
                  <i class="fas fa-th-list"></i>
                  <p>Operator</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="sidebarLayouts">
                  <ul class="nav nav-collapse">
                    <li <?= (isset($_GET['page']) && ($_GET['page'] == 'order' || $_GET['page'] == 'add-order')) ? 'active' : '' ?>>
                      <a href="?page=order">
                        <span class="sub-item">Order</span>
                      </a>
                    </li>
                    <li <?= (isset($_GET['page']) && ($_GET['page'] == 'pickup' || $_GET['page'] == 'add-pickup')) ? 'active' : '' ?>>
                      <a href="?page=pickup">
                        <span class="sub-item">Pickup</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
                
               <?php elseif ($dataNavbar['id_level'] == 3) : ?>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#sidebarLayouts">
                  <i class="fas fa-th-list"></i>
                  <p>Pimpinan</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="sidebarLayouts">
                  <ul class="nav nav-collapse">
                    <li <?= (isset($_GET['page']) && ($_GET['page'] == 'report' || $_GET['page'] == 'add-report')) ? 'active' : '' ?>">
            <a class="sidebar-link justify-content-between ">
                      <a href="sidebar-style-2.html">
                        <span class="sub-item">Report</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
                </div>
            </ul>
            <?php endif ?>
          </div>
        </div>
      </div>