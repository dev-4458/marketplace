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
 * Adminhtml observer
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _canAddTab($product)
    {
        if ($product->getId()) {
            return true;
        }
        if (!$product->getAttributeSetId()) {
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        return false;
    }

    /**
     * add the vendor tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Sandy_Marketplace_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function addProductVendorBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'vendors',
                array(
                    'label' => Mage::helper('sandy_marketplace')->__('Vendors'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/marketplace_vendor_catalog_product/vendors',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save vendor - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Sandy_Marketplace_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function saveProductVendorData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('vendors', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('product');
            $vendorProduct = Mage::getResourceSingleton('sandy_marketplace/vendor_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }}
