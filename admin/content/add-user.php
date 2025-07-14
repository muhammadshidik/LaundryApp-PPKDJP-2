<?php
require_once 'admin/controller/koneksi.php';
include 'admin/controller/administrator-validation.php';

if (isset($_GET['delete'])) {
    $idDelete = $_GET['delete'];
    $query = mysqli_query($connection, "DELETE FROM user WHERE id='$idDelete'");
    header("Location: ?page=user&delete=success");
    die;
} else if (isset($_GET['edit'])) {
    $idEdit = $_GET['edit'];
    $queryEdit = mysqli_query($connection, "SELECT * FROM user WHERE id='$idEdit'");
    $rowEdit = mysqli_fetch_assoc($queryEdit);
    if (isset($_POST['edit'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'] ? $_POST['password'] : $rowEdit['password'];
        $id_level = $_POST['id_level'];

        $queryEdit = mysqli_query($connection, "UPDATE user SET username='$username', email='$email', password='$password', id_level='$id_level' WHERE id='$idEdit'");
        header("Location: ?page=user&edit=success");
        die;
    }
} else if (isset($_POST['add'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $id_level = $_POST['id_level'];

    $queryAdd = mysqli_query($connection, "INSERT INTO user (username, email, password, id_level) VALUES ('$username', '$email', '$password', '$id_level')");
    header("Location: ?page=user&add=success");
    die;
}

$queryLevel = mysqli_query($connection, "SELECT * FROM level");
?>

<div class="card shadow">
    <div class="card-header">
        <h5><?= isset($_GET['edit']) ? 'Edit' : 'Add' ?> User</h5>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label for="username" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter Name"
                        value="<?= isset($_GET['edit']) ? $rowEdit['username'] : '' ?>" required>
                </div>
                <div class="col-sm-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email"
                        value="<?= isset($_GET['edit']) ? $rowEdit['email'] : '' ?>" required>
                </div>
                <div class="col-sm-6 mb-3">
                    <label for="level" class="form-label">Level</label>
                    <select class="form-control" name="id_level" id="">
                        <option value=""> -- Add Level -- </option>
                        <?php while ($rowLevel = mysqli_fetch_assoc($queryLevel)) : ?>
                            <option value="<?= $rowLevel['id'] ?>"
                                <?= isset($_GET['edit']) && ($rowLevel['id'] == $rowEdit['id_level']) ? 'selected' : '' ?>>
                                <?= $rowLevel['level_name'] ?></option>
                        <?php endwhile ?>
                    </select>
                </div>
                <div class="col-sm-6 form-group mb-3 form-password-toggle">
                    <label for="" class="form-label">Password Baru (opsional)</label>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control" name="password"
                            placeholder="Enter password" aria-describedby="password" />
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                </div>
            </div>
            <div class="" align="left">
                <a href="?page=user" class="btn btn-secondary btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm"
                    name="<?php echo isset($_GET['edit']) ? 'edit' : 'add' ?>">
                    <?php echo isset($_GET['edit']) ? 'Simpan' : 'Add' ?>
                </button>
            </div>
        </form>
    </div>
</div>