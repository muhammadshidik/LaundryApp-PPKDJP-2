<?php
include 'admin/controller/administrator-validation.php';
$queryData = mysqli_query($connection, "SELECT user.id, user.deleted_at, user.username, user.email, level.level_name FROM user LEFT JOIN level ON user.id_level = level.id ORDER BY user.id_level ASC, user.updated_at DESC");
?>

<div class="card shadow">
    <div class="card-header">
        <h5>Data User</h5>
    </div>
    <div class="card-body">
        <?php include 'admin/controller/alert-data-crud.php' ?>
        <div align="left" class="button-action">
            <a href="?page=add-user" class="btn btn-primary btn-sm mt-3 mb-3">Tambah User</a>
        </div>
         <table class="table align-middle table-hover text-nowrap">
                <thead class="table-light">
                    <tr class="align-middle text-center">
                        <th style="width:5%;">No</th>
                        <th>Level</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th style="width:15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($rowData = mysqli_fetch_assoc($queryData)) : ?>
                        <tr class="<?= $rowData['deleted_at'] ? 'table-warning' : '' ?>">
                            <td class="text-center fw-semibold"><?= $no++ ?></td>
                            <td>
                                    <strong><?= $rowData['level_name'] ?? '-' ?></strong>
                            </td>
                            <td><?= htmlspecialchars($rowData['username'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($rowData['email'] ?? '-') ?></td>
                            <td class="text-center">
                                <a href="?page=add-user&edit=<?= $rowData['id'] ?>" 
                                   class="btn btn-outline-success btn-sm me-1" title="Edit">
                                    <i class="bx bx-pencil"></i>Edit
                                </a>
                                <a href="?page=add-user&delete=<?= $rowData['id'] ?>" 
                                   onclick="return confirm('Yakin ingin menghapus data ini?')" 
                                   class="btn btn-outline-danger btn-sm" title="Hapus">
                                    <i class="bx bx-trash"></i>Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
    </div>
</div>