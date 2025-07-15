<?php if (isset($_GET['edit']) && $_GET['edit'] == 'success'): ?>
<div class="alert alert-success alert-dismissible" role="alert">
    Perubahan berhasil disimpan.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php elseif (isset($_GET['delete']) && $_GET['delete'] == 'success'): ?>
<div class="alert alert-success alert-dismissible" role="alert">
    Data Berhasil Dihapus !.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php elseif (isset($_GET['add']) && $_GET['add'] == 'success'): ?>
<div class="alert alert-success alert-dismissible" role="alert">
   Data berhasil disimpan.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php elseif (isset($_GET['pickup']) && $_GET['pickup'] == 'success'): ?>
<div class="alert alert-success alert-dismissible" role="alert">
    Pesanan Anda telah berhasil diambil.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif ?>