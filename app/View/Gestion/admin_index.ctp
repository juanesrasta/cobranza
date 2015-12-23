<div class = "all" style="font-size:12px">
	<div class = "left">
		<fieldset style = "height: 100%;">
			<legend> Cartera Asignada </legend>
				<?php echo $this->element('Gestion/filtro_gestion')?>
				<div class = "clear"> </div>
			<div class = "table_info" id = "table_info_deudores" style = "max-height: 150px;">
				<div class = "inner_table">
					<table style="width:886px;" class="table_info_deudores">
						<tr>
							<th> Nombre	</th>
							<th> Cédula	</th>
							<th> Teléfono	</th>
							<th> FECHA_AS 	</th>
							<th> Fecha	</th>
							<th> Gestor	</th>
							<!--<th> Saldo	</th>-->
						</tr>
						<tbody id="tab_datos_deudor">
						<?php $i = 0; 
			 foreach($deudores as $d){
				if ($i==0) {
					$class="seleccionado";
				} else {
					$class="";
				}
			?>
						<tr id="<?=$i+1;?>" class = "tabla_deudores tr_deudores <?php echo $class ?>" name="<?php echo $d['Cobranza']['CEDULAORIF']?>">
							<td><?php echo $d['Cobranza']['NOMBRE']?></td>
							<td><?php echo $d['Cobranza']['CEDULAORIF']?></td>
							<td><?php echo $d['ClienGest']['telefono'] ?></td>
							<td><?php
					$fecha_asig = explode(' ',$d['Cobranza']['FECH_ASIG']);
					echo $fecha_asig[0]?>
							</td>
							<td><?php $fecha_p = explode(' ',$d['ClienGest']['proximag']);
					echo $fecha_p[0]
					?></td>
							<td name="<?php echo $d['Cobranza']['GESTOR'] ?>" class="gestor_seleccionado"><?php echo $d['Cobranza']['GESTOR'] ?></td>
							<!--<td><?php echo 'Saldo'?></td>-->
						</tr>
					<?php 
			$i++;
			} ?>
			</tbody>
					</table>
				</div>
			</div>
			<br>
			<div class = "datamol">
				<?php echo $this->element('Gestion/datamol')?>
			</div>
		</fieldset>
		<fieldset>
			<legend> Vistas </legend>
			<?php
			echo $this->Form->input('Gestión',array(
				'type' => 'button',
				'label' => false,
				'class' => 'accesos_directos click_gestion',
				'style' => 'margin-top: 9px;',
				'name' => 'agregar'
			));
			echo $this->Form->input('Pagos',array(
				'type' => 'button',
				'label' => false,
				'class' => 'accesos_directos boton_pagos'
			));
			echo $this->Form->input('Histórico',array(
				'type' => 'button',
				'label' => false,
				'class' => 'accesos_directos'
			));
			echo $this->Form->input('Rel. Pagos',array(
				'type' => 'button',
				'label' => false,
				'class' => 'accesos_directos'
			));
			echo $this->Form->input('Cerrar',array(
				'type' => 'button',
				'label' => false,
				'class' => 'accesos_directos'
			));
			?>
		</fieldset>
	</div>
	<?php
	echo '<div class = "pestanas_wrapper"><div id = "pestanas_empresas"><div class = "inner_pestanas">';
		foreach ($empresas as $e) {
			echo '<div style= "cursor:pointer" name = "'.$e['Cliente']['rif'].'" class="nombre_empresa">'.$e['Cliente']['nombre'].'</div>';
		}
	echo '</div></div></div>';
	$c= 0;
	foreach ($empresas as $e) { //esto hace que todo se imprima tantas veces empresas hayan
		if ($c == 0 ) {
			$display = 'block';
		} else {
			$display = 'none';
		}
		}
	?>
		<script>
			prueba();
			function prueba(){
				$( ".nombre_empresa:first" ).addClass( "selected_pestana" );
				$( ".nombre_empresa" ).click(function(){
					$( ".nombre_empresa" ).removeClass("selected_pestana");
					$(this).addClass( "selected_pestana" );
				});
			}
			
		</script>
		<div class = "right_wrap" style="display:<?php echo $display?>" id = "">
			<fieldset style = "height: 100%;">
				<legend> Detalle </legend>
				<div class = "edit_datos_deudor">
					<?php echo $this->Html->image('listEdit.png',array('style' => 'margin-bottom:13px','title' => 'Modficar Datos Deudor','class' => 'click_datos_deudor', 'name' => 'editar')); ?><br>
				</div>
				<div class = "deudor_datos">
					<fieldset>
						<legend> Datos del deudor </legend>
						<div class = "left">

                            Nombre  <b class = 'deudor_nombre'><?php echo $deudores[0]['Cobranza']['NOMBRE']?></b><br>
                            Cedula<b class = 'deudor_cedula'><?php echo " ". $deudores[0]['Cobranza']['CEDULAORIF']?></b><br>
							Status:<b class = 'deudor_cond_pago'><?php echo " ".($cond_pago['Statu']['condicion']);?> </b> <br>
							Gestor: <b class = 'deudor_gestor'><?php echo " ". $deudores[0]['Cobranza']['GESTOR']?></b><br>
							Supervisor: <b class = 'deudor_supervisor'><?php echo $supervisor;?></b>
						</div>
						<div class = "left">
							
							Teléfonos / Dirección <div class = "click_telefonos_deudor">
								<?php echo $this->Html->image('listAddContacts.png',array('style' => 'margin-bottom:13px','title' => 'Agregar Teléfono Deudor','class' => 's', 'name' => 'editar')); ?>
							</div>
							<div class = "deudor_telefonos">
								<?php foreach($telefonos as $tel){?>
									<b class = 'deudor_telefono'>									
										<?php if(!empty($tel['Telefono']['cedulaorif'])){echo $tel['Telefono']['telefono']. " - "	;}?>
									</b> 
									<b class = 'deudor_direccion'>									
										<?php if(!empty($tel['Telefono']['ubicacion'])){echo $tel['Telefono']['ubicacion'];}?>
									</b> <br>
								<?php } ?>
							</div>
							<!--Dirección: <b class = 'deudor_direccion'> <?php echo $data_deudor['Data']['Direccion'] ?></b>-->
						</div>
						<div class = "right">
							Asignado: <b class = 'deudor_fecha_asig'><?php
					$fecha_asig = explode(' ',$deudores[0]['Cobranza']['FECH_ASIG']);
					echo $fecha_asig[0]?></b><br>
							Próxima Gestión: <b class = 'deudor_proxima_ges'><?php print_r($deudores[0]['ClienGest']['proximag']);?></b> <br>
							 Nombre del Banco: <b class = 'deudor_banco'><?php echo($empresas[0]['Cliente']['nombre']);?></b>
						</div>
					</fieldset>
				</div>
			</fieldset>
			<fieldset>
				<legend> Gestiones  </legend>

					<div class = "tabla_gestiones" id ="tabla_gestiones">
						<table style  = "width: 795px; float: left;">
							<tr class="encabezado">
								<th> Nro	</th>
								<th> Fecha</th>
								<th> Teléfono </th>
								<th> Producto </th>
								<th> cond_deud	</th>
								<th> proximag </th>
								<th> contacto </th>
								<th> Gestor </th>
								<th> Supervisor </th>
							</tr>
							<tbody id="tab_gest">
							<?php //print_r($gestiones[11][0]['ClienGest']);
							foreach($gestiones as $gestion);
							?>
							<?php
								$index=0;
								for($i=0; $i<count($gestion); $i++){?>
								<?php 
								$c=0;
								if ($i==0) {
									$clase = 'seleccionado';
								} else {
									$clase = '';
								}
							?>
							<tr class="inner_gestiones <?php echo $clase ?>" name="<?php echo $gestion[$index]['ClienGest']['id'] ;?>">
								<td><?php echo $gestion[$index]['ClienGest']['numero'];?></td>
								<td><?php $fecha=explode(" ",$gestion[$index]['ClienGest']['fecha']); echo $fecha[0]; ?></td>
								<td><?php echo $gestion[$index]['ClienGest']['telefono'];?></td>
								<td><?php echo $gestion[$index]['ClienGest']['producto'];?></td>
								<td><?php echo $gestion[$index]['ClienGest']['cond_deud'];?></td>
								<td><?php echo $gestion[$index]['ClienGest']['proximag'];?></td>
								<td><?php echo $gestion[$index]['ClienGest']['contacto'];?></td>
								<td><?php echo $gestion[$index]['Cobranza']['GESTOR'];?></td>
								<td><?php echo $supervisor;?></td>
							</tr>
							<?php $index ++; $c++;} ?>
							</tbody>
						</table>
					</div>
					<div class = "botones_gestiones" style = "float: left;margin-left: -20px;margin-right: 15px;">
						<?php echo $this->Html->image('listEdit.png',array('style' => 'margin-bottom:13px','title' => 'Modficar gestión','class' => 'click_gestion', 'name' => 'editar')); ?><br>
						<?php
