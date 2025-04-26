<?php
    session_start();
    $email = '';
    if (isset ($_SESSION['usuario'])){
      $connect = mysqli_connect("127.0.0.1","root","root","cadastro_test");
      if (!$connect) {
        die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
      }
      $select = mysqli_prepare($connect, "SELECT Email FROM Email WHERE ID_EMAIL = (SELECT ID_Email FROM Usuarios WHERE Nome = ?)");
      mysqli_stmt_bind_param($select, "s", $_SESSION['usuario']);
      mysqli_stmt_execute($select);
      mysqli_stmt_bind_result($select, $email);
      mysqli_stmt_fetch($select);
  }
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Form + SQL/inicio_check</title>
</head>
<body>

<?php if (isset($_SESSION['usuario'])): ?>
  <main>
      <div class="container">
          <div class="flex_column">
              <h1>Bem-vindo, <span><?= $_SESSION['usuario'] ?></span>!</h1>
              <p>Aqui está o conteúdo exclusivo para usuários logados.</p>
              <p>Seu email é: <?= htmlspecialchars($email) ?></p>
              <a href="logout.php"><button>Sair</button></a>
          </div>
      </div>
  </main>
<?php else: ?>
  <main>
      <div class="container">
          <div class="flex">
              <a href="login/"><button>Login</button></a>
              <a href="cadastro/"><button>Cadastra-se</button></a>
          </div>
          <div class="button-mud">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
          </div>
      </div>
  </main>
<?php endif; ?>

</body>

<?php
  mysqli_stmt_close($select);
  mysqli_close($connect);
?>

</html>