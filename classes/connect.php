<?php
$servername = "localhost";
$username = "id17823253_root";
$password = "Ishaan@website18102000";
$database = "id17823253_localhost";

// Create connection
$db = new mysqli($servername, $username, $password, $database);

// Check connection
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}
?>