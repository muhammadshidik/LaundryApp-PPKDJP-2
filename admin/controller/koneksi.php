<?php

$connection = mysqli_connect("localhost", "root", "", "db_laudry");

if (!$connection) {
    echo "Unable to connect";
    die;
}
