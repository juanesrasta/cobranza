<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"> Sistema de Control de Cobranza </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active">
		<?php
		 echo $this->Html->link(
             $this->Html->tag('span', '' , array('class' => 'glyphicon glyphicon-home', 'aria-hidden' => 'true')) . ' Inicio ',
             array(
                 'controller' => 'index',
                 'action' => 'index'
             ),array('escape'=>false)
         );
		?>
		<?php if(!empty($username)){ ?>
			<?php 
				// debug($permisos);
				if($permisos_usuarios['Permiso']['usuarios'] ){ 
			?>
					 <li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Usuarios <span class="caret"></span></a>
					  <ul class="dropdown-menu" role="menu">
					  
						<li> 
							<?php
								 echo $this->Html->link(
									 $this->Html->tag('span', '' , array('class' => 'glyphicon glyphicon-eye-open', 'aria-hidden' => 'true')) . ' Usuarios del Sistema ',
									 array(
										 'controller' => 'users',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
							?>
						</li>
						<li>
							<?php 
								 echo $this->Html->link(
									 $this->Html->tag('span', '' , array('class' => 'glyphicon glyphicon-plus', 'aria-hidden' => 'true')) . ' Agregar Usuario ',
									 array(
										 'controller' => 'users',
										 'action' => 'add'
									 ),array('escape'=>false)
								 );
							?>
						</li>
					  </ul>
					</li>
			<?php 	}  ?>
			
			<?php 
				if($permisos_usuarios['Permiso']['roles'] ){
			?>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Roles <span class="caret"></span></a>
				  <ul class="dropdown-menu" role="menu">
				  
					<li> 
						<?php
							 echo $this->Html->link(
								 $this->Html->tag('span', '' , array('class' => 'glyphicon glyphicon-tower', 'aria-hidden' => 'true')) . ' Administrar Roles ',
								 array(
									 'controller' => 'permisos',
									 'action' => 'index'
								 ),array('escape'=>false)
							 );
						?>
					</li>
				  </ul>
				</li>
				
		<?php 
				}	
			}		
		?>
		
      </ul>
	<?php if(!empty($username)){?>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><?php if(!empty($username)){echo $username;} else{ echo ''; } ?></a></li>
		
			<li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Mi Perfil <span class="caret"></span></a>
			  <ul class="dropdown-menu" role="menu">
				<li> <?php
						echo $this->Html->link(
						 $this->Html->tag('span', '' , array('class' => 'glyphicon glyphicon-search', 'aria-hidden' => 'true')) . ' Ver mis datos ',
						 array(
							 'controller' => 'users',
							 'action' => 'view',
							 $id
						 ),array('escape'=>false)
					 );
					 
				// echo $this->Html->link('Ver mis datos', array('controller' => 'users', 'action' => 'view', $id)); ?></li>
				<li> <?php
					echo $this->Html->link(
						$this->Html->tag('span', '' , array('class' => 'glyphicon glyphicon-pencil', 'aria-hidden' => 'true')) . ' Editar mis datos ',
						array(
							'controller' => 'users',
							'action' => 'edit',
							$id
						),array('escape'=>false)
					);
				// echo $this->Html->link('Editar mis datos',  array('controller' => 'users', 'action' => 'edit', $id)); ?></li>
				<li class="divider"></li>
				<li><?php
						echo $this->Html->link(
							$this->Html->tag('span', '' , array('class' => 'glyphicon glyphicon-off', 'aria-hidden' => 'true')) . ' Cerrar Sesión ',
							array(
								'controller' => 'users',
								'action' => 'logout',
								$id
							), array('escape'=>false)
						);
						?>
				</li>
			  </ul>
			</li>
      </ul>
	<?php }else{	?>
	
			<ul class="nav navbar-nav navbar-right">
				<li> <?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login')); ?></li>
			</ul>
	<?php } ?>
	
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>