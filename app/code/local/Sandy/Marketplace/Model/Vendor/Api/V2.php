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
class Sandy_Marketplace_Model_Vendor_Api_V2 extends Sandy_Marketplace_Model_Vendor_Api
{
    /**
     * Vendor info
     *
     * @access public
     * @param int $vendorId
     * @return object
     * @author Ultimate Module Creator
     */
    public function info($vendorId)
    {
        $result = parent::info($vendorId);
        $result = Mage::helper('api')->wsiArrayPacker($result);
        foreach ($result->products as $key => $value) {
            $result->products[$key] = array('key' => $key, 'value' => $value);
        }
        return $result;
    }
}
