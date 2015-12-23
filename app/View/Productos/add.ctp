<div class = " grid_16 ">
<div class = "grid_4">&nbsp;</div>
<div class = "row login grid_8 ">
	<fieldset>
		<legend> Agregar producto </legend>
		(*) Campos obligatorios  <br><br>
		<?php 
			echo $this->Form->create('Producto', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
		?>
			<div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						 echo $this->Form->input('cliente_id', array(
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1 ">
				  <?php
					echo $this->Form->input('producto',array(
						'label' => 'Producto',
						'placeholder' => 'Producto',
						'class' => 'form-control'
					));
				  ?>
				</div>
			  </div>
			 
			 <span style="font-size:14px;font-weight:bold">Grupo Producto</span>
			  <table class="table_form">
				<tr> 
					<th>
						Gestor Interno
					</th>
					<th>
						Días Atraso
					</th><th>
						Gestor Externo
					</th>
				</tr>
				<tr>
					<td>
						<?php
								echo $this->Form->input('GruposProducto.aIntPorcen', array(
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
					echo $this->Form->submit(__('Agregar Producto'), array('class' => 'btn btn-success btn-block'));
					echo $this->Form->end;
				 ?>
				</div>
			  </div>
		</fieldset>
	</div>
</div>


