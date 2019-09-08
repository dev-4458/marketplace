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
 * Vendor helper
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Helper_Vendor extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the vendors list page
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getVendorsUrl()
    {
        if ($listKey = Mage::getStoreConfig('sandy_marketplace/vendor/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('sandy_marketplace/vendor/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('sandy_marketplace/vendor/breadcrumbs');
    }

    /**
     * check if the rss for vendor is enabled
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function isRssEnabled()
    {
        return  Mage::getStoreConfigFlag('rss/config/active') &&
            Mage::getStoreConfigFlag('sandy_marketplace/vendor/rss');
    }

    /**
     * get the link to the vendor rss list
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRssUrl()
    {
        return Mage::getUrl('sandy_marketplace/vendor/rss');
    }

    /**
     * get base files dir
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getFileBaseDir()
    {
        return Mage::getBaseDir('media').DS.'vendor'.DS.'file';
    }

    /**
     * get base file url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getFileBaseUrl()
    {
        return Mage::getBaseUrl('media').'vendor'.'/'.'file';
    }

    /**
     * get vendor attribute source model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author Ultimate Module Creator
     */
     public function getAttributeSourceModelByInputType($inputType)
     {
         $inputTypes = $this->getAttributeInputTypes();
         if (!empty($inputTypes[$inputType]['source_model'])) {
             return $inputTypes[$inputType]['source_model'];
         }
         return null;
     }

    /**
     * get attribute input types
     *
     * @access public
     * @param string $inputType
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getAttributeInputTypes($inputType = null)
    {
        $inputTypes = array(
            'multiselect' => array(
                'backend_model' => 'eav/entity_attribute_backend_array'
            ),
            'boolean'     => array(
                'source_model'  => 'eav/entity_attribute_source_boolean'
            ),
            'file'          => array(
                'backend_model' => 'sandy_marketplace/vendor_attribute_backend_file'
            ),
            'image'          => array(
                'backend_model' => 'sandy_marketplace/vendor_attribute_backend_image'
            ),
        );

        if (is_null($inputType)) {
            return $inputTypes;
        } else if (isset($inputTypes[$inputType])) {
            return $inputTypes[$inputType];
        }
        return array();
    }

    /**
     * get vendor attribute backend model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author Ultimate Module Creator
     */
    public function getAttributeBackendModelByInputType($inputType)
    {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['backend_model'])) {
            return $inputTypes[$inputType]['backend_model'];
        }
        return null;
    }

    /**
     * filter attribute content
     *
     * @access public
     * @param Sandy_Marketplace_Model_Vendor $vendor
     * @param string $attributeHtml
     * @param string @attributeName
     * @return string
     * @author Ultimate Module Creator
     */
    public function vendorAttribute($vendor, $attributeHtml, $attributeName)
    {
        $attribute = Mage::getSingleton('eav/config')->getAttribute(
            Sandy_Marketplace_Model_Vendor::ENTITY,
            $attributeName
        );
        if ($attribute && $attribute->getId() && !$attribute->getIsWysiwygEnabled()) {
            if ($attribute->getFrontendInput() == 'textarea') {
                $attributeHtml = nl2br($attributeHtml);
            }
        }
        if ($attribute->getIsWysiwygEnabled()) {
            $attributeHtml = $this->_getTemplateProcessor()->filter($attributeHtml);
        }
        return $attributeHtml;
    }

    /**
     * get the template processor
     *
     * @access protected
     * @return Mage_Catalog_Model_Template_Filter
     * @author Ultimate Module Creator
     */
    protected function _getTemplateProcessor()
    {
        if (null === $this->_templateProcessor) {
            $this->_templateProcessor = Mage::helper('catalog')->getPageTemplateProcessor();
        }
        return $this->_templateProcessor;
    }
}