//                        if ($e['Cliente']['rif'] == 11) {
                            echo $this->Html->image('listDelete.gif',array('style' => 'margin-bottom:13px','title' => 'Eliminar gestión')).'<br>';
//                        }
                        ?>
                      <?php echo $this->Html->image('msj.png',array('style' => 'width:16px;','title' => 'Enviar mensaje al gestor')); ?>
					</div>
					<?php 
					if ($c==0) {
						$clase = 'comentario_seleccionado';
					} else {
						$clase = "";
					}
					?>
					<div class = "comentario_gestiones <?php echo $clase?>" style = "float: left; width: 100px;">
						
						<label for="input_comentario">Comentarios</label>
						<textarea name="data[Comentarios]" class="large_input input_comentario" id="input_comentario" cols="30" rows="6">
							<?php if(!empty($comentarios['ClienGest']['observac'])){echo $comentarios['ClienGest']['observac'];}?>
						</textarea>
						
						<label for="input_comentario2">Comentarios</label>
						<textarea name="data[Comentarios]" class="large_input" id="input_comentario2" cols="30" rows="6">
							<?php if(!empty($comentarios['ClienGest']['observac1'])){echo $comentarios['ClienGest']['observac'];}?>
						</textarea>
					</div>
			</fieldset>
			<fieldset>
				<legend> Productos  </legend>
				<div class = "tabla_productos">
					<table class = "inner_tabla_productos"  style="width:795px;">
							<tr>
								<th> Producto 	</th>
								<th> Cuenta	</th>
								<th> Capital</th>
								<th> Intereses 	</th>
								<th> MtoTotal	</th>
								<th> DiasMora	</th>
								<th> Cuotas	</th>
								<th> MontoCuota	</th>
								<th> CapInicial	</th>
								<th> CuentaAsocPago	</th>
								<th> Rif_emp	</th>
								<th> Contrato	</th>
								<th> DescProd1	</th>
								<th> DescProd2	</th>
							</tr>
							<tbody id="tab_prod">
							<?php //print_r($datos_producto);
								$index=0;
								foreach($datos_producto as $dp){
								if($index==0){
									$class="seleccionado";
								}else{
									$class="";
								}
								?>								
								<tr class = "inner_gestiones <?php echo $class;?>">
									<td><?php if(!empty($dp['cp']['PRODUCTO'])){echo $dp['cp']['PRODUCTO'];}?></td>
									<td><?php if(!empty($dp['cp']['CUENTA'])){echo $dp['cp']['CUENTA'];}?></td>
									<td><?php if(!empty($dp['cp']['SaldoInicial'])){echo $dp['cp']['SaldoInicial'];}?></td>
									<td><?php if(!empty($dp['cp']['interes'])){echo $dp['cp']['interes'];}?></td>
									<td><?php if(!empty($dp['cp']['MtoTotal'])){echo $dp['cp']['MtoTotal'];}else{ echo 0;}?></td>
									<td><?php if(!empty($dp['cp']['DIASMORA'])){echo $dp['cp']['DIASMORA'];}?></td>
									<td><?php if(!empty($dp['cp']['NroCuotas'])){echo $dp['cp']['NroCuotas'];}else{ echo 0;}?></td>
									<td><?php if(!empty($dp['cp']['NroCuotas'])){echo $dp['cp']['NroCuotas'];}else{ echo 0;}?></td>
									<td>0<!--Capital Inicial--></td>
									<td><?php if(!empty($dp['cp']['CtaAsocPago'])){echo $dp['cp']['CtaAsocPago'];}else{ echo 0;}?></td>
									<td><?php if(!empty($dp['cp']['RIF_EMP'])){echo $dp['cp']['RIF_EMP'];}else{ echo 0;}?></td>
									<td><?php if(!empty($dp['cp']['Contrato'])){echo $dp['cp']['Contrato'];}else{ echo 0;}?></td>
									<td><?php if(!empty($dp['cp']['DescProd1'])){echo $dp['cp']['DescProd1'];}else{ echo "";}?></td>
									<td><?php if(!empty($dp['cp']['DescProd2'])){echo $dp['cp']['DescProd2'];}else{ echo "";}?></td>
								</tr>
							<?php $index ++; } ?>
							</tbody>
						</table>
					</div>
			</fieldset>
			<fieldset>
				<legend> Estado de Cuenta  </legend>
				<div class = "tabla_edo_cuenta">
					<table style = "float: left; width: 795px;" class = 'inner_tabla_edo_cuentas'>
						<tr class="encabezado">
							<th> Fech_Reg 	</th>
							<th> Fech_Pago 	</th>
							<th> Total_Pago	</th>
							<th> Producto 	</th>
							<th> Cuenta	</th>
							<th> Est_Pago	</th>
							<th> efectivo	</th>
							<th> mto_cheq1	</th>
							<th> mto_otros	</th>
							<th> nro_efect	</th>
							<th> nro_otro	</th>
							<th> cond_pago	</th>
							<th> login_reg	</th>
							
						</tr>
						<tbody id="tab_est_cuenta">
						<?php //print_r($estado_cuenta);
						$cc=0;
						foreach($estado_cuenta as $ec){
							if($cc==0){
								$class="seleccionado";
							}else{
								$$class="";
							}
						?>
							<tr class = "inner_pagos <?php echo $class;?>" style="font-size:11px">
								<td><?php echo $ec['cpg']['FECH_REG'];?></td>
								<td><?php echo $ec['cpg']['FECH_PAGO'];?></td>
								<td><?php echo $ec['cpg']['TOTAL_PAGO'];?></td>
								<td><?php echo $ec['cpg']['PRODUCTO'];?></td>
								<td><?php echo $ec['cpg']['CUENTA'];?></td>
								<td><?php echo $ec['cpg']['EST_PAGO'];?></td>
								<td><?php echo $ec['cpg']['EFECTIVO'];?></td>
								<td><?php echo $ec['cpg']['MTO_CHEQ1'];?></td>
								<td><?php echo $ec['cpg']['MTO_OTROS'];?></td>
								<td><?php echo $ec['cpg']['Nro_Efect'];?></td>
								<td><?php echo $ec['cpg']['NRO_OTRO'];?></td>
								<td><?php echo $ec['cpg']['COND_PAGO'];?></td>
								<td><?php echo $ec['cpg']['LOGIN_REG'];?></td>
							</tr>
						<?php $cc++; }?>
						</tbody>
					</table>
				</div>
					<div class = "comentario_estado">
						<?php
							/*echo $this->Form->input('Saldo Inicial', array(
								'class' => 'estado_input'
							));
							echo $this->Form->input('Saldo Actual', array(
								'class' => 'estado_input'
							));
							echo $this->Form->input('Pagos', array(
								'class' => 'estado_input'
							));*/
						?>
						<label for="Saldo Inicial">Saldo Inicial</label>
						<input name="data[Saldo Inicial]" value="<?php echo $capital=$dp['cp']['SaldoInicial'];?>" class="estado_input" type="text" id="SaldoInicial">
						
						<label for="Saldo Actual">Saldo Actual</label>
						<input name="data[Saldo Actual]" value="<?php echo $capital-$ec['cpg']['EFECTIVO'];?>" class="estado_input" type="text" id="SaldoActual">
						
						<label for="Pagos">Pagos</label>
						<input name="data[Pagos]" class="estado_input" type="text" id="Pagos">
					</div>
			</fieldset>
		</div>
	<?php 
		//$c = $c+1;
	//}
	?>
