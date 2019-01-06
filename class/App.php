<?php

namespace Resume;

class App
{
	/** @var Filesystem $filesystem */
	private $filesystem;
	
	/** @var Page\Index $index */
	private $index;
	
	/** @var Less $less */
	private $less;
	
	/** @var Twig $twig */
	private $twig;
	
	/** @var Yaml $yaml */
	private $yaml;
	
	public function __construct(
		Filesystem $filesystem,
		Page\Index $index,
		Less $less,
		Twig $twig,
		Yaml $yaml
	)
	{
		$this->filesystem = $filesystem;
		$this->index = $index;
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
		$twigPages = $this->filesystem->findPathsMatchingRecursive(
			BASEDIR . "/page",
			"/\.twig$/"
		);
		
		array_map(function ($twigPage) use ($data) {
			$filename = pathinfo($twigPage, PATHINFO_FILENAME);
			$relativePath = str_replace(BASEDIR . "/page/", "", $twigPage);
			$relativeDir = pathinfo($relativePath, PATHINFO_DIRNAME);
			$outPath = BASEDIR . "/build/$relativeDir/$filename.html";
			
			$html = $this->twig->renderTemplate($relativePath, $data);
			$this->filesystem->fileForceContents($outPath, $html);
		}, $twigPages ?? []);
	}
	
	private function compileIndex(): void
	{
		$this->index->compile();
	}
}