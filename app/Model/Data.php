<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class Data extends AppModel {
	 var $primaryKey = 'CedulaOrif';

	function buscarDatos($cedula){
		
		$data_deudor = $this->find('first', array(
			'conditions' => array(
				'CedulaOrif' => $cedula
			),
		));
		if (empty($data_deudor)) {
			$data_deudor['Data']['Direccion'] = ''; 
		}
		return($data_deudor);
	}
}

?>