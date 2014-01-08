<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package	   MetaModels
 * @subpackage PerimeterSearch
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

/**
 * This class is used from DCA tl_metamodel_filtersetting for various callbacks.
 *
 * @package	   MetaModels
 * @subpackage PerimeterSearch
 * @author	   Stefan Heimes <stefan_heimes@hotmail.com>
 */
class TableMetaModelFilterSettingPerimetersearch extends TableMetaModelFilterSetting
{
	/**
	 * @var MetaPalettes
	 */
	protected static $objInstance = null;

	/**
	 * Get the static instance.
	 *
	 * @static
	 * @return MetaPalettes
	 */
	public static function getInstance()
	{
		if (self::$objInstance == null)
		{
			self::$objInstance = new self();
		}
		return self::$objInstance;
	}

	/**
	 * Protected constructor for singleton instance.
	 */
	protected function __construct()
	{
		parent::__construct();
	}

	public function getAttributeNames($objDC)
	{
		$this->objectsFromUrl($objDC);

		$arrResult		 = array();
		$arrData		 = $objDC->getCurrentModel()->getPropertiesAsArray();
		$stringDataMode	 = $arrData['datamode'];

		if (!$this->objMetaModel)
		{
			return;
		}

		$objMetaModel = $this->objMetaModel;

		$arrTypeFilter = $GLOBALS['METAMODELS']['filters'][$objDC->getCurrentModel()->getProperty('type')]['attr_filter'];

		if(!isset($arrTypeFilter[$stringDataMode]))
		{
			return;
		}

		$arrTypeFilter = $arrTypeFilter[$stringDataMode];

		foreach ($objMetaModel->getAttributes() as $objAttribute)
		{
			$strTypeName = $objAttribute->get('type');
			if ($arrTypeFilter && (!in_array($strTypeName, $arrTypeFilter)))
			{
				continue;
			}
			$strSelectVal             = $objMetaModel->getTableName() . '_' . $objAttribute->getColName();
			$arrResult[$strSelectVal] = $objAttribute->getName() . ' [' . $strTypeName . ']';
		}

		return $arrResult;
	}

	/**
	 * Get a list with all supported resolver class for a geo lookup.
	 */
	public function getResolverClass()
	{
		$this->loadLanguageFile('tl_metamodel_filtersetting');

		$arrClasses = (array) $GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class'];
		
		$arrReturn = array();
		foreach ($arrClasses as $value)
		{
			$arrReturn[$value] = (isset($GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch'][$value])) ? $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch'][$value] : $value;
		}
		
		return $arrReturn;
	}

}

