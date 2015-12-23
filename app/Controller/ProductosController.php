<?php
// app/Controller/UsersController.php
App::uses('AppController', 'Controller');

/**
 * Productos Controller
 *
 * @property Productos $Producto
 * @property PaginatorComponent $Paginator
 */

class ProductosController extends AppController {
	
	public $components = array('Paginator');
	public $uses = array('Producto','Cliente','GruposProducto');
	
	public function index() {
		$this->Paginator->settings['conditions'] =  array('Cliente.status' => 'activo');
		$clientes = $this->Paginator->paginate('Cliente');
		$this->set(compact('clientes'));
 	}
	
	public function add() {
        if ($this->request->is('post')) {
			$this->Producto->set($this->data);
			$this->GruposProducto->set($this->data);
			if ($this->Producto->validates($this->data) && $this->GruposProducto->validates($this->data)) {
				$this->GruposProducto->create();
				if ($this->GruposProducto->save($this->request->data)) {
					$data = $this->data;
					$data['Producto']['gruposProducto_id'] = $this->GruposProducto->id;
					$this->Producto->create();
					if ($this->Producto->save($data)) {
						$this->Session->setFlash(__('El Producto ha sido creado con &eacute;xito.'));
						return $this->redirect(array('action' => 'index'));
					}
					$this->Session->setFlash(
						__('El Producto no ha sido creado, intente nuevamente.')
					);
				}
			}
			$this->Session->setFlash(
				__('El Producto no ha sido creado, intente nuevamente.')
			);
        }
		$clientes = $this->Cliente->find('list',array(
			'fields' => array('id','nombre'),
			'conditions' => array('Cliente.status' => 'activo')
		));
		$this->set(compact('clientes'));
    }
	
	
	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Producto invalido'));
		}

		$producto = $this->Producto->findById($id);
		if (!$producto) {
			throw new NotFoundException(__('Producto invalido'));
		}

		if ($this->request->is(array('producto', 'put'))) {
			
			$this->Producto->id = $id;
			$this->GruposProducto->set($this->data);
			if ($this->GruposProducto->validates($this->data)) {
				$this->GruposProducto->save($this->data);
				$data = $this->data;
				$gp_id = $this->GruposProducto->id;
				$data['Producto']['gruposProducto_id'] =  $gp_id; 
				if ($this->Producto->save($data)) {
					$this->Session->setFlash(__('El producto ha sido actualizado satisfactoriamente.'));
					return $this->redirect(array('action' => 'index'));
				}
			}
			$this->Session->setFlash(__('No pudo ser actualizado'));
		}

		if (!$this->request->data) {
			$this->request->data = $producto;
		}
		$clientes = $this->Cliente->find('list',array(
			'fields' => array('id','nombre'),
			'conditions' => array('Cliente.status' => 'activo')
		));
			$this->set(compact('clientes'));
	}
	
	
	public function view($id = null) {
        $this->Producto->id = $id;
        $this->set('producto', $this->Producto->read());
    }
	
	
	/**
 * Activar a cliente 
 * @param int $id Id del usuario administrador que se va a activar
 * @return void
 */
	public function activar($id) {
		if ($this->request->is('post')) {
			if (!$this->Producto->exists($id)) {
				throw new NotFoundException(__('Producto desconocido.'));
			}
			$producto = $this->Producto->findById($id);
			$this->Producto->id = $id;
			if($producto['Producto']['status']=='activo'){
				$this->Producto->saveField('status','inactivo');
				$this->Session->setFlash(__('El producto ha sido desactivado.'),'default',array('class'=>'message success'));
				
			}
			else{
				$this->Producto->saveField('status','activo');
				$this->Session->setFlash(__('El Producto ha sido activado.'),'default',array('class'=>'message success'));
			}
			if(isset($this->request->data['src'])&&$this->request->data['src']=='producto')
				$this->redirect('producto');
			else
				$this->redirect('index');
		}else
			$this->redirect($this->adminDefaultRoute);
	}
}

?>
