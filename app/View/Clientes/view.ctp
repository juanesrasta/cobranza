

<div class="usuarios view grid_12">
<h2><?php echo __('Cliente'); ?></h2>
	<dl>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo $cliente['Cliente']['nombre']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Contacto'); ?></dt>
		<dd>
			<?php echo $cliente['Cliente']['contacto']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cargo'); ?></dt>
		<dd>
			<?php echo $cliente['Cliente']['cargo']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Departamento'); ?></dt>
		<dd>
			<?php echo $cliente['Cliente']['departamento']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Caracteres'); ?></dt>
		<dd>
			<?php echo $cliente['Cliente']['n_caracteres']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo $cliente['Cliente']['status']; ?>
			&nbsp;
		</dd>
	</dl>
	
</div>

