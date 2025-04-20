<?php
	include_once 'config/config.php';
	include_once 'classes/start.php';

    $id = $_SESSION['id'];

    if(!isset($_SESSION['id'])){
        header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/message.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="shortcut icon" href="icon/_icon.png" type="image/png">

    <!-- API LANG -->
    <script>window.gtranslateSettings = {"default_language":"pt","languages":["pt","en","ja"],"wrapper_selector":".gtranslate_wrapper","horizontal_position":"right","vertical_position":"bottom","flag_style":"3d","alt_flags":{"en":"usa","pt":"brazil"}}</script>
    <script src="https://cdn.gtranslate.net/widgets/latest/flags.js" defer></script>
    <!-- API LANG -->

    <title>Nerkodex - Painel</title>
</head>
<body> 

    <div>
        <p>Seja bem vindo <?=$_SESSION['nome'];?>!</p>
        
        <br>

        <h1 class="input-btn"><a href="classes/sair.php">Sair</a></h1>
    </div>

    <!-- API LANG -->
    <div class="gtranslate_wrapper"></div>
    <!-- API LANG -->

</body>
</html>