<?php
// App::uses('Attempt', 'Attempt.Model');

/**
 * Attempt Test Case
 *
 */
class AttemptTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('plugin.attempt.attempt');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Attempt = ClassRegistry::init('Attempt.Attempt');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Attempt);

		parent::tearDown();
	}

	public function testCount() {
		$result = $this->Attempt->count('127.0.0.1', 'admin_login');
		$this->assertEquals(2, $result);

		$result = $this->Attempt->count('127.0.0.7', 'admin_something');
		$this->assertEquals(0, $result);
	}

	public function testLimit() {
		$result = $this->Attempt->limit('127.0.0.1', 'admin_login', 5);
		$this->assertTrue($result);

		$result = $this->Attempt->limit('127.0.0.1', 'admin_login', 1);
		$this->assertFalse($result);

		$result = $this->Attempt->limit('127.0.0.1', 'admin_login', 0);
		$this->assertFalse($result);

		$result = $this->Attempt->limit('127.0.0.7', 'admin_something', 5);
		$this->assertTrue($result);

		$result = $this->Attempt->limit('127.0.0.7', 'admin_something', 0);
		$this->assertFalse($result);
	}

	public function testFail() {
		$result = $this->Attempt->fail('127.0.0.1', 'admin_login', '1 hour');
		$this->assertEquals($result['Attempt']['ip'], '127.0.0.1');
		$this->assertEquals($result['Attempt']['action'], 'admin_login');
	}

	public function testReset() {
		$result = $this->Attempt->reset('127.0.0.1', 'admin_login');
		$this->assertTrue($result);

		// deleteAll returns true no matter what.
		$result = $this->Attempt->reset('127.0.0.9', 'admin_something');
		$this->assertTrue($result);
	}

	public function testCleanup() {
		$result = $this->Attempt->cleanup();
		$this->assertTrue($result);
	}

}