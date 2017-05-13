
<?php require "pages/header.php"; ?>

<?php require 'classes/anuncios.class.php';
require 'classes/usuarios.class.php';
require 'classes/categorias.class.php';
$an = new Anuncios();
$us = new Usuarios();
$ca = new Categorias();
$categorias = $ca->getLista();


$filtros = array(
	'categoria'=>'',
	'estado'=>'',
	'preco'=>''
	);
if(isset($_GET['filtros']) && !empty($_GET['filtros'])){
	$filtros = $_GET['filtros'];
}

$total_anuncio = $an->getTotalAnuncios($filtros);
$total_empresas = $us->getTotalUsuarios();

$pagina_atual = 1;

if(isset($_GET['pagina_atual']) && !empty($_GET['pagina_atual'])){
	$pagina_atual = addslashes($_GET['pagina_atual']);
}

$item_por_pagina = 2;

$total_paginas = ceil($total_anuncio / $item_por_pagina);

$anuncios = $an->getUltimosAnuncios($pagina_atual, $item_por_pagina, $filtros);

?>



<div class="container-fluid">
	<div class="jumbotron">
		<h2>Nós temos para hoje <?php echo $total_anuncio; ?> offertas</h2>
		<p>Mais de <?php echo $total_empresas; ?> empresas cadastradas</p>
	</div>
	<div class="row">
		<div class="col-sm-3">
			<h4>Pesquisa Avançada</h4>
			<form method="GET">
				<div class="form-group">
					<label for="categoria">Categoria:</label>
					<select name="filtros[categoria]" id="categoria" class="form-control">
						<option></option>
						<?php foreach ($categorias as $cate):?>
							<option value="<?php echo $cate['id'] ?>" <?php echo ($cate['id'] == $filtros['categoria'])?'selected="selected"':''?> ><?php echo $cate['nome'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="preco">Preço:</label>
					<select name="filtros[preco]" id="preco" class="form-control">
						<option></option>
						<option value="0-50" <?php echo ($filtros['preco'] == "0-50")?'selected="selected"':''?> >R$ 0 - 50</option>
						<option value="51-100" <?php echo ($filtros['preco'] == "51-100")?'selected="selected"':''?>>R$ 51 - 100</option>
						<option value="101-200" <?php echo ($filtros['preco'] == "101-200")?'selected="selected"':''?>>R$ 101 - 200</option>
						<option value="201-600"<?php echo ($filtros['preco'] == "201-600")?'selected="selected"':''?>>R$ 201 - 600</option>
						<option value="601-1000000" <?php echo ($filtros['preco'] == "601-1000000")?'selected="selected"':''?>>mais de R$ 600</option>
					</select>
				</div>
				<div class="form-group">
					<label for="estado">Estado de Conservação:</label>
					<select name="filtros[estado]" id="estado" class="form-control">
						<option></option>
						<option value="0" <?php echo ($filtros['estado'] == "0")?'selected="selected"':''?>>Ruim</option>
						<option value="1" <?php echo ($filtros['estado'] == "1")?'selected="selected"':''?>>Bom</option>
						<option value="2" <?php echo ($filtros['estado'] == "2")?'selected="selected"':''?>>Otimo</option>
						<option value="3" <?php echo ($filtros['estado'] == "3")?'selected="selected"':''?>>Nunca usado</option>
					</select>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-info">Pesquisar</button>
				</div>
			</form>
		</div>

		<div class="col-sm-9">
			<h4>Últimos Anúncios</h4>
			<table class="table table-striped">
				<tbody>
					<?php foreach($anuncios as $anuncio): ?>
						<tr>
							<td>
								<?php if(empty($anuncio['url'])): ?>
									<img src="assets/images/default.png" border="0"  height="100"/>
								<?php else: ?>
									<img src="assets/images/anuncios/<?php echo $anuncio['url']; ?>" class="foto_redimensionada" border="0" width="100" height="100"/>
								<?php endif; ?>
							</td>
							<td>
								<a href="produto.php?id=<?php echo $anuncio['id']; ?>"><?php echo $anuncio['titulo']; ?></a><br />
								<?php echo $anuncio['categoria'] ?>
							</td>
							<td>
								R$ <?php echo number_format($anuncio['valor'], 2); ?>
							</td>
						</tr>

					<?php endforeach; ?>
				</tbody>
			</table>
			<ul class="pagination">
				<?php for ($q=0; $q < $total_paginas ; $q++):?>
					<li class="<?php echo ($pagina_atual == ($q+1))?'active':'' ?>"><a href="index.php?pagina_atual=<?php echo ($q+1); ?>"><?php echo ($q+1); ?></a></li>
				<?php endfor; ?>
			</ul>
		</div>
	</div>
</div>

<?php require "pages/footer.php"; ?>
