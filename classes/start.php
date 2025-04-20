<?php
    ob_start();

    if((!isset($_SESSION['id'])) AND (!isset($_SESSION['email']))){
        $_SESSION['msg'] = "<p class='erro'>Erro: Necessário realizar o login!</p>";
        header("Location: ../index.php");
    }
?>