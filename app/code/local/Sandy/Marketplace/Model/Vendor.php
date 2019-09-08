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
 * Vendor model
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Model_Vendor extends Mage_Catalog_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'sandy_marketplace_vendor';
    const CACHE_TAG = 'sandy_marketplace_vendor';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'sandy_marketplace_vendor';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'vendor';
    protected $_productInstance = null;

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('sandy_marketplace/vendor');
    }

    /**
     * before save vendor
     *
     * @access protected
     * @return Sandy_Marketplace_Model_Vendor
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the url to the vendor details page
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getVendorUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('sandy_marketplace/vendor/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('sandy_marketplace/vendor/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('sandy_marketplace/vendor/view', array('id'=>$this->getId()));
    }

    /**
     * check URL key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author Ultimate Module Creator
     */
    public function checkUrlKey($urlKey, $active = true)
    {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * get the vendor Description
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getDescription()
    {
        $description = $this->getData('description');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($description);
        return $html;
    }

    /**
     * save vendor relation
     *
     * @access public
     * @return Sandy_Marketplace_Model_Vendor
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->saveVendorRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return Sandy_Marketplace_Model_Vendor_Product
     * @author Ultimate Module Creator
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('sandy_marketplace/vendor_product');
        }
        return $this->_productInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedProducts()
    {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return Sandy_Marketplace_Resource_Vendor_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedProductsCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    /**
     * Retrieve default attribute set id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getDefaultAttributeSetId()
    {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * get attribute text value
     *
     * @access public
     * @param $attributeCode
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAttributeText($attributeCode)
    {
        $text = $this->getResource()
            ->getAttribute($attributeCode)
            ->getSource()
            ->getOptionText($this->getData($attributeCode));
        if (is_array($text)) {
            return implode(', ', $text);
        }
        return $text;
    }

    /**
     * check if comments are allowed
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAllowComments()
    {
        if ($this->getData('allow_comment') == Sandy_Marketplace_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == Sandy_Marketplace_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('sandy_marketplace/vendor/allow_comment');
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        $values['in_rss'] = 1;
        $values['allow_comment'] = Sandy_Marketplace_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        return $values;
    }
    
}
