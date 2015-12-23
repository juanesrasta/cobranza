<div class="page-header" style = "text-align: center;">
	<h1>Status Registrados <br> </h1>
</div>
<div>
<?php
	echo $this->Html->link(
		 $this->Html->image('listAddContacts.png' , array('width' => '15px')).'   Agregar Nuevo status',
		 array(
			 'controller' => 'clientes',
			 'action' => 'addStatus'
		 ),array(
			'escape'=>false,
			'style' => 'margin-left: 12px; font-size: 15px;'
		)
	). '<br><br>';
?>
<table class="table table-hover user_table">
		<tr>
			<th>
			<?php echo $this->Paginator->sort('rif_emp','Empresa'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('codigo','Codigo'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('condicion','Condicion'); ?>
			</th>

			<th style = "text-align: center;">
				<?php echo 'Acciones'; ?>
			</th>
		</tr>
<?php
	foreach($status as $s){ ?>
		
		<tr>
			<td>
				<?php
                if ($s['Statu']['rif_emp'] == 7) {
                    echo 'Banco de Venezuela';
                } else {
                    echo 'Banco Bicentenario';
                }
                ?>
			</td>
			<td>
				<?php echo $s['Statu']['codigo']; ?>
			</td>
			<td>
				<?php echo $s['Statu']['condicion']; ?>
			</td>
			
			<td style = "text-align: center;">
				<?php
				
					echo ' ';
					
						$img = ($s['Statu']['activo']!=1)?$this->webroot.'img/activate.png':$this->webroot.'img/deactivate.png';
						$msg = ($s['Statu']['activo']==0)?"Desea desactivar el statu?":"Desea activar este statu";
						$title = ($s['Statu']['activo']!=1)?'Activar':'Desactivar';
						echo $this->Form->postLink('<img src="'.$img.'" />', array('action' => 'activarStatus', $s['Statu']['id']),array('escape'=>false,'class' => 'tooltip','title'=>$title), __($msg));
					
					
				?>
			</td>
		</tr>
		
<?php
	}
?>

</table>

<div class="listas index grid_12">
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página {:page} de {:pages}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('Antetior'), array(), null, array('class' => 'prev disabled'));
		echo  ' ';
		echo $this->Paginator->numbers(array('separator' => ' '));
		echo  ' ';
		echo $this->Paginator->next(__('Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
	</div>