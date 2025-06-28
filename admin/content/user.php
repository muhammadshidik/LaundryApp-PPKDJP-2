<?php
include 'admin/controller/administrator-validation.php';
$queryData = mysqli_query($connection, "SELECT user.id, user.deleted_at, user.username, user.email, level.level_name FROM user LEFT JOIN level ON user.id_level = level.id ORDER BY user.id_level ASC, user.updated_at DESC");
?>

<div class="card shadow border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center p-4">
        <h4 class="mb-0 text-dark fw-bold"><i class="bx bx-user-circle me-2"></i> Manajemen User</h4>
        <a href="?page=add-user" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <i class="bx bx-plus"></i> Tambah User
        </a>
    </div>

    <div class="card-body p-4">
        <?php include 'admin/controller/alert-data-crud.php'; ?>

        <div class="table-responsive">
            <table class="table align-middle table-hover text-nowrap">
                <thead class="table-light">
                    <tr class="align-middle text-center">
                        <th style="width:5%;">#</th>
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
                                <span class="badge bg-secondary px-3 py-1">
                                    <?= $rowData['level_name'] ?? '-' ?>
                                </span>
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
</div>
