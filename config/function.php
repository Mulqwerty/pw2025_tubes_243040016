<?php
$db = mysqli_connect('localhost', 'root', '', 'galeri_foto');

function select($query)
{
    global $db;
    $result = mysqli_query($db, $query);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function register($data)
{
  global $db;
  $Username = $data["Username"];
  $Password = $data["Password"];
  $Email = $data["Email"];
  $NamaLengkap = $data["NamaLengkap"];
  $Alamat = $data["Alamat"];


  // Query insert data
  $query = "INSERT INTO user VALUES (null, '$Username', '$Password', '$Email', '$NamaLengkap')";
  mysqli_query($db, $query);

  return mysqli_affected_rows($db);
}
