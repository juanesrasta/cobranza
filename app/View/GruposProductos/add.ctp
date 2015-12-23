	<div class = "grid_4">&nbsp;</div>
	<div class = "row login grid_8 ">
	
		<fieldset>
		<legend> Editar Grupo Producto </legend>
	<?php 
		echo $this->Form->create('GruposProducto', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
	?>   
		  <?php echo $this->Form->input('id', array(
						'class' => 'form-control',
						'label' => false
				));
				echo $this->Form->input('Producto.id', array(
						'class' => 'form-control',
						'label' => false,
						'value' => $producto_id,
						'type' => 'hidden'
				));

				echo $this->Form->input('cliente_id', array(
						'class' => 'form-control',
						'label' => false,
						'value' => $cliente_id,
						'type' => 'hidden'
				));
			?>
		  <table class="table_form">
			<tr>
				<td>
					<?php
							echo $this->Form->input('aIntPorcen', array(
									'class' => 'form-control',
									'label' => false,
									'type' => 'text'
							));
						?>
						
				</td>
				<td>
					Mayor a 2500
				</td>
				<td>
					<?php
							echo $this->Form->input('GruposProducto.aExtPorcen', array(
									'class' => 'form-control',
									'label' => false,
									'type' => 'text'
							));
						?>
						
				</td>
			</tr>
			<tr>
				<td>
					<?php
							echo $this->Form->input('GruposProducto.bIntPorcen', array(
									'class' => 'form-control',
									'label' => false,
									'type' => 'text'
							));
						?>
						
				</td>
				<td>
					Entre 720 y 2500
				</td>
				<td>
					<?php
							echo $this->Form->input('GruposProducto.bExtPorcen', array(
									'class' => 'form-control',
									'label' => false,
									'type' => 'text'
							));
						?>
						
				</td>
			</tr>
			<tr>
				<td>
					<?php
							echo $this->Form->input('GruposProducto.cIntPorcen', array(
									'class' => 'form-control',
									'label' => false,
									'type' => 'text'
							));
						?>
						
				</td>
				<td>
					Entre 360 y 720
				</td>
				<td>
					<?php
							echo $this->Form->input('GruposProducto.cExtPorcen', array(
									'class' => 'form-control',
									'label' => false,
									'type' => 'text'
							));
						?>
						
				</td>
			</tr>
			<tr>
				<td>
					<?php
							echo $this->Form->input('GruposProducto.dIntPorcen', array(
									'class' => 'form-control',
									'label' => false,
									'type' => 'text'
							));
						?>
						
				</td>
				<td>
					Menor a 360
				</td>
				<td>
					<?php
							echo $this->Form->input('GruposProducto.dExtPorcen', array(
									'class' => 'form-control',
									'label' => false,
									'type' => 'text'
							));
						?>
						
				</td>
			</tr>
		  </table>
		  <div class="form-group">
			<div class="col-sm-offset-1 col-sm-10 text-center">
			 <?php 
				echo $this->Form->submit(__('Guardar producto'), array('class' => 'btn btn-success btn-block'));
				echo $this->Form->end;
			 ?>
			</div>
		  </div>
		  </fieldset>
</div>