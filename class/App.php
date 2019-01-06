<?php

namespace Resume;

class App
{
	/** @var Filesystem $filesystem */
	private $filesystem;
	
	public function __construct(Filesystem $filesystem)
	{
		$this->filesystem = $filesystem;
	}
	
	public function compile()
	{
		$this->filesystem->deleteTree(BASEDIR . "/tmp");
	}
}