</div>
<div id = "editar_datos_deudor" style = "display:none;" title = "Modificando datos deudor">
	<?php echo $this->element('Gestion/editar_deudor'); ?>
</div>
<div id="editar_gestion" style="display:none" title="Modificando gestión">
	<?php echo $this->element('Gestion/editar_gestion'); ?>
</div>
<div id="agregar_telefono_deudor" style="display:none" title="Agregando Teléfono">
	<?php echo $this->element('Gestion/agregar_telefono'); ?>
<div id="vista_pagos" style="display:none" title="Recepción de Pagos">
	<?php echo $this->element('Gestion/pagos'); ?>
</div>
<script>
//Modificar datos de deudor
// mostrar_datos_modal();

	function mostrar_deudor_modal(){
		$('#editar_datos_deudor').dialog();
		deudor_nombre = $('.deudor_nombre:first').text();
		deudor_cedula = $('.deudor_cedula:first').text();
		deudor_cond_pago = $('.deudor_cond_pago:first').text();
		deudor_gestor = $('.deudor_gestor:first').text();
		deudor_supervisor = $('.deudor_supervisor:first').text();
		deudor_telefono = $('.deudor_telefono:first').text();
		deudor_direccion = $('.deudor_direccion:first').text();
		deudor_fecha_asig = $('.deudor_fecha_asig:first').text();
		deudor_proxima_ges = $('.deudor_proxima_ges:first').text();
		deudor_banco = $('.deudor_banco:first').text();
		
		$('#CobranzaNombre').val($.trim(deudor_nombre));
		$('#CobranzaCedulaorif').val($.trim(deudor_cedula));
		$('#DeudorClienGestTelefono').val($.trim(deudor_telefono));
		$('#StatuCondicion').val($.trim(deudor_cond_pago));
		$('#GestorNombre').val($.trim(deudor_gestor));
		$('#UserSupervisor').val($.trim(deudor_supervisor));
		$('#DataDireccion').val($.trim(deudor_direccion));
		$('#CobranzaFechAsig').val($.trim(deudor_fecha_asig));
		$('#ClienGestProximaG').val($.trim(deudor_proxima_ges));
		$('#CobranzaNombre').val($.trim(deudor_nombre));
	}

	$('.click_datos_deudor').click(function(){
		mostrar_deudor_modal();
	});

	$('.click_telefonos_deudor').click(function(){ // al hacer click en agregar, 
												   //muestra la modal para agregar nuevo telefono
		$('#agregar_telefono_deudor').dialog();
		
		deudor_cedula = $('.deudor_cedula:first').text();
		$('#cedulaDeudor').val($.trim(deudor_cedula));
	});
	
