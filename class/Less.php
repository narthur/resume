<?php

namespace Resume;

class Less
{
	private $lessc;
	
	public function __construct()
	{
		$this->lessc = new \lessc();
	}
	
	/**
	 * @param $path
	 * @return bool|false|int|string
	 * @throws \Exception
	 */
	public function compileFile($path)
	{
		return $this->lessc->compileFile($path);
	}
}