<?php
    session_start();
    ob_start();
    unset($_SESSION['id'], $_SESSION['email']);
    $_SESSION['msg'] = "<p class='sucessoput'>Sucesso ao sair do sistema!</p>";

    header("Location: ../index.php");
?>