// Fin Modificar datos de deudor

//Hacer click en una empresa
$("#pestanas_empresas").delegate('.nombre_empresa','click', function(){
	$('.right_wrap').hide();
	id = $(this).attr('name');
	//seleccion la tabla gestiones
	$('.tabla_gestiones').removeClass('tabla_gestiones_seleccionado');
	$('#'+id+' .tabla_gestiones').addClass('tabla_gestiones_seleccionado');
	//Selecciono el comentario para poderlo editar 
	$('.comentario_gestiones').removeClass('comentario_seleccionado');
	$('#'+id+' .comentario_gestiones').addClass('comentario_seleccionado');
	//Selecciono la primera gestion
	$('.inner_gestiones').removeClass('seleccionado');
	$('#'+id+' .tabla_gestiones tr.inner_gestiones').first().addClass('seleccionado');
	$('#'+id).show();
	
});

//Hacer click en una fila de la tabla deudores
$(".table_info").delegate('.tabla_deudores','click', function(){
	$(".tabla_deudores").removeClass('seleccionado'); 
	$(this).addClass('seleccionado');
	
	//Se carga la info de la parte derecha
	cedula = $(this).attr('name'); //Uso el name para saber a que deudor se le dio click
	ident=$(this).attr('id');
	$("#universo").val(ident);
	cargar_datamol(cedula);
	cargar_pestanas(cedula);
	cargar_info(cedula);
	//cargar_producto(cedula);
	//cargar_pagos(cedula);
	
});

