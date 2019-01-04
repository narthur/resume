<?php

final class TestApp extends \PHPUnit\Framework\TestCase
{
	public function testExists()
	{
		$this->assertTrue(class_exists("\\Resume\\App"));
	}
}
