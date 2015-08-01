<?php
namespace Romano83\Cakephp3Draft\Test\TestCase\Model\Behavior;

use Cake\TestSuite\TestCase;
use Romano83\Cakephp3Draft\Model\Behavior\DraftBehavior;
use Cake\ORM\TableRegistry;

/**
 * Romano83\cakephp3-draft\Model\Behavior\DraftBehavior Test Case
 */
class DraftBehaviorTest extends TestCase
{

    public $fixtures = [ 'plugin.Romano83\Cakephp3Draft.posts' ];
    
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->config = ['conditions' => ['online' => -1]];
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Model);
        TableRegistry::clear();

        parent::tearDown();
    }
    
    /**
     * Test getDraftIdWithoutDraft
     * 
     * @return void
     */
    public function testGetDraftIdWithoutDraft()
    {
        $this->Model = TableRegistry::get('Posts');
        $this->Model->addBehavior('Romano83/Cakephp3Draft.Draft', $this->config);
        $id = $this->Model->getDraftId($this->Model);
        $this->assertEquals(2, $id);
    }
    /**
     * Test getDraftId method
     *
     * @return void
     */
    public function testGetDraftIdWithDraftCreation()
    {        
        $this->Model = TableRegistry::get('Posts');
        $this->Model->addBehavior('Romano83/Cakephp3Draft.Draft', $this->config);
        $entity = $this->Model->newEntity($this->config['conditions']);
        $entity = $this->Model->save($entity);
        $this->assertEquals(2, $entity->id);
        $this->assertEquals(-1, $entity->online);
        
        $this->Model->getDraftId($this->Model);
        $result = $this->Model->find()->select(['id' => $this->Model->primaryKey()])->where($this->config['conditions'])->first();
        $this->assertInstanceOf('\Cake\ORM\Entity', $result);
        $this->assertEquals(2, $result->id);
    }
    
    public function testGetDraftIdWithConditions()
    {
        $this->Model = TableRegistry::get('Posts');
        $this->Model->addBehavior('Romano83/Cakephp3Draft.Draft', $this->config);
        $draftId = $this->Model->getDraftId($this->Model, ['user_id' => 2]);
        $entity = $this->Model->get($draftId);
        $this->assertEquals(-1, $entity->online);
        $this->assertEquals(2, $entity->user_id);
    }
    
    public function testGetDraftIdWithOptions()
    {
        $this->Model = TableRegistry::get('Posts');
        $this->Model->addBehavior('Romano83/Cakephp3Draft.Draft', ['conditions' => ['draft' => 1]]);
        $draftId = $this->Model->getDraftId($this->Model);
        $entity = $this->Model->get($draftId);
        $this->assertEquals(0, $entity->online);
        $this->assertEquals(1, $entity->draft);        
    }
    
    public function testCleanDrafts()
    {
        $this->Model = TableRegistry::get('Posts');
        $this->Model->addBehavior('Romano83/Cakephp3Draft.Draft', $this->config);
        $this->Model->getDraftId($this->Model);
        $result = $this->Model->cleanDrafts($this->Model);
        $this->assertEquals(1, $result);
        $draft = $this->Model->find()->select(['id' => $this->Model->primaryKey()])->where($this->config['conditions'])->first();
        $this->assertEquals(null, $draft);
    }
}
