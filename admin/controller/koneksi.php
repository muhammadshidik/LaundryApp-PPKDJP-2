<?php

$connection = mysqli_connect("localhost", "root", "", "db_laundry");

if (!$connection) {
    echo "Unable to connect";
    die;
}
