<?php

namespace Resume;

class App
{
	/** @var Filesystem $filesystem */
	private $filesystem;
	
	/** @var Less $less */
	private $less;
	
	/** @var Twig $twig */
	private $twig;
	
	/** @var Yaml $yaml */
	private $yaml;
	
	public function __construct(Filesystem $filesystem, Less $less, Twig $twig, Yaml $yaml)
	{
		$this->filesystem = $filesystem;
		$this->less = $less;
		$this->twig = $twig;
		$this->yaml = $yaml;
	}
	
	public function compile()
	{
		$this->filesystem->deleteTree("build");
		$this->compileCss();
		$yaml = $this->filesystem->getFile(BASEDIR . "/resume.yaml");
		$data = $this->yaml->parse($yaml);
		$this->compilePages($data);
		$this->compileIndex();
	}
	
	private function compileCss(): void
	{
		$css = $this->less->compileFile(BASEDIR . "/less/style.less");
		$this->filesystem->fileForceContents(BASEDIR . "/build/style.css", $css);
	}
	
	/**
	 * @param $data
	 */
	private function compilePages($data): void
	{
		$twigPages = $this->filesystem->scanDir(BASEDIR . "/page");
		
		array_map(function ($twigPage) use ($data) {
			$filename = pathinfo($twigPage, PATHINFO_FILENAME);
			$outPath = BASEDIR . "/build/$filename.html";
			
			$this->twig->renderTemplate($twigPage, $data, $outPath);
		}, $twigPages ?? []);
	}
	
	private function compileIndex(): void
	{
		$buildPages = $this->filesystem->scanDir(BASEDIR . "/build");
		
		$this->twig->renderTemplate(
			"layout-index.twig",
			["pages" => $buildPages],
			BASEDIR . "/index.html"
		);
	}
}