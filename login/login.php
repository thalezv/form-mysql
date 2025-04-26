<?php

  session_start();

$login = $_POST["nome"];
$senha = $_POST["senha"];
$connect = mysqli_connect("127.0.0.1","root","root", "cadastro_test");

if (!$connect) {
  die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}else{
  if (isset($_POST["entrar"])) {

    $verifica = mysqli_prepare($connect, "SELECT Senha FROM Usuarios WHERE Nome = ?");
    mysqli_stmt_bind_param($verifica, "s", $login);
    mysqli_stmt_execute($verifica);
    mysqli_stmt_bind_result($verifica, $senha_usuario);
    
    if (!mysqli_stmt_fetch($verifica) || !password_verify($senha, $senha_usuario)) {
      echo "<script language='javascript' type='text/javascript'>
      alert('Login e/ou senha incorretos');window.location.href='../login/';
      </script>";
      exit;
    } else {
      $_SESSION['usuario'] = $login;
      header("Location: ../");
      exit;
    }
  }
}

mysqli_stmt_close($verifica);
mysqli_close($connect);

?>