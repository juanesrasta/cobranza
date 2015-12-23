<?php
/**
 * Attempt Component Class
 *
 * Based on http://bakery.cakephp.org/articles/aep_/2006/11/04/brute-force-protection
 *
 * @author Thomas Heymann
 * @version	0.1
 * @license	http://www.opensource.org/licenses/mit-license.php The MIT License
 * @package app
 * @subpackage app.controllers.components
 **/
 

class AttemptComponent extends Component {
	
	// Called after the Controller::beforeFilter() and before the controller action
	public function startup(Controller $controller) {
		$this->Controller = $controller;
		$this->Attempt = ClassRegistry::init('Attempt.Attempt');
	}

	public function count($action) {
		return $this->Attempt->count(
			$this->Controller->request->clientIp(),
			$action
		);
	}

	public function limit($action, $limit = 5) {
		return $this->Attempt->limit(
			$this->Controller->request->clientIp(),
			$action,
			$limit
		);
	}

	public function fail($action, $duration = '+10 minutes') {
		return $this->Attempt->fail(
			$this->Controller->request->clientIp(),
			$action,
			$duration
		);
	}

	public function reset($action) {
		return $this->Attempt->reset(
			$this->Controller->request->clientIp(),
			$action
		);
	}

	public function cleanup() {
		return $this->Attempt->cleanup();
	}
}