function cargar_pestanas(cedula){ // carga toda la tabla de productos asociada a un deudor
	$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{cedula:cedula},
			url:"<?php echo Router::url(array('action'=>'cargar_empresas')); ?>",
			success:function(data){
				//Cargo las pestanas de las empresas
				i = 0;
				empresas = '';
				$.each(data,function(i,v){
					if (i == 0) {
						//$('.right_wrap').hide();
						$('#'+v.Cliente.rif).show(); //Esto es para que se este mostrando siempre la primera pestaña
					}
					empresas+= '<div class = "inner_pestanas"><div style="cursor:pointer" name ="'+v.Cliente.rif+'" class="nombre_empresa">'+v.Cliente.nombre+'</div></div>';
					i++;
				});
				$('#pestanas_empresas').html(empresas);
				//prueba(); // llama a la función para cambiar el estilo de las pestañas seleccionadas
			},error: function() {
				alert("error_empresa");
			}
	});
}


/*function cargar_producto(cedula){ // carga toda la tabla de productos asociada a un deudor
	$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{cedula:cedula},
			url:"<?php echo Router::url(array('action'=>'cargar_info_producto')); ?>",
			success:function(data){
				//Cargo producto del deudor
				 producto_datos = data.productos[0].Producto.CUENTA
				// console.debug(total = data);
				$.each(data.empresas,function(a,b){
					i = 0;
					info_producto = '';
					info_producto += '<tr><th> Producto 	</th>';
					info_producto += '<th> Cuenta </th>';
					info_producto += '<th> Capital</th>';
					info_producto += '<th> Intereses 	</th>';
					info_producto += '<th> MtoTotal	</th>';
					info_producto += '<th> DiasMora	</th>';
					info_producto += '<th> Cuotas	</th>';
					info_producto += '<th> MontoCuota	</th>';
					info_producto += '<th> CapInicial	</th>';
					info_producto += '<th> CuentaAsocPago	</th>';
					info_producto += '<th> Rif_emp	</th>';
					info_producto += '<th> Contrato	</th>';
					info_producto += '<th> DescProd1	</th>';
					info_producto += '<th> DescProd2	</th></tr>';
					$.each(data.productos[b.Cliente.rif],function(i,v){
						// console.debug(v.ClienProd.Contrato);
						if (i==0) {
							clase = 'seleccionado';
						} else {
							clase = '';
						}
						info_producto += '<tr class = " inner_gestiones ' + clase +'"><td>'+v.Producto.producto+'</td>';
						info_producto += '<td>'+v.ClienProd.CUENTA+'</td>';
						info_producto += '<td>'+v.ClienProd.SaldoInicial+'</td>';
						info_producto += '<td>'+v.ClienProd.Interes+'</td>';
						info_producto += '<td>'+v.ClienProd.MtoTotal+'</td>';
						info_producto += '<td>'+v.ClienProd.DIASMORA+'</td>';
						info_producto += '<td>'+v.ClienProd.NroCuotas+'</td>';
						info_producto += '<td>'+v.ClienProd.NroCuotas+'</td>';
						info_producto += '<td>'+v.ClienProd.NroCuotas+'</td>';
						info_producto += '<td>'+v.ClienProd.CtaAsocPago+'</td>';
						info_producto += '<td>'+v.ClienProd.RIF_EMP+'</td>';
						info_producto += '<td>'+v.ClienProd.Contrato+'</td>';
						info_producto += '<td>'+v.ClienProd.DescProd1+'</td>';
						info_producto += '<td>'+v.ClienProd.DescProd2+'</td>';
						info_producto += '</tr>';
						
						i++;
					});
					$('#'+b.Cliente.rif+' .tabla_productos .inner_tabla_productos').html(info_producto);
				});
			},error: function() {
				alert("error_producto");
			}
	});
}*/

