<?php if (isset($_GET['edit']) && $_GET['edit'] == 'success'): ?>
<div class="alert alert-success alert-dismissible" role="alert">
    Your data has been EDITED successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php elseif (isset($_GET['delete']) && $_GET['delete'] == 'success'): ?>
<div class="alert alert-success alert-dismissible" role="alert">
    Your data has been DELETED successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php elseif (isset($_GET['add']) && $_GET['add'] == 'success'): ?>
<div class="alert alert-success alert-dismissible" role="alert">
    Your data has been ADDED successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php elseif (isset($_GET['pickup']) && $_GET['pickup'] == 'success'): ?>
<div class="alert alert-success alert-dismissible" role="alert">
    Your order has been PICKED UP successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif ?>