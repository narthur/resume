<?php

namespace Resume;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
	protected $baseDir;
	
	/** @var Factory $factory */
	protected $factory;
	
	/** @var Filesystem|StubFilesystem $stubFilesystem */
	protected $stubFilesystem;
	
	/** @var Less|StubLess $stubLess */
	protected $stubLess;
	
	/** @var Twig|StubTwig $stubTwig */
	protected $stubTwig;
	
	/** @var Yaml|StubYaml $stubYaml */
	protected $stubYaml;
	
	protected function setUp()
	{
		parent::setUp();
		
		$this->baseDir = dir(__DIR__);
		
		$this->factory = new Factory();
		
		$this->factory->injectObjects(
			$this->stubFilesystem = new StubFilesystem($this),
			$this->stubLess = new StubLess($this),
			$this->stubTwig = new StubTwig($this),
			$this->stubYaml = new StubYaml($this)
		);
	}
}