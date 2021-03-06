<?php require "pages/header.php" ?>

<div class="container">
	<h1><strong>Cadastre-se</strong></h1>


	<?php
	require 'classes/usuarios.class.php';
	$usuarios = new Usuarios();

	if(isset($_POST['nome']) && !empty($_POST['nome'])){
		$nome = addslashes($_POST['nome']);
		$email = addslashes($_POST['email']);
		$senha = $_POST['senha'];
		$telefone = addslashes($_POST['telefone']);

		if(!empty($nome) && !empty($email) && !empty($senha)){
			if($usuarios->cadastrar($nome, $email, $senha, $telefone)) {
				?>
				<div class="alert alert-success">
					<strong>Parabéns</strong>Cadastrado com sucesso. <a href="login.php" class="alert-link">Agora faça o login</a>
				</div>
				<?php
			}else {
				?>
				<div class="alert alert-warning">
					Esse usuario ja existe!! <a href="login.php" class="alert-link">Agora faça o login</a>
				</div>
				<?php
			}
		}else{
			?>
			<div class="alert alert-warning">Preencha todos os campos por favor</div>
			<?php
		}
	}


	?>
</div>

<div class="container">
	<form method="POST">
		<div class="form-group">
			<label for="nome">Nome: </label>
			<input type="text" name="nome" id="nome" class="form-control">
		</div>

		<div class="form-group">
			<label for="email">Email: </label>
			<input type="email" name="email" id="email" class="form-control">
		</div>

		<div class="form-group">
			<label for="senha">Senha: </label>
			<input type="password" name="senha" id="senha" class="form-control">
		</div>

		<div class="form-group">
			<label for="telefone">Telefone: </label>
			<input type="text" name="telefone" id="telefone" class="form-control">
		</div>

		<button type="submit" class="btn btn-default">Cadastrar</button>
	</form>
</div>

<?php require "pages/footer.php" ?>