<?php 

    session_start();

    // Reset de la variable de session "user"
    $_SESSION['user'] = null;
    // Redirection vers la page d'accueil
    header("Location: index.php");

?>