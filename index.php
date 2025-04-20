<?php include_once 'config/config.php'; ?>
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
     
    <title>Nerkodex - Login</title>
</head>
<body>

    <!-- LOGIN -->
    <form  action="" method="POST">
        
        <div class="linha"></div>
        <h1><a href="#" class="logo"><!--Nerkodex--> <img src="image/_logo.png" class="image_logo"></a></h1>

        <!-- AVISO -->
        <?php if (isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
                    <div class="sucesso animate__animated animate__rubberBand">
                    Cadastrado com sucesso!
                </div>               
        <?php }?>
        <!-- AVISO -->


        <?php
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($dados['SendLogin'])) {
            //var_dump($dados);
            $query_email = "SELECT id, nome, sobrenome, email, senha_usuario 
                            FROM usuarios 
                            WHERE email =:email  
                            LIMIT 1";
            $result_email = $conn->prepare($query_email);
            $result_email->bindParam(':email', $dados['email'], PDO::PARAM_STR);
            $result_email->execute();

        if(($result_email) AND ($result_email->rowCount() != 0)){
            $row_email = $result_email->fetch(PDO::FETCH_ASSOC);
            //var_dump($row_email);
            if(password_verify($dados['senha_usuario'], $row_email['senha_usuario'])){
                $_SESSION['id'] = $row_email['id'];
                $_SESSION['nome'] = $row_email['nome'];
                $_SESSION['sobrenome'] = $row_email['sobrenome'];
                $_SESSION['email'] = $row_email['email'];
                header("Location: _painel.php");
                }
            else{
                $_SESSION['msg'] = "<p class='erro'>Erro: Usuário ou senha inválida!</p>";
                }
        }else{
            $_SESSION['msg'] = "<p class='erro'>Erro: Usuário ou senha inválida!</p>";
        }

        
        }

    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

        <div class="input-group">

            <!-- EMAIL -->
            <input type="email" name="email" placeholder="E-mail" autocomplete="off"/>
            <!-- EMAIL -->

            <!-- SENHA -->
            <input type="password" name="senha_usuario" placeholder="Senha"/>
            <!-- SENHA -->

            <input type="submit" name="SendLogin" value="ENTRAR" class="input-btn">

        </div>

        <p>Não tem uma conta?<a href="_cadastro.php"> Inscrever-se!</a><br></p>
        <a href="_recuperar_senha.php"> Recuperar senha!</a><br></p>

    </form>
    <!-- LOGIN -->

    <!-- API LANG -->
        <div class="gtranslate_wrapper"></div>
    <!-- API LANG -->

    
    <?php if (isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
    <script>setTimeout(() => {$('.sucesso').hide();}, 3000);</script>
    <?php }?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
</html>