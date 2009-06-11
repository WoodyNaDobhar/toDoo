<?php
/* SVN FILE: $Id$ */
/**
 * TestTaskTest file
 *
 * Test Case for test generation shell task
 *
 * PHP versions 4 and 5
 *
 * CakePHP :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2006-2008, Cake Software Foundation, Inc.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2006-2008, Cake Software Foundation, Inc.
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP Project
 * @package       cake
 * @subpackage    cake.tests.cases.console.libs.tasks
 * @since         CakePHP v 1.2.0.7726
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Core', 'Shell');
App::import('Core', array('Controller', 'Model'));

if (!defined('DISABLE_AUTO_DISPATCH')) {
	define('DISABLE_AUTO_DISPATCH', true);
}

if (!class_exists('ShellDispatcher')) {
	ob_start();
	$argv = false;
	require CAKE . 'console' .  DS . 'cake.php';
	ob_end_clean();
}

if (!class_exists('TestTask')) {
	require CAKE . 'console' .  DS . 'libs' . DS . 'tasks' . DS . 'test.php';
	require CAKE . 'console' .  DS . 'libs' . DS . 'tasks' . DS . 'template.php';
}

Mock::generatePartial(
	'ShellDispatcher', 'TestTestTaskMockShellDispatcher',
	array('getInput', 'stdout', 'stderr', '_stop', '_initEnvironment')
);
Mock::generatePartial(
	'TestTask', 'MockTestTask',
	array('in', '_stop', 'err', 'out', 'createFile', 'isLoadableClass')
);

/**
 * Test subject models for fixture generation
 **/
class TestTaskArticle extends Model {
	var $name = 'TestTaskArticle';
	var $useTable = 'articles';
	var $hasMany = array(
		'Comment' => array(
			'className' => 'TestTask.TestTaskComment',
			'foreignKey' => 'article_id',
		)
	);
	var $hasAndBelongsToMany = array(
		'Tag' => array(
			'className' => 'TestTaskTag',
			'joinTable' => 'articles_tags',
			'foreignKey' => 'article_id',
			'associationForeignKey' => 'tag_id'
		)
	);
	function doSomething() {

	}
	function doSomethingElse() {

	}
	function _innerMethod() {

	}
}
class TestTaskTag extends Model {
	var $name = 'TestTaskTag';
	var $useTable = 'tags';
	var $hasAndBelongsToMany = array(
		'Article' => array(
			'className' => 'TestTaskArticle',
			'joinTable' => 'articles_tags',
			'foreignKey' => 'tag_id',
			'associationForeignKey' => 'article_id'
		)
	);
}
/**
 * Simulated Plugin
 **/
class TestTaskAppModel extends Model {
	
}
class TestTaskComment extends TestTaskAppModel {
	var $name = 'TestTaskComment';
	var $useTable = 'comments';
	var $belongsTo = array(
		'Article' => array(
			'className' => 'TestTaskArticle',
			'foreignKey' => 'article_id',
		)
	);
}

class TestTaskCommentsController extends Controller {
	var $name = 'TestTaskComments';
	var $uses = array('TestTaskComment', 'TestTaskTag');
}

/**
 * TestTaskTest class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.console.libs.tasks
 */
class TestTaskTest extends CakeTestCase {

