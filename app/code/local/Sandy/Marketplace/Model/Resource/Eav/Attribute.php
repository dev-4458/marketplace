<?php
/**
 * Sandy_Marketplace extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Sandy
 * @package        Sandy_Marketplace
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Attribute resource model
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Model_Resource_Eav_Attribute extends Mage_Eav_Model_Entity_Attribute
{
    const MODULE_NAME   = 'Sandy_Marketplace';
    const ENTITY        = 'sandy_marketplace_eav_attribute';

    protected $_eventPrefix = 'sandy_marketplace_entity_attribute';
    protected $_eventObject = 'attribute';

    /**
     * Array with labels
     *
     * @var array
     */
    static protected $_labels = null;

    /**
     * constructor
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->_init('sandy_marketplace/attribute');
    }

    /**
     * check if scope is store view
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function isScopeStore()
    {
        return $this->getIsGlobal() == Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE;
    }

    /**
     * check if scope is website
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function isScopeWebsite()
    {
        return $this->getIsGlobal() == Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE;
    }

    /**
     * check if scope is global
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function isScopeGlobal()
    {
        return (!$this->isScopeStore() && !$this->isScopeWebsite());
    }

    /**
     * get backend input type
     *
     * @access public
     * @param string $type
     * @return string
     * @author Ultimate Module Creator
     */
    public function getBackendTypeByInput($type)
    {
        switch ($type) {
            case 'file':
                //intentional fallthrough
            case 'image':
                return 'varchar';
                break;
            default:
                return parent::getBackendTypeByInput($type);
            break;
        }
    }

    /**
     * don't delete system attributes
     *
     * @access public
     * @param string $type
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _beforeDelete()
    {
        if (!$this->getIsUserDefined()) {
            throw new Mage_Core_Exception(
                Mage::helper('sandy_marketplace')->__('This attribute is not deletable')
            );
        }
        return parent::_beforeDelete();
    }
}
