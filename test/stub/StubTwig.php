<?php

namespace Resume;

class StubTwig extends Twig
{
	use Stub;
	
	public function renderTemplate($template, $data, $outPath)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
}