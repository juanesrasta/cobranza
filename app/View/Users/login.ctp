<!--<div class = "row">
	<div class = "col-lg-6 col-lg-offset-3 ">
		<div class="page-header" style = "text-align: center;">
			<h1>Bienvenido al sistema de cobranzas <br>  <small>Introduce tus datos para iniciar sesión</small></h1>
		</div>
	</div>
</div> -->
<div class = "row">
		<div class = "grid_4">&nbsp;</div>
		<div class = "row login grid_8">
			<?php 
				echo $this->Form->create('User', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
			?>
				<fieldset>
					<legend> Login </legend>
				  
					  <?php
						echo $this->Form->input('username',array(
							'label' => 'Nombre de Usuario (*)',
							'placeholder' => 'Nombre de Usuario',
							'class' => 'form-control'
						));
					  ?>
				  <div class="form-group">
					<div class="col-sm-10 col-md-offset-1">
						<?php
							echo $this->Form->input('password',array(
								'label' => 'Contraseña(*)',
								'placeholder' => 'Contraseña',
								'class' => 'form-control'
							));
						?>
					</div>
				  </div>				
				  <div class="form-group">
					<div class="col-sm-offset-1 col-sm-10 ">
					 <?php 
						echo $this->Form->submit(__('Ingresar'), array('class' => 'btn btn-success btn-block'));
						echo $this->Form->end;
					 ?>
					</div>
				  </div>
				</fieldset>
		</div>
</div>