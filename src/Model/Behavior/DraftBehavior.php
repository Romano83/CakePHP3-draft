<?php

namespace Romano83\Cakephp3Draft\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class DraftBehavior extends Behavior
{

    /**
     * Default config
     *
     * @var array $config
     */
    public $config = [
            'conditions' => ['online' => -1]
        ];

    /**
     * Initialize Draft Behavior.
     * Merge default config and user config
     *
     * @param array $config Default config parameters
     *
     * @return void
     */
    public function initialize(array $config)
    {
        $this->config[$this->_table->getAlias()] = \array_merge(
            $this->config,
            $config
        );
    }

    /**
     * Find or create new draft database entry and return entity ID
     *
     * @param \Cake\ORM\Table $table      Table instance
     * @param array|null      $conditions Find conditions
     *
     * @return int             $id         Draft Id
     */
    public function getDraftId(Table $table, $conditions = [])
    {
        $conditions = array_merge($this->config[$table->getAlias()]['conditions'], $conditions);
        $result = $table->find()
            ->select(
                [
                    'id' => $table->getPrimaryKey()
                ]
            )
            ->andWhere($conditions)
            ->first();
        if ($result) {
            return $result->id;
        } else {
            $entity = $table->newEntity($conditions, ['validate' => false]);
            $entity = $table->save($entity);

            return $entity->id;
        }
    }

    /**
     * Delete all draft entries in database
     *
     * @param \Cake\ORM\Table $table Table instance
     *
     * @return bool
     */
    public function cleanDrafts(Table $table)
    {
        return $table->deleteAll($this->config['conditions']);
    }
}
