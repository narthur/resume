<?php

namespace Resume\Page;

class Index extends \Resume\Page
{
	protected $template = "layout-index.twig";
	protected $outPath = BASEDIR . "/index.html";
	
	protected function getData()
	{
		$buildPages = $this->filesystem->scanDir(BASEDIR . "/build");
		
		return ["pages" => $buildPages];
	}
}
