<?php
App::import('Vendor', 'excel_reader');
App::import('Vendor', 'Classes/PHPExcel');
class PagosController extends AppController 
{
	public $components = array('Paginator', 'Attempt.Attempt');

	public $uses = array('User','Gestor','Cliente','ClienPago','Dia','Pago','GruposProducto','Producto','ClienGest','ClienProd','Cobranza');
	
	public function beforeFilter() {
		$this->columnCount = count($this->columnNames);
		parent::beforeFilter();
	}

	public function confirmar()
	{
		$bancosRaw = $this->Cliente->find('all');
		$bancos = array();

		//Solo para que se vea mas pretty
		foreach ($bancosRaw as $banco) 
		{
			$banco = array(
				'rif' 	=> 	$banco['Cliente']['rif'],
				'nombre'=>	$banco['Cliente']['nombre']
				);
			array_push($bancos, $banco);
		}
    }

	public function admin_confirmar()
	{
		$clientes = $this->Cliente->find('list',array(
            'fields' => array('rif','nombre')
        ));
        $gestors = $this->Gestor->buscarGestores();
        $productos_v = $this->Producto->find('list',array(
            'fields' => array('codigo','producto'),
            'conditions' => array('Producto.rif_emp' => 7)
        ));
        $meses = $this->Dia->array_meses();
		$this->set(compact('clientes','gestors','productos_v','meses'));
        $mensaje = '';
        if (!empty($this->data)){
            $mensaje = 'No hay registros que coincidan con la busqueda';
            if ($this->data['Pago']['tipo_boton'] == 'eliminar_pagos') { //Elimino de la tabla pagos
                foreach($this->data['pagos'] as $id => $p) {
                   if ($p == '1') { //Lo elimino
                       $this->Pago->delete($id);
                    }
                }
            }
            $conditions = array(
                'Pago.status' => 'no-confirmado'
            );
            if (!empty($this->data['Pago']['gestor_id'])) {
                $gestor_id = $this->data['Pago']['gestor_id'];
                $conditions1 = array('Cobranza.GESTOR' => $this->data['Pago']['gestor_id']);
                array_push($conditions, $conditions1);
            }
            if (!empty($this->data['Pago']['cliente_id'])) {
                $cliente_id = $this->data['Pago']['cliente_id'];
                $conditions1 = array('Pago.rif_emp' => $this->data['Pago']['cliente_id']);
                array_push($conditions, $conditions1);
            }
            if (!empty($this->data['Pago']['check_por_mes']) && !empty($this->data['Pago']['mes'])) {
                $check_mes = 1;
                $mes_id = $this->data['Pago']['mes'];
                $conditions1 = array('MONTH(Pago.FechaPago)' => $this->data['Pago']['mes']);
                array_push($conditions, $conditions1);
            }
            if (!empty($this->data['Pago']['cliente_id']) && !empty($this->data['Pago']['productos'])) {
                $productos_seleccionados = explode(',',$this->data['Pago']['productos']);
                $array = array();
                foreach ($productos_seleccionados as $p) {
                    if (!empty($p)) {
                        $array[]= array(
                            'Pago.Producto LIKE' => '%'.$p.'%'
                         );
                    }

                }
                $conditions1 = array('or' => $array);
                array_push($conditions, $conditions1);
                $productos_clientes = $this->Producto->find('list',array(
                    'fields' => array('codigo','producto'),
                    'conditions' => array('Producto.rif_emp' => $this->data['Pago']['cliente_id'])
                ));
                $this->set(compact('productos_clientes','productos_seleccionados'));
            }
            $pagos_banco = $this->Pago->find('all',array(
                'conditions' => $conditions,
                'joins' => array(
                    array(
                        'table' => 'cobranzas',
                        'alias' => 'Cobranza',
                        'type' => 'RIGHT',
                        'conditions' => array(
                            'Pago.CedulaOrif = Cobranza.CEDULAORIF',
                        )
                    )
                ),
                'group' => array('Pago.ID')
            ));

            //Buscando los pagos registrados del primer pago del banco
            if (!empty($pagos_banco)) {
                $clien_pagos = $this->ClienPago->find('all',array(
                    'conditions' => array(
                        'ClienPago.CEDULAORIF' => $pagos_banco[0]['Pago']['CedulaOrif'],
                        'ClienPago.EST_PAGO' => 'pendiente'
                    )
                ));
                $clien_pagos[0]['ClienPago']['nombre'] = $pagos_banco[0]['Pago']['Nombre'];
            }

            $this->set(compact('pagos_banco','clien_pagos','cliente_id','gestor_id','check_mes','mes_id'));
        }
        $this->set(compact('mensaje'));
	}

