<?php
require_once 'admin/controller/koneksi.php';
include 'admin/controller/administrator-validation.php';

if (isset($_GET['delete'])) {
    $idDelete = $_GET['delete'];
    $query = mysqli_query($connection, "DELETE FROM level WHERE id='$idDelete'");
    header("Location: ?page=level&delete=success");
    die;
} else if (isset($_GET['edit'])) {
    $idEdit = $_GET['edit'];
    $queryEdit = mysqli_query($connection, "SELECT * FROM level WHERE id='$idEdit'");
    $rowEdit = mysqli_fetch_assoc($queryEdit);
    if (isset($_POST['edit'])) {
        $level_name = $_POST['level_name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $queryEdit = mysqli_query($connection, "UPDATE level SET level_name='$level_name' WHERE id='$idEdit'");
        header("Location: ?page=level&edit=success");
        die;
    }
} else if (isset($_POST['add'])) {
    $level_name = $_POST['level_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $queryAdd = mysqli_query($connection, "INSERT INTO level (level_name) VALUES ('$level_name')");
    header("Location: ?page=level&add=success");
    die;
}
?>

<div class="card shadow">
    <div class="card-header">
        <h5><?= isset($_GET['edit']) ? 'Edit' : 'Add' ?> Level</h5>
</div>
    <div class="card-body">
        <form action="" method="post">
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label for="" class="form-label">Nama Level</label>
                    <input type="text" class="form-control" id="" name="level_name" placeholder="Enter level name"
                        value="<?= isset($_GET['edit']) ? $rowEdit['level_name'] : '' ?>" required>
                </div>
            </div>
            <div align="left" class="">
                 <a href="?page=level" class="btn btn-secondary btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm"
                    name="<?php echo isset($_GET['edit']) ? 'edit' : 'add' ?>">
                    <?php echo isset($_GET['edit']) ? 'Simpan' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>