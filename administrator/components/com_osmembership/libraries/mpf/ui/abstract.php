<?php
/**
 * @package     MPF
 * @subpackage  UI
 *
 * @copyright   Copyright (C) 2016 - 2018 Ossolution Team, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

abstract class MPFUiAbstract implements MPFUiInterface
{
	/**
	 * Css class map array
	 *
	 * @var array
	 */
	protected $classMaps;

	/**
	 * Framework own css classes
	 *
	 * @var array
	 */
	protected $frameworkClasses = [];

	/**
	 * Method to add a new class to class mapping
	 *
	 * @param $class
	 * @param $mappedClass
	 *
	 * @return void
	 */
	public function addClassMapping($class, $mappedClass)
	{
		$class       = trim($class);
		$mappedClass = trim($mappedClass);

		$this->classMaps[$class] = $mappedClass;
	}

	/**
	 * Get the mapping of a given class
	 *
	 * @param string $class The input class
	 *
	 * @return string The mapped class
	 */
	public function getClassMapping($class)
	{
		$class = trim($class);

		return isset($this->classMaps[$class]) ? $this->classMaps[$class] : $class;
	}

	/**
	 * Get framework own css class
	 *
	 * @param string $class
	 * @param int    $behavior
	 *
	 * @return string
	 */
	public function getFrameworkClass($class, $behavior = 0)
	{
		if (!in_array($class, $this->frameworkClasses))
		{
			return null;
		}

		switch ($behavior)
		{
			case 1:
				return ' ' . $class;
				break;
			case 2;
				return $class . ' ';
				break;
			case 3:
				return ' class="' . $class . '"';
				break;
			default:
				return $class;
				break;
		}
	}
}