    public function recepcion(){
        $bancosRaw = $this->Cliente->find('all');
        $bancos = array();

        //Solo para que se vea mas pretty
        $bancos = $this->Cliente->find('list',array('fields'=> array('rif','nombre')));

        $this->set('bancos'		,$bancos);

        if (!empty($this->data)) {

            $empresaId = $this->data['Pago']['banco'];

            if	($this->data['Pago']['banco'] == 7) {
                $nombrearchivo = WWW_ROOT.str_replace("/", DS, 'files/').$this->data['Pagos']['archivo']['name'];
                /* copiamos el archivo*/
                if (move_uploaded_file($this->data['Pagos']['archivo']['tmp_name'],$nombrearchivo)) {
                    $data = new Spreadsheet_Excel_Reader($nombrearchivo, true);
                    $temp = $data->dumptoarray(1);

                    $pagos = array();

                    for($i=2; $i< count($temp); $i++)
                    {

                        $fechaRaw 	= 	strtotime($temp[$i][11]);
                        $FechaPago	=	date('Y-m-d',$fechaRaw);

                        $CedulaOrif = 	$temp[$i][1];
                        $Producto 	=	$temp[$i][8]; 					//Para que se vea del tipo TdC -
                        $MontoPago	=	str_replace ( ',' , '', $temp[$i][12]); //Para retirar las comas de los numero 1,000.00
                        $status 	=	'no-confirmado';						//Se guardara asi siempre y cuano no se consida el registro en clien_pagos

                        $pagoPrevio =	$this->ClienPago->find('first',array(
                            'conditions' => array(
                                'ClienPago.FECH_PAGO' => $FechaPago,
                                'ClienPago.COD_PROD LIKE' => $Producto.'%',
                                'ClienPago.TOTAL_PAGO' => $MontoPago
                            )
                        ));
                        if(count($pagoPrevio)>0)
                        {
                            $status 	= 'confirmado';

                            $DiasMora 	=  $temp[$i][20];
                            $update = array(
                                'ClienPago' => array(
                                    'unique_id' => $pagoPrevio['ClienPago']['unique_id'],
                                    'diasMora' => $DiasMora,
                                    'EST_PAGO' => 'confirmado'
                                )
                            );
                            $this->ClienPago->save($update);
                        }

                        $pago = array(
                            'RIF_EMP'	=> $empresaId,
                            'Fecha'		=> date('Y/m/d'),
                            'CedulaOrif'=> $CedulaOrif,
                            'Nombre'	=> $temp[$i][2],
                            'Contrato'	=> $temp[$i][3],
                            'Cuenta'	=> $temp[$i][34],
                            'Producto'	=> $temp[$i][9],
                            'FechaPago'	=> $FechaPago,
                            'MontoPago'	=> $MontoPago,
                            'DiasMora'	=> $temp[$i][20],
                            'status'	=> $status
                        );

                        array_push($pagos, $pago);
                    }
                    $this->Pago->create();
                    $this->Pago->saveMany($pagos);
                }
            } else { //Banco Bicentenario
                $pagos = array();
                $nombrearchivo = WWW_ROOT.str_replace("/", DS, 'files/').$this->data['Pagos']['archivo']['name'];
                /* copiamos el archivo*/
                if (move_uploaded_file($this->data['Pagos']['archivo']['tmp_name'],$nombrearchivo)) {
                    $hoy = date('Y-m-d');
                    $objPHPExcel = PHPExcel_IOFactory::load($nombrearchivo);
                    $count = 0;
                    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

                        if ($count == 0 || $count == 1) { //:a pestaña que tiene los datos que me interesan
                            foreach ($worksheet->getRowIterator() as $row) {
                                $cellIterator = $row->getCellIterator();
                                $cellIterator->setIterateOnlyExistingCells(FALSE); // Loop all cells, even if it is not set
                                if ($row->getRowIndex()>1) {
                                    $i = 0;
                                    foreach ($cellIterator as $cell) {
                                        if ($i == 8) {
                                            $fechaRaw = $cell->getCalculatedValue();
                                            $timestamp = PHPExcel_Shared_Date::ExcelToPHP($fechaRaw);
                                            $FechaPago = date("Y-m-d", $timestamp);
                                        } elseif ($i == 1) {
                                            $CedulaOrif = $cell->getCalculatedValue();
                                        } elseif ($i == 5) {
                                            $Producto = $cell->getCalculatedValue();
                                        } elseif ($i == 9) {
                                            $MontoPago = $cell->getCalculatedValue();
                                        } elseif ($i == 3) {
                                            $nombre = $cell->getCalculatedValue();
                                        } elseif ($i == 4) {
                                            $contrato = $cell->getCalculatedValue();
                                            $cuenta = $cell->getCalculatedValue();
                                        }  elseif ($i==14) {
                                            $DiasMora = $cell->getCalculatedValue();
                                        }
                                        $i++;
                                    }
                                    $status = 'no-confirmado';
                                    $pagoPrevio = $this->ClienPago->find('first', array(
                                        'conditions' => array(
                                            'ClienPago.FECH_PAGO' => $FechaPago,
                                            'ClienPago.PRODUCTO LIKE' => '%' . $Producto . '%',
                                            'ClienPago.TOTAL_PAGO' => $MontoPago
                                        )
                                    ));

                                    if (count($pagoPrevio) > 0) {

                                        $status = 'confirmado';

                                        if (empty($DiasMora)) {
                                            $DiasMora = '2500';
                                        }

                                        $update = array(
                                            'ClienPago' => array(
                                                'unique_id' => $pagoPrevio['ClienPago']['unique_id'],
                                                'diasMora' => $DiasMora,
                                                'EST_PAGO' => 'confirmado'
                                            )
                                        );
                                        $this->ClienPago->save($update);
                                    }
                                    if (!empty($CedulaOrif)) {
                                        $pago = array(
                                            'RIF_EMP' => $empresaId,
                                            'Fecha' => date('Y-m-d'),
                                            'CedulaOrif' => $CedulaOrif,
                                            'Nombre' => $nombre,
                                            'Contrato' => $contrato,
                                            'Cuenta' => $cuenta,
                                            'Producto' => $Producto,
                                            'FechaPago' => $FechaPago,
                                            'MontoPago' => $MontoPago,
                                            'DiasMora' => 2500,
                                            'status' => $status
                                        );

                                        array_push($pagos, $pago);
                                    }

                                }
                            }
                            $this->Pago->create();
                            $this->Pago->saveMany($pagos);
                        }
                        $count++;
                    }
                }

            }
        }
    }

    public function comision_gestor()
    {
		//Para el filtro
		$supervisors = $this->User->find('list',array(
			'fields' => array('id','nombre_completo'),
			'conditions' => array('User.rol' => 'supervisor')
		));
		$gestores_b = $this->Gestor->find('all',array( 
			'fields' => array('Clave','User.nombre_completo','User.supervisor_id'),
			'conditions' => array('User.supervisor_id <>' => '0')
		));
		foreach ($gestores_b as $g) {
			$gestors[$g['Gestor']['Clave']] = $g['User']['nombre_completo'];
		}
		$clients = $this->Cliente->find('list',array(
			'fields' => array('rif','nombre')
		));
        $meses = $this->Dia->array_meses();
		$mes = date('m');
        $condiciones = array('MONTH(ClienPago.FECHA_CONF)'=>$mes);
        $arreglo_comisiones = $this->ClienPago->buscarGestionesConfirmadasParaComision($condiciones);
        $arreglo_comisiones = $this->Gestor->comisiones($arreglo_comisiones);
        $registros = count($arreglo_comisiones);

        $this->set(compact('supervisors','gestors','clients','mes','meses','arreglo_comisiones','registros'));
		
    }

    public function buscar_comision(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $supervisor_id = $this->request->data['supervisor_id'];
            $gestor_id = $this->request->data['gestor_id'];
            $cliente_id = $this->request->data['cliente_id'];
            $mes = $this->request->data['mes'];
            $check_especifica = $this->request->data['check_especifica'];

            $condiciones = array('MONTH(ClienPago.FECHA_CONF)'=>$mes);
            if ($check_especifica == 1) {
                $cedula = $this->request->data['cedula'];
                if (!empty($cedula)) {
                    $condiciones1 = array('ClienPago.CEDULAORIF LIKE' =>  '%'.$cedula.'%');
                    array_push($condiciones, $condiciones1);
                }
            }
            if(!empty($supervisor_id)){
                $condiciones1 = array('User.supervisor_id' =>  $supervisor_id);
                array_push($condiciones, $condiciones1);
            }
            if(!empty($gestor_id)){
                $condiciones1 = array('Gestor.Clave' =>  $gestor_id);
                array_push($condiciones, $condiciones1);

            }

            if(!empty($cliente_id)){
                $condiciones1 = array('ClienPago.RIF_EMP' =>  $cliente_id);
                array_push($condiciones, $condiciones1);
            }
            $arreglo_comisiones = $this->ClienPago->buscarGestionesConfirmadasParaComision($condiciones);
            $arreglo_comisiones = $this->Gestor->comisiones($arreglo_comisiones);
            return json_encode($arreglo_comisiones);
        }else{
            $this->redirect($this->defaultRoute);
        }
    }

    public function admin_relacion_pago(){
        $conditions = array();
        if(!empty($this->data)){
            if (!empty($this->data['User']['gestor_id'])) {
                $conditions2 = array('ClienPago.LOGIN_REG' => $this->data['User']['gestor_id']);
                array_push($conditions, $conditions2);
            } elseif (!empty($this->data['User']['supervisor_id'])) {
                //busco todos los gestores bajo este supervisor
                $gestores_s = $this->Gestor->find('all',array(
                    'fields' => array('Clave'),
                    'conditions' => array('User.supervisor_id' => ($this->data['User']['supervisor_id']))
                ));

                $claves = Hash::combine($gestores_s, '{n}.Gestor.Clave', '{n}.Gestor.Clave');
                $conditions2 = array('ClienPago.LOGIN_REG' => $claves);
                array_push($conditions, $conditions2);
            }
            if (!empty($this->data['User']['cliente_id'])) {
                $conditions2 = array('ClienPago.RIF_EMP' => $this->data['User']['cliente_id']);
                array_push($conditions, $conditions2);
            }
            if ($this->data['User']['confirmado'] == 1) {
                $conditions2 = array('ClienPago.EST_PAGO' => 'confirmado');
                array_push($conditions, $conditions2);
            }
            if ($this->data['User']['check_por_mes'] == 1) {
                if (!empty($this->data['User']['mes'])) {
                    $conditions2 = array('MONTH(ClienPago.FECH_PAGO)' => $this->data['User']['mes']);
                    array_push($conditions, $conditions2);
                }
            } elseif ($this->data['User']['check_por_fecha'] == 1) {
                if (!empty($this->data['User']['dia_pago'])) {
                    $conditions2 = array('ClienPago.FECH_PAGO' => $this->data['User']['dia_pago']);
                    array_push($conditions, $conditions2);
                }
            }
        }
        $pagos = $this->ClienPago->buscarGestiones($conditions);
        $pagos = $this->Gestor->comisiones($pagos);
        //BUSCAR TODOS LOS CLIENTES
        $clientes = $this->Cliente->find('list',array(
            'fields' => array('rif','nombre')
        ));
        //BUSCAR TODOS LOS SUPERVISORES
        $supervisors = $this->User->find('list',array(
            'fields' => array('id','nombre_completo'),
            'conditions' => array('User.rol' => 'supervisor')
        ));
        //BUSCAR TODOS LOS GESTORES
        $gestors = $this->Gestor->buscarGestores();
        $this->set(compact('pagos','clientes','supervisors','gestors'));
    }

    function admin_quitar_confirmacion(){
        $gestors = $this->Gestor->buscarGestores();
        $clientes = $this->Cliente->find('list',array(
            'fields' => array('rif','nombre')
        ));
        $meses = $this->Dia->array_meses();
        $mes_actual = date('m');
        $dia_actual = date('d-m-Y');

        $conditions = array(
            'ClienPago.EST_PAGO' => 'confirmado'
        );
        if (!empty($this->data)) {
            //debug($this->data);die();
            if ($this->data['User']['tipo_boton'] == 'buscar') {
                if (!empty($this->data['User']['gestor_id'])) {
                    $conditions2 = array('ClienPago.LOGIN_REG' => $this->data['User']['gestor_id'],);
                    array_push($conditions, $conditions2);
                }
                if (!empty($this->data['User']['cliente_id'])) {
                    $conditions2 = array('ClienPago.RIF_EMP' => $this->data['User']['cliente_id'],);
                    array_push($conditions, $conditions2);
                }
                if ($this->data['User']['check_por_mes'] == 1) {
                    if (!empty($this->data['User']['mes'])) {
                        $conditions2 = array('MONTH(ClienPago.FECH_PAGO)' => $this->data['User']['mes'],);
                        array_push($conditions, $conditions2);
                    }
                }
                if ($this->data['User']['check_por_fecha'] == 1) {
                    if (!empty($this->data['User']['dia_pago'])) {
                        $fecha_pago = date("Y-m-d", strtotime($this->data['User']['dia_pago']));
                        $conditions2 = array('ClienPago.FECH_PAGO' => $fecha_pago,);
                        array_push($conditions, $conditions2);
                    }
                }
                if ($this->data['User']['cedula_deudor'] == 1) {
                    if (!empty($this->data['User']['busqueda_especifica'])) {
                        $conditions2 = array('ClienPago.CEDULAORIF LIKE' => '%'.$this->data['User']['busqueda_especifica'].'%',);
                        array_push($conditions, $conditions2);
                    }
                }
                if ($this->data['User']['nombre_deudor'] == 1) {
                    if (!empty($this->data['User']['busqueda_especifica'])) {
                        $conditions2 = array('Cobranza.NOMBRE LIKE' => '%' . $this->data['User']['busqueda_especifica'] . '%',);
                        array_push($conditions, $conditions2);
                    }
                }
                if ($this->data['User']['cuenta'] == 1) {
                    if (!empty($this->data['User']['busqueda_especifica'])) {
                        $conditions2 = array('ClienPago.CUENTA LIKE' => '%'.$this->data['User']['busqueda_especifica'].'%',);
                        array_push($conditions, $conditions2);
                    }
                }
            } else {
               if (!empty($this->data['quitar_confirmacion'])){
                    foreach($this->data['quitar_confirmacion'] as $key => $qc) {
                        if ($qc == 1) {
                            $cambiar_status = array('ClienPago' => array(
                                'unique_id' => $key,
                                'EST_PAGO' => 'pendiente'
                            ));
                            $this->ClienPago->save($cambiar_status);
                        }
                    }
                }
            }
        } else {
            $conditions2 = array('MONTH(ClienPago.FECH_PAGO)' => $mes_actual,);
            array_push($conditions, $conditions2);
        }

        $pagos = $this->ClienPago->find('all',array(
            'fields' => array('ClienPago.*','Cobranza.NOMBRE'),
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'table' => 'cobranzas',
                    'alias' => 'Cobranza',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.CEDULAORIF = Cobranza.CEDULAORIF',
                    )
                )
            ),
            'group' => array('ClienPago.CEDULAORIF')
        ));
        $this->set(compact('gestors','clientes','meses','mes_actual','dia_actual','pagos'));
    }

    public function aplicaciones(){
       $supervisors = $this->User->find('list',array(
            'fields' => array('id','nombre_completo'),
            'conditions' => array('User.rol' => 'supervisor')
        ));
        $gestores_b = $this->Gestor->find('all',array( 
            'fields' => array('Clave','User.nombre_completo','User.supervisor_id'),
            'conditions' => array('User.supervisor_id <>' => '0')
        ));
        foreach ($gestores_b as $g) {
            $gestors[$g['Gestor']['Clave']] = $g['User']['nombre_completo'];
        }

        $fechas_r = $this->ClienPago->find('all',array(
            'fields' => array('FECH_REG'),
            'conditions' => array('FECH_REG !=' => null),
            'group' => array('FECH_REG'),
        ));

        $i = 0;
        foreach ($fechas_r as $f) {
            $fechas[ $i] = $f['ClienPago']['FECH_REG'];
            $i++;
        }

        $pagos = array('');
        $condiciones = array('');
        $condiciones1 = array('ClienPago.RIF_EMP' =>  7);
        array_push($condiciones, $condiciones1);

        if ($this->request->is('post')) {

           $supervisor_id = $this->data['Pagos']['supervisor_id'];
           $gestor_id = $this->data['Pagos']['gestor_id'];

           $confirmados = $this->request->data('Pagos.confirmados');
           $fecha = $this->data['Pagos']['fecha_id'];

           $cedula = $this->data['Pagos']['cedula'];
           $nombre = $this->data['Pagos']['nombre'];
           $cuenta = $this->data['Pagos']['cuenta'];

           if(!empty($supervisor_id)){
                $condiciones1 = array('User.supervisor_id' =>  $supervisor_id);
                array_push($condiciones, $condiciones1);
            }
            if(!empty($gestor_id)){
                $condiciones1 = array('Gestor.Clave' =>  $gestor_id);
                array_push($condiciones, $condiciones1);

            }
            if($confirmados == 1){
                $condiciones1 = array('ClienPago.EST_PAGO' =>  'confirmado');
                array_push($condiciones, $condiciones1);

            }
            if(!empty($fecha)){

                $condiciones1 = array('ClienPago.FECH_REG' =>  $fechas[$fecha]);
                array_push($condiciones, $condiciones1);

            }
            if(!empty($cedula)){
            
                $condiciones1 = array('ClienPago.CEDULAORIF' =>  $cedula);
                array_push($condiciones, $condiciones1);

            }
            if(!empty($nombre)){
               
                $condiciones1 = array('Data.Nombre' =>  $nombre);
                array_push($condiciones, $condiciones1);

            }
             if(!empty($cuenta)){
               
                $condiciones1 = array('ClienPago.CUENTA' =>  $cuenta);
                array_push($condiciones, $condiciones1);

            }
            $pagos = $this->ClienPago->buscarPagos($condiciones);
            
        }
        if(!$this->request->is('post') || empty($condiciones)){
            $pagos = $this->ClienPago->buscarPagos($condiciones);

        }
             
        
       // debug($pagos);
       // $pagos = $this->ClienPago->buscarPagos();
        
        $this->set(compact('supervisors','gestors', 'fechas', 'pagos'));
    }


    public function preliquidacion(){
        $supervisors = $this->User->find('list',array(
            'fields' => array('id','nombre_completo'),
            'conditions' => array('User.rol' => 'supervisor')
        ));
        $gestores_b = $this->Gestor->find('all',array( 
            'fields' => array('Clave','User.nombre_completo','User.supervisor_id'),
            'conditions' => array('User.supervisor_id <>' => '0')
        ));
        foreach ($gestores_b as $g) {
            $gestors[$g['Gestor']['Clave']] = $g['User']['nombre_completo'];
        }

        $fechas_r = $this->ClienPago->find('all',array(
            'fields' => array('FECH_REG'),
            'conditions' => array('FECH_REG !=' => null),
            'group' => array('FECH_REG'),
        ));

        $i = 0;
        foreach ($fechas_r as $f) {
            $fechas[ $i] = $f['ClienPago']['FECH_REG'];
            $i++;
        }

        $pagos = array('');
        $condiciones = array('');
        $condiciones1 = array('ClienPago.RIF_EMP' =>  11);
        array_push($condiciones, $condiciones1);

        if ($this->request->is('post')) {

           $supervisor_id = $this->data['Pagos']['supervisor_id'];
           $gestor_id = $this->data['Pagos']['gestor_id'];

           $confirmados = $this->request->data('Pagos.confirmados');
           $fecha = $this->data['Pagos']['fecha_id'];

           $cedula = $this->data['Pagos']['cedula'];
           $nombre = $this->data['Pagos']['nombre'];
           $cuenta = $this->data['Pagos']['cuenta'];

           if(!empty($supervisor_id)){
                $condiciones1 = array('User.supervisor_id' =>  $supervisor_id);
                array_push($condiciones, $condiciones1);
            }
            if(!empty($gestor_id)){
                $condiciones1 = array('Gestor.Clave' =>  $gestor_id);
                array_push($condiciones, $condiciones1);

            }
            if($confirmados == 1){
                $condiciones1 = array('ClienPago.EST_PAGO' =>  'confirmado');
                array_push($condiciones, $condiciones1);

            }
            if(!empty($fecha)){

                $condiciones1 = array('ClienPago.FECH_REG' =>  $fechas[$fecha]);
                array_push($condiciones, $condiciones1);

            }
            if(!empty($cedula)){
            
                $condiciones1 = array('ClienPago.CEDULAORIF' =>  $cedula);
                array_push($condiciones, $condiciones1);

            }
            if(!empty($nombre)){
               
                $condiciones1 = array('Cobranza.NOMBRE' =>  $nombre);
                array_push($condiciones, $condiciones1);

            }
             if(!empty($cuenta)){
               
                $condiciones1 = array('ClienPago.CUENTA' =>  $cuenta);
                array_push($condiciones, $condiciones1);

            }
            $pagos = $this->ClienPago->buscarPagosLiquidacion($condiciones);
            
        }
        if(!$this->request->is('post') || empty($condiciones)){
            $pagos = $this->ClienPago->buscarPagosLiquidacion($condiciones);

        }
             
        
       //debug($pagos);
       // $pagos = $this->ClienPago->buscarPagos();
        
        $this->set(compact('supervisors','gestors', 'fechas', 'pagos'));
    }


    public function buscar_gestores()
    {
        if ($this->request->isAjax()) {

            $this->autoRender = false;
            $supervisor_id = $this->request->data['supervisor_id'];

            $gestores = $this->Gestor->buscarGestoresPorSupervisor($supervisor_id);

            return json_encode($gestores);
        } else {
            $this->redirect($this->defaultRoute);
        }
    }

    function cargar_productos_por_empresa(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $cliente_id = $this->request->data['cliente_id'];
            $productos = $this->Producto->find('list',array(
                'fields' => array('codigo','producto'),
                'conditions' => array('Producto.rif_emp' => $cliente_id)
            ));
            return json_encode($productos);
        }else{
            $this->redirect($this->defaultRoute);
        }
    }

    function pagos_banco(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $cliente = $this->request->data['cliente'];
            $gestor = $this->request->data['gestor'];
            $fecha = $this->request->data['fecha'];
            $cadena_productos = $this->request->data['productos'];
            $productos = explode(',',$cadena_productos);

            $conditions = array('Pago.status' => 'no-confirmado');
            if (!empty($cliente)) {
                $conditions2 = array('Pago.RIF_EMP' => $cliente,);
                array_push($conditions, $conditions2);
            }
            if (!empty($gestor)) {
                //Me falta
            }
            if (!empty($fecha)) {
                $conditions2 = array('MONTH(Pago.FechaPago)' => $fecha,);
                array_push($conditions, $conditions2);
            }
            if (!empty($productos[0])) {
                //$conditions2 = array('Pago.Producto' => '%'.$.'%',);
                //array_push($conditions, $conditions2);
            }
            return json_encode($productos);
        }else{
            $this->redirect($this->defaultRoute);
        }
    }

    function admin_reporte_pagos(){
        $gestors = $this->Gestor->buscarGestores();
        $clientes = $this->Cliente->find('list',array(
            'fields' => array('Cliente.rif','Cliente.nombre'),
            'conditions' => array('Cliente.status' => 'activo')
        ));
        $meses = $this->Dia->array_meses();
        $this->set(compact('gestors','clientes','meses'));
        if (!empty($this->data)) {
            $conditions = array();
            if (!empty($this->data['User']['Pagos']) && $this->data['User']['Pagos']!='ambos') {
                $conditions2 = array('ClienPago.EST_PAGO' => $this->data['User']['Pagos']);
                array_push($conditions, $conditions2);
            }
            if (!empty($this->data['User']['cliente_id'])) {
               $conditions2 = array('ClienPago.RIF_EMP' => $this->data['User']['cliente_id']);
                array_push($conditions, $conditions2);
            }
            if (!empty($this->data['User']['gestor_id'])) {
                $conditions2 = array('Gestor.Clave' => $this->data['User']['gestor_id']);
                array_push($conditions, $conditions2);
            }
            if (!empty($this->data['User']['mes_id'])) {
                $conditions2 = array('MONTH(ClienGest.FechaPago)' => $this->data['User']['mes_id']);
                array_push($conditions, $conditions2);
            }
            $arreglo_comisiones = $this->ClienPago->find('all',array(
                'fields' => array('ClienPago.*','Gestor.*','User.*','Producto.*','GruposProducto.*'),
                'joins' => array(
                    array(
                        'table' => 'gestors',
                        'alias' => 'Gestor',
                        'type' => 'INNER',
                        'conditions' => array(
                            'ClienPago.LOGIN_REG = Gestor.Clave',
                        )
                    ),
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'INNER',
                        'conditions' => array(
                            'User.id = Gestor.user_id',
                        )
                    ),
                    array(
                        'table' => 'productos',
                        'alias' => 'Producto',
                        'type' => 'INNER',
                        'conditions' => array(
                            'ClienPago.COD_PROD = Producto.codigo',
                            'ClienPago.RIF_EMP = Producto.rif_emp'
                        )
                    ),
                    array(
                        'table' => 'grupos_productos',
                        'alias' => 'GruposProducto',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Producto.gruposProducto_id = GruposProducto.id',
                        )
                    )
                ),
                'conditions' => $conditions,
            ));
            $arreglo_comisiones = $this->Gestor->comisiones($arreglo_comisiones);
            $this->set(compact('arreglo_comisiones'));
        }
    }

    public function cargar_clien_pagos(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $cedula = $this->request->data['cedula'];
            $condiciones = array(
                'ClienPago.EST_PAGO'=>'pendiente',
                'ClienPago.CEDULAORIF' => $cedula
            );

//            if ($check_especifica == 1) {
//                $cedula = $this->request->data['cedula'];
//                if (!empty($cedula)) {
//                    $condiciones1 = array('ClienPago.CEDULAORIF LIKE' =>  '%'.$cedula.'%');
//                    array_push($condiciones, $condiciones1);
//                }
//            }
            $nombre = $this->Pago->find('first',array(
                'fields' => array('Pago.Nombre'),
                'conditions' => array('Pago.CedulaOrif' => $cedula)
            ));
            $clien_pagos = $this->ClienPago->find('all',array(
                'conditions' => $condiciones
            ));
            if (!empty($clien_pagos)){
                $clien_pagos[0]['ClienPago']['nombre'] = $nombre['Pago']['Nombre'];
                $return['clien_pagos'] = $clien_pagos;
                $return['hay'] = 'Si';
            } else {
                $return['hay'] = 'No';
            }
            return json_encode($return);
        }else{
            $this->redirect($this->defaultRoute);
        }
    }

    public function cargar_pagos(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $cedula = $this->request->data['cedula'];
            $pagos_banco = $this->Pago->find('all',array(
                'conditions' => array(
                    'Pago.status' => 'no-confirmado',
                    'Pago.CedulaOrif' => $cedula
                ),
                'joins' => array(
                    array(
                        'table' => 'cobranzas',
                        'alias' => 'Cobranza',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Pago.CedulaOrif = Cobranza.CEDULAORIF',
                        )
                    )
                ),
                'group' => array('Cobranza.CEDULAORIF')
            ));
            if (!empty($pagos_banco)) {
                $return['hay'] = 'Si';
                $return['pagos_banco'] = $pagos_banco;
            } else {
                $return['hay'] = 'no';
            }
            return json_encode($return);
        }else{
            $this->redirect($this->defaultRoute);
        }
    }

    public function eliminar_clien_pagos(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $registros = $this->request->data['registros'];
            $clien_pagos = explode(',',$registros);
            foreach ($clien_pagos as $c){
                if (!empty($c)) {
                    $this->ClienPago->delete($c);
                }
            }
            return json_encode(true);
        }else{
            $this->redirect($this->defaultRoute);
        }
    }

    function editar_clien_pago(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $monto_pago = $this->request->data['monto_pago'];
            $fecha_pago = $this->request->data['fecha_pago'];
            $id = $this->request->data['id'];

            $editar  = array('ClienPago' => array(
                'unique_id' => $id,
                'FECH_PAGO' => $fecha_pago,
                'TOTAL_PAGO' => $monto_pago
            ));
            $this->ClienPago->save($editar);
            return json_encode(true);
        }else{
            $this->redirect($this->defaultRoute);
        }
    }

    function confirmar_pagos(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $id_clien_pagos = $this->request->data['id_clien_pagos'];
            $id = explode(',',$id_clien_pagos);
            foreach ($id as $i) {
                if (!empty($i)) {
                    $editar  = array('ClienPago' => array(
                        'unique_id' => $i,
                        'EST_PAGO' => 'confirmado',
                    ));
                    $this->ClienPago->save($editar);
                }
            }
            return json_encode(true);
        }else{
            $this->redirect($this->defaultRoute);
        }

    }

    function buscar_gestiones(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $cedula = $this->request->data['cedula'];
            $gestiones = $this->ClienGest->find('all',array(
                'conditions' => array(
                    'ClienGest.cedulaorif' => $cedula,
                ),
                'order' => array('ClienGest.id DESC')
            ));
            $pagos = $this->ClienPago->find('all',array(
                'conditions' => array(
                    'ClienPago.cedulaorif' => $cedula
                )
            ));
            $productos =  $this->ClienProd->find('all',array(
                'conditions' => array(
                    'ClienProd.CEDULAORIF' => $cedula
                )
            ));
            $nombre = $this->Cobranza->find('first',array(
                'fields' => array('NOMBRE'),
                'conditions' => array(
                    'Cobranza.CEDULAORIF' => $cedula
                )
            ));
            //Buscar saldo inicial, actual y pagos
            $saldoInicial = 0;
            $saldoActual = 0;
            foreach ($productos as $p) {
                $saldoInicial += $p['ClienProd']['SaldoInicial'];
                $saldoActual += $p['ClienProd']['SALDO_VENC'];
            }
            $pagos_total = 0;
            foreach ($pagos as $p) {
                if ($p['ClienPago']['EST_PAGO'] == 'confirmado') {
                    $pagos_total += $p['ClienPago']['TOTAL_PAGO'];
                }
            }
            $return = array(
                'gestiones' => $gestiones,
                'pagos' => $pagos,
                'productos' => $productos,
                'nombre' => $nombre,
                'saldoInicial' => $saldoInicial,
                'saldoActual' => $saldoActual,
                'total_pago' => $pagos_total
            );
            return json_encode($return);
        }else{
            $this->redirect($this->defaultRoute);
        }

    }

    function eliminar_pagos_viejos(){
        if($this->request->isAjax()){
            $this->autoRender = false;
            $mes = $this->request->data['mes'];
            $this->Pago->deleteAll(array('MONTH(Pago.FechaPago)' => $mes), false);
            return json_encode(true);
        }else{
            $this->redirect($this->defaultRoute);
        }
    }
}

?>
