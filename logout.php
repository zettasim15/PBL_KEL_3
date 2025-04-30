<?php
// Mulai sesi untuk menghancurkan sesi yang ada
session_start();

// Hancurkan semua sesi
session_unset(); // Menghapus semua data sesi
session_destroy(); // Menghancurkan sesi

// Redirect ke halaman login setelah logout
header("Location:lp.html");
exit();
?>
