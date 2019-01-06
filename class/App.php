<?php

namespace Resume;

class App
{
	/** @var Filesystem $filesystem */
	private $filesystem;
	
	/** @var Less $less */
	private $less;
	
	public function __construct(Filesystem $filesystem, Less $less)
	{
		$this->filesystem = $filesystem;
		$this->less = $less;
	}
	
	public function compile()
	{
		$this->filesystem->deleteTree("build");
		$this->compileCss();
		$this->filesystem->getFile(BASEDIR . "/resume.yaml");
	}
	
	private function compileCss(): void
	{
		$css = $this->less->compileFile(BASEDIR . "/less/style.less");
		$this->filesystem->fileForceContents(BASEDIR . "/build/style.css", $css);
	}
}