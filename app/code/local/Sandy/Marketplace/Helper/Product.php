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
 * Product helper
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Helper_Product extends Sandy_Marketplace_Helper_Data
{

    /**
     * get the selected vendors for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getSelectedVendors(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedVendors()) {
            $vendors = array();
            foreach ($this->getSelectedVendorsCollection($product) as $vendor) {
                $vendors[] = $vendor;
            }
            $product->setSelectedVendors($vendors);
        }
        return $product->getData('selected_vendors');
    }

    /**
     * get vendor collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return Sandy_Marketplace_Model_Resource_Vendor_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedVendorsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('sandy_marketplace/vendor_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