	var $fixtures = array('core.article', 'core.comment', 'core.articles_tag', 'core.tag');
/**
 * setUp method
 *
 * @return void
 * @access public
 */
	function startTest() {
		$this->Dispatcher =& new TestTestTaskMockShellDispatcher();
		$this->Dispatcher->shellPaths = Configure::read('shellPaths');
		$this->Task =& new MockTestTask($this->Dispatcher);
		$this->Task->Dispatch =& $this->Dispatcher;
		$this->Task->Template =& new TemplateTask($this->Dispatcher);
	}

/**
 * tearDown method
 *
 * @return void
 * @access public
 */
	function endTest() {
		ClassRegistry::flush();
	}

/**
 * Test that file path generation doesn't continuously append paths.
 *
 * @access public
 * @return void
 */
	function testFilePathGeneration () {
		$file = TESTS . 'cases' . DS . 'models' . DS . 'my_class.test.php';

		$this->Task->Dispatch->expectNever('stderr');
		$this->Task->Dispatch->expectNever('_stop');

		$this->Task->setReturnValue('in', 'y');
		$this->Task->expectAt(0, 'createFile', array($file, '*'));
		$this->Task->bake('Model', 'MyClass');

		$this->Task->expectAt(1, 'createFile', array($file, '*'));
		$this->Task->bake('Model', 'MyClass');

		$file = TESTS . 'cases' . DS . 'controllers' . DS . 'comments_controller.test.php';
		$this->Task->expectAt(2, 'createFile', array($file, '*'));
		$this->Task->bake('Controller', 'Comments');
	}

/**
 * Test that method introspection pulls all relevant non parent class 
 * methods into the test case.
 *
 * @return void
 **/
	function testMethodIntrospection() {
		$result = $this->Task->getTestableMethods('TestTaskArticle');
		$expected = array('doSomething', 'doSomethingElse');
		$this->assertEqual($result, $expected);
	}

/**
 * test that the generation of fixtures works correctly.
 *
 * @return void
 **/
	function testFixtureArrayGenerationFromModel() {
		$subject = ClassRegistry::init('TestTaskArticle');
		$result = $this->Task->generateFixtureList($subject);
		$expected = array('plugin.test_task.test_task_comment', 'app.articles_tags', 
			'app.test_task_article', 'app.test_task_tag');

		$this->assertEqual(sort($result), sort($expected));
	}

/**
 * test that the generation of fixtures works correctly.
 *
 * @return void
 **/
	function testFixtureArrayGenerationFromController() {
		$subject = new TestTaskCommentsController();
		$result = $this->Task->generateFixtureList($subject);
		$expected = array('plugin.test_task.test_task_comment', 'app.articles_tags', 
			'app.test_task_article', 'app.test_task_tag');

		$this->assertEqual(sort($result), sort($expected));
	}

/**
 * test user interaction to get object type
 *
 * @return void
 **/
	function testGetObjectType() {
		$this->Task->expectOnce('_stop');
		$this->Task->setReturnValueAt(0, 'in', 'q');
		$this->Task->getObjectType();

		$this->Task->setReturnValueAt(1, 'in', 2);
		$result = $this->Task->getObjectType();
		$this->assertEqual($result, $this->Task->classTypes[1]);
	}

/**
 * creating test subjects should clear the registry so the registry is always fresh
 *
 * @return void
 **/
	function testRegistryClearWhenBuildingTestObjects() {
		ClassRegistry::flush();
		$model = ClassRegistry::init('TestTaskComment');
		$model->bindModel(array(
			'belongsTo' => array(
				'Random' => array(
					'className' => 'TestTaskArticle',
					'foreignKey' => 'article_id',
				)
			)
		));
		$keys = ClassRegistry::keys();
		$this->assertTrue(in_array('random', $keys));
		$object =& $this->Task->buildTestSubject('Model', 'TestTaskComment');

		$keys = ClassRegistry::keys();
		$this->assertFalse(in_array('random', $keys));
	}

/**
 * test that getClassName returns the user choice as a classname.
 *
 * @return void
 **/
	function testGetClassName() {
		$this->Task->setReturnValueAt(0, 'in', 'MyCustomClass');
		$result = $this->Task->getClassName('Model');
		$this->assertEqual($result, 'MyCustomClass');

		$this->Task->setReturnValueAt(1, 'in', 1);
		$result = $this->Task->getClassName('Model');
		$options = Configure::listObjects('model');
		$this->assertEqual($result, $options[0]);
	}

/**
 * Test the user interaction for defining additional fixtures.
 *
 * @return void
 **/
	function testGetUserFixtures() {
		$this->Task->setReturnValueAt(0, 'in', 'y');
		$this->Task->setReturnValueAt(1, 'in', 'app.pizza, app.topping, app.side_dish');
		$result = $this->Task->getUserFixtures();
		$expected = array('app.pizza', 'app.topping', 'app.side_dish');
		$this->assertEqual($result, $expected);
	}

/**
 * test that resolving classnames works
 *
 * @return void
 **/
	function testGetRealClassname() {
		$result = $this->Task->getRealClassname('Model', 'Post');
		$this->assertEqual($result, 'Post');

		$result = $this->Task->getRealClassname('Controller', 'Posts');
		$this->assertEqual($result, 'PostsController');

		$result = $this->Task->getRealClassname('Helper', 'Form');
		$this->assertEqual($result, 'FormHelper');

		$result = $this->Task->getRealClassname('Behavior', 'Containable');
		$this->assertEqual($result, 'ContainableBehavior');

		$result = $this->Task->getRealClassname('Component', 'Auth');
		$this->assertEqual($result, 'AuthComponent');
	}

/**
 * test baking files.
 *
 * @return void
 **/
	function testBakeModelTest() {
		$this->Task->setReturnValue('createFile', true);
		$this->Task->setReturnValue('isLoadableClass', true);

		$result = $this->Task->bake('Model', 'TestTaskArticle');

		$this->assertPattern('/App::import\(\'Model\', \'TestTaskArticle\'\)/', $result);
		$this->assertPattern('/class TestTaskArticleTestCase extends CakeTestCase/', $result);

		$this->assertPattern('/function startTest\(\)/', $result);
		$this->assertPattern("/\\\$this->TestTaskArticle \=\& ClassRegistry::init\('TestTaskArticle'\)/", $result);

		$this->assertPattern('/function endTest\(\)/', $result);
		$this->assertPattern('/unset\(\$this->TestTaskArticle\)/', $result);

		$this->assertPattern('/function testDoSomething\(\)/', $result);
		$this->assertPattern('/function testDoSomethingElse\(\)/', $result);

		$this->assertPattern("/'app\.test_task_article'/", $result);
		$this->assertPattern("/'plugin\.test_task\.test_task_comment'/", $result);
		$this->assertPattern("/'app\.test_task_tag'/", $result);
		$this->assertPattern("/'app\.articles_tag'/", $result);
	}

/**
 * test baking controller test files, ensure that the stub class is generated.
 *
 * @return void
 **/
	function testBakeControllerTest() {
		$this->Task->setReturnValue('createFile', true);
		$this->Task->setReturnValue('isLoadableClass', true);

		$result = $this->Task->bake('Controller', 'TestTaskComments');

		$this->assertPattern('/App::import\(\'Controller\', \'TestTaskComments\'\)/', $result);
		$this->assertPattern('/class TestTaskCommentsControllerTestCase extends CakeTestCase/', $result);

		$this->assertPattern('/class TestTestTaskCommentsController extends TestTaskCommentsController/', $result);
		$this->assertPattern('/var \$autoRender = false/', $result);
		$this->assertPattern('/function redirect\(\$url, \$status = null, \$exit = true\)/', $result);

		$this->assertPattern('/function startTest\(\)/', $result);
		$this->assertPattern("/\\\$this->TestTaskComments \=\& new TestTestTaskCommentsController()/", $result);

		$this->assertPattern('/function endTest\(\)/', $result);
		$this->assertPattern('/unset\(\$this->TestTaskComments\)/', $result);

		$this->assertPattern("/'app\.test_task_article'/", $result);
		$this->assertPattern("/'plugin\.test_task\.test_task_comment'/", $result);
		$this->assertPattern("/'app\.test_task_tag'/", $result);
		$this->assertPattern("/'app\.articles_tag'/", $result);
	}

/**
 * test Constructor generation ensure that constructClasses is called for controllers
 *
 * @return void
 **/
	function testGenerateContsructor() {
		$result = $this->Task->generateConstructor('controller', 'PostsController');
		$expected = "new TestPostsController();\n\t\t\$this->PostsController->constructClasses();\n";
		$this->assertEqual($result, $expected);

		$result = $this->Task->generateConstructor('model', 'Post');
		$expected = "ClassRegistry::init('Post');\n";
		$this->assertEqual($result, $expected);

		$result = $this->Task->generateConstructor('helper', 'FormHelper');
		$expected = "new FormHelper()\n";
		$this->assertEqual($result, $expected);
	}

/**
 * Test that mock class generation works for the appropriate classes
 *
 * @return void
 **/
	function testMockClassGeneration() {
		$result = $this->Task->hasMockClass('controller');
		$this->assertTrue($result);
	}

/**
 * test bake() with a -plugin param
 *
 * @return void
 **/
	function testBakeWithPlugin() {
		$this->Task->plugin = 'TestTest';

		$path = APP . 'plugins' . DS . 'test_test' . DS . 'tests' . DS . 'cases' . DS . 'helpers' . DS . 'form.test.php';
		$this->Task->expectAt(0, 'createFile', array($path, '*'));
		$this->Task->bake('Helper', 'Form');
	}

/**
 * Test filename generation for each type + plugins
 *
 * @return void
 **/
	function testTestCaseFileName() {
		$this->Task->path = '/my/path/tests/';

		$result = $this->Task->testCaseFileName('Model', 'Post');
		$expected = $this->Task->path . 'cases' . DS . 'models' . DS . 'post.test.php';
		$this->assertEqual($result, $expected);

		$result = $this->Task->testCaseFileName('Helper', 'Form');
		$expected = $this->Task->path . 'cases' . DS . 'helpers' . DS . 'form.test.php';
		$this->assertEqual($result, $expected);

		$result = $this->Task->testCaseFileName('Controller', 'Posts');
		$expected = $this->Task->path . 'cases' . DS . 'controllers' . DS . 'posts_controller.test.php';
		$this->assertEqual($result, $expected);

		$result = $this->Task->testCaseFileName('Behavior', 'Containable');
		$expected = $this->Task->path . 'cases' . DS . 'behaviors' . DS . 'containable.test.php';
		$this->assertEqual($result, $expected);

		$result = $this->Task->testCaseFileName('Component', 'Auth');
		$expected = $this->Task->path . 'cases' . DS . 'components' . DS . 'auth.test.php';
		$this->assertEqual($result, $expected);

		$this->Task->plugin = 'TestTest';
		$result = $this->Task->testCaseFileName('Model', 'Post');
		$expected = APP . 'plugins' . DS . 'test_test' . DS . 'tests' . DS . 'cases' . DS . 'models' . DS . 'post.test.php';
		$this->assertEqual($result, $expected);
	}

/**
 * test execute with a type defined
 *
 * @return void
 **/
	function testExecuteWithOneArg() {
		$this->Task->args[0] = 'Model';
		$this->Task->setReturnValueAt(0, 'in', 'TestTaskTag');
		$this->Task->setReturnValue('isLoadableClass', true);
		$this->Task->expectAt(0, 'createFile', array('*', new PatternExpectation('/class TestTaskTagTestCase extends CakeTestCase/')));
		$this->Task->execute();
	}

/**
 * test execute with type and class name defined
 *
 * @return void
 **/
	function testExecuteWithTwoArgs() {
		$this->Task->args = array('Model', 'TestTaskTag');
		$this->Task->setReturnValueAt(0, 'in', 'TestTaskTag');
		$this->Task->setReturnValue('isLoadableClass', true);
		$this->Task->expectAt(0, 'createFile', array('*', new PatternExpectation('/class TestTaskTagTestCase extends CakeTestCase/')));
		$this->Task->execute();
	}
}
?>