<?php
require_once 'admin/controller/koneksi.php';


$idEdit = $_SESSION['id'];
$queryEdit = mysqli_query($connection, "SELECT * FROM user WHERE id='$idEdit'");
$rowEdit = mysqli_fetch_assoc($queryEdit);

if (isset($_POST['edit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    if (!empty($_FILES['photo']['name'])) {
        $namaFotoLama = $_FILES['photo']['name'];

        $ext = array('jpg', 'jpeg', 'png', 'jfif', 'webp', 'heic');
        $img_ext = pathinfo($namaFotoLama, PATHINFO_EXTENSION);

        if (!in_array($img_ext, $ext)) {
            header("Location: ?page=my-profile&edit=errorExtension");
            die;
        } else {
            if (file_exists('img/profile_picture/' . $rowEdit['profile_picture'])) {
                unlink('img/profile_picture/' . $rowEdit['profile_picture']);
            }
            $new_image_name = "profile_picture" . $idEdit . "." . $img_ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], 'img/profile_picture/' . $new_image_name);
            $queryEdit = mysqli_query($connection, "UPDATE user SET username='$username', email='$email', profile_picture='$new_image_name' WHERE id='$idEdit'");
        }
    }
    $queryEdit = mysqli_query($connection, "UPDATE user SET username='$username', email='$email' WHERE id='$idEdit'");
    header("Location: ?page=my-profile&edit=success");
    die;
}

$queryLevel = mysqli_query($connection, "SELECT * FROM level");

// if ((isset($_GET['pg']) == 'my-profile') && (isset($_GET['edit']) == 'success')) {
//     header('location: ?page=my-profile');
//     die;
// }

?>

<div class="card shadow">
    <div class="card-header">
        <h3>My Profile</h3>
    </div>
    <div class="card-body">
        <?php if (isset($_GET['edit']) && $_GET['edit'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                Edit profile success!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif (isset($_GET['edit']) && $_GET['edit'] == 'errorExtension'): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                Wrong file extension!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif ?>
        <img width="150px"
            src="<?= !empty($rowEdit['profile_picture']) && file_exists('img/profile_picture/' . $rowEdit['profile_picture']) ? 'img/profile_picture/' . $rowEdit['profile_picture'] : 'https://placehold.co/100' ?>"
            alt="" class="mt-4 rounded">
        <hr>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="username" placeholder="Masukkan nama"
                        value="<?= isset($rowEdit['username']) ? $rowEdit['username'] : '' ?>" required>
                </div>
                <div class="col-sm-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email"
                        value="<?= isset($rowEdit['email']) ? $rowEdit['email'] : '' ?>" required>
                </div>

                <div class="col-sm-6 mb-3">
                    <label for="level" class="form-label">Level</label>
                    <select class="form-control" name="id_level" id="" disabled="true">
                        <option value=""> -- Add Level -- </option>
                        <?php while ($rowLevel = mysqli_fetch_assoc($queryLevel)) : ?>
                            <option value="<?= $rowLevel['id'] ?>"
                                <?= isset($rowEdit['id_level']) && ($rowLevel['id'] == $rowEdit['id_level']) ? 'selected' : '' ?>>
                                <?= $rowLevel['level_name'] ?></option>
                        <?php endwhile ?>
                    </select>
                </div>
                <?php if ($rowEdit['id_level'] == 2) : ?>
                    <div class="col-sm-6 mb-3">
                        <label for="email" class="form-label">Jurusan</label>
                        <select class="form-control" name="id_jurusan" id="" disabled="true">
                            <option value=""> -- Add Jurusan -- </option>
                            <?php while ($rowJurusan = mysqli_fetch_assoc($queryJurusan)) : ?>
                                <option value="<?= $rowJurusan['id'] ?>"
                                    <?= isset($rowEdit['id_jurusan']) && ($rowJurusan['id'] == $rowEdit['id_jurusan']) ? 'selected' : '' ?>>
                                    <?= $rowJurusan['nama_jurusan'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </div>
                <?php endif ?>
                <div class="col-sm-6 mb-3">
                    <label for="photoProfile" class="form-label">Foto Profil</label>
                    <input type="file" class="form-control" id="profile_picture" name="photo">
                </div>
            </div>
            <div class="">
                <button type="submit" class="btn btn-primary" name="edit">
                    Edit Profile
                </button>
            </div>
        </form>
    </div>
</div>