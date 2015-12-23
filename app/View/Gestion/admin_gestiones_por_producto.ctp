<div id="bloquea" style="width: 20%;height: 80px;overflow: hidden;z-index: 10000;position: relative;text-align: center;background: #FFFFFF;margin-left: auto;margin-right: auto;display:none">
	<?php echo $this->Html->image('enviando.gif',array('style' => 'margin-top:20px')) ?>
</div>
<div class="page-header" style = "text-align: center;">
	<h1> Gestiones por Producto<br>  <small></small></h1>
</div>
<div class = "search_box" style= "margin: 1%;">
	<fieldset class = "gest_status_wrapper">
		<fieldset class = 'gest_status'>
			<?php
				echo $this->Form->create(null, array(
			'url' => array_merge(array('controller' => 'gestion', 'action' => 'admin_gestiones_por_producto'), $this->params['pass'])
		));
				echo $this->Form->input('gestor', array('label' => 'Gestor ', 'empty' => 'Todos'));
				echo $this->Form->input('empresa', array('label' => 'Empresa ', 'empty' => 'Todas'));	
				
			?>
		</fieldset>
	
<?php

		echo '<table class= "gest_stat_table"> <tr><td>';
			echo $this->Form->input('fecha1', array('label' => 'Desde:  ', 'empty' => 'Todos','id' => 'pickDate1','type' => 'text','readonly' => 'false', 'class' => 'short_input'));
		echo '</td><td style = "text-align: right;">';
			echo 'Fecha:';
		echo '</td><td >';
			$now = new DateTime();
			echo '<b>'.  $now->format('d-m-Y').' </b>';
		echo '</td></tr><tr><td>';
			echo $this->Form->input('fecha2', array('label' => 'Hasta:  ', 'empty' => 'Todos','id' => 'pickDate2','type' => 'text','readonly' => 'false', 'class' => 'short_input'));
		echo '</td><td>';
			echo $this->Form->submit(__('Buscar'), array('style' => 'float: right;','onClick' => "activar_pantalla()"));
		echo '</td><td></tr></table>';
		
		echo $this->Form->end();
?>
</fieldset>

</div>

<div class="gestiones_x_producto">
	<span style="font-size:15px; font-weight:bold; margin-left:13px">Cantidad de Gestiones realizadas por producto</span>
	<table class="table_gestiones_realizadas">
		<tr>
			<th>Nombre</th>
			<th>Total</th>
			<?php 
			foreach ($productos as $p) {
			?>
				<th><?php echo $p ?></th>
			<?php
			}
			?>
		</tr>
		<?php 
		$i=0;
		foreach($gestores as $g) {
			if ($i == 0) {
				$class = "seleccionado";
			} else {
				$class = "";
			}
		?>
			<tr class="tr_gestiones_producto <?php echo $class ?>">
				<td><?php echo $g['User']['nombre_completo'] ?></td>
				<td>
				<?php 
				if (!empty( $totales[$g['Gestor']['Clave']])) {
					echo $totales[$g['Gestor']['Clave']];
				} 
				?>
				</td>
				<?php 
				foreach ($productos as $p) {
					echo '<td>';
					if (!empty( $gestiones_producto[$g['Gestor']['Clave']][$p])) {
						echo $gestiones_producto[$g['Gestor']['Clave']][$p];
					} 
					echo '</td>';
				}
				?>
			</tr>
		<?php	
			$i++;
		}
		?>
	</table>
</div>

<script>
$('.tr_gestiones_producto').click(function(){
	$('.tr_gestiones_producto').removeClass('seleccionado');
	$(this).addClass('seleccionado');
});

$('#pickDate1').datepicker({
    dateFormat: "dd-mm-yy",
});
$('#pickDate2').datepicker({
    dateFormat: "dd-mm-yy",
});

function activar_pantalla() {
	document.getElementById('total_wrap').style.display='block';
	document.getElementById('bloquea').style.display='block';
}
	
</script>