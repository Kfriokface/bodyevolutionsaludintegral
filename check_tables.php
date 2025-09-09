<?php
require 'wp-config.env.php';
$cn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (!$cn) { die("Conexión fallida\n"); }
$res = mysqli_query($cn, "SHOW TABLES LIKE '%\\_options'");
while ($row = mysqli_fetch_row($res)) {
  echo $row[0], PHP_EOL;
}
