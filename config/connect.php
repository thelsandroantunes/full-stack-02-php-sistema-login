<?php
/* local, produção */
session_start();
/** Requiremento PHPMailer */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

/** Modo -> LOCAL, PRODUÇÃO */
$modo = 'local';

if($modo == 'local'){
  $servidor = "localhost";
  $usuario = "root";
  $senha = "";
  $banco = "login";
}
if($modo == 'producao'){
  $servidor = "";
  $usuario = "";
  $senha = "";
  $banco = "";
}

try{
  $pdo = new PDO("mysql:host=$servidor;dbname=$banco", $usuario, $senha);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo '<script>console.log("Banco conectado com sucesso!")</script>';
}catch(PDOException $error){
  echo "Falha ao se conectar com o banco:".$error->getMessage();
}

/** Função limpa formulário */
function cleanPost($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

/** Função para autenticação */
function auth($tokenSession){
  global $pdo;  
  /** Verificar se tem autorização */
  $sql = $pdo->prepare("SELECT * FROM usuarios WHERE token=? LIMIT 1");
  $sql->execute(array($tokenSession));
  $user = $sql->fetch(PDO::FETCH_ASSOC);
  
  /** Caso não encontre o usuário */
  if(!$user){
    //print 'USUÁRIO-TOKEN: false';
    return false;
  }else{
    //print 'USUÁRIO-TOKEN: ok';
    return $user;
  }
}