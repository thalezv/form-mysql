<?php

if (empty($_POST["nome"]) || empty($_POST["email"]) || empty($_POST["senha"])) {
  echo "<script>alert('Todos os campos devem ser preenchidos'); window.location.href='../cadastro/';</script>";
  exit;
}

$login = $_POST["nome"];
$email = $_POST["email"];
$senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
$connect = mysqli_connect("127.0.0.1","root","root","cadastro_test");

if (!$connect) {
  die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}
else{
  $select = mysqli_prepare($connect, "SELECT Nome FROM Usuarios WHERE Nome = ? ");
  mysqli_stmt_bind_param($select, "s", $login);
  mysqli_stmt_execute($select);
  $result = mysqli_stmt_get_result($select);

  if(mysqli_num_rows($result) > 0){
    echo"<script language='javascript' type='text/javascript'>
    alert('Esse login já existe'); window.location.href='../cadastro/';</script>";

  }else{
    $insert = mysqli_prepare($connect, "INSERT INTO Usuarios (Nome,Senha) VALUES (?, ?)");
    mysqli_stmt_bind_param($insert, "ss", $login, $senha);
    mysqli_stmt_execute($insert);
    $check_email = mysqli_prepare($connect, "SELECT ID_Email FROM Email WHERE Email = ?");
    mysqli_stmt_bind_param($check_email, "s", $email);
    mysqli_stmt_execute($check_email);
    $result_email = mysqli_stmt_get_result($check_email);
    $row_email = mysqli_fetch_assoc($result_email);
    
    if ($row_email) {
        $email_id = $row_email['ID_Email'];
    } else {
        $insert2 = mysqli_prepare($connect, "INSERT INTO Email (Email) VALUES (?)");
        mysqli_stmt_bind_param($insert2, "s", $email);
        mysqli_stmt_execute($insert2);
        $email_id = mysqli_insert_id($connect);
    }

    if ($insert && isset($email_id)){
      $update = mysqli_prepare($connect, "UPDATE Usuarios SET ID_Email = ? WHERE Nome = ?");
      mysqli_stmt_bind_param($update, "is", $email_id, $login);
      mysqli_stmt_execute($update);
      echo"<script language='javascript' type='text/javascript'>
      alert('Usuário cadastrado com sucesso!');window.location.
      href='../login/'</script>";
    }else{
      echo"<script language='javascript' type='text/javascript'>
      alert('Não foi possível cadastrar esse usuário');window.location
      .href='../cadastro/'</script>";
    }
  }
}

mysqli_close($connect);

?>