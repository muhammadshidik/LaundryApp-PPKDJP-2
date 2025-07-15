<?php

$connection = mysqli_connect("localhost", "root", "", "db_laundri");

if (!$connection) {
    echo "Unable to connect";
    die;
}
