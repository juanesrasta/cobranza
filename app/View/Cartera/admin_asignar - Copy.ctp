<fieldset>
    <div class="filtro_izquierdo_asignar">
        <?php
        echo $this->Form->create('Pago');
        echo $this->Form->input('parametros',array(
            'type'=> 'checkbox',
            'label'=> false,
            'class' => 'check_asignar',
            'style' => array('margin-top:74px')
        ));
        echo $this->Form->input('boton',array(
            'type' => 'hidden',
            'id' => 'tipo_boton',
           'value' => 'consultar'
        ));
        ?>
        <fieldset class="fieldset_asignar_confirmacion" id="field_parametros">
            <legend>Asignacion Por Parametros</legend>
            <table class="table_asignar_cartera">
                <tr>
                    <td></td><td></td><td></td>
                    <td>Desde</td>
                    <td>Hasta</td>
                </tr>
                <tr>
                    <td>Empresa:</td>
                    <td>
                        <?php
                        echo $this->Form->input('cliente_id',array(
                            'label' => false,
                            'id' => 'select_cliente',
                            'style'=>'float:left',
                            'empty' => '',
                            'disabled' => true
                        ));
                        ?>
                    </td>
                    <td>Cedula o Rif</td>
                    <td><?php
                        echo $this->Form->input('cedula_desde',array('label'=>false,'disabled'=>true));
                        ?>
                    </td>
                    <td><?php
                        echo $this->Form->input('cedula_hasta',array('label'=>false,'disabled'=>true));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Producto:</td>
                    <td>
                        <?php
                        echo $this->Form->input('producto_id',array(
                            'label' => false,
                            'id' => 'select_producto',
                            'style'=>'float:left',
                            'disabled'=>true
                        ));
                        ?>
                    </td>
                    <td>Monto</td>
                    <td><?php
                        echo $this->Form->input('monto_desde',array('label'=>false,'disabled'=>true));
                        ?>
                    </td>
                    <td><?php
                        echo $this->Form->input('monto_hasta',array('label'=>false,'disabled'=>true));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Fecha Asig:</td>
                    <td>
                        <?php
                        echo $this->Form->input('fecha_asig',array(
                            'label' => false,
                            'id' => 'select_fecha',
                            'style'=>'float:left',
                            'disabled'=>true
                        ));
                        ?>
                    </td>
                    <td>D/Mora</td>
                    <td><?php
                        echo $this->Form->input('mora_desde',array('label'=>false,'disabled'=>true));
                        ?>
                    </td>
                    <td><?php
                        echo $this->Form->input('mora_hasta',array('label'=>false,'disabled'=>true));
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <?php
        echo $this->Form->input('traspaso',array(
            'type'=> 'checkbox',
            'label'=> false,
            'class' => 'check_asignar',
            'style' => array('margin-top:45px'),
        ));
        ?>
        <fieldset class="fieldset_asignar_confirmacion" id="field_traspaso">
            <legend>Asignacion Por Traspaso</legend>
            <table class="table_asignar_cartera">
                <tr>
                    <td>Gestor:</td>
                    <td>
                        <?php
                        echo $this->Form->input('clave_gestor',array(
                            'label' => false,
                            'options' => $gestors,
                            'id' => 'select_traspaso_gestor',
                            'style'=>'float:left',
                            'disabled' => true
                        ));
                        ?>
                </td>
                <td></td>
                <td></td><td></td>
                </tr>
                <tr>
                    <td>Empresa:</td>
                    <td>
                        <?php
                        echo $this->Form->input('traspaso_empresa_id',array(
                            'label' => false,
                            'id' => 'select_traspaso_empresa',
                            'style'=>'float:left',
                            'disabled' => true,
                            'options' => $clientes,
                            'empty' => ''
                        ));
                        ?>
                    </td>
                    <td>Gestion</td>
                    <td colspan="2"><?php
                        echo $this->Form->input('traspaso_gestion_id',array(
                            'label' => false,
                            'id' => 'select_traspaso_gestion',
                            'style'=>'float:left',
                            'disabled' => true
                        ));
                        ?>
                    </td>

                </tr>
        </table>
    </fieldset>
        <?php
        echo $this->Form->input('directa',array(
            'type'=> 'checkbox',
            'label'=> false,
            'class' => 'check_asignar',
            'style' => array('margin-top:37px'),
        ));
        ?>
        <fieldset class="fieldset_asignar_confirmacion" id="field_directa">
            <legend>Asignacion directa</legend>
            <table class="table_asignar_cartera">
                <tr>
                    <td style="width: 43px;vertical-align: middle">Cedula:</td>
                    <td>
                        <?php
                        echo $this->Form->input('cedula_directa',array(
                            'label' => false,
                            'id' => 'select_cedula_directa',
                            'style'=>'float:left',
                            'disabled' => true
                        ));
                        ?>
                    </td>
                    <td></td>
                    <td></td><td></td>
                </tr>
            </table>
        </fieldset>
        <fieldset class="fieldset_asignar_confirmacion orden">
            <legend>Orden</legend>
            <?php
            echo $this->Form->radio('tipo_orden',array('ASC' => 'Asc','DESC' => 'Desc'),array('legend'=>false));
            echo '&nbsp&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp';
            echo $this->Form->radio('campo_orden',array('ClienProd.FechaReg' => 'Fecha','ClienProd.COD_PROD'=>'Producto','ClienProd.SaldoIncial' => 'Monto','ClienProd.DIASMORA' =>'Mora' ),array('legend'=>false));
            ?>
        </fieldset>
    </div>
    <div class="filtro_derecho_asignar">
        <?php
        echo $this->Form->submit('Consultar',array('disabled' => true,'id' => 'consultar_asignacion'));
        if(!empty($this->data)) {
            echo $this->Form->input('gestor_id', array(
                'label' => 'Asignar al gestor',
                'empty' => ''
            ));
            ?>
            <table>
                <tr>
                    <th>Casos</th>
                    <th>Cuentas</th>
                    <th>Monto en Bs.</th>
                </tr>
                <tr>
                    <td id="casos_operador"></td>
                    <td id="cuentas_operador"></td>
                    <td id="monto_operador"></td>
                </tr>
            </table>
            <?php
            echo $this->Form->submit('Asignar',array('onClick' => 'asignar_gestor()'));
            ?>
            <b>Cartera sin Asignar</b>
            <table>
                <tr>
                    <th>Casos</th>
                    <th>Cuentas</th>
                    <th>Monto en Bs.</th>
                </tr>
                <tr>
                    <td><?php echo $total_casos ?></td>
                    <td><?php echo $total_cuenta ?></td>
                    <td><?php echo round($total_monto, 2) ?></td>
                </tr>
            </table>
        <?php
        } ?>
    </div>
