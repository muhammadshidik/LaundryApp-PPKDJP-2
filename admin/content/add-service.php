<?php
require_once 'admin/controller/koneksi.php';
include 'admin/controller/administrator-validation.php';

if (isset($_GET['delete'])) {
    $idDelete = $_GET['delete'];
    $query = mysqli_query($connection, "DELETE FROM type_of_service WHERE id='$idDelete'");
    header("Location: ?page=service&delete=success");
    die;
} else if (isset($_GET['edit'])) {
    $idEdit = $_GET['edit'];
    $queryEdit = mysqli_query($connection, "SELECT * FROM type_of_service WHERE id='$idEdit'");
    $rowEdit = mysqli_fetch_assoc($queryEdit);
    if (isset($_POST['edit'])) {
        $service_name = $_POST['service_name'];
        $price = $_POST['price'];
        $description = $_POST['description'];

        $queryEdit = mysqli_query($connection, "UPDATE type_of_service SET service_name='$service_name', price='$price', description='$description' WHERE id='$idEdit'");
        header("Location: ?page=service&edit=success");
        die;
    }
} else if (isset($_POST['add'])) {
    $service_name = $_POST['service_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $queryAdd = mysqli_query($connection, "INSERT INTO type_of_service (service_name, price, description) VALUES ('$service_name', '$price', '$description')");
    header("Location: ?page=service&add=success");
    die;
}
?>

<div class="card shadow">
    <div class="card-header">
        <h5><?= isset($_GET['edit']) ? 'Edit' : 'Add' ?> Service</h5>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label for="" class="form-label">Nama Service</label>
                    <input type="text" class="form-control" id="" name="service_name" placeholder="Masukkan Nama Service"
                        value="<?= isset($_GET['edit']) ? $rowEdit['service_name'] : '' ?>" required>
                </div>
                <div class="col-sm-6 mb-3">
                    <label for="" class="form-label">Harga/Kg</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">Rp</span>
                        <input type="number" name="price" class="form-control"
                            placeholder="Masukkan Harga.."
                            value="<?= isset($_GET['edit']) ? $rowEdit['price'] : '' ?>">
                    </div>
                </div>
                <div class="col-sm-6 mb-3">
                    <label for="" class="form-label">Deskripsi</label>
                    <textarea name="description" id="" class="form-control"
                        placeholder="Masukkan Deskripsi"><?= isset($_GET['edit']) ? $rowEdit['description'] : '' ?></textarea>
                </div>
            </div>
            <div class="" align="left">
                <a href="?page=service" class="btn btn-secondary btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm"
                    name="<?php echo isset($_GET['edit']) ? 'edit' : 'add' ?>">
                    <?php echo isset($_GET['edit']) ? 'Simpan' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>