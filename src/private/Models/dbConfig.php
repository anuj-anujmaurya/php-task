<?php
$conn = mysqli_connect("mysql-server", "root", "secret", "contact");
if(!$conn) {
    die("connection to db was unsuccessful");
}
