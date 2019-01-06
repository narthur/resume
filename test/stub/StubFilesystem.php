<?php

namespace Resume;

class StubFilesystem extends Filesystem
{
	use Stub;
	
	public function getFile($path)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
	
	public function deleteTree($projectRelativePath)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
	
	public function fileForceContents($path, $contents)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
	
	public function makeTree($path)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
	
	public function scanDir($path)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
	
	public function isDir($path)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
	
	public function findPathsMatchingRecursive($path, $regex)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
}