</fieldset>
<?php
if (!empty($this->data) && $this->data['Pago']['parametros'] == '1') {
    if (!empty($deudores)) { ?>
        <table>
            <tr>
                <th>Cedula o Rif</th>
                <th>Nombre</th>
                <th>Fecha Asig</th>
                <th>Producto</th>
                <th>Monto</th>
                <th>DiasMora</th>
                <th>
                    <?php echo $this->Form->input('seleccionar_todos',array(
                        'type' => 'checkbox',
                        'id'=> 'seleccionar_deudores',
                        'label' => false,
                    )) ?>
                </th>
            </tr>
            <?php
                foreach ($deudores as $n) {
                    ?>
                    <tr>
                        <td><?php echo $n['NuevoDeudor']['CEDULAORIF'] ?></td>
                        <td><?php echo $n['NuevoDeudor']['NOMBRE'] ?></td>
                        <td><?php echo $n['ClienProd']['FECHA_REG'] ?></td>
                        <td><?php echo $n['ClienProd']['PRODUCTO'] ?></td>
                        <td><?php echo $n['ClienProd']['SaldoInicial'] ?></td>
                        <td><?php echo $n['ClienProd']['DIASMORA'] ?></td>
                        <td><?php
                            echo $this->Form->input('deudores',array(
                                'name' => 'deudores_seleccionados['.$n['NuevoDeudor']['id'].']',
                                'type' => 'checkbox',
                                'label' => false,
                                'class' => 'check_deudores'
                            ));
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            ?>
        </table>
    <?php
    }
}?>

<script>
    function asignar_gestor(){
        //Si no ha seleccionado ningun operador da error
        if ($('#PagoGestorId').val() == '') {
            $('#tipo_boton').val('sin_operador');
            alert('Se debe seleccionar un operador');
        } else {
            $('#tipo_boton').val('asignar');
        }
    }

    $( "#PagoAdminAsignarForm" ).submit(function( event ) {
        if ($('#tipo_boton').val() == 'sin_operador') {
            return false;
        }
    });

    $('.check_asignar').change(function(){
        if($(this).is(':checked')) {
            $('#consultar_asignacion').prop('disabled',false);
            $('.check_asignar').attr('checked', false);
            $(this).prop('checked',true);
            //Chequeo cual hizo check y cargo los datos del filtro
            name_check = $(this).prop('name');
            if (name_check == 'data[Pago][parametros]') {
                $('#field_parametros').find('input, select').prop('disabled',false);
                $('#field_traspaso').find('input, select').prop('disabled',true);
                $('#field_directa').find('input, select').prop('disabled',true);
            }else if (name_check == 'data[Pago][traspaso]') {
                $('#field_parametros').find('input, select').prop('disabled',true);
                $('#field_traspaso').find('input, select').prop('disabled',false);
                $('#field_directa').find('input, select').prop('disabled',true);
            }else if (name_check == 'data[Pago][directa]') {
                $('#field_parametros').find('input, select').prop('disabled',true);
                $('#field_traspaso').find('input, select').prop('disabled',true);
                $('#field_directa').find('input, select').prop('disabled',false);
            }
        } else {
            $('#consultar_asignacion').prop('disabled',true);
            $('#field_parametros').find('input, select').prop('disabled',true);
            $('#field_traspaso').find('input, select').prop('disabled',true);
            $('#field_directa').find('input, select').prop('disabled',true);
        }
    });
    $('#select_cliente').change(function(){
        $.ajax({
            type:'POST',
            dataType:'JSON',
            data:{cliente_id:$("#select_cliente").val()},
            url:"<?php echo Router::url(array('controller' => 'pagos','action'=>'cargar_productos_por_empresa')); ?>",
            success:function(data){
                options_p = "<option value = ''>TODOS</option>";
                $.each(data, function (i, v) {
                    options_p += "<option value='" + i + "'>" + v + "</option>";
                });
                $("#select_producto").html(options_p);
            },
            beforeSend:function(){
                $("#select_cliente").attr('disabled',true);
            },
            complete:function(){
                $("#select_cliente").removeAttr('disabled');
            }

        });
    });

    $('#select_traspaso_empresa').change(function(){
        $.ajax({
            type:'POST',
            dataType:'JSON',
            data:{cliente_id:$("#select_traspaso_empresa").val()},
            url:"<?php echo Router::url(array('controller' => 'cartera','action'=>'cargar_status_por_empresa')); ?>",
            success:function(data){
                options_p = "<option value = ''>TODOS</option>";
                $.each(data, function (i, v) {
                    options_p += "<option value='" + i + "'>" + v + "</option>";
                });
                $("#select_traspaso_gestion").html(options_p);
            },
            beforeSend:function(){
                $("#select_traspaso_empresa").attr('disabled',true);
            },
            complete:function(){
                $("#select_traspaso_empresa").removeAttr('disabled');
            }

        });
    });

    $('#select_fecha').datepicker({
        dateFormat: "dd-mm-yy",
    });
    $('#PagoGestorId').change(function(){
        gestor_id = $(this).val();
        $.ajax({
            type:'POST',
            dataType:'JSON',
            data:{gestor_id:gestor_id},
            url:"<?php echo Router::url(array('controller' => 'cartera','action'=>'buscar_cuentas_por_gestor')); ?>",
            success:function(data){
                console.debug(data);
                $('#casos_operador').html(data.casos);
                $('#cuentas_operador').html(data.cuentas);
                $('#monto_operador').html(data.monto);
            },
            beforeSend:function(){
                $("#PagoGestorId").attr('disabled',true);
            },
            complete:function(){
                $("#PagoGestorId").removeAttr('disabled');
            }

        });
    });

    $('#seleccionar_deudores').change(function() {
        if ($(this).is(':checked')) {
            $('check_deudores').prop('checked',true);
        } else {
            $('check_deudores').prop('checked',false);
        }
    });

</script>