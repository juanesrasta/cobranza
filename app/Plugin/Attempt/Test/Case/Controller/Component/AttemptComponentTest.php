<?php
App::uses('Controller', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('AttemptComponent', 'Attempt.Controller/Component');

/**
 * TestProjectsController *
 */
class TestAttemptController extends Controller {
	public $autoRender = false;
}

/**
 * ProjectsController Test Case
 *
 */
class AttemptComponentTestCase extends CakeTestCase {

	public $AttemptComponent = null;
    public $Controller = null;

	public $fixtures = array('plugin.attempt.attempt');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$Collection = new ComponentCollection();
		$this->AttemptComponent = new AttemptComponent($Collection);

		$request = new CakeRequest();
		$this->Controller = new TestAttemptController($request);
		$this->AttemptComponent->startup($this->Controller);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->AttemptComponent);
        unset($this->Controller);
	}

	public function testCount() {
		$result = $this->AttemptComponent->count('admin_login');
		$this->assertEquals(2, $result);

		$result = $this->AttemptComponent->count('something');
		$this->assertEquals(0, $result);
	}

	public function testLimit() {
		$result = $this->AttemptComponent->limit('admin_login');
		$this->assertTrue($result);

		$result = $this->AttemptComponent->limit('admin_login', 2);
		$this->assertFalse($result);

		$result = $this->AttemptComponent->limit('something', 3);
		$this->assertTrue($result);
	}

	public function testFail() {
		$result = $this->AttemptComponent->fail('admin_login');
		$this->assertEquals($result['Attempt']['ip'], '127.0.0.1');
		$this->assertEquals($result['Attempt']['action'], 'admin_login');

		$result = $this->AttemptComponent->fail('admin_login', '+20 minutes');
		$this->assertEquals($result['Attempt']['ip'], '127.0.0.1');
		$this->assertEquals($result['Attempt']['action'], 'admin_login');
	}

	public function testReset() {
		$result = $this->AttemptComponent->reset('admin_login');
		$this->assertTrue($result);

		// deleteAll returns true no matter what.
		$result = $this->AttemptComponent->reset('something');
		$this->assertTrue($result);
	}

	public function testCleanup() {
		$result = $this->AttemptComponent->cleanup();
		$this->assertTrue($result);
	}

}