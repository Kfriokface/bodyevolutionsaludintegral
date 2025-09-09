<?php
require 'wp-config.env.php';
$cn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (!$cn) { die("Conexión fallida\n"); }
$prefix = 'wp_'; // <-- cámbialo si tu tabla fue p.ej. wpx1_options
$q = "SELECT option_name, option_value FROM {$prefix}options WHERE option_name IN ('siteurl','home')";
$res = mysqli_query($cn, $q);
while ($row = mysqli_fetch_assoc($res)) {
  echo $row['option_name'], " = ", $row['option_value'], PHP_EOL;
}
