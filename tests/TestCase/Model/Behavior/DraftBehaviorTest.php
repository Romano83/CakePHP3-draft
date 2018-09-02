<?php
namespace Romano83\Cakephp3Draft\Test\TestCase\Model\Behavior;

use Cake\ORM\Locator\TableLocator;
use Cake\TestSuite\TestCase;

/**
 * Romano83\cakephp3-draft\Model\Behavior\DraftBehavior Test Case
 */
class DraftBehaviorTest extends TestCase
{

    public $fixtures = [ 'plugin.Romano83\Cakephp3Draft.posts' ];

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var \Cake\ORM\Locator\TableLocator
     */
    private $tableLocator;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->tableLocator = new TableLocator();
        $this->config = ['conditions' => ['online' => -1]];
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($model);
        $this->tableLocator->clear();

        parent::tearDown();
    }

    /**
     * Test testGetDraftIdWithoutDraft
     *
     * @return void
     */
    public function testGetDraftIdWithoutDraft()
    {
        $model = $this->tableLocator->get('Posts');
        $model->addBehavior('Romano83/Cakephp3Draft.Draft', $this->config);
        $id = $model->getDraftId($model);
        $this->assertEquals(2, $id);
    }

    /**
     * Test testGetDraftIdWithDraftCreation
     *
     * @return void
     */
    public function testGetDraftIdWithDraftCreation()
    {
        $model = $this->tableLocator->get('Posts');
        $model->addBehavior('Romano83/Cakephp3Draft.Draft', $this->config);
        $entity = $model->newEntity($this->config['conditions']);
        $entity = $model->save($entity);
        $this->assertEquals(2, $entity->id);
        $this->assertEquals(-1, $entity->online);

        $model->getDraftId($model);
        $result = $model->find()->select(['id' => $model->getPrimaryKey()])->where($this->config['conditions'])->first();
        $this->assertInstanceOf('\Cake\ORM\Entity', $result);
        $this->assertEquals(2, $result->id);
    }

    /**
     * Test testGetDraftIdWithDraftCreation
     *
     * @return void
     */
    public function testGetDraftIdWithConditions()
    {
        $model = $this->tableLocator->get('Posts');
        $model->addBehavior('Romano83/Cakephp3Draft.Draft', $this->config);
        $draftId = $model->getDraftId($model, ['user_id' => 2]);
        $entity = $model->get($draftId);
        $this->assertEquals(-1, $entity->online);
        $this->assertEquals(2, $entity->user_id);
    }

    /**
     * Test testGetDraftIdWithOptions
     *
     * @return void
     */
    public function testGetDraftIdWithOptions()
    {
        $model = $this->tableLocator->get('Posts');
        $model->addBehavior('Romano83/Cakephp3Draft.Draft', ['conditions' => ['draft' => 1]]);
        $draftId = $model->getDraftId($model);
        $entity = $model->get($draftId);
        $this->assertEquals(0, $entity->online);
        $this->assertEquals(1, $entity->draft);
    }

    /**
     * Test testCleanDrafts
     *
     * @return void
     */
    public function testCleanDrafts()
    {
        $model = $this->tableLocator->get('Posts');
        $model->addBehavior('Romano83/Cakephp3Draft.Draft', $this->config);
        $model->getDraftId($model);
        $result = $model->cleanDrafts($model);
        $this->assertEquals(1, $result);
        $draft = $model->find()->select(['id' => $model->getPrimaryKey()])->where($this->config['conditions'])->first();
        $this->assertEquals(null, $draft);
    }
}
