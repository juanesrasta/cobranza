<?php
// app/Controller/UsersController.php
App::uses('AppController', 'Controller');

/**
 * Clientes Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */

class GestionController extends AppController {
	public $components = array('Paginator', 'Attempt.Attempt');
	public $uses = array('User','Role','Cliente','Statu','Producto','Cobranza','Gestor','ClienGest','Data','Status','Contacto','Telefono','ClienPago','Dia','Datatel','Datastatu','ClienProd','Feriado');
		
	public function admin_index() {
		ini_set('memory_limit', '-1'); 
		set_time_limit(0);
 		//Datos para la busquedas
		$supervisors = $this->Gestor->find('list',array(
			'fields' => array('id', 'nombre'),
			'conditions' => array('supervisa' => '1', 'activo'=>'1')
		));
		$gestores_b = $this->Gestor->find('all',array( // operadores¿?
			'fields' => array('clave','nombre','id'),
			'conditions' => array('activo' => '1', 'supervisa'=>'0')
		));
		//print_r($gestores_b);exit();
		foreach ($gestores_b as $g) {
			$gestores[$g['Gestor']['clave']] = $g['Gestor']['nombre'];
		}
		$clientes = $this->Cliente->find('list',array(
			'fields' => array('rif','nombre')
		));
		$status = $this->Statu->find('list',array(
			'fields' => array('codigo','condicion')
		));
		$contactos = $this->Contacto->find('list',array(
			'fields' => array('codigo','nombre')
		));
		$productos = $this->Producto->find('list',array(
			'fields' => array('codigo','producto')
		));
		
		//Deudores
		$deudores = $this->Cobranza->consultarDeudores();
		$cedula_deudor = $deudores[0]['ClienGest']['cedulaorif'];
		//Busco las empresas asociadas al primer deudor
		$empresas = $this->Cobranza->buscarEmpresas($deudores[0]['ClienGest']['cedulaorif']);
		//Busco las gestiones del primer deudor
		$gestiones = $this->Cobranza->buscarGestiones($deudores[0]['ClienGest']['cedulaorif'],$empresas);
		//Buscando el supervisor del primer deudor
		$supervisor = $this->User->find('first',array('conditions' => array('User.id' => $gestiones[$empresas[0]['Cliente']['rif']][0]['User']['supervisor_id'])));
		$supervisor = $supervisor['User']['nombre_completo'];
		$data_deudor = $this->Data->buscarDatos($cedula_deudor);
		$cond_pago = $this->Statu->findByCodigo($deudores[0]['ClienGest']['cond_deud']);
		$telefonos  = $this->Telefono->buscarTelefonos($deudores[0]['ClienGest']['cedulaorif']);
		$dias_no_laborables = $this->Dia->find('all');
		$telefonos_data_mol = $this->Datatel->find('all',array('conditions' => array('Datatel.CedulaOrif' => $cedula_deudor)));
		$direccion_data_mol = $this->Data->find('first',array('conditions' => array('Data.CedulaOrif' => $cedula_deudor)));
		$status_data_mol = $this->Datastatu->find('list',array(	'fields' => array('unique_id','descripcion')
		));
		$datos_producto=$this->ClienProd->consultaDatosProductos($cedula_deudor);
		$comentarios = $this->ClienGest->find('first',array('conditions' => array('id' => $deudores[0]['ClienGest']['id'])));
		$estado_cuenta=$this->Cobranza->consultaEstadoCuenta($cedula_deudor);
		//$status_data_mol[0] = '';
		//print_r($comentarios);
		$this->set(compact('deudores','gestiones','supervisor','empresas','data_deudor','cond_pago','telefonos','dias_no_laborables','telefonos_data_mol','status_data_mol','direccion_data_mol','datos_producto','estado_cuenta','comentarios','supervisors','clientes','status','productos','gestores','contactos','total_data_cobranza'));
	}

	public function admin_gestion_dia(){
		ini_set('memory_limit', '-1'); 
		set_time_limit(0);
		$hoy = $this->ClienGest->consultarUltimaFechaGestion();
		$hoy = $hoy[0][0]['fecha'];	
		
		///////De la siguiente manera se compone la fecha con formato DATE y no DATETIME que es la forma en la que esta estructurada
		/////// en la variable hoy....
		$structurando_date=explode("-",$hoy);
		$form_date=explode(" ",$structurando_date[2]);
		$arr_date=array($structurando_date[0],$structurando_date[1],$form_date[0]);
		$date = implode("-",$arr_date); 
		///////////finaliza creacion de fecha tipo DATE
		$gestores = $this->Gestor->gestores();
		$index=0;
		foreach($gestores as $ges){
			$id_gestor = $ges['Gestor']['id'];
			$nuevas[$index] = $this->ClienGest->gestionesNuevas($hoy,$id_gestor);
			$agendas[$index]=$this->ClienGest->gestionesAgenda($date, $id_gestor);
			$atrasadas[$index]=$this->ClienGest->gestionesAtrasadas($date, $id_gestor);
			$realizadas[$index] = $this->ClienGest->gestionesRealizadas($date, $id_gestor);
			$supervisor[$index] = $this->ClienGest->ConsultaSupervisorDeGestor($id_gestor);
			$index ++;
		}
		
		$supervisores = $this->Gestor->consultaSupervisores();
		//$supervisores[0] = 'Todos';
		//Buscar las gestiones realizadas del primer gestor 
		$gest_realizadas = $this->ClienGest->buscar_gestiones_realizadas_por_gestor($date,$gestores[0]['Gestor']['Clave']);
	
		$this->set(compact('gestores','nuevas','agendas','gest_realizadas','atrasadas','realizadas','date','supervisores','supervisor'));
	}
	
	public function admin_gestiones_por_producto(){
		
		$gestores = $this->Gestor->find('all'); //Busco los gestores porque hay que listarlos todos
		
		if($this->request->is('post') && (!empty($this->request->data['User']['gestor']))){
			$gestores = $this->Gestor->find('all', array(
				'conditions' => array(
					'Gestor.Clave' => $this->request->data['User']['gestor']
				)
			) );
		}
		
		foreach ($gestores as $g) {
			//Como la busqueda tienes que darle al boton consultar creo que puedes recargar y llenar el array conditions para usar el mismo query que ya yo hice, por ahora conditions solo va a tener el gestor
			if($this->request->is('post')){
				$conditions = $this->ClienGest->busqueda_gestiones_producto($this->request->data, $g['Gestor']['Clave']);
				// debug($this->request->data);
			}else{
				$conditions = array('ClienGest.gest_asig' => $g['Gestor']['Clave']);
			}
			//Hago la busqueda por gestor, y guardo en el arreglo $gestiones['Clave'] para saber en la vista a cual pertenece cada gestion
			$gestiones[$g['Gestor']['Clave']] = $this->ClienGest->find('all',array(
				'fields' => array('COUNT(ClienGest.id) as contador','ClienGest.gest_asig','ClienGest.producto','ClienGest.rif_emp','Cliente.nombre'),
				'conditions' => $conditions,
				'group' => array('ClienGest.producto'),
				'joins' => array(
					array(
						'table' => 'clientes',
						'alias' => 'Cliente',
						'type' => 'INNER',
						'conditions' => array(
							'ClienGest.rif_emp = Cliente.rif'
						)
					),
				),
			));

			foreach($gestiones[$g['Gestor']['Clave']] as $gest) {
				//Tengo que ir almacenando todos los productos que se van a colocar en la tabla
				$productos[$gest['ClienGest']['producto']] = $gest['ClienGest']['producto'];
				//Para poderlos listar en la tabla con facilidad creo un arreglo de total de gestiones por producto en el mismo orden que el array productos
				$gestiones_producto[$g['Gestor']['Clave']][$gest['ClienGest']['producto']] = $gest[0]['contador'];
				//Voy calculando los totales
				if (empty($totales[$g['Gestor']['Clave']])) {
					$totales[$g['Gestor']['Clave']] = 0;
				}
				$totales[$g['Gestor']['Clave']] = $totales[$g['Gestor']['Clave']] + $gest[0]['contador'];
			}
		}
		
		$gestors = $this->Gestor->find('list', array(
		'fields' => array('Clave','Clave')));
		
		$empresas = $this->Cliente->find('list', array(
		'fields' => array('nombre','nombre')));
		
		$this->set(compact('gestores','gestiones_producto','productos','totales','gestors','empresas'));
	}
	
