<?php
App::uses('AppModel', 'Model');
class Cobranza extends AppModel {
	function buscarGestiones($cedula,$empresas) { //Funcion que busca todas las gestiones de un deudor dada su cedula
		$empresas = Set::combine($empresas, '{n}.Cliente.id', '{n}.Cliente.rif');
		foreach ($empresas as $e) {
			$gestiones[$e] = $this->find('all',array(
				'fields' => array('ClienGest.*','Cobranza.*','User.*','Gestor.*'),
				'conditions' => array('Cobranza.CEDULAORIF' => $cedula,'Cobranza.RIF_EMP' => $e),
				'joins' => array(
					array(
						'table' => 'clien_gests',
						'alias' => 'ClienGest',
						'type' => 'INNER',
						'conditions' => array(
							'ClienGest.cedulaorif' => $cedula,
							'ClienGest.rif_emp' => $e,
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
				'order' => array('ClienGest.id DESC')
			));
		}
		return $gestiones;
	}
	
	function buscarEmpresas($cedula){
		$empresas = $this->find('all',array(
			'fields' => array('DISTINCT(Cobranza.rif_emp)','Cliente.*'),
			'conditions' => array('Cobranza.cedulaorif' => $cedula),
			'joins' => array(
				array(
					'table' => 'clientes',
					'alias' => 'Cliente',
					'type' => 'INNER',
					'conditions' => array(
						'Cliente.rif = Cobranza.rif_emp',
					)
				),
			),
		));
		return($empresas);
	}
	////METODOS DESARROLLADOS POR JUAN CARLOS
	
	public function consultarDeudores(){
		$deudores = $this->query("SELECT Cobranza.NOMBRE, Cobranza.CEDULAORIF, ClienGest.telefono, Cobranza.FECH_ASIG, ClienGest.proximag,Cobranza.GESTOR, ClienGest.cedulaorif, ClienGest.rif_emp, ClienGest.numero, ClienGest.id, ClienGest.cond_deud
		FROM cobranzas as Cobranza
			INNER JOIN clien_gests as ClienGest ON ClienGest.cedulaorif=Cobranza.CEDULAORIF AND ClienGest.numero = Cobranza.UltGestion
			INNER JOIN gestors as Gestor ON Gestor.Clave = Cobranza.Gestor
			INNER JOIN users as User ON User.id = Gestor.user_id
			GROUP BY Cobranza.CEDULAORIF  limit 100
		");
		return $deudores;
	}
	
	public function consultarTotalCobranzas(){
		$cobranzas=$this->query("SELECT cza.NOMBRE, cza.CEDULAORIF, cza.FECH_ASIG, cza.GESTOR
			FROM cobranzas as cza 
			INNER JOIN clien_gests as ClienGest ON ClienGest.cedulaorif=cza.CEDULAORIF AND ClienGest.numero = cza.UltGestion
			INNER JOIN gestors as Gestor ON Gestor.Clave = cza.Gestor
			INNER JOIN users as User ON User.id = Gestor.user_id
			GROUP BY cza.CEDULAORIF LIMIT 100");
		return $cobranzas;
	}	
	
	public function extraerTelefonoUniversoDeudores($cedula){
		$telefono=$this->query("SELECT ClienGest.telefono, ClienGest.proximag
		From clien_gests as ClienGest WHERE ClienGest.cedulaorif='" .$cedula."' GROUP BY ClienGest.cedulaorif");
		return $telefono;
	}
	
	public function consultaDeudor($cedula){
		$deudores = $this->query("SELECT cza.`CEDULAORIF`,cza.`NOMBRE`,cza.`GESTOR`, st.`condicion`,cza.`FECH_ASIG`,cl.nombre, cl.rif, ClienGest.proximag
			 FROM cobranzas AS cza
			INNER JOIN clien_gests AS ClienGest ON ClienGest.cedulaorif=cza.CEDULAORIF
			 AND ClienGest.numero = cza.UltGestion
			INNER JOIN gestors AS Gestor ON Gestor.Clave = cza.Gestor
			INNER JOIN clientes AS cl ON cl.rif=cza.`RIF_EMP`
			INNER JOIN users AS User ON User.id = Gestor.user_id
			INNER JOIN status AS st ON cza.CONDICION=st.codigo
			WHERE cza.CEDULAORIF='".$cedula."'  GROUP BY cza.CEDULAORIF");
		return $deudores;
	}
	
	public function consultarGestores(){
		$gestores = $this->query("SELECT id, Clave, Nombre FROM gestors WHERE activo=1 ");
		return $gestores;
	}
	
	public function consultaEstadoCuenta($cedula){
		$estado=$this->query("SELECT cpg.CEDULAORIF,cpg.FECH_REG, cpg.FECH_PAGO,cpg.TOTAL_PAGO, cpg.PRODUCTO, cpg.CUENTA, cpg.EST_PAGO, cpg.EFECTIVO,
							cpg.MTO_CHEQ1, cpg.MTO_OTROS, cpg.Nro_Efect, cpg.NRO_OTRO, cpg.COND_PAGO, cpg.LOGIN_REG
							FROM clien_pago AS cpg WHERE CedulaOrif='".$cedula."' ");
		return $estado;
	}
	
	public function consultaGestiones($cedula, $empresa){
		$gestions = $this->query("SELECT Cobranza.GESTOR, ClienGest.id, ClienGest.cedulaorif, ClienGest.fecha, ClienGest.numero,
					ClienGest.telefono, ClienGest.producto, ClienGest.cond_deud, ClienGest.proximag,
					ClienGest.contacto
					FROM cobranzas AS Cobranza
					INNER JOIN clien_gests AS ClienGest ON ClienGest.cedulaorif = Cobranza.CEDULAORIF AND ClienGest.rif_emp=Cobranza.RIF_EMP
					INNER JOIN gestors AS Gestor ON Gestor.Clave=Cobranza.GESTOR
					INNER JOIN users AS User ON User.id=Gestor.user_id
					WHERE Cobranza.CEDULAORIF='".$cedula."' AND Cobranza.RIF_EMP='".$empresa."'
		");
		return $gestions;
	}	
	
	public function consultaCifras($cedula){
		$cifras=$this->query("SELECT cpg.TOTAL_PAGO, cpg.RIF_EMP, cpg.CEDULAORIF, cpg.EFECTIVO, cpg.MTO_CHEQ1,cpg.TOTAL_PAGO,
			cp.SaldoInicial, cp.CEDULAORIF, cp.RIF_EMP 
			FROM clien_pago AS cpg
			INNER JOIN clien_prod AS cp ON cp.CEDULAORIF=cpg.`CEDULAORIF`
			WHERE cpg.`CEDULAORIF`='".$cedula."' ");
		return $cifras;
	}
	//////FINALIZAN METODOS JUAN CARLOS/////
}

?>