<?php

$connection = mysqli_connect("localhost", "root", "", "db_laundry_ujikom");

if (!$connection) {
    echo "Unable to connect";
    die;
}
