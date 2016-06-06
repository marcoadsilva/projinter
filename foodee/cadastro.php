<?
include("classes/database.class.php");
$db = Database::getInstance();
$mysqli = $db->getConnection();
 
// Se o usuário clicou no botão cadastrar efetua as ações
if ($_POST['cadastrar']) {
 
	// Recupera os dados dos campos
	$nome = $_POST['nome'];
	$email = $_POST['email'];
	$foto = $_FILES["foto"];
	$perfil = $_POST['perfil'];
	
	$errors = array();
	
	if (empty($nome)) {
		$errors[] = "Entre com o nome da pessoa.";
	}
	
	if (empty($email)) {
		$errors[] = "Entre com o e-mail da pessoa.";
	}

	if ($result = $mysqli->query("SELECT * FROM `pessoas` WHERE `email` = '" . $email . "';")) {
		/* determine number of rows result set */
		$row_cnt = $result->num_rows;

		if ($row_cnt > 0)
			$errors[] = "Este e-mail já está cadastrado.";

		/* close result set */
		$result->close();
	}
	
	/*if (empty($foto["name"])) {
		$errors[] = "É necessário enviar a imagem de perfil da pessoa.";
	}*/
	
	if (!empty($foto["name"])) {
		// Largura mínima em pixels
		$largura_min = 200;
		// Altura mínima em pixels
		$altura_min = 200;

		// Largura máxima em pixels
		$largura_max = 1500;
		// Altura máxima em pixels
		$altura_max = 1500;
		
		// Tamanho máximo do arquivo em bytes
		$tamanho = 2097152;
		
		// Verifica se o arquivo é uma imagem
		if(!preg_match("~^image/p?(jpeg|jpeg|png|gif|bmp)$~i", $foto["type"])){
			$errors[] = "Envie uma imagem válida.";
		}

		// Pega as dimensões da imagem
		$dimensoes = getimagesize($foto["tmp_name"]);
		
		// Verifica se a largura x altura da imagem é menor que o permitida
		if($dimensoes[0] < $largura_min OR $dimensoes[1] < $altura_min) {
			$errors[] = "As dimensões da imagem não podem ser menores que ".$largura."x".$altura." pixels";
		}

		// Verifica se a largura x altura da imagem é maior que o permitida
		if($dimensoes[0] > $largura_max OR $dimensoes[1] > $altura_max) {
			$errors[] = "As dimensões da imagem não podem ser maiores que ".$largura."x".$altura." pixels";
		}

		// Verifica se o tamanho da imagem é maior que o tamanho permitido
		if($foto["size"] > $tamanho) {
			$errors[] = "A imagem deve ter no máximo ".(int)($tamanho/1048576)." mb.";
		}
	}

	// Se não houver nenhum erro
	if (count($errors) == 0) {
		$nome_miniatura = "default.png";

		if (!empty($foto["name"])) {
			// Pega extensão da imagem
			preg_match("/.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
			
			$time = md5(uniqid(time()));
			
			// Gera um nome único para a imagem
			$nome_imagem = $time . "." . $ext[1];
			
			$caminho = "fotos/";
			
			// Caminho de onde ficará a imagem
			$caminho_imagem = $caminho . "real/" . $nome_imagem;
			
			// Faz o upload da imagem para seu respectivo caminho
			move_uploaded_file($foto["tmp_name"], $caminho_imagem);
			
			// pegando as dimensoes reais da imagem, largura e altura
			list($width, $height) = getimagesize($caminho_imagem);

			// setando a largura da miniatura
			$new_width = 200;
			// setando a altura da miniatura
			$new_height = 200;
			
			$image_p = imagecreatetruecolor($new_width, $new_height);
			$image = imagecreatefromjpeg($caminho_imagem);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			// Cria um nome para a miniatura
			$nome_miniatura = $time . ".jpg";

			// o 3º argumento é a qualidade da miniatura de 0 a 100
			imagejpeg($image_p, $caminho . $nome_miniatura, 100);
			imagedestroy($caminho . $image_p);
		}
		
		// Insere os dados no banco
		$sql = $mysqli->query("INSERT INTO pessoas (nome, email, foto, perfil) VALUES ('".$nome."', '".$email."', '".$nome_miniatura."', '".$perfil."')");
		
		// Se os dados forem inseridos com sucesso
		if ($sql){
			echo '<div class="alert alert-success" role="alert">Pessoa cadastrada com sucesso!</div>';
		}
	} else {
		$e = '<div class="alert alert-danger" role="alert"><strong>Opssss!</strong> Foram encontrados os seguintes erros:<br />';
		foreach ($errors as $erro) {
			$e .= "<li>" . $erro . "</li>";
		}
		$e .= '</div>';
		echo $e;
	}
}
?>

<div class="page-header"><h1>Cadastro de Pessoa</h1></div>
<form action="" method="post" enctype="multipart/form-data" name="cadastro" >
	<div class="col-md-4">
		<div class="form-group">
			<label for="nome">Nome</label>
			<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>
		</div>
		<div class="form-group">
			<label for="email">Endereço de e-mail</label>
			<input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
		</div>
		<div class="form-group">
			<label for="foto">Foto de Perfil</label>
			<input type="file" id="foto" name="foto">
			<p class="help-block">A imagem deve ter no máximo 1500x1500px.</p>
		</div>
		<div class="form-group">
			<label for="perfil">Perfil (opcional)</label>
			<textarea class="form-control" id="perfil" name="perfil" rows="5"></textarea>
		</div>
		<input type="submit" class="btn btn-default" name="cadastrar" value="Cadastrar" />
		<div style="margin-top: 30px;"></div>
	</div>
</form>