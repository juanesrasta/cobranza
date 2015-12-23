<?php
class GruposProductosController extends AppController {
	
	public $components = array('Paginator');
	public $uses = array('GruposProducto','Cliente','Producto');
	
	function index($cliente_id = null) {
		$clientes = $this->Cliente->find('list',array(
			'fields' => array('id','nombre'),
			'conditions' => array('Cliente.status' => 'activo')
		));
		$clientes[0] = array('0' => 'Selecciona una empresa');
		$this->set(compact('clientes'));
		if (!empty($this->data) || !empty($cliente_id)) {
			if (!empty($this->data['GruposProducto']['cliente_id'])) {
				$cliente_id = $this->data['GruposProducto']['cliente_id'];
			}
			$this->Paginator->settings['conditions'] =  array('Producto.cliente_id' => $cliente_id,);
			$productos = $this->Paginator->paginate('Producto');
			$busqueda = true;
			$this->set(compact('productos','busqueda','cliente_id'));
		}
	}
	
	function add($cliente_id,$producto_id) {
		$producto = $this->Producto->findById($producto_id);
		if (!empty($this->data)) {
			$data = $this->data;
			$this->GruposProducto->set($this->data);
			if ($this->GruposProducto->validates($this->data)) {
				$this->GruposProducto->save($this->data);
				$gp_id = $this->GruposProducto->id;
				$data['Producto']['gruposProducto_id'] =  $gp_id; 
				if ($this->Producto->save($data)) {
					$this->Session->setFlash(__('El producto ha sido actualizado satisfactoriamente.'));
					$this->redirect(array('action' => 'index',$data['GruposProducto']['cliente_id']));
				}
			}
			$this->Session->setFlash(__('No pudo ser actualizado'));
			
		} else {
			$this->data = $this->GruposProducto->find('first',array(
				'conditions' => array(
					'Producto.id' => $producto_id
				),
			));
		}
	
		$this->set(compact('cliente_id','producto_id'));
	}
}

?>
