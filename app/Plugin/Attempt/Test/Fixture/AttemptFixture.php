<?php
/**
 * AttemptFixture
 *
 */
class AttemptFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'ip' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 64, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'expires' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ip' => array('column' => array('ip', 'action'), 'unique' => 0), 'expires' => array('column' => 'expires', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '4f5a6edd-311c-43ab-8d38-464d3c87f3ee',
			'ip' => '127.0.0.1',
			'action' => 'admin_login',
			'created' => '2012-03-09 20:58:05',
			'expires' => '2012-03-09 20:58:05'
		),
		array(
			'id' => '5f5a6edd-311c-43ab-8d38-464d3c87f3ee',
			'ip' => '127.0.0.1',
			'action' => 'admin_login',
			'created' => '2022-03-09 20:58:07',
			'expires' => '2022-03-09 20:58:07'
		),
		array(
			'id' => '6f5a6edd-311c-43ab-8d38-464d3c87f3ee',
			'ip' => '127.0.0.1',
			'action' => 'admin_login',
			'created' => '2015-03-09 20:58:05',
			'expires' => '2015-03-09 20:58:05'
		),
	);
}