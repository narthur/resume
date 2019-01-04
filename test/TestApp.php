<?php

final class TestApp extends \Resume\TestCase
{
	public function testExists()
	{
		$this->assertTrue(class_exists("\\Resume\\App"));
	}
}
