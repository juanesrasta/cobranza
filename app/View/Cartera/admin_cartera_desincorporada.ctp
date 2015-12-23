<div class = "all" style="font-size:12px">
    <div class = "left">
        <fieldset style = "height: 100%;">
            <legend> Cartera Asignada </legend>
            <?php echo $this->element('Cartera/filtro_desincorporados')?>
            <div class = "clear"> </div>
            <div class = "table_info" id = "table_info_deudores" style = "max-height: 150px;">
                <div class = "inner_table">
                    <table style="width:886px;" class="table_info_deudores">
                        <tr>
                            <th> Cédula	</th>
                            <th> Nombre	</th>
                            <th> Empresa	</th>
                            <th> Gestor	</th>
                        </tr>
                        <?php
                        if (!empty($desincorporados)) {
                            $i = 0;
                            foreach ($desincorporados as $d) {
                                if ($i == 0) {
                                    $clase = 'seleccionado';
                                } else {
                                    $clase = '';
                                }
                                ?>
                                <tr class="<?php echo $clase?>">
                                    <td><?php echo $d['Desincorporado']['cedulaorif']?></td>
                                    <td><?php echo $d['Desincorporado']['nombre']?></td>
                                    <?php
                                    if ($d['Desincorporado']['rif_emp'] == 7){
                                        $empresa = 'Banco de Venezuela';
                                    } else {
                                        $empresa = 'Bicentenario';
                                    }
                                    ?>
                                    <td><?php echo $empresa ?></td>
                                    <td><?php echo $d['Desincorporado']['gestor']?></td>
                                </tr>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
            <br>
        </fieldset>
    </div>
    <div class = "right_wrap">
            <div class = "deudor_datos">
                <fieldset>
                    <legend> Datos del deudor </legend>
                    <?php
                    if(!empty($desincorporados)) {
                        $nombre = $desincorporados[0]['Desincorporado']['nombre'];
                        $cedula = $desincorporados[0]['Desincorporado']['cedulaorif'];
                        $status = $desincorporados[0]['Desincorporado']['status'];
                        $gestor = $desincorporados[0]['Desincorporado']['gestor'];
                        $fecha = $desincorporados[0]['ClienProd']['FECHA_REG'];
                        if ($desincorporados[0]['Desincorporado']['rif_emp'] = 7) {
                            $banco = 'Banco de Venezuela';
                        } else {
                            $banco = 'Banco Bicentenario';
                        }
                    } else {
                        $nombre = '';
                        $cedula = '';
                        $status = '';
                        $gestor = '';
                        $fecha = '';
                        $banco = '';
                    }
                    ?>
                    <div class = "left">
                        <b class = 'deudor_nombre' style="float:left"><?php echo $nombre ?></b>
                        <b class = 'deudor_cedula'><?php echo ' '.$cedula ?></b>
                        <br>
                        Status:<b class = 'deudor_cond_pago'><?php echo $status ?></b> <br>
                        Gestor: <b class = 'deudor_gestor'><?php echo $gestor ?> </b>
                    </div>
                    <div class = "right">
                        Asignado: <?php echo $fecha?></b> <br>
                        Nombre del Banco: <b class = 'deudor_banco'><?php echo $banco ?></b>
                    </div>
                </fieldset>
            </div>
            <fieldset>
                <legend> Gestiones  </legend>
                <div class = "tabla_gestiones" id ="tabla_gestiones">
                    <table style  = "width: 795px; float: left;">
                        <tr class="encabezado">
                            <th> Nro	</th>
                            <th> Fecha </th>
                            <th> Teléfono </th>
                            <th> Producto </th>
                            <th> cond_deud	</th>
                            <th> proximag </th>
                            <th> contacto </th>
                            <th> Gestor </th>
                        </tr>
                        <?php
                        if(!empty($gestiones)){
                            $i =0;
                            foreach($gestiones as $g){
                                if ($i == 0) {
                                    $clase = 'seleccionado';
                                } else {
                                    $clase = '';
                                }
                                ?>
                                <tr class="<?php echo $clase ?>">
                                    <td><?php echo $g['ClienGest']['numero']?></td>
                                    <td><?php echo $g['ClienGest']['fecha_reg']?></td>
                                    <td><?php echo $g['ClienGest']['telefono']?></td>
                                    <td><?php echo $g['ClienGest']['producto']?></td>
                                    <td><?php echo $g['ClienGest']['cond_deud']?></td>
                                    <td><?php echo $g['ClienGest']['proximag']?></td>
                                    <td><?php echo $g['ClienGest']['contacto']?></td>
                                    <td><?php echo $g['ClienGest']['gest_asig']?></td>
                                </tr>
                            <?php
                                $i++;
                            }
                        }
                        ?>
                    </table>
                </div>
                <div class = "comentario_gestiones" style = "float: left; width: 100px;">
                    <?php
                    if(!empty($gestiones)) {
                        $comentario1 = $gestiones[0]['ClienGest']['observac'];
                        $comentario2 = $gestiones[0]['ClienGest']['Observac1'];
                    } else {
                        $comentario1 = '';
                        $comentario2 = '';
                    }
                    echo $this->Form->input('Comentarios', array(
                        'class' => 'large_input input_comentario',
                        'type' => 'textarea',
                        'value' => $comentario1,
                        'id' => 'input_comentario',
                    ));
                    echo $this->Form->input('Comentarios', array(
                        'class' => 'large_input',
                        'type' => 'textarea',
                        'value' => $comentario2,
                        'id' => 'input_comentario2',
                    ));
                    ?>
                </div>
            </fieldset>
            <fieldset>
                <legend> Producto </legend>
                <div class = "tabla_productos">
                    <table class = "inner_tabla_productos"  style="width:795px;">
                        <tr>
                            <th> Producto 	</th>
                            <th> Cuenta	</th>
                            <th> Intereses 	</th>
                            <th> MtoTotal	</th>
                            <th> DiasMora	</th>
                            <th> Cuotas	</th>
                            <th> CapInicial	</th>
                            <th> CuentaAsocPago	</th>
                            <th> Contrato	</th>
                            <th> DescProd1	</th>
                            <th> DescProd2	</th>
                        </tr>
                        <?php
                        if (!empty($desincorporados)) {
                                ?>
                                <tr>
                                    <td><?php echo $desincorporados[0]['ClienProd']['COD_PROD']?></td>
                                    <td><?php echo $desincorporados[0]['ClienProd']['CUENTA']?></td>
                                    <td><?php echo $desincorporados[0]['ClienProd']['Interes']?></td>
                                    <td><?php echo $desincorporados[0]['ClienProd']['MtoTotal']?></td>
                                    <td><?php echo $desincorporados[0]['ClienProd']['DIASMORA']?></td>
                                    <td><?php echo $desincorporados[0]['ClienProd']['NroCuotas']?></td>
                                    <td><?php echo $desincorporados[0]['ClienProd']['SaldoInicial']?></td>
                                    <td><?php echo $desincorporados[0]['ClienProd']['CtaAsocPago']?></td>
                                    <td><?php echo $desincorporados[0]['ClienProd']['Contrato']?></td>
                                    <td><?php echo $desincorporados[0]['ClienProd']['DescProd1']?></td>
                                    <td><?php echo $desincorporados[0]['ClienProd']['DescProd2']?></td>

                                </tr>
                                <?php
                        }
                        ?>
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
                        <?php
                        if (!empty($pagos)) {
                            foreach($pagos as $p) {
                                ?>
                                <tr>
                                    <td><?php echo $p['ClienPago']['FECH_REG']?></td>
                                    <td><?php echo $p['ClienPago']['FECH_PAGO']?></td>
                                    <td><?php echo $p['ClienPago']['TOTAL_PAGO']?></td>
                                    <td><?php echo $p['ClienPago']['COD_PROD']?></td>
                                    <td><?php echo $p['ClienPago']['CUENTA']?></td>
                                    <td><?php echo $p['ClienPago']['FECH_REG']?></td>
                                    <td><?php echo $p['ClienPago']['EST_PAGO']?></td>
                                    <td><?php echo $p['ClienPago']['EFECTIVO']?></td>
                                    <td><?php echo $p['ClienPago']['MTO_CHEQ1']?></td>
                                    <td><?php echo $p['ClienPago']['MTO_OTROS']?></td>
                                    <td><?php echo $p['ClienPago']['Nro_Efect']?></td>
                                    <td><?php echo $p['ClienPago']['NRO_OTRO']?></td>
                                    <td><?php echo $p['ClienPago']['COND_PAGO']?></td>
                                    <td><?php echo $p['ClienPago']['LOGIN_REG']?></td>
                                </tr>
                            <?php
                            }
                        }
                        ?>
                    </table>
                </div>
                <div class = "comentario_estado">
                    <?php
                    if (!empty($desincorporados)) {
                        $saldo_inicial = $desincorporados[0]['ClienProd']['SaldoInicial'];
                        $saldo_actual = $desincorporados[0]['ClienProd']['MONTO_SALV'];
                        $sum_pago = $pagos[0][0]['SUM(`ClienPago`.`TOTAL_PAGO`)'];
                    } else {
                        $saldo_inicial = '';
                        $saldo_actual = '';
                        $sum_pago = '';
                    }
                    echo $this->Form->input('Saldo Inicial', array(
                        'class' => 'estado_input',
                        'val' => $saldo_inicial
                    ));
                    echo $this->Form->input('Saldo Actual', array(
                        'class' => 'estado_input',
                        'val' => $saldo_actual
                    ));
                    echo $this->Form->input('Pagos', array(
                        'class' => 'estado_input',
                        'val' => $sum_pago
                    ));
                    ?>
                </div>
            </fieldset>
        </div>
</div>