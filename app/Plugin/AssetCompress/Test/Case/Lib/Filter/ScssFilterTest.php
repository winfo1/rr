<?php
App::uses('ScssFilter', 'AssetCompress.Filter');

class ScssFilterTest extends CakeTestCase {

	public function setUp() {
		parent::setUp();
		$this->_pluginPath = App::pluginPath('AssetCompress');
		$this->_cssDir = $this->_pluginPath . 'Test' . DS . 'test_files' . DS . 'css' . DS;
		$this->filter = new ScssFilter();
	}

	public function testParsing() {
		$this->skipIf(DS === '\\', 'Requires ruby and sass rubygem to be installed');
		$hasSass = `which sass`;
		$this->skipIf(empty($hasSass), 'Requires ruby and sass to be installed');
		$this->filter->settings(array('sass' => trim($hasSass)));

		$content = file_get_contents($this->_cssDir . 'test.scss');
		$result = $this->filter->input($this->_cssDir . 'test.scss', $content);
		$expected = file_get_contents($this->_cssDir . 'compiled_scss.css');
		$this->assertEquals($expected, $result);
	}

}
