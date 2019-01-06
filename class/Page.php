<?php

namespace Resume;

abstract class Page
{
	/** @var Filesystem $filesystem */
	protected $filesystem;
	
	/** @var Twig $twig */
	private $twig;
	
	protected $template;
	protected $outPath;
	
	public function __construct(Filesystem $filesystem, Twig $twig)
	{
		$this->filesystem = $filesystem;
		$this->twig = $twig;
	}
	
	public function compile() {
		$html = $this->twig->renderTemplate(
			$this->template,
			$this->getData()
		);

		$this->filesystem->fileForceContents($this->outPath, $html);
	}
	
	abstract protected function getData();
}