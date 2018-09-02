<?php

namespace Romano83\Cakephp3Draft\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PostsFixture extends TestFixture {

	public $fields = [
		'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
		'name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
		'content' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
		'online' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => ''],
		'draft' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => ''],
		'user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => ''],
		'created' => ['type' => 'datetime'],
		'updated' => ['type' => 'datetime'],
		'_constraints' => [
		'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
	],
		'_options' => [
			'engine' => 'InnoDB',
			'collation' => 'utf8_general_ci'
		]
	];

	public $records = [
		[
			'id' => 1,
			'name' => 'First Article',
			'content' => 'First Article content',
			'online' => '1',
			'draft' => '0',
			'user_id' => '0',
			'created' => '2007-03-18 10:39:23',
			'updated' => '2007-03-18 10:41:31',
		]
	];
}