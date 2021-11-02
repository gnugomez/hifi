<?php


/* Common */

if (!function_exists("get_array_value")) {
	function get_array_value($array = array(), $value = "", $default = "")
	{
		$res = $default;
		if (is_array($array)) {
			if (isset($array[$value])) {
				$res = $array[$value];
			}
		}
		return $res;
	}
}
if (!function_exists("get_object_value")) {
	function get_object_value($object = "", $value = "", $default = "")
	{
		$res = $default;
		if (is_object($object)) {
			if (property_exists($object, $value)) {
				$res = $object->$value;
			}
		}
		return $res;
	}
}
if (!function_exists("substr_last_char")) {
	function substr_last_char($string)
	{
		return substr($string, strlen($string) - 1, 1);
	}
}
if (!function_exists("substr_first_char")) {
	function substr_first_char($string)
	{
		return substr($string, 0, 1);
	}
}
if (!function_exists("substr_chop_end")) {
	function substr_chop_end($string)
	{
		return substr($string, 0, strlen($string) - 1);
	}
}
if (!function_exists("substr_chop")) {
	function substr_chop($string)
	{
		return substr_chop_end($string);
	}
}
if (!function_exists("substr_chop_start")) {
	function substr_chop_start($string)
	{
		return substr($string, 1, strlen($string) - 1);
	}
}
function importFromDir(string $dir, string $extension = ".php")
{
	$views = glob("${dir}/*${extension}");
	foreach ($views as $view) {
		require_once $view;
	}
}
function cast($destination, $sourceObject)
{
	if (is_string($destination)) {
		$destination = new $destination();
	}
	$sourceReflection = new ReflectionObject($sourceObject);
	$destinationReflection = new ReflectionObject($destination);
	$sourceProperties = $sourceReflection->getProperties();
	foreach ($sourceProperties as $sourceProperty) {
		$sourceProperty->setAccessible(true);
		$name = $sourceProperty->getName();
		$value = $sourceProperty->getValue($sourceObject);
		if ($destinationReflection->hasProperty($name)) {
			$propDest = $destinationReflection->getProperty($name);
			$propDest->setAccessible(true);
			$propDest->setValue($destination, $value);
		} else {
			$destination->$name = $value;
		}
	}
	return $destination;
}
