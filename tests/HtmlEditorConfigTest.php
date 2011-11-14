<?php
/**
 * @package sapphire
 * @subpackage tests
 */
class HtmlEditorConfigTest extends SapphireTest {

	function testEnablePluginsByString() {
		$c = new HtmlEditorConfig();
		$c->enablePlugins('plugin1');
		$this->assertContains('plugin1', array_keys($c->getPlugins()));
	}
	
	function testEnablePluginsByArray() {
		$c = new HtmlEditorConfig();
		$c->enablePlugins(array('plugin1', 'plugin2'));
		$this->assertContains('plugin1', array_keys($c->getPlugins()));
		$this->assertContains('plugin2', array_keys($c->getPlugins()));
	}
	
	function testEnablePluginsByMultipleStringParameters() {
		$c = new HtmlEditorConfig();
		$c->enablePlugins('plugin1', 'plugin2');
		$this->assertContains('plugin1', array_keys($c->getPlugins()));
		$this->assertContains('plugin2', array_keys($c->getPlugins()));
	}
	
	function testEnablePluginsByArrayWithPaths() {
		$c = new HtmlEditorConfig();
		$c->enablePlugins(array('plugin1' => '/mypath/plugin1', 'plugin2' => '/mypath/plugin2'));
		$plugins = $c->getPlugins();
		$this->assertContains('plugin1', array_keys($plugins));
		$this->assertEquals('/mypath/plugin1', $plugins['plugin1']);
		$this->assertContains('plugin2', array_keys($plugins));
		$this->assertEquals('/mypath/plugin2', $plugins['plugin2']);
	}
	
	function testDisablePluginsByString() {
		$c = new HtmlEditorConfig();
		$c->enablePlugins('plugin1');
		$c->disablePlugins('plugin1');
		$this->assertNotContains('plugin1', array_keys($c->getPlugins()));
	}
	
	function testDisablePluginsByArray() {
		$c = new HtmlEditorConfig();
		$c->enablePlugins(array('plugin1', 'plugin2'));
		$c->disablePlugins(array('plugin1', 'plugin2'));
		$this->assertNotContains('plugin1', array_keys($c->getPlugins()));
		$this->assertNotContains('plugin2', array_keys($c->getPlugins()));
	}
	
	function testDisablePluginsByMultipleStringParameters() {
		$c = new HtmlEditorConfig();
		$c->enablePlugins('plugin1', 'plugin2');
		$c->disablePlugins('plugin1', 'plugin2');
		$this->assertNotContains('plugin1', array_keys($c->getPlugins()));
		$this->assertNotContains('plugin2', array_keys($c->getPlugins()));
	}
	
	function testDisablePluginsByArrayWithPaths() {
		$c = new HtmlEditorConfig();
		$c->enablePlugins(array('plugin1' => '/mypath/plugin1', 'plugin2' => '/mypath/plugin2'));
		$c->disablePlugins(array('plugin1', 'plugin2'));
		$plugins = $c->getPlugins();
		$this->assertNotContains('plugin1', array_keys($plugins));
		$this->assertNotContains('plugin2', array_keys($plugins));
	}
	
	function testGenerateJSWritesPlugins() {
		$c = new HtmlEditorConfig();
		$c->enablePlugins(array('plugin1'));
		$c->enablePlugins(array('plugin2' => '/mypath/plugin2'));

		$this->assertContains('plugin1', $c->generateJS());
		$this->assertContains('tinymce.PluginManager.load("plugin2", "/mypath/plugin2");', $c->generateJS());
	}

	function testLinkOptions() {
		$c = new HtmlEditorConfig();
		$c->addLinkOption(new HtmlEditorField_LinkOption(
			'Test1',
			'Test1',
			new FieldGroup(),
			10
		));
		$c->addLinkOption(new HtmlEditorField_LinkOption(
			'Test2',
			'Test2',
			new FieldGroup(),
			20
		));

		$options = $c->getLinkOptions();
		$this->assertEquals(count($options), 2);
		$this->assertEquals($options['Test1']->name, 'Test1');

		$c->removeLinkOption('Test1');
		$options = $c->getLinkOptions();
		$this->assertEquals(count($options), 1);
		$this->assertEquals($options['Test2']->name, 'Test2');

		$c->resetLinkOptions();
		$options = $c->getLinkOptions();
		$this->assertEquals(count($options), 0);
	}

	function testLinkScripts() {
		$c = new HtmlEditorConfig();
		$c->addLinkScript('script.js');
		
		$scripts = $c->getLinkScripts();
		$this->assertEquals(count($scripts), 1);
		$this->assertEquals($scripts[0], 'script.js');

		$c->resetLinkScripts();
		$scripts = $c->getLinkScripts();
		$this->assertEquals(count($scripts), 0);
	}
}
