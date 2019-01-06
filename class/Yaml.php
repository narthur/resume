<?php

namespace Resume;

class Yaml
{
	public function parse($yaml)
	{
		return \Symfony\Component\Yaml\Yaml::parse($yaml);
	}
}