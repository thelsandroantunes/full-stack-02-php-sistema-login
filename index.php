<?php
  require('./config/connect.php');

 /* session_start();
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		{
			echo '<script>console.log("refresh")</script>';
		}
		else
		{
			$_SESSION['last_request']  = $request;
      echo '<script>console.log("post")</script>';
			
		}
	}*/

  if (isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    /* Receber dados do Formulário e limpar */
    $email = cleanPost($_POST['email']);
    $password = cleanPost($_POST['password']);
    $password_cript = sha1($password);
    
    /* Verficar se usuário existe */
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? AND senha=? LIMIT 1");
    $sql->execute(array($email,$password_cript));
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      /* Usuário existe */
      /** Verificar Confirmação de Cadastro */
      if($user['status']=="confirmado"){
        /* Criar Token */        
        $token = sha1(uniqid().date('d-m-Y-H-i-s'));

        /* Atualizar Token deste usuário */
        $sql = $pdo->prepare("UPDATE usuarios SET token=? WHERE email=? AND senha=?");
        if ($sql->execute(array($token, $email, $password_cript))) {
          /* Armazemar o Token na Sessão */
          $_SESSION['TOKEN'] = $token;
          $path = './pages/login/restricted.php';
          header("location: $path");
        }
      }else{
        $error_login = "Por favor confirme seu cadastro no seu e-mail informado!";
      }  
    }else{
      $error_login = "Usuário e/ou Senha Incorretos!";
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
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/media.css">
  <link rel="stylesheet" href="./assets/css/notify.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
    integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> 
  <!-- ===== CSS End ===== -->
  <title>Login</title>
</head>

<body>
  <div id="container">
    <div class="banner">
      <img src="./assets/img/login_3.png" alt="imagem-login" width="300" height="300">
      <p style="color: #fff; font-weight: 400;">
        Seja bem vindo, acesse e aproveite todo o conteúdo,
        <br>somos uma equipe de profissionais empenhados em
        <br>trazer o melhor conteúdo direcionado a você, usuário.
      </p>
    </div>
    <!-- ===== Formulário Login Start ===== -->
    <form method="post">    
      <div class="box-login">
        <h1>
          Olá!<br>
          Seja bem vindo ao <br>DESAFIO RJ TECH.
        </h1>
        
        <?php if (isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
                <div class="sucesso animate__animated animate__rubberBand">
                Cadastrado com sucesso!
              </div>               
        <?php }?>

        <?php if(isset($error_login)){ ?>
          <div style="text-align: center;" class="erro-geral animate__animated animate__rubberBand">
          <?php  echo $error_login; ?>
          </div>
        <?php } ?>
        
        <div class="box">
          <h2>faça o seu login agora</h2>        
          <div class="input-icons">
            <i class="fa fa-user icon"></i>
            <input type="email" name="email" id="email" placeholder="exemplo@email.com" required>
          </div>
          <div class="input-icons">
            <i class="fa fa-key icon"></i>
            <input type="password" name="password" id="password" placeholder="Digite sua senha..." required>
            <i id="visibilityBtn"><span id="icon-0" class="show_password material-symbols-outlined">visibility</span></i>
          </div>

          <a href="#">
            <p>Esqueceu a sua senha?</p>
          </a>

          <button type="submit">Fazer Login</button>

          <a href="./pages/login/account.php">
            <p>Criar uma conta</p>
          </a>
        </div>        
      </div>
    </form>
    <!-- ===== Formulário Login End ===== -->
  </div>
  
  <!-- ===== JS Start ===== -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="./assets/js/index.js"></script>
  <!-- ===== JS End ===== -->

  <?php if (isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
    <script>
    setTimeout(() => {
           $('.sucesso').hide();            
     }, 3000);
    </script>
   <?php }?> 
</body>

</html>