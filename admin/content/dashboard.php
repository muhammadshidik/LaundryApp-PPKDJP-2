<?php
// getting account username
$idDashboard = $_SESSION['id'];
$queryDashboard = mysqli_query($connection, "SELECT * FROM user WHERE id = '$idDashboard'");
$rowDashboard = mysqli_fetch_array($queryDashboard);

?>

<style>
    .card {
        min-height: 600px;
    }

    .card .card-body img {
        max-width: 250px;
    }
</style>

<div class="card">
    <div class="card-header ">
        <h5>Dashboard</h5>
    </div>
    <div class="card-body d-flex align-items-center justify-content-center gap-3">
        <div class="row">
            <div class="col-sm-12 mb-5" align="center">
                <img src="admin/img/logo/logo.png" alt="">
            </div>
            <div class="col-sm-12" align="center">
                <h2>Assalamualaikum Ya habibi, <?= $rowDashboard['username'] ?>!</h2>
            </div>
        </div>
    </div>
</div>