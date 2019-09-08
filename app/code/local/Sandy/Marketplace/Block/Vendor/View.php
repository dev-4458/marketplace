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
 * Vendor view block
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Vendor_View extends Mage_Core_Block_Template
{
    /**
     * get the current vendor
     *
     * @access public
     * @return mixed (Sandy_Marketplace_Model_Vendor|null)
     * @author Ultimate Module Creator
     */
    public function getCurrentVendor()
    {
        return Mage::registry('current_vendor');
    }
}
