<?php
// Pastikan session dan koneksi database sudah ada dari file utama
$navbarID = $_SESSION['id'];
$queryNavbar = mysqli_query($connection, "SELECT * FROM user WHERE id = '$navbarID'");
$dataNavbar = mysqli_fetch_assoc($queryNavbar);
?>

<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="?page=dashboard" class="logo">
                <img src="template/assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
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
        </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#dashboardMenu">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="dashboardMenu">
                        <ul class="nav nav-collapse">
                            <li <?= !isset($_GET['page']) || ($_GET['page'] == 'dashboard') ? 'class="active"' : '' ?>>
                                <a href="?page=dashboard">
                                    <span class="sub-item">Dashboard</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Master Data</h4>
                </li>

                <?php if ($dataNavbar['id_level'] == 1) : ?>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#adminMenu">
                            <i class="fas fa-layer-group"></i>
                            <p>Admin</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="adminMenu">
                            <ul class="nav nav-collapse">
                                <li <?= (isset($_GET['page']) && in_array($_GET['page'], ['user', 'add-user'])) ? 'class="active"' : '' ?>>
                                    <a href="?page=user"><span class="sub-item">User</span></a>
                                </li>
                                <li <?= (isset($_GET['page']) && in_array($_GET['page'], ['level', 'add-level'])) ? 'class="active"' : '' ?>>
                                    <a href="?page=level"><span class="sub-item">Level</span></a>
                                </li>
                                <li <?= (isset($_GET['page']) && in_array($_GET['page'], ['customer', 'add-customer'])) ? 'class="active"' : '' ?>>
                                    <a href="?page=customer"><span class="sub-item">Customer</span></a>
                                </li>
                                <li <?= (isset($_GET['page']) && in_array($_GET['page'], ['service', 'add-service'])) ? 'class="active"' : '' ?>>
                                    <a href="?page=service"><span class="sub-item">Service</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php elseif ($dataNavbar['id_level'] == 2) : ?>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#operatorMenu">
                            <i class="fas fa-th-list"></i>
                            <p>Operator</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="operatorMenu">
                            <ul class="nav nav-collapse">
                                <li <?= (isset($_GET['page']) && in_array($_GET['page'], ['order', 'add-order'])) ? 'class="active"' : '' ?>>
                                    <a href="?page=order"><span class="sub-item">Order</span></a>
                                </li>
                                <li <?= (isset($_GET['page']) && in_array($_GET['page'], ['pickup', 'add-pickup'])) ? 'class="active"' : '' ?>>
                                    <a href="?page=pickup"><span class="sub-item">Pickup</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php elseif ($dataNavbar['id_level'] == 3) : ?>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#pimpinanMenu">
                            <i class="fas fa-file-contract"></i>
                            <p>Pimpinan</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="pimpinanMenu">
                            <ul class="nav nav-collapse">
                                <li <?= (isset($_GET['page']) && in_array($_GET['page'], ['report', 'add-report'])) ? 'class="active"' : '' ?>>
                                    <a href="?page=report"><span class="sub-item">Report</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>