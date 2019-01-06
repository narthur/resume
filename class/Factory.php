<?php

namespace Resume;

class Factory
{
	private $namespace = __NAMESPACE__;
	
	private $objects = [];
	
	public function injectObject($object) {
		$this->objects[] = $object;
	}
	
	/**
	 * @param $class
	 * @return null
	 * @throws \ReflectionException
	 */
	public function get($class)
	{
		$qualifiedName = $this->getQualifiedName($class);
		$dependencyNames = $this->getDependencyNames($qualifiedName);
		$dependencies = array_map([$this, "get"], $dependencyNames);
		
		return $this->getObject($qualifiedName, ...$dependencies);
	}
	
	/**
	 * @param $className
	 * @return array|mixed
	 * @throws \ReflectionException
	 */
	private function getDependencyNames($className)
	{
		$reflection = new \ReflectionClass($className);
		$constructor = $reflection->getConstructor();
		$params = ($constructor) ? $constructor->getParameters() : [];
		
		return array_map(function (\ReflectionParameter $param) {
			$name = $param->getClass()->name;
			return $this->getQualifiedName($name);
		}, $params);
	}
	
	/**
	 * @param $name
	 * @return string
	 */
	private function getQualifiedName($name)
	{
		$isQualified = strpos(trim($name, "\\"), "$this->namespace\\") === 0;
		
		return $isQualified ? $name : "\\$this->namespace\\$name";
	}
	
	/**
	 * @param string $class
	 * @param array ...$dependencies
	 * @return mixed
	 */
	private function getObject($class, ...$dependencies)
	{
		$matchingObjects = array_filter($this->objects, function($object) use($class) {
			return is_a($object, $class);
		});
		
		if ($matchingObjects) {
			return end($matchingObjects);
		}
		
		$object = new $class(...$dependencies);
		
		$this->objects[] = $object;
		
		return $object;
	}
}
