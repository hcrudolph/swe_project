<?php
App::uses('AccountsDate', 'Model');

/**
 * AccountsDate Test Case
 *
 */
class AccountsDateTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.accounts_date'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->AccountsDate = ClassRegistry::init('AccountsDate');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AccountsDate);

		parent::tearDown();
	}

}
