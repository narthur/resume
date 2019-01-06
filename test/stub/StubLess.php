<?php

namespace Resume;

class StubLess extends Less
{
	use Stub;
	
	public function compileFile($path)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
}