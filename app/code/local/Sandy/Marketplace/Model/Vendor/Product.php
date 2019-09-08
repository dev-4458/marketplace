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
 * Vendor product model
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Model_Vendor_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->_init('sandy_marketplace/vendor_product');
    }

    /**
     * Save data for vendor-product relation
     * @access public
     * @param  Sandy_Marketplace_Model_Vendor $vendor
     * @return Sandy_Marketplace_Model_Vendor_Product
     * @author Ultimate Module Creator
     */
    public function saveVendorRelation($vendor)
    {
        $data = $vendor->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveVendorRelation($vendor, $data);
        }
        return $this;
    }

    /**
     * get products for vendor
     *
     * @access public
     * @param Sandy_Marketplace_Model_Vendor $vendor
     * @return Sandy_Marketplace_Model_Resource_Vendor_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getProductCollection($vendor)
    {
        $collection = Mage::getResourceModel('sandy_marketplace/vendor_product_collection')
            ->addVendorFilter($vendor);
        return $collection;
    }
}
