<?php 

class Anuncios{

	public function getTotalAnuncios(){
		global $pdo;

		$sql = $pdo->query("SELECT count(*) as c from anuncios");
		$row = $sql->fetch();

		return $row['c'];
	}
	


	public function getMeusAnuncios(){
		global $pdo;

		$array = array();
		$sql = $pdo->prepare("SELECT 
			*,
			(select anuncios_imagens.url from anuncios_imagens where anuncios_imagens.id_anuncio = anuncios.id limit 1) as url from anuncios
			where id_usuario = :id_usuario");
		$sql->bindValue(":id_usuario", $_SESSION['cLogin']);
		$sql->execute();

		if($sql->rowCount() > 0)
			$array = $sql->fetchAll();

		return $array;
	}

	public function addAnuncio($titulo, $categoria, $valor, $descricao, $estado){
		global $pdo;

		$sql = $pdo->prepare("INSERT INTO anuncios set id_categoria = :id_categoria ,id_usuario = :id_usuario, titulo = :titulo, descricao = :descricao, valor = :valor, estado = :estado");
		$sql->bindValue(":id_categoria", $categoria);
		$sql->bindValue(":id_usuario", $_SESSION['cLogin']);
		$sql->bindValue(":titulo", $titulo);
		$sql->bindValue(":descricao", $descricao);
		$sql->bindValue(":valor", $valor);
		$sql->bindValue(":estado", $estado);
		$sql->execute();


	}

	


	public function editAnuncio($titulo, $categoria, $valor, $descricao, $estado, $fotos, $id){
		global $pdo;
		$sql = $pdo->prepare("UPDATE anuncios set titulo = :titulo, id_categoria = :categoria, descricao = :descricao, valor = :valor, estado = :estado where anuncios.id = :id");
		$sql->bindValue(":titulo", $titulo);
		$sql->bindValue(":categoria",$categoria);
		$sql->bindValue(":valor",$valor);
		$sql->bindValue(":descricao",$descricao);
		$sql->bindValue(":estado", $estado);
		$sql->bindValue(":id",$id);
		$sql->execute();

		if(count($fotos)>0){
			for ($i=0; $i < count($fotos['tmp_name']); $i++) { 

				$tipo = $fotos['type'][$i];

				if(in_array($tipo, array('image/jpeg','image/png'))){
					$tmpname = md5(time().rand(0, 8888)).'.jpg';

					move_uploaded_file($fotos['tmp_name'][$i],'assets/images/anuncios/'.$tmpname);

					list($width_orig, $height_orig) = getimagesize('assets/images/anuncios/'.$tmpname);

					$ratio = $width_orig/$height_orig;

					$width = 500;
					$height = 500;

					if($width/$height > $ratio){
						$width = $height*$ratio;
						echo "<br/>é maior do que proporçao <br/>";
					}else{
						$height = $width/$ratio;
						echo " <br/>nao é maior do que proporçao<br/>";
					}


					$img = imagecreatetruecolor($width, $height);

					if($tipo == 'image/jpeg'){
						$origin = imagecreatefromjpeg('assets/images/anuncios/'.$tmpname);
					}else if($tipo == 'image/png'){
						$origin = imagecreatefrompng('assets/images/anuncios/'.$tmpname);
					}

					imagecopyresampled($img, $origin, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

					imagejpeg($img,'assets/images/anuncios/'.$tmpname, 75);

					$sql = $pdo->prepare("INSERT into anuncios_imagens set id_anuncio = :id_anuncio, url = :url");
					$sql->bindValue(":id_anuncio",$id);
					$sql->bindValue(":url",$tmpname);
					$sql->execute();


				}
			}
		}
	}
	public function getAnuncio($id){
		$array = array();
		global $pdo;


		$sql = $pdo->prepare("SELECT * from anuncios where id = :id");
		$sql->bindValue(":id", $id);
		$sql->execute();



		if($sql->rowCount() > 0){
			$array = $sql->fetch();
			$array['fotos']= array();
			$sql = $pdo->prepare("SELECT id,url from anuncios_imagens where id_anuncio = :id_anuncio");
			$sql->bindValue(":id_anuncio",$id);
			$sql->execute();

			if($sql->rowCount() > 0){
				$array['fotos'] = $sql->fetchAll();
			}
		}

		return $array;
	}

	public function deleteAnuncio($id){
		global $pdo;


		$sql = $pdo->prepare("DELETE from anuncios_imagens where id_anuncio = :id_anuncio");
		$sql->bindValue(":id_anuncio", $id);
		$sql->execute();

		$sql = $pdo->prepare("DELETE from anuncios where id = :id");
		$sql->bindValue(":id", $id);
		$sql->execute();

	}

	public function excluiFoto($id){
		global $pdo;

		$id_anuncio = 0;

		$sql = $pdo->prepare("SELECT id_anuncio from anuncios_imagens where id = :id");
		$sql->bindValue(":id",$id);
		$sql->execute();

		if($sql->rowCount() > 0){
			$row = $sql->fetch();
			$id_anuncio = $row['id_anuncio'];
		}


		$sql = $pdo->prepare("DELETE from anuncios_imagens where id = :id");
		$sql->bindValue(":id", $id);
		$sql->execute();

		return $id_anuncio;
	}
}

?>
