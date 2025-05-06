<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'lib/vendor/autoload.php';
    include_once 'config/config.php';

    //VERIFICAR SE A POSTAGEM EXISTE DE ACORDO COM OS CAMPOS
    if(isset($_POST['nome']) && isset($_POST['sobrenome']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){
        //VERIFICAR SE TODOS OS CAMPOS FORAM PREENCHIDOS
        if(empty($_POST['nome']) or empty($_POST['sobrenome']) or empty($_POST['email']) or empty($_POST['senha']) or empty($_POST['repete_senha']) or empty($_POST['termos'])){
            $erro_geral = "Todos os campos são obrigatórios!";
        }else{
            //RECEBER VALORES VINDOS DO POST E LIMPAR
            $nome = ($_POST['nome']);
            $sobrenome = ($_POST['sobrenome']);
            $email =($_POST['email']);
            $senha = ($_POST['senha']);
            $senha_cript = password_hash($senha, PASSWORD_DEFAULT);
            $repete_senha = ($_POST['repete_senha']);
            $checkbox = ($_POST['termos']);
            $nivel_id = '1';
            $recuperar_senha = 'NULL';
            $status = 'Ativo';

            //VERIFICAR SE O NOME É APENAS LETRAS E ESPAÇOS
            if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
                $erro_nome = "Somente permitido letras e espaços em branco!";
            }

            //VERIFICAR SE O SOBRENOME É APENAS LETRAS E ESPAÇOS
            if (!preg_match("/^[a-zA-Z-' ]*$/",$sobrenome)) {
                $erro_sobrenome = "Somente permitido letras e espaços em branco!";
            }

            //VERIFICAR SE O EMAIL É VÁLIDO
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erro_email = "Formato de e-mail inválido!";
            }

            //VERIFICAR SE A SENHA TEM MAIS DE 8 DÍGITOS
            if(strlen($senha) < 8 ){
                $erro_senha = "Senha deve ter 8 caracteres ou mais!";
            }

            //VERIFICAR SE O RETEPE SENHA É IGUAL A SENHA
            if($senha !== $repete_senha){
                $erro_repete_senha = "Senha e repetição estão diferentes!";
            }

            //VERIFICAR SE O CHECKBOX FOI MARCADO
            if($checkbox !== "ok"){
                $erro_checkbox = "Desativado";
            }

            if(!isset($erro_geral) && !isset($erro_nome) && !isset($erro_sobrenome) && !isset($erro_email) && !isset($erro_senha) && !isset($erro_repete_senha) && !isset($erro_checkbox)){
                //VERIFICAR SE ESTE EMAIL JÁ ESTÁ CADASTRADO NO BANCO
                $sql = $conn->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");
                $sql->execute(array($email));
                $user = $sql->fetch();
                //SE NÃO EXISTIR O USUARIO - ADICIONAR NO BANCO
                if(!$user){
                    $data_cadastro = date('d/m/Y');
                    $sql = $conn->prepare("INSERT INTO usuarios VALUES (null,?,?,?,?,?,?,?,?,?)");
                    if($sql->execute(array($nome,$sobrenome,$email,$senha_cript,$checkbox,$data_cadastro,$nivel_id,$recuperar_senha,$status))){
                        
                        //CONEXÃO 
                        if($modo =="local"){
                            header('location: index.php?result=ok');
                        }

                    }
                }else{
                    //JÁ EXISTE USUARIO APRESENTAR ERRO
                    $erro_geral = "Email já cadastrado!";
                }
            }
        }
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

    <title>mumuz3ra - Cadastro</title>
</head>
<body> 

    <!-- CADASTRO -->
    <form method="POST">
        <div class="linha"></div>
        <h1><a href="#" class="logo"><!--Nerkodex--> <img src="image/_logo.png" class="image_logo"></a></h1>

        <!-- AVISO -->
        <?php if(isset($erro_geral)){ ?>
            <div class="erro-geral animate__animated animate__rubberBand">
            <?php  echo $erro_geral; ?>
            </div>
        <?php } ?>
        <!-- AVISO -->

        <div class="input-group">
            
            <!-- NOME -->
            <input <?php if(isset($erro_geral) or isset($erro_nome)){echo 'class="erro-input"';}?> type="text" name="nome" placeholder="Nome" autocomplete="off" <?php if(isset($_POST['nome'])){ echo "value='".$_POST['nome']."'";}?>>
            <?php if(isset($erro_nome)){ ?>
            <div class="erro"><?php echo $erro_nome; ?></div>
            <?php } ?> 
            <!-- NOME -->

            <!-- SOBRENOME -->
            <input <?php if(isset($erro_geral) or isset($erro_sobrenome)){echo 'class="erro-input"';}?> type="text" name="sobrenome" placeholder="Sobrenome" autocomplete="off" <?php if(isset($_POST['sobrenome'])){ echo "value='".$_POST['sobrenome']."'";}?>>
            <?php if(isset($erro_sobrenome)){ ?>
            <div class="erro"><?php echo $erro_sobrenome; ?></div>
            <?php } ?> 
            <!-- SOBRENOME -->

            <!-- EMAIL -->
            <input <?php if(isset($erro_geral) or isset($erro_email)){echo 'class="erro-input"';}?> type="email" name="email" placeholder="E-mail" autocomplete="off" <?php if(isset($_POST['email'])){ echo "value='".$_POST['email']."'";}?>>
            <?php if(isset($erro_email)){ ?>
            <div class="erro"><?php echo $erro_email; ?></div>
            <?php } ?> 
            <!-- EMAIL -->   

            <!-- SENHA -->
            <input <?php if(isset($erro_geral) or isset($erro_senha)){echo 'class="erro-input"';}?> type="password" name="senha" placeholder="Senha mínimo 8 Dígitos" <?php if(isset($_POST['senha'])){ echo "value='".$_POST['senha']."'";}?>>
            <?php if(isset($erro_senha)){ ?>
            <div class="erro"><?php echo $erro_senha; ?></div>
            <?php } ?>
            <!-- SENHA -->

            <!-- SENHA REPETE-->
            <input <?php if(isset($erro_geral) or isset($erro_repete_senha)){echo 'class="erro-input"';}?> type="password" name="repete_senha" placeholder="Repita a senha criada" <?php if(isset($_POST['repete_senha'])){ echo "value='".$_POST['repete_senha']."'";}?>>
            <?php if(isset($erro_repete_senha)){ ?>
            <div class="erro"><?php echo $erro_repete_senha; ?></div>
            <?php } ?>
            <!-- SENHA REPETE-->

            <!-- CHECK BOX / TERMOS POLÍTICA -->
            <div <?php if(isset($erro_geral) or isset($erro_checkbox)){echo 'class="input-group erro-input"';}else{echo 'class="input-group"';}?>>
                <input type="checkbox" id="termos" name="termos" value="ok"/>
                <label for="termos">Ao se inscrever você concorda com nossos <a href="#" class="underline">Termos</a> e a nossa <a href="#" class="underline">Política de Privacidade.</a></label>
            </div>
            <!-- CHECK BOX / TERMOS POLÍTICA -->
            
            <input type="submit" value="ENVIAR" class="input-btn">
            
        </div>  

        <p>já tem uma conta?<a href="index.php"> Entrar!</a></p>

    </form>
    <!-- CADASTRO -->

    <!-- API LANG -->
    <div class="gtranslate_wrapper"></div>
    <!-- API LANG -->

</body>
</html>
