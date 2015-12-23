<div class = "grid_16">
	<div class = "grid_4">&nbsp;</div>

	<div class = "grid_8">
		<div class = "col-xs-12 col-sm-4 col-sm-offset-4 col-md-8 col-md-offset-2 col-lg-4 col-lg-offset-4">
			<fieldset>
				<legend> Editar roles </legend>
				<?php
					echo $this->Form->create('Permiso', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
				?>	
					 <div class="form-group"  style = "text-align: center;">
						<div class="col-sm-10 col-md-offset-1">
							<?php
								echo $this->Form->input('nombre',array(
									'label' => false,
									'placeholder' => 'Nombre',
									'class' => 'form-control',
									'disabled' => 'disabled'
								));
							?>
						</div>
					  </div>
					  <div class="form-group " style = "text-align: center;" >
						<div class="col-sm-10 col-md-offset-1 ">
						  <?php
							echo 'Menú Usuarios: ';
							echo $this->Form->checkbox('usuarios', array('hiddenField' => true));
						  ?>
						</div>
					  </div>
					  
					   <div class="form-group" style = "text-align: center;">
						<div class="col-sm-10 col-md-offset-1">
							<?php
								echo 'Menú Clientes: ';
								echo $this->Form->checkbox('clientes', array('hiddenField' => true));
							?>
						</div>
					  </div>
					  
					  
					    <div class="form-group" style = "text-align: center;">
						<div class="col-sm-10 col-md-offset-1">
							<?php
								echo 'Menú Productos: ';
								echo $this->Form->checkbox('productos', array('hiddenField' => true));
							?>
						</div>
					  </div>
					  
					  <div class="form-group" style = "text-align: center;">
						<div class="col-sm-10 col-md-offset-1">
							<?php
								echo 'Menú Roles: ';
								echo $this->Form->checkbox('roles', array('hiddenField' => true));
							?>
						</div>
					  </div>
					  
					  <div class="form-group" style = "text-align: center;">
						<div class="col-sm-10 col-md-offset-1">
							<?php
								echo 'Menú Gestión: ';
								echo $this->Form->checkbox('gestion', array('hiddenField' => true));
							?>
						</div>
					  </div>
					  

					  <div class="form-group" style = "text-align: center;">
						<div class="col-sm-offset-1 col-sm-10 text-center">
						<br>
						<br>
						 <?php 
							echo $this->Form->submit(__('Editar Privilegios'), array('class' => 'btn btn-success btn-block'));
							echo $this->Form->end;
						 ?>
						</div>
					  </div>
			</fieldset>
		</div>
	</div>
</div>