/*function cargar_pagos(cedula){ // carga toda la tabla de productos asociada a un deudor
	$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{cedula:cedula},
			url:"<?php echo Router::url(array('action'=>'cargar_info_pagos')); ?>",
			success:function(data){
				//Cargo producto del deudor
				// producto_datos = data.productos[0].Producto.CUENTA
				// console.debug(total = data);
				$.each(data.empresas,function(a,b){
					i = 0;
					info_pago = '';
					info_pago += '<tr class="encabezado"><th> Fech_Reg 	</th>';
					info_pago += '<th> Fech_Pago 	</th>';
					info_pago += '<th> Total_Pago	</th>';
					info_pago += '<th> Producto 	</th>';
					info_pago += '<th> Cuenta	</th>';
					info_pago += '<th> Est_Pago	</th>';
					info_pago += '<th> efectivo	</th>';
					info_pago += '<th> mto_cheq1	</th>';
					info_pago += '<th> mto_otros	</th>';
					info_pago += '<th> nro_efect	</th>';
					info_pago += '<th> nro_otro	</th>';
					info_pago += '<th> cond_pago	</th>';
					info_pago += '<th> login_reg	</th>';
					info_pago += '</tr>';
					$.each(data.pagos[b.Cliente.rif],function(i,v){
						// console.debug(v.ClienProd.Contrato);
						if (i==0) {
							clase = 'seleccionado';
						} else {
							clase = '';
						}
						info_pago += '<tr class =" inner_pagos ' + clase +'"><td>'+v.ClienPago.FECH_REG+'</td>';
						info_pago += '<td>'+v.ClienPago.FECH_PAGO+'</td>';
						info_pago += '<td>'+v.ClienPago.TOTAL_PAGO+'</td>';
						info_pago += '<td>'+v.ClienPago.PRODUCTO+'</td>';
						info_pago += '<td>'+v.ClienPago.CUENTA+'</td>';
						info_pago += '<td>'+v.ClienPago.EST_PAGO+'</td>';
						info_pago += '<td>'+v.ClienPago.EFECTIVO+'</td>';
						info_pago += '<td>'+v.ClienPago.MTO_CHEQ1+'</td>';
						info_pago += '<td>'+v.ClienPago.MTO_OTROS+'</td>';
						info_pago += '<td>'+v.ClienPago.Nro_Efect+'</td>';
						info_pago += '<td>'+v.ClienPago.NRO_OTRO+'</td>';
						info_pago += '<td>'+v.ClienPago.COND_PAGO+'</td>';
						info_pago += '<td>'+v.ClienPago.LOGIN_REG+'</td>';
						info_pago += '</tr>';
						
						i++;
					});
					$('#'+b.Cliente.rif+' .tabla_edo_cuenta .inner_tabla_edo_cuentas').html(info_pago);
					
					$('#'+b.Cliente.rif+' .estado_input').val(Math.random()*100);
				});
			},error: function() {
				alert("error_pagos");
			}
	});
}*/

