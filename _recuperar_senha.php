<?php

include_once 'config/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './lib/vendor/autoload.php';
$mail = new PHPMailer(true);

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

    <title>Nerkodex - Recuperar Senha</title>
</head>
<body>

    <!-- RECUPERAR SENHA -->
    <form method="POST">
        
        <div class="linha"></div>
        <h1><a href="#" class="logo"><!--Nerkodex--> <img src="image/_logo.png" class="image_logo"></a></h1>
        

        <?php
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (!empty($dados['SendRecupSenha'])) {
        //var_dump($dados);
        $query_email = "SELECT id, nome, email 
                    FROM usuarios 
                    WHERE email =:email 
                    LIMIT 1";
        $result_email = $conn->prepare($query_email);
        $result_email->bindParam(':email', $dados['email'], PDO::PARAM_STR);
        $result_email->execute();

        if (($result_email) and ($result_email->rowCount() != 0)) {
            $row_email = $result_email->fetch(PDO::FETCH_ASSOC);
            $chave_recuperar_senha = password_hash($row_email['id'], PASSWORD_DEFAULT);
            //echo "Chave $chave_recuperar_senha <br>";

            $query_up_email = "UPDATE usuarios 
                        SET recuperar_senha =:recuperar_senha 
                        WHERE id =:id 
                        LIMIT 1";
            $result_up_email = $conn->prepare($query_up_email);
            $result_up_email->bindParam(':recuperar_senha', $chave_recuperar_senha, PDO::PARAM_STR);
            $result_up_email->bindParam(':id', $row_email['id'], PDO::PARAM_INT);

            if ($result_up_email->execute()) {
                $link = "http://localhost/_login/_atualizar_senha.php?chave=$chave_recuperar_senha";

                try {
                    /*$mail->SMTPDebug = SMTP::DEBUG_SERVER;*/
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();
                    $mail->Host       = 'sandbox.smtp.mailtrap.io';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = '';
                    $mail->Password   = '';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 2525;

                    $mail->setFrom('movie_flix@atendimento.com', 'Atendimento');
                    $mail->addAddress($row_email['email'], $row_email['nome']);

                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Recuperar senha';
                    $mail->Body    = 'Prezado(a) ' . $row_email['nome'] .".<br><br>Você solicitou uma alteração de senha.<br><br>Para continuar o processo de recuperação de sua senha, clique no link abaixo ou cole o endereço no seu navegador: <br><br><a href='" . $link . "'>" . $link . "</a><br><br>Se você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma até que você ative este código.<br><br>";
                    $mail->AltBody = 'Prezado(a) ' . $row_email['nome'] ."\n\nVocê solicitou alteração de senha.\n\nPara continuar o processo de recuperação de sua senha, clique no link abaixo ou cole o endereço no seu navegador: \n\n" . $link . "\n\nSe você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma até que você ative este código.\n\n";

                    $mail->send();

                    $_SESSION['msg'] = "<p class='sucesso animate__animated animate__rubberBand'>E-mail enviado com sucesso!</p>";
                    header("Location: index.php");
                } catch (Exception $e) {
                    echo "Erro: E-mail não enviado sucesso. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                echo  "<p class='erro'>Erro: Tente novamente!</p>";
            }
        } else {
            echo "<p class='erro'>Erro: Usuário não encontrado!</p>";
        }
    }

    if (isset($_SESSION['msg_rec'])) {
        echo $_SESSION['msg_rec'];
        unset($_SESSION['msg_rec']);
    }

    ?>

<?php
        $email = "";
        if (isset($dados['email'])) {
            $email = $dados['email'];
        } ?>

        <div class="input-group">
            <!-- EMAIL -->
            <input type="email" name="email" placeholder="E-mail" value="<?php echo $email; ?>"/>
            <!-- EMAIL -->

            <!-- CHECK BOX / TERMOS POLÍTICA -->
            <input type="checkbox" name="checkbox" id="checkbox" checked/>
            <label for="checkbox">Ao recuperar a senha você concorda com nossos <a href="#" class="underline">Termos</a> e <a href="#" class="underline">Política de Privacidade.</a></label>
            <!-- CHECK BOX / TERMOS POLÍTICA -->

            <input type="submit" name="SendRecupSenha" value="RECUPERAR" class="input-btn">
        </div>

        <p>Lembrou sua senha?<a href="index.php"> Login!</a><br></p>

    </form>
    <!-- RECUPERAR SENHA -->

    <!-- API LANG -->
    <div class="gtranslate_wrapper"></div>
    <!-- API LANG -->

</body>
</html>
