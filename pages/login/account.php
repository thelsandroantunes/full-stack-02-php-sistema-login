<?php 
  require('../../config/connect.php');

  /* Verificar a postagem de acordo com os campos do formulário*/
  if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['cpassword'])){
    /* Verficar o preenchimento de todos os campos */
    if (empty($_POST['username']) or empty($_POST['email']) or empty($_POST['password']) or empty($_POST['cpassword']) or empty($_POST['termos'])) {
      $error_all = "Todos os campos são obrigatórios!";
    }else{
      //Receber valores do Formulário e limpar
      $username = cleanPost($_POST['username']);
      $email =cleanPost($_POST['email']);
      $password = cleanPost($_POST['password']);
      $password_cript = sha1($password);
      $cpassword = cleanPost($_POST['cpassword']);
      $checkbox = cleanPost($_POST['termos']);    

      /* Verificar Nome Válido */
      if (!preg_match("/^[a-zA-Z-' ]*$/",$username)) {
        $error_name = "Permitido apenas letras e espaços em branco!";
      }
      /* Verificar Email Válido */
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_email = "Formato de email inválido!";
      }
      /* Verificar Senha Válido com mais de 6 dígitos */
      if(strlen($password) < 6 ){
        $error_password = "Senha deve ter 6 caracteres ou mais!";
      }
      /* Verificar Senha Igual anterior */
      if($password !== $cpassword){
        $error_cpassword = "Senha e repetição de senha diferentes!";
      }
      /* Verificar Checkbox marcado */      
      if($checkbox!=="ok"){
        $error_checkbox = "Desativado";
      }

      if (!isset($error_username) && !isset($error_email) && !isset($error_password) && !isset($error_cpassword) && !isset($error_checkbox)) {
        //Verficiar se email está cadastrado
        $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");
        $sql->execute(array($email));
        $usuario = $sql->fetch();
      }
      /* Se não existir usuário fazer cadastro */
      if(!$usuario){
        $recupera_senha="";
        $token="";
        $status = "confirmado"; //NOVO somente em produção
        $data_cadastro = date('d/m/Y H:i:s');
        $sql = $pdo->prepare("INSERT INTO usuarios VALUES (null,?,?,?,?,?,?,?)");
        if($sql->execute(array($username,$email,$password_cript,$recupera_senha,$token,$status, $data_cadastro))){
          /** Modo LOCALHOST */
          if ($modo == 'local') {
            header('location: ../../index.php?result=ok');
          }
          /** Modo em PRODUçÃO */
          if ($modo == "producao") {
            /** Enviar e-mail para usuário */

          }
        }
      }else{
          //Error se já for cadastrado
          $error_all = "Usuário já cadastrado";
      }

    }
  }
?>

<!DOCTYPE html>
<html lang="pt-br">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ===== CSS Start ===== -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/media.css">
    <link rel="stylesheet" href="../../assets/css/notify.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
      integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> 

    <!-- ===== CSS End ===== -->
    <title>Cadastrar</title>
  </head>

  <body>
    <div id="container">
      <!-- ===== Banner Info Start ===== -->
      <div class="banner">
        <img src="../../assets/img/login_2.png" alt="imagem-login" width="300" height="300">
        <p style="color: #fff;">
          Seja bem vindo, acesse e aproveite todo o conteúdo,
          <br>somos uma equipe de profissionais empenhados em
          <br>trazer o melhor conteúdo direcionado a você usuário.
        </p>
      </div>
      <!-- ===== Banner Info End ===== -->
      <!-- ===== Formulário de Cadastro Start ===== -->
      <form method="post">
        <div class="box-login">
          <div class="box-account">
            <a href="../../index.php">
              <img src="../../assets/img/user_3.png" alt="icone-usuário" title="fazer-login" width="120" height="120">
            </a>
            <h2>informe seus dados</h2>
            
            <?php if(isset($error_all)){ ?>
              <div class="erro-geral animate__animated animate__rubberBand">
              <?php  echo $error_all; ?>
              </div>
            <?php } ?>

            <div class="input-icons">
              <i class="fa fa-user icon"></i>
              <input <?php if(isset($error_all) or isset($error_username)){echo 'class="erro-input"';} ?> type="text" name="username" id="username" placeholder="Nome Completo" <?php if(isset($_POST['username'])){ echo "value='".$_POST['username']."'";}?> required>
              <?php if(isset($error_name)){ ?>
              <div class="erro"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
            <div class="input-icons">
              <i class="fa fa-envelope icon"></i>
              <input <?php if(isset($error_all) or isset($error_email)){echo 'class="erro-input"';} ?> type="email" name="email" id="email" placeholder="Seu melhor e-mail" <?php if(isset($_POST['email'])){ echo "value='".$_POST['email']."'";}?> required>
              <?php if(isset($error_email)){ ?>
              <div class="erro"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
            <div class="input-icons">
              <i class="fa fa-key icon"></i>
              <input <?php if(isset($error_all) or isset($error_password)){echo 'class="erro-input"';} ?> type="password" name="password" id="password" placeholder="Senha" <?php if(isset($_POST['password'])){ echo "value='".$_POST['password']."'";}?> required >
              <i id="visibilityBtn_1"><span id="icon-1" class="show_password material-symbols-outlined">visibility</span></i>
              <?php if(isset($error_password)){ ?>
              <div class="erro"><?php echo $error_password; ?></div>
              <?php } ?>
            </div>
            <div class="input-icons">
              <i class="fa fa-key icon"></i>              
              <input <?php if(isset($error_all) or isset($error_cpassword)){echo 'class="erro-input"';} ?> type="password" name="cpassword" id="cpassword" placeholder="Confirmar a Senha" <?php if(isset($_POST['cpassword'])){ echo "value='".$_POST['cpassword']."'";}?> required>
              <i id="visibilityBtn_2"><span id="icon-2" class="show_password material-symbols-outlined">visibility</span></i>
              
              <?php if(isset($error_cpassword)){ ?>
              <div class="erro"><?php echo $error_cpassword; ?></div>
              <?php } ?>
            </div>
            <div <?php if(isset($error_all) or isset($error_checkbox)){echo 'class="check erro-input"';}else{echo 'class="check"';} ?> >
              <input type="checkbox" name="termos" id="termos" value="ok" required>
              <label for="termos" class="texto_link">
                Ao se cadastrar você concorda com a nossa
                <a class="link" id="link_1" href="#">Política de Privacidade</a>
                e os
                <a class="link" id="link_2" href="#">Termos de uso</a>
              </label>
              <?php if(isset($error_checkbox)){ ?>
              <div class="erro"><?php echo $error_checkbox; ?></div>
              <?php } ?>
            </div>

            
            <button type="submit">Criar conta</button>
            
            <a href="../../index.php"><p>Já tenho uma conta</p></a>

          </div>
        </div>
      </form>
      <!-- ===== Formulário de Cadastro End ===== -->
    </div>

    <!-- ===== JS Start ===== -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="../../assets/js/account.js"></script>
    <!-- ===== JS End ===== -->
  </body>
</html>