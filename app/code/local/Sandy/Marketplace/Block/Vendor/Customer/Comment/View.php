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
 * Vendor customer comments list
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Vendor_Customer_Comment_View extends Mage_Customer_Block_Account_Dashboard
{
    /**
     * get current comment
     *
     * @access public
     * @return Sandy_Marketplace_Model_Vendor_Comment
     * @author Ultimate Module Creator
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }

    /**
     * get current vendor
     *
     * @access public
     * @return Sandy_Marketplace_Model_Vendor
     * @author Ultimate Module Creator
     */
    public function getVendor()
    {
        return Mage::registry('current_vendor');
    }
}
