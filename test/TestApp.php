<?php

use Resume\App;

final class TestApp extends \Resume\TestCase
{
	/** @var App $app */
	private $app;
	
	protected function setUp()
	{
		parent::setUp(); // TODO: Change the autogenerated stub
		
		$this->app = $this->factory->get("App");
	}
	
	public function testExists()
	{
		$this->assertTrue(class_exists("\\Resume\\App"));
	}
	
	public function testDeletesBuildTree()
	{
		$this->app->compile();
		
		$this->stubFilesystem->assertMethodCalledWith(
			"deleteTree",
			"build"
		);
	}
	
	public function testCompilesLess()
	{
		$this->app->compile();
		
		$this->stubLess->assertMethodCalledWith(
			"compileFile",
			BASEDIR . "/less/style.less"
		);
	}
	
	public function testWritesCssFile()
	{
		$this->stubLess->setReturnValue("compileFile", "compiled_css");
		
		$this->app->compile();
		
		$this->stubFilesystem->assertMethodCalledWith(
			"fileForceContents",
			BASEDIR . "/build/style.css",
			"compiled_css"
		);
	}
	
	public function testGetsYaml()
	{
		$this->app->compile();
		
		$this->stubFilesystem->assertMethodCalledWith(
			"getFile",
			BASEDIR . "/resume.yaml"
		);
	}
	
	public function testParsesYaml()
	{
		$this->stubFilesystem->setReturnValue("getFile", "yaml");
		
		$this->app->compile();
		
		$this->stubYaml->assertMethodCalledWith("parse", "yaml");
	}
	
	public function testCompilesPages()
	{
		$this->stubFilesystem->setReturnValue("findPathsMatchingRecursive", [
			BASEDIR . "/page/page.twig"
		]);
		
		$this->stubYaml->setReturnValue("parse", ["DATA"]);
		
		$this->app->compile();
		
		$this->stubTwig->assertMethodCalledWith(
			"renderTemplate",
			"page.twig",
			["DATA"]
		);
	}
	
	public function testCompilesIndex()
	{
		$this->stubFilesystem->setReturnValue("scanDir", [
			"build/page.html"
		]);
		
		$this->app->compile();
		
		$this->stubTwig->assertMethodCalledWith(
			"renderTemplate",
			"layout-index.twig",
			["pages" => ["build/page.html"]]
		);
	}
	
	public function testCompilesSubPages()
	{
		$this->stubFilesystem->setReturnValue("findPathsMatchingRecursive", [
			BASEDIR . "/page/sub/page.twig"
		]);
		
		$this->app->compile();
		
		$this->stubTwig->assertMethodCalledWith(
			"renderTemplate",
			"sub/page.twig",
			null
		);
	}
	
	public function testSavesCompiledPages()
	{
		$this->stubFilesystem->setReturnValue("findPathsMatchingRecursive", [
			BASEDIR . "/page/page.twig"
		]);
		
		$this->stubTwig->setReturnValue("renderTemplate", "rendered_template");
		
		$this->app->compile();
		
		$this->stubFilesystem->assertMethodCalledWith(
			"fileForceContents",
			BASEDIR . "/build/./page.html",
			"rendered_template"
		);
	}
	
	public function testSavesIndex()
	{
		$this->stubTwig->setReturnValue("renderTemplate", "rendered_template");
		
		$this->app->compile();
		
		$this->stubFilesystem->assertMethodCalledWith(
			"fileForceContents",
			BASEDIR . "/index.html",
			"rendered_template"
		);
	}
}
