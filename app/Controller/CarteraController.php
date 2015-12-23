<?php
App::import('Vendor', 'Classes/PHPExcel');
//App::import('Vendor', 'php-excel-reader/excel_reader2'); //importt statemen
class CarteraController extends AppController {
	//public $components = array('Paginator', 'Attempt.Attempt');
	public $uses = array('Desincorporado','Cliente','Cobranza','ClienProd','Statu','NuevoDeudor','ClienGest','ClienPago','PagoBanco','Telefono','Producto','Gestor');
	
	public function beforeFilter() {
		$this->columnCount = count($this->columnNames);
		parent::beforeFilter();
	}

	function admin_recepcion_cartera(){
		$clientes = $this->Cliente->find('list',array(
			'fields' => array('rif','nombre'),
		));
        $clien_prods = array();
        $nuevos_deudores = array();
		$hoy = date('Y-m-d');
		$this->set(compact('clientes','hoy'));
		if (!empty($this->data)) {
			$nombrearchivo = WWW_ROOT.str_replace("/", DS, 'files/').$this->data['Cliente']['archivo']['name'];
			/* copiamos el archivo*/
			if (move_uploaded_file($this->data['Cliente']['archivo']['tmp_name'],$nombrearchivo)) {
				$hoy = date('Y-m-d');
				if ($this->data['Cliente']['cliente_id'] == 7) { //Archivo banco de Venezuela
					$fp = fopen($nombrearchivo, "r");
					$i = 0;
					$cedulas = array();
					while(!feof($fp)) {	
						$linea = fgets($fp);
						$cadena =  explode("\t", $linea); //Ya tengo separado por td
						if ($i > 0){
							$telefonos = array();
							$direcciones = array();
							if (!empty($cadena[0])) {
								//Los datos de la tabla cobranza
								$cedula = $cadena[0];
								$cedulas[] = $cedula;
								$nombre = $cadena[1];
								$producto = $cadena[10];
                                $cod_producto = explode('-',$producto);
                                $cod_producto = $cod_producto[0].'-';
								$dias_vencido = $cadena[5];
								$monto_saldo = $cadena[4];
								$cuenta = $cadena[2];
								$pago = $cadena[23];
								$fecha_reg = $cadena[9];
								$telefonos[0] = $cadena[15];
								$telefonos[1] = $cadena[20];
								$direcciones[0]['direccion'] = $cadena[11];
								$direcciones[0]['ubicacion'] = $cadena[12];
								$direcciones[0]['estado'] = $cadena[13];
								$direcciones[0]['ciudad'] = $cadena[14];
								$direcciones[1]['direccion'] = $cadena[16];
								$direcciones[1]['ubicacion'] = $cadena[17];
								$direcciones[1]['estado'] = $cadena[18];
								$direcciones[1]['ciudad'] = $cadena[19];
								$this->Telefono->agregarTlf($cedula,$telefonos,$direcciones);
								$existe_usuario_con_producto = $this->ClienProd->find('first',array(
									'fields' => array('ClienProd.unique_id'),
									'conditions' => array(
										'RIF_EMP' => 7,
										'CEDULAORIF' => $cedula,
										'COD_PROD' => $cod_producto,
                                        'PRODUCTO' => $producto,
									)
								));
								if (!empty($existe_usuario_con_producto)) { //Ya existe el producto entonces actualizo, no hace falta verificar si existe el usuario en las otras tablas
                                    $clien_prod = array(
										'unique_id' => $existe_usuario_con_producto['ClienProd']['unique_id'],
										'DIASMORA' => $dias_vencido,
										'MONTO_SALV' => $monto_saldo,
										'FECHA_REG'=> $fecha_reg,
										'CUENTA' => $cuenta,
										'PAYOFF' => $pago,
									);
                                    array_push($clien_prods, $clien_prod);
								} else { //no existe el producto asociado a la persona
									$exite_usuario_bdv = $this->Cobranza->find('first',array(
										'fields' => array('Cobranza.CEDULAORIF'),
										'conditions' => array(
											'CEDULAORIF' => $cedula,
											'RIF_EMP' => 7
										)
									));
									if (empty($exite_usuario_bdv)) {
										//No existe usuario en el banco de VZLA
										//Busco si el usuario ya esta en la tabla cobranza
										$existe_usuario = $this->Cobranza->find('first',array(
											'fields' => array('Cobranza.GESTOR'),
											'conditions' => array(
												'CEDULAORIF' => $cedula,
											)
										));
										
										if (empty($existe_usuario)) { //Nuevo deudor, se agrega a la tabla nueva
											$existe_nuevo_deudor = $this->NuevoDeudor->find('first',array(
												'conditions' => array(
													'NuevoDeudor.CEDULAORIF' => $cedula,
													'NuevoDeudor.NOMBRE' => $nombre,
													'NuevoDeudor.RIF_EMP' => 7,
												)
											));
											if (empty($existe_nuevo_deudor)) {
                                                $clien_prod = array(
                                                    'DIASMORA' => $dias_vencido,
                                                    'MONTO_SALV' => $monto_saldo,
                                                    'SaldoInicial' => $monto_saldo,
                                                    'FECHA_REG'=> $fecha_reg,
                                                    'CUENTA' => $cuenta,
                                                    'PAYOFF' => $pago,
                                                    'COD_PROD' => $cod_producto,
                                                    'PRODUCTO' => $producto,
                                                    'RIF_EMP' => 7,
                                                    'CEDULAORIF' => $cedula,
                                                );
                                                $this->ClienProd->create();
                                                $this->ClienProd->save($clien_prod);
                                                $id_clien_prod = $this->ClienProd->id;
                                                $nuevo_deudor = array(
                                                    'CEDULAORIF' => $cedula,
                                                    'NOMBRE' => $nombre,
                                                    'RIF_EMP' => 7,
                                                    'clienProd_id' => $id_clien_prod
                                                );
                                                $this->NuevoDeudor->create();
                                                $this->NuevoDeudor->save($nuevo_deudor);
											}
										} else {
											$gestor = $existe_usuario['Cobranza']['GESTOR'];
											//Creo el registro en cobranza
											$cobranza = array(
                                                'RIF_EMP' => 7,
												'CEDULAORIF' => $cedula,
												'NOMBRE' => $nombre,
												'FECH_ASIG' => $fecha_reg,
												'GESTOR' => $gestor,
												'FECHA_REG' => $hoy,
											);
                                            array_push($cobranzas,$cobranza);
										}
									}	
								}
							}
						}
					$i++;
					}
					//Quito de la tabla gestiones los que ya no estan en el archivo
					//$this->ClienGest->deleteAll(array('NOT' => array('ClienGest.cedulaorif' => $cedulas,'ClienGest.rif_emp'=>7)));
				} elseif ($this->data['Cliente']['cliente_id'] == 11) { //Banco Bicentenario
					$objPHPExcel = PHPExcel_IOFactory::load($nombrearchivo);
					$count = 0;
					foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
						if ($count == 1) { //:a pestaña que tiene los datos que me interesan
							foreach ($worksheet->getRowIterator() as $row) {
								$cellIterator = $row->getCellIterator();
								$cellIterator->setIterateOnlyExistingCells(FALSE); // Loop all cells, even if it is not set
								if ($row->getRowIndex()>2){
                                    $i = 0;
									foreach ($cellIterator as $cell) {	
										if ($i == 14) {
											$cedula = $cell->getCalculatedValue();
											$cedulas[] = $cedula;
										} elseif ($i == 15) {
											$nombre = $cell->getCalculatedValue();
										} elseif ($i == 1) {
											$producto = $cell->getCalculatedValue();
										} elseif( $i == 32) {
											$dias_vencido = $cell->getCalculatedValue();
										} elseif ( $i == 20) {
											$monto_saldo = $cell->getCalculatedValue();
										} elseif ($i == 5) {
											$cuenta = $cell->getCalculatedValue();
										} elseif ($i == 8) {
											$pago = $cell->getCalculatedValue();
										} elseif ($i==32) {
											$dias_vencido = $cell->getCalculatedValue();
										} elseif ($i==16) {
											$telefonos[0] = $cell->getCalculatedValue(); 
										} elseif ($i==17) {
											$telefonos[1] = $cell->getCalculatedValue(); 
										}
                                        $i++;
									}
									$fecha_reg = $hoy;
									if (strpos($producto, 'Convecredit') === false) {
										//EL producto no es tdc
									} else { //Si es una tdc siempre va a ser TDC
										$producto = 'TDC -';
										$desc_producto = 'Tarjeta de Credito';
									}
									$this->Telefono->agregarTlf($cedula,$telefonos);
									$existe_usuario_con_producto = $this->ClienProd->find('first',array(
										'fields' => array('ClienProd.unique_id'),
										'conditions' => array(
											'RIF_EMP' => 11,
											'CEDULAORIF' => $cedula,
											'COD_PROD' => $producto,
										)
									));
									if (!empty($existe_usuario_con_producto)) { //Ya existe el producto entonces actualizo, no hace falta verificar si existe el usuario en las otras tablas
										$clien_prod = array('ClienProd' => array(
											'unique_id' => $existe_usuario_con_producto['ClienProd']['unique_id'],
											'DIASMORA' => $dias_vencido,
											'MONTO_SALV' => $monto_saldo,
											'FECHA_REG'=> $fecha_reg,
											'CUENTA' => $cuenta,
											'PAYOFF' => $pago,
										));
										$this->ClienProd->save($clien_prod);
									} else { //no existe el producto asociado a la persona
										$exite_usuario_bdv = $this->Cobranza->find('first',array(
											'fields' => array('Cobranza.CEDULAORIF'),
											'conditions' => array(
												'CEDULAORIF' => $cedula,
												'RIF_EMP' => 11
											)
										));
										if (empty($exite_usuario_bdv)) { //Ya existe este usuario con deuda en el venezuela
											//No existe usuario en el banco de VZLA
											//Busco si el usuario ya esta en la tabla cobranza
											$existe_usuario = $this->Cobranza->find('first',array(
												'fields' => array('Cobranza.GESTOR'),
												'conditions' => array(
													'CEDULAORIF' => $cedula,
												)
											));
											
											if (empty($existe_usuario)) { //Nuevo deudor, se agrega a la tabla nueva
												$existe_nuevo_deudor = $this->NuevoDeudor->find('first',array(
													'conditions' => array(
														'NuevoDeudor.CEDULAORIF' => $cedula,
														'NuevoDeudor.NOMBRE' => $nombre,
														'NuevoDeudor.RIF_EMP' => 11,
													)
												));
												if (empty($existe_nuevo_deudor)) {
                                                    $clien_prod = array(
                                                        'DIASMORA' => $dias_vencido,
                                                        'MONTO_SALV' => $monto_saldo,
                                                        'SaldoInicial' => $monto_saldo,
                                                        'FECHA_REG'=> $fecha_reg,
                                                        'CUENTA' => $cuenta,
                                                        'PAYOFF' => $pago,
                                                        'COD_PROD' => $producto,
                                                        'PRODUCTO' => $producto,
                                                        'CEDULAORIF' => $cedula,
                                                        'RIF_EMP' => 11
                                                    );
                                                    $this->ClienProd->create();
                                                    $this->ClienProd->save($clien_prod);
                                                    $id_clien_prod = $this->ClienProd->id;
                                                    $nuevo_deudor = array('NuevoDeudor' => array(
														'CEDULAORIF' => $cedula,
														'NOMBRE' => $nombre,
														'RIF_EMP' => 11,
                                                        'clienProd_id' => $id_clien_prod
													));
													$this->NuevoDeudor->create();
													$this->NuevoDeudor->save($nuevo_deudor);
												}
											} else {
												$gestor = $existe_usuario['Cobranza']['GESTOR'];
												//Creo el registro en cobranza
												$cobranza = array('Cobranza'=>array(
													'RIF_EMP' => 11,
													'CEDULAORIF' => $cedula,
													'NOMBRE' => $nombre,
													'FECH_ASIG' => $fecha_reg,
													'GESTOR' => $gestor,
													'FECHA_REG' => $hoy,
												));
												$this->Cobranza->create();
												$this->Cobranza->save($cobranza);
											}
										}	
									}
								}
							}
						}
					}
				}
                //Guardo los deudores que no tienen el producto asociado
                $this->ClienProd->create();
                $this->ClienProd->saveMany($clien_prods);
                //Guardo los nuevos deudores
                $this->NuevoDeudor->create();
                $this->NuevoDeudor->save($nuevos_deudores);
                //Guardo en la tabla cobranza
                $this->Cobranza->create();
                $this->Cobranza->save($cobranzas);

				$this->Session->setFlash('Archivo subido satisfactoriamente');
			} else {
				$this->Session->setFlash('Error al subir el archivo, verificar.');
			}
		}
	}
	
	function admin_anexar_cartera() {
		$clientes = $this->Cliente->find('list',array(
			'fields' => array('rif','nombre')
		));
		$hoy = date('Y-m-d');
		$this->set(compact('clientes','hoy'));
		if (!empty($this->data)) {
			$nombrearchivo = WWW_ROOT.str_replace("/", DS, 'files/').$this->data['Cliente']['archivo']['name'];
			/* copiamos el archivo*/
			if (move_uploaded_file($this->data['Cliente']['archivo']['tmp_name'],$nombrearchivo)) {
				$objPHPExcel = PHPExcel_IOFactory::load($nombrearchivo);
				$count = 0;
				
	            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					
					if ($count == 0) {
						foreach ($worksheet->getRowIterator() as $row) {
							$cellIterator = $row->getCellIterator();
							$cellIterator->setIterateOnlyExistingCells(FALSE); // Loop all cells, even if it is not set
							if($row->getRowIndex()==1){ //headers
					   
							}elseif ($row->getRowIndex()>2){
								
								$data['Contacto'] = array();
								$i=0;
								$telefonos = array();
								foreach ($cellIterator as $cell) {	
									if ($i == 14) {
										$nuevo_deudor['NuevoDeudor']['CEDULAORIF'] = $cell->getCalculatedValue();
										//$cedulas[] = $cedula;
									} elseif ($i == 15) {
										$nuevo_deudor['NuevoDeudor']['NOMBRE'] = $cell->getCalculatedValue();
									} elseif ($i == 1) {
										$nuevo_deudor['NuevoDeudor']['producto'] = $cell->getCalculatedValue();
									} elseif( $i == 32) {
										$nuevo_deudor['NuevoDeudor']['dias_vencido'] = $cell->getCalculatedValue();
									} elseif ( $i == 20) {
										$nuevo_deudor['NuevoDeudor']['monto_saldo'] = $cell->getCalculatedValue();
									} elseif ($i == 5) {
										$nuevo_deudor['NuevoDeudor']['cuenta'] = $cell->getCalculatedValue();
									} elseif ($i == 8) {
										$nuevo_deudor['NuevoDeudor']['pago'] = $cell->getCalculatedValue();
									} elseif ($i==16){
										$telefonos[] =$cell->getCalculatedValue();
									} elseif ($i==17){
										$telefonos[]=$cell->getCalculatedValue();
									} 
									//$row.=($row == "") ? "\"" . $cell . "\"" : "" . $sep . "\"" . $cell . "\"";
									$i++;
								}
								$nuevo_deudor['NuevoDeudor']['fecha_reg'] = $hoy;
								if (strpos($nuevo_deudor['NuevoDeudor']['producto'], 'Convecredit') === false) {
									//EL producto no es tdc
								} else { //Si es una tdc siempre va a ser TDC
									$nuevo_deudor['NuevoDeudor']['producto'] = 'TDC -';
									$desc_producto = 'Tarjeta de Credito';
								}
								$nuevo_deudor['NuevoDeudor']['RIF_EMP'] = '11';
								$this->NuevoDeudor->create();
								$this->NuevoDeudor->save($nuevo_deudor);
								//guardo en la tabla telefonos
								$this->Telefono->agregarTlf($nuevo_deudor['NuevoDeudor']['CEDULAORIF'],$telefonos);
							}
						}
					}
					$count ++;
	            }
			}
			$this->Session->setFlash('El archivo ha sido cargado satisfactoriamente');
		}
	}

    function admin_reporte_cartera(){
        $clientes = $this->Cliente->find('list',array(
            'fields' => array('rif','nombre')
        ));
        $gestors = $this->Gestor->buscarGestores();
        $this->set(compact('clientes','gestors'));
        if (!empty($this->data)) {
            $conditions = array();
            $cartera = array();
            if (!empty($this->data['Cliente']['cartera']) && $this->data['Cliente']['cartera'] != 'no-asignado') {
                $joins = array(
                    array(
                        'table' => 'cobranzas',
                        'alias' => 'Cobranza',
                        'type' => 'INNER',
                        'conditions' => array(
                            'ClienGest.cedulaorif = Cobranza.CEDULAORIF',
                            'ClienGest.numero = Cobranza.UltGestion',
                        )
                    ),
                );
                if (!empty($this->data['Cliente']['cliente_id'])) {
                    $conditions2 = array('ClienGest.rif_emp' => $this->data['Cliente']['cliente_id']);
                    array_push($conditions, $conditions2);
                }
                if (!empty($this->data['Cliente']['producto_id'])) {
                    $conditions2 = array('ClienGest.producto' => $this->data['Cliente']['producto_id']);
                    array_push($conditions, $conditions2);
                }
                if (!empty($this->data['Cliente']['statu_id'])) {
                    $conditions2 = array('ClienGest.cond_deud' => $this->data['Cliente']['statu_id']);
                    array_push($conditions, $conditions2);
                }
                if (!empty($this->data['Cliente']['fecha_asig'])) {
                    $conditions2 = array('Cobranza.FECH_ASIG' => $this->data['Cliente']['fecha_asig']);
                    array_push($conditions, $conditions2);
                }
                if (!empty($this->data['Cliente']['gestor_id'])) {
                    $conditions2 = array('Cobranza.GESTOR' => $this->data['Cliente']['gestor_id']);
                    array_push($conditions, $conditions2);
                }
                $cartera = $this->ClienGest->find('all',array(
                    'fields' => array('ClienGest.*','Cobranza.*'),
                    'conditions' => $conditions,
                    'group' => array('ClienGest.cedulaorif'),
                    'order' => array('ClienGest.id DESC'),
                    'joins' => $joins
                ));
            }

            //Le agrego a la cartera
            if ((!empty($this->data['Cliente']['cartera']) && ($this->data['Cliente']['cartera'] == 'todos' || $this->data['Cliente']['cartera'] == 'no-asignado')) || empty($this->data['Cliente']['cartera'])) {
                $conditions_n = array();
                if (!empty($this->data['Cliente']['cliente_id'])) {
                    $conditions2 = array('NuevoDeudor.rif_emp' => $this->data['Cliente']['cliente_id']);
                    array_push($conditions_n, $conditions2);
                }
                if (!empty($this->data['Cliente']['producto_id'])) {
                    $conditions2 = array('NuevoDeudor.producto' => $this->data['Cliente']['producto_id']);
                    array_push($conditions_n, $conditions2);
                }
                $nuevos_deudores = $this->NuevoDeudor->find('all',array('conditions' => $conditions_n));
                foreach($nuevos_deudores as $nd) {
                    $cartera2 = array(
                        'ClienGest' => array(
                            'cedulaorif' => $nd['NuevoDeudor']['CEDULAORIF'],
                            'rif_emp' => $nd['NuevoDeudor']['RIF_EMP'],
                            'producto' => $nd['NuevoDeudor']['producto'],
                            'cuenta' => $nd['NuevoDeudor']['cuenta'],
                            'proximag' => 'No asignada',
                            'telefono' => 'Sin tlf asociado',
                            'cond_deud' => '-',
                            'observac' => '-'
                        ),
                        'Cobranza' => array(
                            'NOMBRE' => $nd['NuevoDeudor']['NOMBRE'],
                            'FECH_ASIG' => 'Sin fecha',
                            'GESTOR' => 'No asignado',

                        )
                    );
                    array_push($cartera, $cartera2);
                }

            }
            $this->set(compact('cartera'));
        }
    }

    function actualizar_por_empresa(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $cliente_id = $this->request->data['cliente_id'];
            $filtros = array();
            if (!empty($cliente_id)) {
                //Busco Productos
                $productos = $this->Producto->find('list',array(
                    'fields' => array('codigo','producto'),
                    'conditions' => array('Producto.rif_emp' => $cliente_id)
                ));
                //Busco status
                $status = $this->Statu->find('list',array(
                    'fields' => array('codigo','condicion'),
                    'conditions' => array('Statu.rif_emp' => $cliente_id)
                ));
                $filtros['productos'] = $productos;
                $filtros['status'] = $status;
            }
            return json_encode($filtros);
        }else{
            $this->redirect($this->defaultRoute);
        }
    }

    function admin_asignar(){
        $clientes = $this->Cliente->find('list',array(
            'fields' => array('rif','nombre')
        ));
        $total_monto = $this->ClienProd->find('all',array(
            'fields' => array('SUM(ClienProd.MONTO_SALV)'),
            'group' => array('ClienProd.unique_id'),
            'joins' => array(
                array(
                    'table' => 'cobranzas',
                    'alias' => 'Cobranza',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienProd.CEDULAORIF = Cobranza.CEDULAORIF',
                        'Cobranza.GESTOR' => 'operador'
                    )
                ),
            ),
        ));
        $gestors = $this->Gestor->buscarGestores();
        $this->set(compact('gestors','clientes'));
        if (!empty($this->data)) {
            $conditions = array();
            debug($this->data);
            if (!empty($this->data['Pago']['tipo_orden'])) {
               $orden = 'ASC';
            } else {
                $orden = 'DESC';
            }
            if ($this->data['Pago']['boton'] == 'asignar') {
                if($this->data['Pago']['parametros'] == '1') {
                    foreach ($this->data['deudores_seleccionados'] as $k => $d) {
                        if ($d == 1) {
                            $producto = $this->NuevoDeudor->findById($k);
                            //Guardo en la tabla cobranza
                            $nueva_cuenta = array(
                                'Cobranza' => array(
                                    'RIF_EMP' => $producto['ClienProd']['RIF_EMP'],
                                    'CEDULAORIF' => $producto['ClienProd']['CEDULAORIF'],
                                    'NOMBRE' => $producto['NuevoDeudor']['NOMBRE'],
                                    'FECH_ASIG' => date('Y-m-d'),
                                    'GESTOR' => $this->data['Pago']['gestor_id'],
                                    'FECHA_REG' => $producto['ClienProd']['FECHA_REG'],
                                )
                            );
                            $this->Cobranza->create();
                            $this->Cobranza->save($nueva_cuenta);
                            //borro de la tabla nuevo deudor
                            $this->NuevoDeudor->delete($k);
                        }
                    }
                }elseif($this->data['Pago']['traspaso'] == '1') {
                    foreach ($this->data['deudores_seleccionados'] as $k => $d) {
                        if ($d == 1) {
                            //busco todos los registros de la tabla cobranza con el numero de cedula
                            $cuentas = $this->Cobranza->find('all',array('conditions'=>array('Cobranza.CEDULAORIF' => $k)));
                            foreach ($cuentas as $c) {
                                $update = array('Cobranza'=>array(
                                    'id' => $c['Cobranza']['id'],
                                    'GESTOR' => $this->data['Pago']['gestor_id'],
                                ));
                                $this->Cobranza->save($update);
                            }
                            $gestiones = $this->ClienGest->find('all',array('conditions'=>array('ClienGest.cedulaorif' => $k)));
                            foreach ($gestiones as $c) {
                                $update = array('ClienGest'=>array(
                                    'id' => $c['ClienGest']['id'],
                                    'gest_asig' => $this->data['Pago']['gestor_id'],
                                ));
                                $this->ClienGest->save($update);
                            }
                        }
                    }
                }
            } elseif(($this->data['Pago']['parametros'] == '1') || (!empty($this->data['Pago']['es_nuevo']) && $this->data['Pago']['es_nuevo'] == '1')){
                //Busqueda por parametro
                if (!empty($this->data['Pago']['campo_orden'])) {
                    if ($this->data['Pago']['campo_orden'] == 'fecha') {
                        $order =  array('ClienProd.FECHA_REG '.$orden);
                    } elseif ($this->data['Pago']['campo_orden'] == 'producto') {
                        $order =  array('ClienProd.PRODUCTO '.$orden);
                    } elseif ($this->data['Pago']['campo_orden'] == 'monto') {
                        //monto
                        $order =  array('ClienProd.SALDOINICIAL'.$orden);
                    } else {
                        $order =  array('ClienProd.DIASMORA '.$orden);
                    }
                }
                if (!empty($this->data['Pago']['cliente_id'])){
                    $conditions1 = array('NuevoDeudor.RIF_EMP' => $this->data['Pago']['cliente_id']);
                    array_push($conditions, $conditions1);
                    //Paso los productos de esa empresa para cargarlos en el filtro
                    $productos = $this->Producto->find('list',array(
                        'fields' => array('codigo','producto'),
                        'conditions' => array('Producto.rif_emp' => $this->data['Pago']['cliente_id'])
                    ));
                    $productos[0]= 'TODOS';
                    $this->set(compact('productos'));
                }
                if (!empty($this->data['Pago']['producto_id'])){
                    $conditions1 = array('ClienProd.COD_PROD' => $this->data['Pago']['producto_id']);
                    array_push($conditions, $conditions1);
                }
                if (!empty($this->data['Pago']['cedula_desde'])){
                    $conditions1 = array('NuevoDeudor.CEDULAORIF >=' => $this->data['Pago']['cedula_desde']);
                    array_push($conditions, $conditions1);
                }
                if (!empty($this->data['Pago']['cedula_hasta'])){
                    $conditions1 = array('intval(NuevoDeudor.CEDULAORIF) <=' => intval($this->data['Pago']['cedula_hasta']));
                    array_push($conditions, $conditions1);
                }
                if (!empty($this->data['Pago']['monto_desde'])){
                    $conditions1 = array('NuevoDeudor.monto_saldo >=' => $this->data['Pago']['monto_desde']);
                    array_push($conditions, $conditions1);
                }
                if (!empty($this->data['Pago']['monto_hasta'])){
                    $conditions1 = array('NuevoDeudor.monto_saldo <=' => $this->data['Pago']['monto_hasta']);
                    array_push($conditions, $conditions1);
                }
                if (!empty($this->data['Pago']['mora_desde'])){
                    $conditions1 = array('NuevoDeudor.dias_vencido >=' => $this->data['Pago']['mora_desde']);
                    array_push($conditions, $conditions1);
                }
                if (!empty($this->data['Pago']['mora_hasta'])){
                    $conditions1 = array('NuevoDeudor.dias_vencido <=' => $this->data['Pago']['mora_hasta']);
                    array_push($conditions, $conditions1);
                }
                $deudores = $this->NuevoDeudor->find('all',array('conditions' => $conditions,'order' => $order));
            } elseif($this->data['Pago']['traspaso'] == '1' || (!empty($this->data['Pago']['es_nuevo']) && $this->data['Pago']['es_nuevo'] == '0')){
               //Busco en la tabla cobranzas
                if (!empty($this->data['Pago']['campo_orden'])) {
                    if ($this->data['Pago']['campo_orden'] == 'fecha') {
                        $order =  array('Cobranza.FECH_ASIG '.$orden);
                    } elseif ($this->data['Pago']['campo_orden'] == 'producto') {
                        $order =  array('ClienProd.PRODUCTO '.$orden);
                    } elseif ($this->data['Pago']['campo_orden'] == 'monto') {
                        //monto
                        $order =  array('SUM(`ClienProd`.`MONTO_SALV`) '.$orden);
                    } else {
                        $order =  array('ClienProd.DIASMORA '.$orden);
                    }
                }
                $conditions = array();
                if (!empty($this->data['Pago']['clave_gestor'])) {
                    $conditions1 = array('Cobranza.GESTOR' => $this->data['Pago']['clave_gestor']);
                    array_push($conditions, $conditions1);
                }
                if (!empty($this->data['Pago']['traspaso_empresa_id'])) {
                    $conditions1 = array('Cobranza.RIF_EMP' => $this->data['Pago']['traspaso_empresa_id']);
                    array_push($conditions, $conditions1);
                }
                $deudores = $this->ClienProd->find('all',array(
                    'fields' => array('Cobranza.id','SUM(ClienProd.MONTO_SALV)','ClienProd.CEDULAORIF','Cobranza.CONDICION','Cobranza.FECH_ASIG','ClienProd.PRODUCTO','ClienProd.DIASMORA'),
                    'group' => ('ClienProd.CEDULAORIF'),
                    'conditions' => $conditions,
                    'joins' => array(
                        array(
                            'table' => 'cobranzas',
                            'alias' => 'Cobranza',
                            'type' => 'INNER',
                            'conditions' => array(
                                'ClienProd.CEDULAORIF = Cobranza.CEDULAORIF',
                            )
                        ),
                    ),
                    'order' => $order
                ));
            } elseif($this->data['Pago']['directa'] == '1') {
                //Debo buscar en ambas tablas
                //Primero verifico en nuevos_deudores
                if(!empty($this->data['Pago']['cedula_directa'])) {
                    $deudores = $this->NuevoDeudor->find('all',array(
                        'conditions' => array('NuevoDeudor.CEDULAORIF' => $this->data['Pago']['cedula_directa'])
                    ));
                    if (!empty($deudores)) {
                        $es_nuevo = true;
                    } else {
                        $deudores = $this->ClienProd->find('all',array(
                            'fields' => array('Cobranza.id','SUM(ClienProd.MONTO_SALV)','ClienProd.CEDULAORIF','Cobranza.CONDICION','Cobranza.FECH_ASIG','ClienProd.PRODUCTO','ClienProd.DIASMORA'),
                            'group' => ('ClienProd.CEDULAORIF'),
                            'conditions' => array('ClienProd.CEDULAORIF' => $this->data['Pago']['cedula_directa']),
                            'joins' => array(
                                array(
                                    'table' => 'cobranzas',
                                    'alias' => 'Cobranza',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'ClienProd.CEDULAORIF = Cobranza.CEDULAORIF',
                                    )
                                ),
                            ),
                        ));
                        $es_nuevo = false;
                    }
                    $this->set(compact('es_nuevo'));
                }
            }

            //Calculando los datos de toda la cartera no asignada
            $total_casos = $this->NuevoDeudor->find('all',array(
                'fields' => array('DISTINCT NuevoDeudor.CEDULAORIF'),
            ));
            $total_casos = count($total_casos);
            $total_monto = $this->NuevoDeudor->find('all',array(
                'fields' => array('SUM(ClienProd.SaldoInicial)')
            ));
            $total_monto = $total_monto[0][0]['SUM(`ClienProd`.`SaldoInicial`)'];
            $total_cuenta = $this->NuevoDeudor->find('all',array(
                'fields' => array('COUNT(NuevoDeudor.id)')
            ));
            $total_cuenta = $total_cuenta[0][0]['COUNT(`NuevoDeudor`.`id`)'];

            $this->set(compact('deudores','total_casos','total_monto','total_cuenta'));
        }
    }

    function cargar_status_por_empresa(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $cliente_id = $this->request->data['cliente_id'];
            $status = $this->Statu->find('list',array(
                'fields' => array('codigo','condicion'),
                'conditions' => array('Statu.rif_emp' => $cliente_id)
            ));
            return json_encode($status);
        }else{
            $this->redirect($this->defaultRoute);
        }
    }

    function buscar_cuentas_por_gestor(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $clave = $this->request->data['gestor_id'];
            $total_casos = $this->ClienProd->find('all',array(
                'fields' => array('DISTINCT(ClienProd.CEDULAORIF)'),
                'joins' => array(
                    array(
                        'table' => 'cobranzas',
                        'alias' => 'Cobranza',
                        'type' => 'INNER',
                        'conditions' => array(
                            'ClienProd.CEDULAORIF = Cobranza.CEDULAORIF',
                            'Cobranza.GESTOR' => $clave
                        )
                    ),
                ),
            ));
            $total_casos = count($total_casos);
            $total_monto = $this->ClienProd->find('all',array(
                'fields' => array('SUM(ClienProd.MONTO_SALV)'),
                'group' => array('ClienProd.unique_id'),
                'joins' => array(
                    array(
                        'table' => 'cobranzas',
                        'alias' => 'Cobranza',
                        'type' => 'INNER',
                        'conditions' => array(
                            'ClienProd.CEDULAORIF = Cobranza.CEDULAORIF',
                            'Cobranza.GESTOR' => $clave
                        )
                    ),
                ),
            ));
            if (!empty($total_monto)) {
                $monto = $total_monto[0][0]['SUM(`ClienProd`.`MONTO_SALV`)'];
            } else {
                $monto = 0;
            }


            $cuentas = $this->ClienProd->find('all',array(
                'fields' => array('COUNT(ClienProd.unique_id)'),
                'group' => array('ClienProd.unique_id'),
                'joins' => array(
                    array(
                        'table' => 'cobranzas',
                        'alias' => 'Cobranza',
                        'type' => 'INNER',
                        'conditions' => array(
                            'ClienProd.CEDULAORIF = Cobranza.CEDULAORIF',
                            'Cobranza.GESTOR' => $clave
                        )
                    ),
                ),
            ));
            if (!empty($cuentas)) {
                $total_cuenta = $cuentas[0][0]['COUNT(`ClienProd`.`unique_id`)'];
            } else {
                $total_cuenta = 0;
            }


            $return = array(
                'casos' => $total_casos,
                'cuentas' => $total_cuenta,
                'monto' => $monto
            );
            return json_encode($return);
        }else{
            $this->redirect($this->defaultRoute);
        }
    }

    function admin_cartera_desincorporada(){
        $clientes = $this->Cliente->find('list',array(
            'fields' => array('rif','nombre')
        ));
        if (!empty($this->data)) {
            $conditions = array();
            if (!empty($this->data['Cartera']['cliente_id'])) {
                $conditions1 = array('Desincorporado.rif_emp <=' => $this->data['Cartera']['cliente_id']);
                array_push($conditions, $conditions1);
            }
            if (!empty($this->data['Cartera']['statu_id'])) {
                $conditions1 = array('Desincorporado.status <=' => $this->data['Cartera']['statu_id']);
                array_push($conditions, $conditions1);
            }
            if (!empty($this->data['Cartera']['fecha'])) {
                $conditions1 = array('Desincorporado.fecha <=' => $this->data['Cartera']['fecha']);
                array_push($conditions, $conditions1);
            }
            if ((!empty($this->data['Cartera']['busqueda_personalizada']) ||$this->data['Cartera']['busqueda_personalizada'] == 0) && !empty($this->data['Cartera']['text_busqueda_personalizada'])) {
                if ($this->data['Cartera']['busqueda_personalizada'] == 0) { //cedula
                    $conditions1 = array('Desincorporado.cedulaorif' => $this->data['Cartera']['text_busqueda_personalizada']);
                } elseif ($this->data['Cartera']['busqueda_personalizada'] == 1) { //nombre
                    $conditions1 = array('Desincorporado.nombre LIKE' => '%'.$this->data['Cartera']['text_busqueda_personalizada'].'%');
                }elseif ($this->data['Cartera']['busqueda_personalizada'] == 2) { //cuenta
                    $conditions1 = array('Desincorporado.cedulaorif' => $this->data['Cartera']['text_busqueda_personalizada']);
                }
                array_push($conditions, $conditions1);
            }
            $desincorporados = $this->Desincorporado->find('all',array(
                'conditions' => $conditions
            ));
            //Busco las gestiones del primer ex-deudor
            $gestiones = $this->ClienGest->find('all',array(
                'conditions' => array(
                    'ClienGest.producto' => $desincorporados[0]['Desincorporado']['cod_prod'],
                    'ClienGest.cedulaorif' => $desincorporados[0]['Desincorporado']['cedulaorif'],
                    'ClienGest.rif_emp' => $desincorporados[0]['Desincorporado']['rif_emp'],
                ),
                'order' => array('ClienGest.id DESC')
            ));
            //Busco los estados de cuenta del primer deudor
            $pagos = $this->ClienPago->find('all',array(
                'fields' => array('ClienPago.*','SUM(ClienPago.TOTAL_PAGO)'),
                'conditions' => array(
                    'ClienPago.RIF_EMP' => $desincorporados[0]['Desincorporado']['rif_emp'],
                    'ClienPago.CEDULAORIF' => $desincorporados[0]['Desincorporado']['cedulaorif'],
                    'ClienPago.COD_PROD' => $desincorporados[0]['Desincorporado']['cod_prod'],
                )
            ));
            $this->set(compact('desincorporados','gestiones','pagos'));
        }
        $this->set(compact('clientes'));
    }
}

?>
