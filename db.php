<?php
$conn = mysqli_connect("localhost", "root", "", "nadara");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>