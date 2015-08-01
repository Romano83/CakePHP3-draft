# Draft plugin for CakePHP 3

This Draft plugin give you the ability to quickly create draft system for your models.

_This plugin is the [Grafikart's Cakephp-Draft plugin](https://github.com/Grafikart/CakePHP-Draft) for CakePHP 3.*_

## Installation

### Requirements
* [composer](http://getcomposer.org)
* [CakePHP 3.*](https://github.com/cakephp/cakephp)

### Steps to install

* Run :
```
composer require romano83/cakephp3-draft:1.*
```

## How to use

In your `config\bootstrap.php` file, add this line 
```php
Plugin::load('Romano83/cakephp3-draft');
``` 
Or
```php
Plugin::loadAll();
```

The plugin is now loaded and you can add the `DraftBehavior` in your tables:

```php
<?php
namespace MyApp\Model\Table;

use Cake\ORM\Table;

class PostsTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Romano83/Cakephp3Draft.Draft');
	}
}
```

By default, the plugin use an "online" field to set the state of a content.
* online = -1 when the content is a draft
* online = 0 when the content is offline
* online = 1 when content is online

## Customization

If you want to use custom fields, you can override default behavior configuration :

```php
<?php
namespace MyApp\Model\Table;

use Cake\ORM\Table;

class PostsTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior(
			'Romano83/Cakephp3Draft.Draft', [
				'conditions' => [
					'draft' => 1
				]
			]
		);
	}
}
``` 
For instance, this configuration will use a "draft" field (set to 1), to create Draft.

## Methods

With the plugin attached, the model will have new method, `getDraftId(Table $table, array $conditions = [])` witch return draft ID.
The first parameter is a `\Cake\ORM\Table` instance and the second one is an optional array.

### How to implement this method
Here, is an example of how to use this method in your `PostsController`:
```php
public function add(){
	$post = $this->Posts->newEntity();
	if($this->request->is(['post', 'put'])){
		// Do your stuff here...
	}else{
		$post->id = $this->Posts->getDraftId($this->Posts); // get the last draft Id or create new one if needed
	}
}

// OR
public function add(){
	$post = $this->Posts->newEntity();
	if($this->request->is(['post', 'put'])){
		// Do your stuff here...
	}else{
		$post->id = $this->Posts->getDraftId($this->Posts, ['user_id' => 2]); // Get a draft Id for a content belonging to user 2 (or create a new one)
	}
}

```

If you want to clean your table from drafts, you can implement this method:
```php

$this->Posts->cleanDrafts($this->Posts);
```

## How to contribute
- Create a ticket in GitHub, if you have found a bug.
- Create a new branch if you want do a PR.
- Your code **must follow** the [Coding Standard of CakePHP](http://book.cakephp.org/3.0/en/contributing/cakephp-coding-conventions.html).
- You **must** add testcases if needed.


## Special thanks

   * [Grafikart](https://github.com/Grafikart) for the first version of this plugin !

