<div id="bloquea" style="width: 20%;height: 80px;overflow: hidden;z-index: 10000;position: relative;text-align: center;background: #FFFFFF;margin-left: auto;margin-right: auto;display:none">
	<?php echo $this->Html->image('enviando.gif',array('style' => 'margin-top:20px')) ?>
</div>
<div style="float:left; margin-top:8px" class="filtro_gestiones_dia">
<?php
	//echo $this->Form->input('supervisor_id',array('options' => $supervisores,'class'=>'filtro_supervisor','value'=>0)). '<br><br>';
?>

<label for="supervisor_id">Supervisor</label>
<select name="data[supervisor_id]" class="filtro_supervisor" id="supervisor_id">
	<option value="0">TODOS</option>
	<?php foreach($supervisores as $spvisor){?>
		<option value="<?=$spvisor['gestors']['id'];?>"><?=$spvisor['gestors']['Nombre'];?></option>
	<?php }?>
</select>
</div>
<div style="float:right;font-weight: bold;font-size: 15px;margin-top: 13px;margin-right: 33px;">
	<span style="font-weight:normal">Fecha:</span> <?php echo $date;?>
</div>
<div class="float_left gestiones_x_dia">
	<span style="font-size:15px; font-weight:bold">Descripcion por Gestor</span>
	<table class="table_gestores" style="font-size:13px">
		<tr>
			<th>Nombre</th>
			<th>Nuevas</th>
			<th>Agenda</th>
			<th>Atraso</th>
			<th>Realizadas</th>
			<th>Supervisor</th>
		</tr>
		<?php
		$i = 0;
		foreach ($gestores as $d) {
			if ($i == 0) {
				$class = "seleccionado";
			} else {
				$class = "";
			}
		?>
			<tr class="gestores_dia <?php echo $class ?>" name="<?php echo $d['Gestor']['Clave']?>">
				<td><?php echo utf8_encode($d['Gestor']['Nombre']);?></td>
				<td><?php echo $nuevas[$i][0][0]['nuevas']?></td>
				<td><?php echo $agendas[$i][0][0]['agenda'];?></td>
				<td><?php echo $atrasadas[$i][0][0]['atrasadas'];?></td>
				<td><?php if(!empty($realizadas[$i][0][0]['realizadas'])){echo $realizadas[$i][0][0]['realizadas'];}else{ echo 0;}?></td>
				<td><?php if(!empty($supervisor[$i][0]['gtr1']['Nombre'])){echo $supervisor[$i][0]['gtr1']['Nombre'];}else{echo "";}?></td>
			</tr>
		<?php
			$i++;
		}
		?>
	</table>
</div>
<div class="gestiones_realizadas">
	<span style="font-size:15px; font-weight:bold">Gestiones Realizadas en el día</span>
	<table class="table_gestiones_realizadas" style="font-size:14px;">
		<tr>
			<th>Fecha</th>
			<th>Tipo Gestión</th>
			<th>Obervación</th>
		</tr>
		<?php 
		 if (!empty($gest_realizadas)) {
			$i = 0;
			foreach($gest_realizadas as $r) {
				if ($i == 0) {
					$clase = "seleccionado";
				} else {
					$clase = "";
				}
				?>
				<tr class="tr_gestiones_realizadas <?php echo $clase?>">
					<td><?php echo $r['ClienGest']['fecha'] ?></td>
					<td><?php echo $r['ClienGest']['cond_deud'] ?></td>
					<td><?php echo $r['ClienGest']['observac'] ?></td>
				</tr>
				<?php
			}
		 }
		?>
	</table>
</div>
<script>
	$('.table_gestores').delegate('.gestores_dia','click',function(){
		//Selecciono el gestor
		$('.gestores_dia').removeClass('seleccionado');
		$(this).addClass('seleccionado');
		
		//Busco sus gestiones realizadas
		gestor = $(this).attr('name');
		$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{gestor:gestor},
			url:"<?php echo Router::url(array('action'=>'buscar_gestiones_realizadas')); ?>",
			success:function(data){
				//Coloco las gestiones realizadas
				$('.tr_gestiones_realizadas ').remove();
				i = 0;
				tabla_gestiones_realizadas = '';
				$.each(data.gest_realizadas,function(a,b){
					if (i == 0) {
						clase="seleccionado";
					} else {
						clase = '';
					}
					
					tabla_gestiones_realizadas += '<tr class="tr_gestiones_realizadas '+clase+'">';
					tabla_gestiones_realizadas += '<td>'+b.ClienGest.fecha+'</td>';
					tabla_gestiones_realizadas += '<td>'+b.ClienGest.cond_deud+'</td>';
					tabla_gestiones_realizadas += '<td>'+b.ClienGest.observac+'</td>';
					tabla_gestiones_realizadas += '</tr>';
					i++;
				});
				$(".table_gestiones_realizadas").append(tabla_gestiones_realizadas);
			},
			beforeSend:function(){
				loader();
			},
			complete:function(){
				//buscar_deudores();
				$('#preload').empty().append('');
			}
		});
	});
	$('.table_gestiones_realizadas').delegate('.tr_gestiones_realizadas','click',function(){
		$('.tr_gestiones_realizadas').removeClass('seleccionado');
		$(this).addClass('seleccionado');
	});
	
	$('.filtro_supervisor').change(function(){
		supervisor = $(this).val();
		$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{supervisor:supervisor},
			url:"<?php echo Router::url(array('action'=>'buscar_gestores')); ?>",
			success:function(data){
				$('.gestores_dia').empty().append('');
				$('.tr_gestiones_realizadas ').empty().append('');
				for(var i=0; i<data.length; i++){
					if (i == 0) {
						clase="seleccionado";
					} else {
						clase = '';
					}
					var gestores = $('<tr class="gestores_dia '+clase+'" name="'+data[i].clave+'"><td>'+data[i].gestor+
					'</td><td>'+data[i].nuevas+'</td><td>'+data[i].agendas+
					'</td><td>'+data[i].atrasadas+'</td><td>'+data[i].realizadas+
					'</td><td>'+data[i].spvisor+
					'</td></tr>');
					gestores.appendTo(".table_gestores");
				}
				var gestion_hoy = $('<tr class="tr_gestiones_realizadas '+clase+'"><td>'+data[0].fecha_reg+
					'</td><td>'+data[0].condicion+'</td><td>'+data[0].observacion+
					'</td></tr>');
					
					gestion_hoy.appendTo(".table_gestiones_realizadas");	
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
	});
	
	
	function loader(){
		$('#preload').append('<div><img width="40" src="/cobranza/app/webroot/img/status.gif"/></div>');
		$('#preload').css({'position':'absolute','z-index':'1','margin-top':'25%','margin-left':'49%'});
	}
</script>