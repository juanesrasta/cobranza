<?php
App::uses('AppModel', 'Model');
class ClienProd extends AppModel {
	public $useTable = 'clien_prod';
	 var $primaryKey = 'unique_id';

    public $hasMany = array(
        'Desincorporado' => array(
            'className'    => 'Desincorporado',
            'foreignKey'   => 'clien_prod_id'
        ),
    );
	
	public function consultaDatosProductos($cedula){
		$productos=$this->query("SELECT cp.PRODUCTO,cp.CUENTA, cp.SaldoInicial, cp.interes,MtoTotal,cp.DIASMORA,cp.NroCuotas,cp.CtaAsocPago,RIF_EMP,Contrato,DescProd1,DescProd2
		FROM clien_prod AS cp WHERE cp.cedulaorif='".$cedula."' ");
		return $productos;
	}
}

?>