	public function actualizar_status_productos(){ // función para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;
			$cliente_id = $this->request->data['cliente_id'];
			
			if(empty($cliente_id)) {
				$conditions_p = array();
				$conditions_s = array();
			} else {
				$conditions_p = array('Producto.rif_emp' => $cliente_id);
				$conditions_s = array('Statu.rif_emp' => $cliente_id);
			}
			$result = array(
				'status'=> array(),
				'productos' => array(),
			);			
			$productos = $this->Producto->find('list',array(
				'fields' => array('codigo','producto'),
				'conditions' => $conditions_p
			));			
			if (!empty($productos)) {
				$result['productos'] = $productos;
			}			
			$status = $this->Statu->find('list',array(
				'fields' => array('codigo','condicion'),
				'conditions' => $conditions_s
			));			
			if (!empty($status)) {
				$result['status'] = $status;
			}			
			return json_encode($result);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	public function actualizar_gestores(){ // función para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;
			$supervisor_id = $this->request->data['supervisor_id'];
			
			$conditions = array('supervisor' => $supervisor_id, 'activo'=>'1');

			$result = array(
				'gestores'=> array()
			);

			$gestores = $this->Gestor->find('list',array( // operadores¿?
				'fields' => array('clave','nombre'),
				'conditions' => $conditions
			));	
			
			if (!empty($gestores)) {
				$result['gestores'] = $gestores;
			}	
			
			return json_encode($result);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	public function actualizar_deudores(){ // función para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;
			$supervisor_id = $this->request->data['supervisor_id'];
			$gestor_clave = $this->request->data['gestor_clave'];
			$producto = $this->request->data['producto'];
			$statu = $this->request->data['statu'];
			$cliente = $this->request->data['cliente'];
			$tipo = $this->request->data['tipo'];
			$fecha = $this->request->data['fecha'];
			//$cedula = $this->request->data['cedula'];
			
			$conditions = array();
			
			if (!empty($gestor_clave)) {
				$conditions2 = array('Cobranza.GESTOR' => $gestor_clave);
				array_push($conditions, $conditions2);
			} else {
				if(empty($supervisor_id)) {
					$conditions1 = array('User.supervisor_id <>' => 0);
					array_push($conditions, $conditions1);
				} else {
					$conditions1 = array('Gestor.supervisor' => $supervisor_id);
					array_push($conditions, $conditions1);
				}
				
			}
			
			//Busco por cliente
			if (!empty($cliente)) {
				$conditions1 = array('ClienGest.rif_emp' => $cliente);
				array_push($conditions, $conditions1);
			}
			
			//Filtro por producto			
			if (!empty($producto)) {
				$conditions1 = array('ClienGest.producto' => $producto);
				array_push($conditions, $conditions1);
			}
			
			if (!empty($statu)) {
				$conditions1 = array('ClienGest.cond_deud' => $statu);
				array_push($conditions, $conditions1);
			}
			
			//Filtro por tipo
			if (!empty($tipo)) {
				$hoy = date('Y-m-d');
				if ($tipo == 'atraso') {
					$conditions1 = array('ClienGest.proximag <' => $hoy,'ClienGest.numero = Cobranza.UltGestion');
					array_push($conditions, $conditions1);
				} elseif ($tipo == 'agenda') {
					$conditions1 = array('ClienGest.proximag ' => $hoy,'ClienGest.numero = Cobranza.UltGestion');
					array_push($conditions, $conditions1);
				} elseif ($tipo == 'nuevas') {
					$conditions1 = array('Cobranza.FECH_ASIG ' => $hoy,'ClienGest.numero = Cobranza.UltGestion');
					array_push($conditions, $conditions1);
				} elseif ($tipo == 'gesthoy') {
					$conditions1 = array('ClienGest.proximag ' => $hoy,'ClienGest.numero = Cobranza.UltGestion');
					array_push($conditions, $conditions1);
				} elseif ($tipo == 'cartera') {
					$conditions1 = array('ClienGest.proximag ' => $hoy,'ClienGest.numero = Cobranza.UltGestion');
					array_push($conditions, $conditions1);
				}
			}
			
			//filtro por fecha
			if (!empty($fecha)) {
				$fecha=date("Y-m-d",strtotime($fecha));
				$conditions1 = array('Cobranza.FECH_ASIG' => $fecha);
				array_push($conditions, $conditions1);
			}
			
			//Filtro por cedula del deudor
			// if (!empty($cedula)) {
				// $conditions1 = array('ClienGest.cedulaorif' => $cedula);
				// array_push($conditions, $conditions1);
			// }
			
			$deudores = $this->Cobranza->find('all',array(
			'fields' => array('ClienGest.*','Cobranza.*'),
			'group' => array('Cobranza.CEDULAORIF'),
			'conditions' => $conditions,
			'joins' => array(
				array(
					'table' => 'clien_gests',
					'alias' => 'ClienGest',
					'type' => 'INNER',
					'conditions' => array(
						'ClienGest.cedulaorif = Cobranza.CEDULAORIF',
					)
				),
				array(
					'table' => 'gestors',
					'alias' => 'Gestor',
					'type' => 'INNER',
					'conditions' => array(
						'Gestor.Clave = Cobranza.Gestor',
					),
				),
				array(
					'table' => 'users',
					'alias' => 'User',
					'type' => 'INNER',
					'conditions' => array(
						'User.id = Gestor.user_id',
					)
				)
			),
			));
			return json_encode($deudores);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	/////METODO DESARROLLADO POR JUAN CARLOS
	////METODO ACTUALIZARA LOS DATOS DEL DEUDOR CADA VEZ QUE SE HAGA CLICK EN LA TABLA DE DEUDORES
	public function recargarDatosGestores(){
		if($this->request->isAjax()){
			$this->layout = false;
			$this->autoRender = false;
			//$cedula = $this->request->data['cedula'];
			$gestores=$this->Cobranza->consultarGestores();
			foreach($gestores as $gest){
				$id=$gest['gestors']['id'];
				$clave=$gest['gestors']['Clave'];
				$nombre=$gest['gestors']['Nombre'];
				$datos[]=array('id'=>$id, 'clave'=>$clave, 'nombre'=>$nombre);
			}
			echo json_encode($datos);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	public function enviaDatosDeudor(){
		if($this->request->isAjax()){
			$this->layout = false;
			$this->autoRender = false;
			$cedula = $this->request->data['cedula'];
			$deudor=$this->Cobranza->consultaDeudor($cedula);
			$nombre=$deudor[0]['cza']['NOMBRE'];
			$ci=$deudor[0]['cza']['CEDULAORIF'];
			$gestor=$deudor[0]['cza']['GESTOR'];
			$status=$deudor[0]['st']['condicion'];
			$asignado=$deudor[0]['cza']['FECH_ASIG'];
			$proximag=$deudor[0]['ClienGest']['proximag'];
			$banco=$deudor[0]['cl']['nombre'];
			$rif=$deudor[0]['cl']['rif'];
			$datos=array('nombre'=>$nombre, 'ci'=>$ci, 'gestor'=>$gestor, 'status'=>$status, 
			'asignado'=>$asignado, 'proximag'=>$proximag, 'banco'=>$banco, 'rif'=>$rif);
			echo json_encode($datos);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	public function enviaDatosUniversoDeudores(){
		if($this->request->isAjax()){
			$this->layout = false;
			$this->autoRender = false;
			//$cedula = $this->request->data['cedula'];
			$universo_deudores = $this->Cobranza->consultarTotalCobranzas();
			$index=0;
			foreach($universo_deudores as $ud){
				$cedula=$ud['cza']['CEDULAORIF'];
				$telefono_deudor[$index] = $this->Cobranza->extraerTelefonoUniversoDeudores($cedula);
				$telefono = $telefono_deudor[$index][0]['ClienGest']['telefono'];
				$proximag = $telefono_deudor[$index][0]['ClienGest']['proximag'];
				$nombre = $ud['cza']['NOMBRE'];
				$fech_asig = $ud['cza']['FECH_ASIG'];
				$gestor = $ud['cza']['GESTOR'];
				
				$datos[]=array('cedula'=>$cedula, 'telefono'=>$telefono, 'proximag'=>$proximag, 'nombre'=>$nombre,
				'fech_asig'=>$fech_asig, 'gestor'=>$gestor);
				$index ++;
			}
			echo json_encode($datos);
			
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	public function enviaDatosTelefono(){
		if($this->request->isAjax()){
			$this->layout = false;
			$this->autoRender = false;
			$cedula = $this->request->data['cedula'];
			$telefonos=$this->Telefono->consultaDatosTelefonicos($cedula);
			foreach($telefonos as $tlf){
				$telefono = $tlf['telefonos']['telefono'];
				$direccion =$tlf['telefonos']['direccion'];
				$ubicacion =$tlf['telefonos']['ubicacion'];
				$datos[]=array('direccion'=>$direccion, 'telefono'=>$telefono, 'ubicacion'=>$ubicacion);
			}
			echo json_encode($datos);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}		
	
	function enviaDatosGestiones(){
		if($this->request->isAjax()){
			$this->layout = false;
			$this->autoRender = false;
			$cedula = $this->request->data['cedula'];
			$empresa = $this->request->data['empresa'];
			$empresas = $this->Cobranza->buscarEmpresas($cedula);
			$gestiones = $this->Cobranza->buscarGestiones($cedula,$empresas);
			$supervisor = $this->User->find('first',array('conditions' => array('User.id' => $gestiones[$empresas[0]['Cliente']['rif']][0]['User']['supervisor_id'])));
			$supvisor = $supervisor['User']['nombre_completo'];
			$gestiones=$this->Cobranza->consultaGestiones($cedula,$empresa);
			foreach($gestiones as $gest){
				$id=$gest['ClienGest']['id'];
				$cedula = $gest['ClienGest']['cedulaorif'];
				$fechat = explode(" ",$gest['ClienGest']['fecha']);
				$fecha=$fechat[0];
				$numero = $gest['ClienGest']['numero'];
				$telefono = $gest['ClienGest']['telefono'];
				$producto = $gest['ClienGest']['producto'];
				$condicion = $gest['ClienGest']['cond_deud'];
				$proximag = $gest['ClienGest']['proximag'];
				$contacto = $gest['ClienGest']['contacto'];
				$gestor = $gest['Cobranza']['GESTOR'];
				$datos[]=array('id'=>$id,'cedula'=>$cedula, 'numero'=>$numero, 'fecha'=>$fecha, 'telefono'=>$telefono, 'producto'=>$producto, 'condicion'=>$condicion, 'proximag'=>$proximag, 'contacto'=>$contacto, 'gestor'=>$gestor, 'supvisor'=>$supvisor);
			}
			echo json_encode($datos);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	public function enviaDatosProductos(){
		if($this->request->isAjax()){
			$this->layout = false;
			$this->autoRender = false;
			$cedula = $this->request->data['cedula'];
			$productos=$this->ClienProd->consultaDatosProductos($cedula);
			foreach($productos as $pdtos){
				$pdto = $pdtos['cp']['PRODUCTO'];
				$cuenta = $pdtos['cp']['CUENTA'];
				$saldoI = $pdtos['cp']['SaldoInicial'];
				$interes = $pdtos['cp']['interes'];
				$mTotal = $pdtos['cp']['MtoTotal'];
				$dMora = $pdtos['cp']['DIASMORA'];
				$NroCuotas = $pdtos['cp']['NroCuotas'];
				$CtaAsocPago = $pdtos['cp']['CtaAsocPago'];
				$rif = $pdtos['cp']['RIF_EMP'];
				$contrato = $pdtos['cp']['Contrato'];
				$desc1 = $pdtos['cp']['DescProd1'];
				$desc2 = $pdtos['cp']['DescProd2'];
				$datos[]=array('pdto'=>$pdto, 'cuenta'=>$cuenta, 'saldoI'=>$saldoI, 'interes'=>$interes, 'mTotal'=>$mTotal, 'dMora'=>$dMora, 'NroCuotas'=>$NroCuotas, 'CtaAsocPago'=>$CtaAsocPago, 'rif'=>$rif, 'contrato'=>$contrato, 'desc1'=>$desc1, 'desc2'=>$desc2);
			}
			echo json_encode($datos);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	public function enviaDatosEstadosCuentas(){
		if($this->request->isAjax()){
			$this->layout = false;
			$this->autoRender = false;
			$cedula = $this->request->data['cedula'];
			$estadosC=$this->Cobranza->consultaEstadoCuenta($cedula);
			foreach($estadosC as $ec){
				$cedula = $ec['cpg']['CEDULAORIF'];
				$fecha_reg = $ec['cpg']['FECH_REG'];
				$fecha_pago = $ec['cpg']['FECH_PAGO'];
				$total_pago = $ec['cpg']['TOTAL_PAGO'];
				$producto = $ec['cpg']['PRODUCTO'];
				$cuenta = $ec['cpg']['CUENTA'];
				$est_pago = $ec['cpg']['EST_PAGO'];
				$efectivo = $ec['cpg']['EFECTIVO'];
				$mto_cheq1 = $ec['cpg']['MTO_CHEQ1'];
				$mto_otros = $ec['cpg']['MTO_OTROS'];
				$nro_efect = $ec['cpg']['Nro_Efect'];
				$nro_otro = $ec['cpg']['NRO_OTRO'];
				$cond_pago = $ec['cpg']['COND_PAGO'];
				$login_reg = $ec['cpg']['LOGIN_REG'];
				$datos[]=array('cedula'=>$cedula, 'fecha_reg'=>$fecha_reg, 'fecha_pago'=>$fecha_pago,'total_pago'=>$total_pago,
				'producto'=>$producto, 'cuenta'=>$cuenta,'est_pago'=>$est_pago, 'efectivo'=>$efectivo, 'mto_cheq1'=>$mto_cheq1,
				'mto_otros'=>$mto_otros, 'nro_efect'=>$nro_efect,'nro_otro'=>$nro_otro, 'cond_pago'=>$cond_pago, 'login_reg'=>$login_reg);
			}
			echo json_encode($datos);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	public function enviaCifras(){
		if($this->request->isAjax()){
			$this->layout = false;
			$this->autoRender = false;
			$cedula = $this->request->data['cedula'];
			$cifras=$this->Cobranza->consultaCifras($cedula);
			foreach($cifras as $cifra){
				$cedula = $cifra['cpg']['CEDULAORIF'];
				$rif= $cifra['cpg']['RIF_EMP'];
				$capital=$cifra['cp']['SaldoInicial'];
				$efectivo = $cifra['cpg']['EFECTIVO'];
				$cheq = $cifra['cpg']['MTO_CHEQ1'];
				$total_pago = $cifra['cpg']['TOTAL_PAGO'];
				$datos=array('cedula'=>$cedula, 'rif'=>$rif, 'capital'=>$capital, 'efectivo'=>$efectivo, 'cheq'=>$cheq, 'total_pago'=>$total_pago);
			}
			echo json_encode($datos);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	/////FINALIZAN METODOS JUAN CARLOS/////
		
	function cargar_info_datos_deudor() { //funcion para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;
			$cedula = $this->request->data['cedula'];
			$telefonos = $this->Telefono->buscarTelefonos($cedula);
			$empresas = $this->Cobranza->buscarEmpresas($cedula);
			$gestiones = $this->Cobranza->buscarGestiones($cedula,$empresas);
			$supervisor = $this->User->find('first',array('conditions' => array('User.id' => $gestiones[$empresas[0]['Cliente']['rif']][0]['User']['supervisor_id'])));
			$supervisor = $supervisor['User']['nombre_completo'];
			$data_deudor = $this->Data->buscarDatos($cedula);
			// $cond_pago = $this->Statu->findByCodigo($empresas[0]['Cliente']['rif'][0]['ClienGest']['cond_deud']);
			$cond_pago = 'Promesa de Pago';
			$result = array(
				'gestiones' => $gestiones,
				'supervisor' => $supervisor,
				'empresas' => $empresas,
				'telefonos' => $telefonos
			);
			return json_encode($result);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function cargar_info_comentario() { //funcion para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;
			$gestion_id = $this->request->data['gestion_id'];
			$gestion = $this->ClienGest->find('first',array('conditions' => array('id' => $gestion_id)));
			$observacion = $gestion['ClienGest']['observac'];
            $comentario2 = $gestion['ClienGest']['Observac1'];
            $return = array('observacion' => $observacion,'comentario2' => $comentario2);
			return json_encode($return);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function cargar_info_producto() { //funcion para ajax, carga producto asociado al deudor?
		if($this->request->isAjax()){
			$this->autoRender = false;
			$cedula = $this->request->data['cedula'];
			$empresas = $this->Cobranza->buscarEmpresas($cedula);
			$productos = $this->Producto->buscarProductos($cedula,$empresas);
			$result = array(
				'productos' => $productos,
				'empresas' => $empresas
			);
			return json_encode($result);
			
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function cargar_info_pagos() { //funcion para ajax, carga pagos de productos asociados al deudor?
		if($this->request->isAjax()){
			$this->autoRender = false;
			$cedula = $this->request->data['cedula'];
			$empresas = $this->Cobranza->buscarEmpresas($cedula);
			$pagos = $this->Producto->buscarPagos($cedula,$empresas);
			$result = array(
				'pagos' => $pagos,
				'empresas' => $empresas
			);
			return json_encode($result);
			
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function cargar_empresas() { //funcion para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;
			$cedula = $this->request->data['cedula'];
			$empresas = $this->Cobranza->buscarEmpresas($cedula);
			return json_encode($empresas);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	public function editar_prueba(){ // función para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;
			
			return json_encode('prueba');
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	public function editar_deudor(){ // función para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;
			$nombre = $this->request->data['nombre'];
			$cedula = $this->request->data['cedula'];
			$proxima_g =  $this->request->data['proxima_g'];
			$gestor = $this->request->data['gestor'];
			
			$datos_cobranza = $this->Cobranza->find('first', array(
				'conditions' => array(
					'Cobranza.CEDULAORIF' => $cedula,
				)
			));
			
			$update_deudor = array('Cobranza' => array(
				'id' => $datos_cobranza['Cobranza']['id'],
				'NOMBRE' => $nombre,
				'GESTOR' => $gestor
			));
			
			$operador_datos = $this->User->find('first',array('conditions' => array('User.username' => $gestor)));
			$supervisor_id = $operador_datos['User']['supervisor_id'];
			$supervisor = $this->User->find('first',array('conditions' => array('User.id' => $supervisor_id)));
			$supervisor = $supervisor['User']['nombre_completo'];
			
			$date = strtotime($proxima_g);
			$proxima_g =  date('Y-m-d', $date);
			
			$update_proxima_g = $this->ClienGest->buscar_proxima_g($cedula);
			$update_proxima_g['ClienGest']['proximag'] = $proxima_g;
			
			$this->ClienGest->save($update_proxima_g);
			$this->Cobranza->save($update_deudor);
			// $this->Data->save($update_deudor_data);
			
				$result = array(
					'nombre' => $nombre,
					// 'telefono' => $telefono,
					'cedula' => $cedula,
					'proxima_g' => $proxima_g,
					// 'status' => $status,
					// 'direccion' => $direccion,
					'gestor' => $gestor,
					'supervisor' => $supervisor 
					// 'supervisor' => $supervisor // no hace falta cambiar supervisor
				);
			return json_encode($result);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	public function agregar_telefono_deudor(){ // función para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;
			$telefono = $this->request->data['telefono'];
			$ubicacion = $this->request->data['ubicacion'];
			$cedula = $this->request->data['cedula'];
			$direccion = $this->request->data['direccion'];
			
			$add_telefono = array('Telefono' => array(
				'cedulaorif' => $cedula,
				'ubicacion' => $ubicacion,
				'telefono' => $telefono,
				'direccion' => $direccion
			));
						
			$this->Telefono->save($add_telefono);
			
			return json_encode($add_telefono);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	public function editar_gestion(){ // función para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;
			$id_gestion = $this->request->data['id_gestion'];
			$fecha_reg = $this->request->data['fecha_reg'];
			$proximag = $this->request->data['proximag'];
			$telefono = $this->request->data['telefono'];
			$status = $this->request->data['status'];
			$contacto = $this->request->data['contacto'];
			$producto = $this->request->data['producto'];
			$gestor = $this->request->data['gestor'];
			if ($status == 'PP' || $status == 'MM') {
				if ($status == 'PP') {
					$fecha_pago = $this->request->data['fecha_pago'];
					$bolivares = $this->request->data['bolivares'];
					$comentario1 = $this->Statu->concatenar_comentario($status,$fecha_pago,$bolivares,1);
				} else {
					$nombre = $this->request->data['nombre'];
					$apellido = $this->request->data['apellido'];
					$parentesco = $this->request->data['parentesco'];
					$comentario1 = $this->Statu->concatenar_comentario($status,$nombre,$apellido,$parentesco);
				}
			} else {
				$find_producto = $this->Producto->find('first',array(
					'conditions' => array('Producto.codigo' => $producto)
				));
				$rif = $find_producto['Cliente']['rif'];
				$comentario = $this->Statu->find('first',array(
					'conditions' => array(
						'Statu.rif_emp' => $rif,
						'Statu.codigo' => $status
					)
				));
				$comentario1 = $comentario['Statu']['condicion'];
			}
			$comentario2 = $this->request->data['comentario2'];
			$cedula = $this->request->data['cedula'];
			$result = array(
				'numero' => '',
				'gestor' => '',
				'id' => '',
			);
			$fecha_hora = date('Y-m-d H:i:s');
			if ($id_gestion != 0) {
				$update_gestion = array('ClienGest' => array(
					'id' => $id_gestion,
					'fecha_reg' => $fecha_reg,
					'proximag' => $proximag,
					'telefono' => $telefono,
					'cond_deud' => $status,
					'contacto' => $contacto,
					'producto' => $producto,
					'observac' => $comentario1,
					'Observac1' => $comentario2,
					'fecha' => $fecha_hora,
					'gest_asig' => $gestor
				));
				$this->ClienGest->save($update_gestion);
				$result = array(
					'comentario' => $comentario1
				);
			} else {
				$find_gestiones = $this->ClienGest->find('first',array(
					'conditions' => array('ClienGest.cedulaorif' => $cedula),
					'order' => array('ClienGest.numero DESC'),
				));
				$numero = $find_gestiones['ClienGest']['numero']+1;
				$find_producto = $this->Producto->find('first',array(
					'conditions' => array('Producto.codigo' => $producto)
				));
				$find_cobranza =  $this->Cobranza->find('first',array(
					'conditions' => array('Cobranza.CEDULAORIF' => $cedula,'Cobranza.RIF_EMP' => $find_producto['Cliente']['rif']),
				));
				$update_gestion = array('ClienGest' => array(
					'cedulaorif' => $cedula,
					'fecha' => $fecha_hora,
					'fecha_reg' => $fecha_reg,
					'proximag' => $proximag,
					'telefono' => $telefono,
					'cond_deud' => $status,
					'contacto' => $contacto,
					'producto' => $producto,
					'observac' => $comentario1,
					'Observac1' => $comentario2,
					'rif_emp' => $find_producto['Cliente']['rif'],
					'numero' => $numero,
					'gest_asig' => $gestor
				));
				$this->ClienGest->save($update_gestion);
				$id = $this->ClienGest->id;
				//Cambio el numero de la ultima gestion
				$update_cobranza = array('Cobranza' => array(
					'id' => $find_cobranza['Cobranza']['id'],
					'UltGestion' => $numero
				));
				$this->Cobranza->save($update_cobranza);
				$result = array(
					'numero' => $numero,
					'gestor' => $find_cobranza['Cobranza']['GESTOR'],
					'id' => $id,
					'rif' => $find_producto['Cliente']['rif'],
					'comentario' => $comentario1
				);
			}
			return json_encode($result);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function buscar_proximas_gestiones_y_comentario() { //funcion para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;
			$gestor = $this->request->data['gestor'];
			$fecha = date('Y-m-d');
			$usuarios_gestores = $this->Cobranza->find('all',array(
				'fields' => array('DISTINCT Cobranza.CEDULAORIF'),
				'conditions' => array('Cobranza.gestor' => $gestor)
			));
			$cedulas = Hash::combine($usuarios_gestores, '{n}.Cobranza.CEDULAORIF', '{n}.Cobranza.CEDULAORIF');
			$proximas_gestiones = $this->ClienGest->find('all',array(
				'fields' => array('ClienGest.proximag','COUNT(*) as numeroGestiones'),
				'conditions' => array(
					'ClienGest.proximag >' => $fecha,
					'ClienGest.cedulaorif' => $cedulas
				),
				'group' => array('ClienGest.proximag')
			));
			
			//Busco el comentario
			$status = $this->request->data['status'];
			$producto = $this->request->data['producto'];
			$comentario = $this->Statu->buscar_comentario($status,$producto);
			$result = array(
				'proximas_gestiones' => $proximas_gestiones,
				'comentario' => $comentario
			);
			return json_encode($result);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function buscar_productos_por_deudor(){
		if($this->request->isAjax()){
			$this->autoRender = false;
			$cedula = $this->request->data['cedula_deudor'];
			$empresas = $this->Cobranza->buscarEmpresas($cedula);
			$productos = $this->Producto->buscarProductos($cedula,$empresas);
			$productos = Hash::combine($productos, '{n}.{n}.ClienProd.COD_PROD', '{n}.{n}.Producto.producto');
			return json_encode($productos);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function buscar_status(){
		if($this->request->isAjax()){
			$this->autoRender = false;
			$producto = $this->request->data['producto'];
			$empresa = $this->Producto->find('first',array(
				'conditions' => array('Producto.codigo' => $producto)
			));
			$rif = $empresa['Cliente']['rif'];
			$status = $this->Statu->find('all',array(
				'conditions' => array('rif_emp' => $rif)
			));
			return json_encode($status);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function buscar_comentario() { //funcion para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;	
			//Busco el comentario
			$status = $this->request->data['status'];
			$producto = $this->request->data['producto'];
			$comentario = $this->Statu->buscar_comentario($status,$producto);
			return json_encode($comentario);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function guardar_pago() { //funcion para ajax
		if($this->request->isAjax()){
			$this->autoRender = false;	
			$cedula = $this->request->data['cedula'];
			$gestor = $this->request->data['gestor'];
			$fecha_reg = $this->request->data['fecha_reg'];
			$date = strtotime($fecha_reg);
			$fecha_reg =  date('Y-m-d', $date);
			$fecha = $this->request->data['fecha'];
			$date = strtotime($fecha);
			$fecha =  date('Y-m-d', $date);
			$efectivo_monto = $this->request->data['efectivo_monto'];
			$efectivo_documento = $this->request->data['efectivo_documento'];
			$cheque_monto = $this->request->data['cheque_monto'];
			$otro_monto = $this->request->data['otro_monto'];
			$otro_documento = $this->request->data['otro_documento'];
			$cheque_documento = $this->request->data['cheque_documento'];
			$total_pago = $this->request->data['total_pago'];
			$producto = $this->request->data['producto'];
			$empresa = $this->Producto->find('first',array(
				'conditions' => array('Producto.codigo' => $producto)
			));
			$rif = $empresa['Cliente']['rif'];
			// $user_logueado = $this->Auth->User('id');
			// $usuario_gestor = $this->Gestor->find('first',array(
				// 'conditions' => array('Gestor.user_id' => $user_logueado)
			// ));
			// if (!empty($usuario_gestor)) {
				// $login_reg = $usuario_gestor['Gestor']['Clave'];
			// } else {
				// $usuario_logueado = $this->User->findById($user_logueado);
				// $login_reg = $usuario_logueado['User']['username'];
			// }
			//guardo pago
			$pago = array('ClienPago' => array(
				'RIF_EMP' => $rif,
				'FECH_PAGO' => $fecha,
				'CEDULAORIF' => $cedula,
				'FECH_REG' => $fecha_reg,
				'PRODUCTO' => $empresa['Producto']['producto'],
				'COD_PROD' => $producto,
				'TOTAL_PAGO' => $total_pago,
				'EFECTIVO' => $efectivo_monto,
				'Nro_Efect' => $efectivo_documento,
				'MTO_CHEQ1' => $cheque_monto,
				'MTO_OTROS' => $otro_monto,
				'Nro_Efect' => $efectivo_documento,
				'NRO_CHEQ1' => $cheque_documento,
				'NRO_OTRO' => $otro_documento,
				'EST_PAGO' => 'Pendiente',
				'LOGIN_REG' => $gestor
			));
			$this->ClienPago->save($pago);
			return json_encode($pago);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function listar_telefonos_deudor(){
		if($this->request->isAjax()){
			$this->autoRender = false;
			$cedula = $this->request->data['cedula_deudor'];
			$telefonos  = $this->Telefono->buscarTelefonos($cedula);
			$telefonos = Hash::combine($telefonos, '{n}.Telefono.telefono', '{n}.Telefono.telefono');
			return json_encode($telefonos);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function info_datamol(){
		if($this->request->isAjax()){
			$this->autoRender = false;
			$cedula_deudor = $this->request->data['cedula'];
			$telefonos_data_mol = $this->Datatel->find('all',array('conditions' => array('Datatel.CedulaOrif' => $cedula_deudor)));
			if (empty($telefonos_data_mol)) {
				$telefonod_data_mol[0]['Datatel']['Telefono'] = 0;
			}
			$direccion_data_mol = $this->Data->find('first',array('conditions' => array('Data.CedulaOrif' => $cedula_deudor)));
			if (empty($direccion_data_mol)) {
				$direccion_data_mol['Data']['Direccion'] = "";
			}
			$result = array(
				'telefonos_data_mol' => $telefonos_data_mol,
				'direccion_data_mol' => $direccion_data_mol
			);
			return json_encode($result);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function actualizar_status_telefono(){
		if($this->request->isAjax()){
			$this->autoRender = false;
			$cedula = $this->request->data['cedula'];
			$telefono = $this->request->data['telefono'];
			$status = $this->request->data['status'];
			$registro = $this->Datatel->find('first',array(
				'conditions' => array(
					'Datatel.CedulaOrif' => $cedula,
					'Datatel.Telefono' => $telefono
				)
			));
			$update = array('Datatel' => array(
				'id' => $registro['Datatel']['id'],
				'status' => $status
			));
			$this->Datatel->save($update);
			return json_encode($status);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function actualizar_status_direccion__datamol(){
		if($this->request->isAjax()){
			$this->autoRender = false;
			$cedula = $this->request->data['cedula'];
			$status = $this->request->data['status'];
			
			$update = array('Data' => array(
				'CedulaOrif' => $cedula,
				'Status' => $status
			));
			$this->Data->save($update);
			return json_encode($status);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function buscar_gestores(){ //FUncion AJAX
		ini_set('memory_limit', '-1'); 
		set_time_limit(0);
		if($this->request->isAjax()){
			$this->layout = false;
			$this->autoRender = false;
			$supervisor = $this->request->data['supervisor'];
			$hoy = $this->ClienGest->consultarUltimaFechaGestion();
			$hoy = $hoy[0][0]['fecha'];	
			///////De la siguiente mabera se compone la fecha con formato DATE y no DATETIME que es la forma en la que esta estructurada
			/////// en la variable hoy....
			$structurando_date=explode("-",$hoy);
			$form_date=explode(" ",$structurando_date[2]);
			$arr_date=array($structurando_date[0],$structurando_date[1],$form_date[0]);
			$date = implode("-",$arr_date); 
			///////////finaliza creacion de fecha tipo DATE
			if ($supervisor == 0) {
				$gestores = $this->Gestor->gestores();
			} else {
				$gestores = $this->Gestor->consultarGestoresDeSupervisor($supervisor);
			}
			
			$index=0;
			foreach($gestores as $ges){
				$id_gestor = $ges['Gestor']['id'];
				$nuevas[$index] = $this->ClienGest->gestionesNuevas($hoy,$id_gestor);
				$agendas[$index]=$this->ClienGest->gestionesAgenda($date, $id_gestor);
				$atrasadas[$index]=$this->ClienGest->gestionesAtrasadas($date, $id_gestor);
				$realizadas[$index] = $this->ClienGest->gestionesRealizadas($date, $id_gestor);
				$supervisa[$index] = $this->ClienGest->ConsultaSupervisorDeGestor($id_gestor);
				
				/////SE DEFINE LAS VARIABLES QUE SE ENVIARAN EN EL OBJETO JSON////
				$gestor = $ges['Gestor']['Nombre'];
				$clave = $ges['Gestor']['Clave'];
				$Dnuevas=$nuevas[$index][0][0]['nuevas'];
				$Dagendas = $agendas[$index][0][0]['agenda'];
				if(!empty($atrasadas[$index][0][0]['atrasadas'])){
					$Datrasadas = $atrasadas[$index][0][0]['atrasadas'];
				}else{
					$Datrasadas=0;
				}
				if(!empty($realizadas[$index][0][0]['realizadas'])){
					$Drealizadas = $realizadas[$index][0][0]['realizadas'];
				}else{
					$Drealizadas=0;
				}
				
				if(!empty($supervisa[$index][0]['gtr1']['Nombre'])){
					$Dsupervisor = $supervisa[$index][0]['gtr1']['Nombre'];
				}else{
					$Dsupervisor=0;
				}
				//Buscar las gestiones realizadas del primer gestor 
				$gest_realizadas = $this->ClienGest->buscar_gestiones_realizadas_hoy($date,$gestores[0]['Gestor']['Clave']);
				if(!empty($gest_realizadas[0]['ClienGest']['fecha_reg'])){
					$fecha=$gest_realizadas[0]['ClienGest']['fecha_reg'];
				}else{
					$fecha="";
				}
				
				if(!empty($gest_realizadas[0]['ClienGest']['cond_deud'])){
					$condicion=$gest_realizadas[0]['ClienGest']['cond_deud'];
				}else{
					$condicion="";
				}
				
				if(!empty($gest_realizadas[0]['ClienGest']['observac'])){
					$observacion=$gest_realizadas[0]['ClienGest']['observac'];
				}else{
					$observacion="";
				}
				$datos[]=array('gestor'=>$gestor, 'nuevas'=>$Dnuevas, 'agendas'=>$Dagendas, 'atrasadas'=>$Datrasadas, 'realizadas'=>$Drealizadas,'clave'=>$clave, 'fecha_reg'=>$fecha, 'condicion'=>$condicion, 'observacion'=>$observacion, 'spvisor'=>$Dsupervisor);
				$index ++;
			}
			
			return json_encode($datos);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function buscar_gestiones_realizadas(){ //FUncion AJAX
		if($this->request->isAjax()){
			$this->autoRender = false;
			$gestor = $this->request->data['gestor'];
			ini_set('memory_limit', '-1'); 
			set_time_limit(0);
			$hoy = $this->ClienGest->consultarUltimaFechaGestion();
			$hoy = $hoy[0][0]['fecha'];	
			
			///////De la siguiente mabera se compone la fecha con formato DATE y no DATETIME que es la forma en la que esta estructurada
			/////// en la variable hoy....
			$structurando_date=explode("-",$hoy);
			$form_date=explode(" ",$structurando_date[2]);
			$arr_date=array($structurando_date[0],$structurando_date[1],$form_date[0]);
			$date = implode("-",$arr_date); 
			///////////finaliza creacion de fecha tipo DATE
			//Buscar las gestiones realizadas del primer gestor 
			$gest_realizadas = $this->ClienGest->buscar_gestiones_realizadas_por_gestor($date,$gestor);
	
			$result = array(
				'gest_realizadas' => $gest_realizadas
			);
			
			return json_encode($result);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	function admin_gestiones_por_status(){
		ini_set('memory_limit', '-1'); 
		set_time_limit(0);
	
		$empresas = $this->Cliente->find('list', array(
		'fields' => array('rif','nombre')));
		
		$fechas = $this->ClienGest->procesoFechas();
		$fecha_max = $fechas[0][0]['mayor']; 
		$fecha_min = $fechas[0][0]['menor']; 
		if($this->request->is('post')){ // si trae algún filtro...
			// debug($this->request->data);
			
			if(!empty($this->request->data['User']['fecha1'])) {
				$date1 = explode("-",$this->request->data['User']['fecha1']);
				$fecha1 = $date1[2]."-".$date1[1]."-".$date1[0]." 12:01:37";
			}else{
				$fecha1 = $fecha_min;
			}
			if(!empty($this->request->data['User']['fecha2'])) {
				$date = explode("-",$this->request->data['User']['fecha2']);
				$fecha2 = $date[2]."-".$date[1]."-".$date[0]." 23:59:37";
			}else{
				$fecha2 = $fecha_max;
			}			
			$gestores = $this->Gestor->gestores();
			if(!empty($this->request->data['User']['empresa'])){ // cuando la busqueda es por empresa
			// buscamos los operadores asociados a la empresa seleccionada y hacemos la busqueda en base a los operadores luego.
				$empresa = $this->request->data['User']['empresa'];
				$gestores = $this->ClienGest->consultaGestoresPorEmpresa($empresa);
				$index=0;
				foreach($gestores as $g){
					$gestor = $g['Gestor']['Clave'];
					$deudores[$index] = $this->ClienGest->consultaDeudoresPorGestores($gestor, $fecha1, $fecha2, $empresa);
					$index ++;
				}
			} else if(!empty($this->request->data['gestore'])){
					$gestor = $this->request->data['gestore'];
					$gestore = $this->Gestor->definoGestor($gestor);
	$deudores[$this->request->data['gestore']] = $this->ClienGest->consultaDeudoresPorGestores($gestore[0]['Gestor']['Clave'], $fecha1, $fecha2,0);
			}else{
				$indice=0;
				$gestores = $this->Gestor->gestores();
				foreach($gestores as $g){
				$deudores[$indice] = $this->ClienGest->consultaDeudoresPorGestores($g['Gestor']['Clave'],$fecha1,$fecha2,0);
				$indice ++;
				}
			}
		}else { // sin ningun submit
			$cont=0;
			$gestores = $this->Gestor->gestores();
			foreach($gestores as $g){
				///AL INVOCAR LA FUNCION extraerDatosDeudores SE PASARAN DOS VARIABLES CON VALOR CERO 0 QUE REPRESENTAN LAS FECHAS y la EMPRESA, PERO, POR NO ///SER NECESARIAS PARA ESTA CONSULTA SE ENVIA 0,0
				$deudores[$cont] = $this->ClienGest->consultaDeudoresPorGestores($g['Gestor']['Clave'],0,0,0);
				$cont ++;
			}
		}		
		$this->set(compact('empresas','gestores','gestore','deudores'));
	}
	
	
	function admin_gestiones_general(){		
		ini_set('memory_limit', '-1'); 
		set_time_limit(0);	

		$fechas = $this->ClienGest->procesoFechas();
		$fecha_max = $fechas[0][0]['mayor']; 
		$fecha_min = $fechas[0][0]['menor']; 
		
		if($this->request->is('post')){ // si trae algún filtro...
			// debug($this->request->data);
			//$conditions = $this->ClienGest->busqueda_consulta_general($this->request->data);
			if(!empty($this->request->data['User']['fecha1'])) {
				$date1 = explode("-",$this->request->data['User']['fecha1']);
				$fecha1 = $date1[2]."-".$date1[1]."-".$date1[0]." 12:01:37";
			}else{
				$fecha1 = $fecha_min;
			}
			if(!empty($this->request->data['User']['fecha2'])) {
				$date = explode("-",$this->request->data['User']['fecha2']);
				$fecha2 = $date[2]."-".$date[1]."-".$date[0]." 23:59:37";
			}else{
				$fecha2 = $fecha_max;
			}	
			
			// búsqueda por cédula, deudor o teléfono			
			if(!empty($this->request->data['User']['buscar'])) {
				$buscar = $this->request->data['User']['buscar'];
			}else{
				$buscar=0;
			}
			
			// Búsqueda por gestor (nombre)
			if(!empty($this->request->data['User']['gestore'])) {
				$gestor = $this->request->data['User']['gestore'];
			}else{
				$gestor=0;
			}
			if(!empty($this->request->data['User']['empresa'])) {
				$empresa = $this->request->data['User']['empresa'];
			}else{
				$empresa=0;
			}
			
			// Búsqueda por status
			if(!empty($this->request->data['User']['statu'])) {
				$status = $this->request->data['User']['statu'];
			}else{
				$status = 0;
			}
			
			//busqueda por supervisor
			// Buscamos los gestores que corresponden a cada supervisor y los metemos en un arreglo.
			if(!empty($this->request->data['supervisor'])) {
				$supervisor = $this->request->data['supervisor'];
				$gestores = $this->Gestor->consultarGestoresDeSupervisor($supervisor);
				// Dados los gestores, hacemos un arreglo con condiciones para meterlo en el OR del query general
				
				$or_condition = array();
				$num=0;
				foreach($gestores as $g){
					$gestor = $g['Gestor']['Clave'];
					$consultas=$this->ClienGest->consultaBusquedaGeneral(0,0,0,0,0,$gestor);
					$num++;
				}				
			}
				$consultas=$this->ClienGest->consultaBusquedaGeneral($fecha1, $fecha2, $gestor, $empresa, $status, $gestiona=0);
			
		}else{ //  vista sin filtro
			$consultas = $this->ClienGest->consultaBusquedaGeneral($fecha1=$fecha_min, $fecha2=$fecha_max, $gestor=0, $empresa=0, $status=0, $gestiona=0);
		}
		
		// datos que se pasarán siempre para llenar los select
		
		$empresas = $this->Cliente->find('list', array(
		'fields' => array('nombre','nombre')));

		$gestores = $this->Gestor->find('list', array(
		'fields' => array('Clave','Nombre')));
		
		$supervisors = $this->Gestor->consultaSupervisores();
		
		$status = $this->Statu->find('list', array(
		'fields' => array('codigo','codigo')));
		
		$this->set(compact('consultas','supervisors','empresas','gestores','status'));
	}
	
	function admin_cambio_fecha(){
	
		if(!empty($this->request->data)){
			debug($this->request->data);
			$empresa = $this->request->data['User']['empresa'];
			$gestor = $this->request->data['User']['gestore'];
			$status = $this->request->data['User']['statu'];
			$fecha = $this->request->data['User']['fecha'];
			if(!empty($this->request->data['User']['atraso'])){
				$atraso = $this->request->data['User']['atraso'];
			}else{
				$atraso = null;
			}			
			$cantidad = $this->request->data['User']['cantidad'];
			
			$data = array('empresa' => $empresa, 'gestor' => $gestor, 'status' => $status, 'atraso'=> $atraso, 'del_dia' => $fecha);
			
			$conditions = $this->ClienGest->busqueda_cambio_fecha($data);
			
			$consultas = $this->ClienGest->find('all', array(
				'conditions' => $conditions,
				'joins' => array(
					array(
						'table' => 'clientes',
						'alias' => 'Cliente',
						'type' => 'INNER',
						'conditions' => array(
							'ClienGest.rif_emp = Cliente.rif'
						)
					),
				),
			));
			// debug($consultas);
			$fecha_cambio_nueva = strtotime($this->request->data['ClienGest']['fecha_cambio']);
			$fecha_cambio_nueva = date('Y-m-d:H:i:s',$fecha_cambio_nueva);
			foreach($consultas as $c){
				$update_proxima_g['ClienGest']['proximag'] = $fecha_cambio_nueva;
				$update_proxima_g['ClienGest']['id'] = $c['ClienGest']['id'];
				$this->ClienGest->saveAll($update_proxima_g);
				// debug($update_proxima_g);
			}
			
		}
		$empresas = $this->Cliente->find('list', array(
		'fields' => array('rif','nombre')));

		$gestores = $this->Gestor->find('list', array(
		'fields' => array('Clave','Nombre')));
		
		$supervisors = $this->User->find('list', array(
			'fields' => array('id','username'),
				'conditions' => array(
					'rol' => 'supervisor'
				)
			));
		
		$status = $this->Statu->find('list', array(
		'fields' => array('codigo','codigo')));
		
		$this->set(compact('consultas','supervisors','empresas','gestores','status'));
	}
	
	function cargar_info_cambio_fecha() { //funcion para ajax, vista cambio_fecha
		if($this->request->isAjax()){
			$this->autoRender = false;
			$empresa = $this->request->data['empresa'];
			$gestor = $this->request->data['gestor'];
			$status = $this->request->data['status'];
			$atraso = $this->request->data['atraso'];
			$del_dia = $this->request->data['del_dia'];
			
			$data = array('empresa' => $empresa, 'gestor' => $gestor, 'status' => $status, 'atraso'=> $atraso, 'del_dia' => $del_dia);
			
			$conditions = $this->ClienGest->busqueda_cambio_fecha($data);
			
			$consultas = $this->ClienGest->find('count', array(
				'conditions' => $conditions,
				'joins' => array(
					array(
						'table' => 'clientes',
						'alias' => 'Cliente',
						'type' => 'INNER',
						'conditions' => array(
							'ClienGest.rif_emp = Cliente.rif'
						)
					),
				),
			));
			
			return json_encode($consultas);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}

	function admin_generar_archivo(){
	
		ini_set('memory_limit', '256M');
		set_time_limit(0);
	
		// debug(date("Y-m-01:00:00:00"));
		// debug(date("Y-m-t:00:00:00"));
		
		if(!empty($this->request->data)){
			// debug($this->request->data);
			$empresa = $this->request->data['User']['empresa'];
			$cedula = $this->request->data['User']['cedula'];
			$fecha1 = $this->request->data['User']['fecha_desde'];
			$fecha2 = $this->request->data['User']['fecha_hasta'];
			
			if(!empty($this->request->data['User']['sin_gestion'])){
				$sin_gestion = $this->request->data['User']['sin_gestion'];
			}else{
				$sin_gestion = 0;
			}
			if(!empty($this->request->data['User']['sin_gestionv'])){
				$sin_gestionv = $this->request->data['User']['sin_gestionv'];
			}else{
				$sin_gestionv = 0;
			}
				
			
			$data = array('User' => array('empresa' => $empresa, 'cedula' => $cedula, 'fecha1' => $fecha1, 'fecha2'=> $fecha2, 'sin_gestion' => $sin_gestion, 'sin_gestionv' => $sin_gestionv));
			
			// debug($data);
			
			$conditions = $this->ClienGest->busqueda_generar_archivo($data);
			
			if($conditions == null){
				
				$consultas = $this->ClienProd->find('all', array(
					'conditions' => $conditions,
					'fields' => array('ClienProd.*','Cobranza.NOMBRE'),
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
				
			}else{
			
				/* PAOLA Esta es la consulta principal index, aquí es donde tienen que listarse las personas de este mes pero solo la ultima gestion, arriba esta el arreglo conditions donde se ven bien las condiciones */ 
			
				$consultas = $this->ClienGest->find('all', array(
					'conditions' => $conditions,
					'fields' => array('ClienGest.*','Cobranza.NOMBRE'),
					'joins' => array(
						array(
							'table' => 'cobranzas',
							'alias' => 'Cobranza',
							'type' => 'INNER',
							'conditions' => array(
								'ClienGest.cedulaorif = Cobranza.CEDULAORIF',
							)
						),
						array(
							'table' => 'clientes',
							'alias' => 'Cliente',
							'type' => 'INNER',
							'conditions' => array(
								'ClienGest.rif_emp = Cliente.rif'
							)
						
						),
					),
				));
			}
			
			// debug($data);
			// debug($conditions);
		}else{
			$sin_gestion = 0;
			$fecha_primero_mes = date('Y-m-01:00:00:00');
			$fecha_ultimo_mes = date('Y-m-t:00:00:00');
			$consultas = $this->ClienGest->find('all', array(
					'conditions' => array(
						'ClienGest.fecha >= ' => $fecha_primero_mes,
						'ClienGest.fecha <= ' => $fecha_ultimo_mes,
					),
					'fields' => array('ClienGest.*','Cobranza.NOMBRE','Cliente.rif'),
					'order' =>  array('fecha' => 'DESC'),
					'joins' => array(
						array(
							'table' => 'cobranzas',
							'alias' => 'Cobranza',
							'type' => 'INNER',
							'conditions' => array(
								'ClienGest.cedulaorif = Cobranza.CEDULAORIF',
								),
						),
						array(
							'table' => 'clientes',
							'alias' => 'Cliente',
							'type' => 'INNER',
							'conditions' => array(
								'ClienGest.rif_emp = Cliente.rif'
							)
						
						),
					),
				));
		}
		
		$empresas = $this->Cliente->find('list', array(
		'fields' => array('rif','nombre')));
		
		// debug($consultas);
		
		// aquí mandamos a la vista la empresa que fue seleccionada en la misma vista
		if(!empty($this->request->data['User']['empresa'])){ 
			$empresa = $this->request->data['User']['empresa'];
			$this->set('empresa', $empresa);
			// debug($empresa);
		}
		$this->set(compact('empresas','consultas','sin_gestion'));
	}
}

?>
