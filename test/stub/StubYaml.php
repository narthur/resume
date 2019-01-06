<?php

namespace Resume;

class StubYaml extends Yaml
{
	use Stub;
	
	public function parse($yaml)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
}