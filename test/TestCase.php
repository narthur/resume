<?php

namespace Resume;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
	protected $baseDir;
	
	/** @var Factory $factory */
	protected $factory;
	
	/** @var Filesystem|StubFilesystem $stubFilesystem */
	protected $stubFilesystem;
	
	protected function setUp()
	{
		parent::setUp();
		
		$this->baseDir = dir(__DIR__);
		
		$this->stubFilesystem = new StubFilesystem($this);
		
		$this->factory = new Factory();
		
		$this->factory->injectObject($this->stubFilesystem);
	}
}