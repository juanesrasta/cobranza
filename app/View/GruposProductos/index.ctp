<?php if (!empty($cliente_id)) {
	$this->Paginator->options(array('url' =>  array($cliente_id)));
}?>
<div class="page-header" style = "text-align: center;">
	<h1>Grupo de Productos<br></h1>
</div>
<div>
<div class = "search_box" style= "margin: 1%;">
	<fieldset>
	<legend>Búsqueda</legend>
	<?php echo $this->Form->create('GruposProducto');?>
	<div class="form-group">
		<div class="col-sm-10 col-md-offset-1 ">
		  <?php
			if (!empty($cliente_id)) {
				$value = $cliente_id;
			} else {
				$value = 0;
			}
			echo $this->Form->input('cliente_id',array(
				'label' => 'Empresa',
				'class' => 'form-control',
				'value' => $value,
			)); 
		  ?>
		</div>
	</div>
	<?php 
	echo $this->Form->submit(__('Buscar productos'), array('div' => 'search_button', 'class' => 'boton_busqueda'));
	echo $this->Form->end();
	?>
	</fieldset>
</div>
<?php
if (!empty($busqueda)) {
	if (!empty($productos)) {
		foreach ($productos as $p) {
			echo '<h3 style = float:left>&nbsp&nbsp'.$p['Producto']['producto'].'</h3>&nbsp&nbsp&nbsp&nbsp';
				echo $this->Html->link(
					$this->Html->image('listEdit.png', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Editar dias de atraso y porcentajes de gestores')) . '  ',
					array(
					 'action' => 'add',
					 $cliente_id,$p['Producto']['id']
					),array('escape'=>false)
				);
			?>
			<table class="table table-hover user_table">
				<tr>
					<th><?php echo $this->Paginator->sort('GruposProducto.aIntPorcent','Gestor Interno'); ?></th>
					<th>Dias de Atraso</th>
					<th><?php echo $this->Paginator->sort('GruposProducto.aExtPorcen','Gestor Externo'); ?></th>
				</tr>
				<tr>
					<td><?php echo $p['GruposProducto']['aIntPorcen']?></td>
					<td>Mayor a 2500</td>
					<td><?php echo $p['GruposProducto']['aExtPorcen']?></td>
				</tr>
				<tr>
					<td><?php echo $p['GruposProducto']['bIntPorcen']?></td>
					<td>Entre 720 y 2500</td>
					<td><?php echo $p['GruposProducto']['bExtPorcen']?></td>
				</tr>
				<tr>
					<td><?php echo $p['GruposProducto']['cIntPorcen']?></td>
					<td>Entre 360 y 720</td>
					<td><?php echo $p['GruposProducto']['cExtPorcen']?></td>
				</tr>
				<tr>
					<td><?php echo $p['GruposProducto']['dIntPorcen']?></td>
					<td>Menor a 360</td>
					<td><?php echo $p['GruposProducto']['dExtPorcen']?></td>
				</tr>
			</table>
			<br><br>
			<?php
		}
	} else {
		echo 'Esta empresa no tiene productos asociados.';
	}
}
?>