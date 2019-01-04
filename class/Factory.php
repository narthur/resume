<?php

namespace Resume;

class Factory
{
	private $namespace = __NAMESPACE__;
	
	public function injectObject($object) {
	
	}
	
	/**
	 * @param $method
	 * @param array $args
	 * @return null
	 * @throws \ReflectionException
	 */
	public function __call($method, $args = [])
	{
		$isGet = substr($method, 0, 3) === "get";
		if (!$isGet) return null;
		$name = substr($method, 3, strlen($method) - 3);
		$qualifiedName = $this->getQualifiedName($name);
		$dependencyNames = $this->getDependencyNames($qualifiedName);
		$dependencies = array_map(function ($dependencyName) {
			$methodName = "get$dependencyName";
			return $this->$methodName();
		}, $dependencyNames);
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
		if ($isQualified) return $name;
		$isNamespacedMethod = strpos($name, "_") !== false;
		if ($isNamespacedMethod) return $this->convertNamespacedMethodNameToQualifiedName($name);
		return "\\$this->namespace\\$name";
	}
	
	/**
	 * @param string $class
	 * @param array ...$dependencies
	 * @return mixed
	 */
	private function getObject($class, ...$dependencies)
	{
		$propertyName = $this->getPropertyName($class);
		if (!isset($this->$propertyName)) $this->$propertyName = new $class(...$dependencies);
		return $this->$propertyName;
	}
	
	/**
	 * @param $class
	 * @return string
	 */
	private function getPropertyName($class)
	{
		return lcfirst($this->getSimpleClassName($class));
	}
	
	/**
	 * @param $name
	 * @return string
	 */
	private function getSimpleClassName($name)
	{
		$nameFragments = explode("\\", $name);
		return end($nameFragments);
	}
	
	/**
	 * @param $methodName
	 * @return string
	 */
	private function convertNamespacedMethodNameToQualifiedName($methodName)
	{
		$fragments = explode("_", $methodName);
		$partiallyQualifiedName = implode("\\", $fragments);
		return "\\$this->namespace\\$partiallyQualifiedName";
	}
}