$(".inner_tabla_productos").delegate('.inner_gestiones','click', function(){
	$(".inner_tabla_productos .inner_gestiones").removeClass('seleccionado');
	$(this).addClass('seleccionado');
});

$(".tabla_edo_cuenta").delegate('.inner_gestiones','click', function(){
	$(".tabla_edo_cuenta .inner_gestiones").removeClass('seleccionado');
	$(this).addClass('seleccionado');
	var ci = $(this).attr('name');
	actualizaCifrasCuenta(ci);
});

$(".tabla_gestiones").delegate('.inner_gestiones','click', function(){
	$(".tabla_gestiones .inner_gestiones").removeClass('seleccionado');
	$(this).addClass('seleccionado');
	
	//Cargar la info del comentario
	gestion_id = $(this).attr('name');
	cargar_info_comentario(gestion_id);
});

function cargar_info_comentario(gestion_id) {
	$.ajax({
		type:'POST',
		dataType:'JSON',
		data:{gestion_id:gestion_id},
		url:"<?php echo Router::url(array('action'=>'cargar_info_comentario')); ?>",
		success:function(data){
			$('#input_comentario').val(data.observacion);
			$('#input_comentario2').val(data.comentario2);
		},error: function() {
			alert("error_comentario");
		}
	});
}


////////////METODOS DESARROLLADOS POR JUAN CARLOS //////////
/////FUNCTION QUE ACTUALIZA LOS DATOS DEL DEUDOR (DATOS DEUDOR)
var ci;
function cargar_info(cedula){
	var empresa;
	$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{cedula:cedula},
			url:"<?php echo Router::url(array('action'=>'enviaDatosDeudor')); ?>",
			success:function(data){
				if(data){
					var fecha = data.asignado.split(" ");
					$('.deudor_nombre').html(data.nombre);
					$('.deudor_cedula').html(data.ci);
					$('.deudor_cond_pago').html(data.status);
					$('.deudor_gestor').html(data.gestor);
					$('.deudor_fecha_asig').html(fecha);
					$('.deudor_proxima_ges').html(data.proximag);
					$('.deudor_banco').html(data.banco);
					ci=data.ci;
					empresa=data.rif;
					actualizaDatosGestion(cedula, data.rif);
				}else{
					return false;
				}
			}
	});
	actualizaDatosTelefonos(cedula);
	actualizaDatosProductos(cedula);
	actualizaEstadosCuentas(cedula);
	actualizaCifrasCuenta(cedula);
	cargar_pestanas(cedula);
}


///////FUNCTION QUE ACTULIZA LOS DATOS TELEFONOS Y DIRECCION EN EL BLOQUE DATOS DEUDOR
function actualizaDatosTelefonos(cedula){
	$('.deudor_telefono').empty().append('');
	$('.deudor_direccion').empty().append('');
	$('.deudor_telefonos').empty().append('');
	$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{cedula:cedula},
			url:"<?php echo Router::url(array('action'=>'enviaDatosTelefono')); ?>",
			success:function(data){
				if(data){
					for(var i=0; i<data.length; i++){
						var telefono = $('<span>'+data[i].telefono+'</span><span> - '+data[i].ubicacion+'</span>');
						telefono.appendTo('.deudor_telefonos');
					}
				}else{
					return false;
				}
			}
	});
}

////FUNCION QUE PERMITE ACTUALIZAR EL BLOQUE "DATOS GESTIONES"
function actualizaDatosGestion(cedula, empresa){
	var cont=0;
	$("#tab_gest").empty().append('');
	$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{cedula:cedula, empresa:empresa},
			url:"<?php echo Router::url(array('action'=>'enviaDatosGestiones')); ?>",
			success:function(data){
				if(data){
					for(var i=0; i<data.length; i++){
						if(i==0){
							var select = 'seleccionado';
						}else select='';
					var gestions=$('<tr class="inner_gestiones '+select+'" name="'+data[i].id+'"><td>'+data[i].numero+'</td><td>'+data[i].fecha+
						'</td><td>'+data[i].telefono+'</td><td>'+data[i].producto+
						'</td><td>'+data[i].condicion+'</td><td>'+data[i].proximag+
						'</td><td>'+data[i].contacto+'</td><td>'+data[i].gestor+
						'</td><td>'+data[i].supvisor+
						'</td></tr>');
						gestions.appendTo("#tab_gest");
					}
					cargar_info_comentario(data[0].id);
				}//else{
					//return false;
				//}
			}
	});
}

