<?php
session_start();
session_destroy();
echo "<script>
            alert('Logout Berhasil!');
            location.href = '../admin/admin.php';
          </script>";
?>
