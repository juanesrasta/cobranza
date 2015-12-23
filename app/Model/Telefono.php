<?php
App::uses('AppModel', 'Model');

class Telefono extends AppModel {

	 var $primaryKey = 'unique_id';
	 
	public function buscarTelefonos($cedula){
		$telefonos = $this->find('all', array(
			'conditions' => array(
				'cedulaorif' => $cedula
			),
		));
		
		return($telefonos);
	}
	
	public function agregarTlf($cedula,$telefonos,$direcciones = null) {
		if (!empty($direcciones)) {
			$i = 0;
			foreach ($direcciones as $d){
				$existe_direccion = $this->find('first',array(
					'conditions' => array('direccion' => $d['direccion'],'cedulaorif' => $cedula)
				));
				if (!empty($existe_direccion)) {
					if (!empty($telefonos[$i])) {
						$actualizar_tlf = array('Telefono' => array(
							'unique_id' => $existe_direccion['Telefono']['unique_id'],
							'telefono' => $telefonos[$i],
							'cedulaorif' => $cedula
						));
						$this->save($actualizar_tlf);
					} 
				} else { //SI no existe la direccion la agrego
					if (!empty($telefonos[$i])) {
						$actualizar_tlf = array('Telefono' => array(
							'direccion' => $d['direccion'],
							'telefono' => $telefonos[$i],
							'ubicacion' => $direcciones[$i]['ubicacion'],
							'estado' => $direcciones[$i]['estado'],
							'ciudad' => $direcciones[$i]['ciudad'],
							'cedulaorif' => $cedula
						));
						$this->create();
						$this->save($actualizar_tlf);
					}
				}
				$i++;
			}
		} else { //Si es archivo de bicentenario y solo me pasaron telefonos
			foreach ($telefonos as $t) {
				$existe_telefono = $this->find('first',array(
					'conditions' => array('telefono' => $t,'cedulaorif' => $cedula)
				));
				if (empty($existe_telefono)) {
					$actualizar_tlf = array('Telefono' => array(
						'telefono' => $t,
						'cedulaorif' => $cedula
					));
					$this->create();
					$this->save($actualizar_tlf);
				}	
			}
		}
	}
	
/////////METODOS DESARROLLADOS PO JUAN CARLOS 
	public function consultaDatosTelefonicos($cedula){
		$telefonos=$this->query("SELECT direccion, telefono,ubicacion FROM telefonos WHERE cedulaorif='".$cedula."' ");
		return $telefonos;
	}
/////FINALIZAN METODOS JUAN CARLOS///
}
?>