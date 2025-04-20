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

    <title>Nerkodex - Atualizar Senha</title>
</head>
<body>

    <!-- ATUALIZAR SENHA -->
    <form method="POST" action="">
        <div class="linha"></div>
        <h1><a href="#" class="logo"><!--Nerkodex--> <img src="image/_logo.png" class="image_logo"></a></h1>
        

        <?php
    $chave = filter_input(INPUT_GET, 'chave', FILTER_DEFAULT);


    if (!empty($chave)) {
        //var_dump($chave);

        $query_email = "SELECT id 
                            FROM usuarios 
                            WHERE recuperar_senha =:recuperar_senha  
                            LIMIT 1";
        $result_email = $conn->prepare($query_email);
        $result_email->bindParam(':recuperar_senha', $chave, PDO::PARAM_STR);
        $result_email->execute();

        if (($result_email) and ($result_email->rowCount() != 0)) {
            $row_email = $result_email->fetch(PDO::FETCH_ASSOC);
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //var_dump($dados);
            if (!empty($dados['SendNovaSenha'])) {
                $senha_usuario = password_hash($dados['senha_usuario'], PASSWORD_DEFAULT);
                $recuperar_senha = 'NULL';

                $query_up_email = "UPDATE usuarios 
                        SET senha_usuario =:senha_usuario,
                        recuperar_senha =:recuperar_senha
                        WHERE id =:id 
                        LIMIT 1";
                $result_up_email = $conn->prepare($query_up_email);
                $result_up_email->bindParam(':senha_usuario', $senha_usuario, PDO::PARAM_STR);
                $result_up_email->bindParam(':recuperar_senha', $recuperar_senha);
                $result_up_email->bindParam(':id', $row_email['id'], PDO::PARAM_INT);

                if ($result_up_email->execute()) {
                    $_SESSION['msg'] = "<p class='sucesso animate__animated animate__rubberBand'>Senha atualizada com sucesso!</p>";
                    header("Location: index.php");
                } else {
                    echo "<p style='color: #ff0000'>Erro: Tente novamente!</p>";
                }
            }
        } else {
            $_SESSION['msg_rec'] = "<p style='color: #ff0000'>Erro: Link inválido, solicite novo link para atualizar a senha!</p>";
            header("Location: _recuperar_senha.php");
        }
    } else {
        $_SESSION['msg_rec'] = "<p style='color: #ff0000'>Erro: Link inválido, solicite novo link para atualizar a senha!</p>";
        header("Location: _recuperar_senha.php");
    }

    ?>

        <?php
        $email = "";
        if (isset($dados['senha_usuario'])) {
            $email = $dados['senha_usuario'];
        } ?>


        <div class="input-group">
            <!-- SENHA -->
            <input type="password" name="senha_usuario" placeholder="Nova senha" value="<?php echo $email; ?>"/>  
            <!-- SENHA -->

            <input type="submit" name="SendNovaSenha" value="ATUALIZAR" class="input-btn">
        </div>

        <p>Lembrou sua senha?<a href="index.php"> Login!</a><br></p>

    </form>
    <!-- ATUALIZAR SENHA -->

    <!-- API LANG -->
    <div class="gtranslate_wrapper"></div>
    <!-- API LANG -->

</body>
</html>