//////FUNCTION QUE PERMITE ACTUALIZAR LOS DATOS DEL BLOQUE Productos
function actualizaDatosProductos(cedula){
	$("#tab_prod").empty().append('');
	$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{cedula:cedula},
			url:"<?php echo Router::url(array('action'=>'enviaDatosProductos')); ?>",
			success:function(data){
				if(data){
					for(var i=0; i<data.length; i++){
					if(i==0){
						var select = 'seleccionado';
					}else select='';
					var productos = $('<tr class="inner_gestiones '+select+'"><td>'+data[i].pdto+
					'</td><td>'+data[i].cuenta+'</td><td>'+data[i].saldoI+
					'</td><td>'+data[i].interes+'</td><td>'+data[i].mTotal+
					'</td><td>'+data[i].dMora+'</td><td>'+data[i].NroCuotas+
					'</td><td>'+0+'</td><td>'+0+
					'</td><td>'+data[i].CtaAsocPago+'</td><td>'+data[i].rif+
					'</td><td>'+data[i].contrato+'</td><td>'+data[i].desc1+
					'</td><td>'+data[i].desc2+
					'</td></tr>');
						
					productos.appendTo("#tab_prod");
					}
				}//else{
					//return false;
				//}
			}
	});
}

function actualizaEstadosCuentas(cedula){
	$("#tab_est_cuenta").empty().append('');
	$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{cedula:cedula},
			url:"<?php echo Router::url(array('action'=>'enviaDatosEstadosCuentas')); ?>",
			success:function(data){
				for(var i=0; i<data.length; i++){
					if(i==0){
						var select = 'seleccionado';
					}else select='';
		var estadosC = $('<tr style="font-size:11px" class="inner_gestiones '+select+'" name="'+data[i].cedula+'"><td>'+data[i].fecha_reg+
					'</td><td>'+data[i].fecha_pago+'</td><td>'+data[i].total_pago+
					'</td><td>'+data[i].producto+'</td><td>'+data[i].cuenta+
					'</td><td>'+data[i].est_pago+'</td><td>'+data[i].efectivo+
					'</td><td>'+data[i].mto_cheq1+'</td><td>'+data[i].mto_otros+
					'</td><td>'+data[i].nro_efect+'</td><td>'+data[i].nro_otro+
					'</td><td>'+data[i].cond_pago+'</td><td>'+data[i].login_reg+
					'</td></tr>');
						
					estadosC.appendTo("#tab_est_cuenta");
					}
			}
	});
}
function actualizaCifrasCuenta(cedula){
	$("#SaldoInicial").val('');
	$("#SaldoActual").val('');
	$("#Pagos").val('');
	
	$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{cedula:cedula},
			url:"<?php echo Router::url(array('action'=>'enviaCifras')); ?>",
			success:function(data){
				$("#SaldoInicial").val(data.capital);
				$("#SaldoActual").val(parseFloat(data.capital) - parseFloat(data.total_pago));
				$("#Pagos").val(data.total_pago);
			}
	});
}

function extraerUniversoDeudores(dato){
	$(".tr_deudores").empty().append('');
	$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{cedula:dato},
			url:"<?php echo Router::url(array('action'=>'enviaDatosUniversoDeudores')); ?>",
			success:function(data){
				if(data){
					for(var i=0; i<data.length; i++){
						if (i==0) {
						clase = 'seleccionado';
						} else { clase = ''; }
		var estadosC = $('<tr id="'+(i+1)+'" class="tabla_deudores tr_deudores '+clase+'" name="'+data[i].cedula+'"><td>'+data[i].nombre+
						'</td><td>'+data[i].cedula+'</td><td>'+data[i].telefono+
						'</td><td>'+data[i].fech_asig+'</td><td>'+data[i].proximag+
						'</td><td>'+data[i].gestor+
						'</td></tr>');
						estadosC.appendTo("#tab_datos_deudor");
					}
					$("#universo").val(data.length);
					cargar_info(data[0].cedula);
					$("#universo").val($('.tr_deudores').attr('id'));
					$("#total_universo").val(data.length);
				}else{
					return false;
				}
			},
			beforeSend:function(){
				$("#select_supervisor").attr('disabled',true);
				loader();
			},
			complete:function(){
				$("#select_supervisor").removeAttr('disabled');
				//buscar_deudores();
				$('#preload').empty().append('');
			}
	});
}


/////////FINALIZAN METODOS DESARROLLADOS POR JUAN CARLOS//////////

//  calendario jquery

$('#pickDate').datepicker({
    dateFormat: "dd-mm-yy",
});

</script>