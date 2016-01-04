<?php
App::uses('AppModel', 'Model');

class Statu extends AppModel {
 public $name = 'Statu';
 
	public $belongsTo = array(
        'Cliente' => array(
            'className'    => 'Cliente',
            'foreignKey'   => 'cliente_id'
        ),
    );
	
	/*function buscar_comentario($status,$producto){
		$this->Producto = ClassRegistry::init('Producto');
		if ($status == 'PP' || $status == 'MM') {
			if ($status == 'PP') {
				$comentario = 'PP PARA EL POR BS. ';
			} else {
				$comentario  = 'NOMBRE, APELLIDO,   PARENTESCO';
			}
			
		} else {
			if (!empty($status)) {
				$empresa = $this->Producto->find('first',array(
					'conditions' => array('Producto.codigo' => $producto)
				));
				$rif = $empresa['Cliente']['rif'];
				$status = $this->find('first',array(
					'conditions' => array(
						'rif_emp' => $rif,
						'Statu.codigo' => $status
					)
				));
				$comentario = $status['Statu']['condicion'];
			} else {
				$comentario = '';
			}
		}
		return($comentario);
	}*/
	
	
	function buscar_comentario($status,$producto){
		$this->Producto = ClassRegistry::init('Producto');
		if ($status == 'PP' || $status == 'MM') {
			if ($status == 'PP') {
				$comentario = 'PP PARA EL POR BS. ';
			} else {
				$comentario  = 'NOMBRE, APELLIDO,   PARENTESCO';
			}
			
		} else {
			if (!empty($status)) {
				$empresa = $this->Producto->find('first',array(
					'conditions' => array('Producto.codigo' => $producto)
				));
				//$rif = $empresa['Cliente']['rif']; //7
				
				$cliente=$this->Cliente->find('first',array( 
					'fields'=>array('Cliente.id','Cliente.rif'), 
					'conditions' => array('Cliente.id' => $empresa['Producto']['cliente_id']) 
					)
				);
				$rif = $cliente['Cliente']['rif']; 
			
				$status = $this->find('first',array(
					'conditions' => array(
						'rif_emp' => $rif,
						'Statu.codigo' => $status
					)
				));
				//$arr=array("comentario"=>$comentario,"status"=>$status,"empresa"=>$empresa,"rif"=>$rif);
				$comentario = $status['Statu']['condicion'];
			} else {
				$comentario = '';
			}
		}
		return($comentario);
	}
	
	function concatenar_comentario($status,$param1, $param2, $param3){
		if ($status == 'PP') {
			//param1 fecha, param2 bolivares
			$comentario = 'PP PARA EL '.$param1.' POR BS. '.$param2;
		} else {
			//param1 nombre, param2 apellido, param3 parentesco
			$comentario = 'MENSAJE CON '.$param1.' '.$param2.' '.$param3;
		}
		return $comentario;
	}
	
}

?>