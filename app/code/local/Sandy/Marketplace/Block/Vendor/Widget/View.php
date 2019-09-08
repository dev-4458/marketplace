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
 * Vendor widget block
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Vendor_Widget_View extends Mage_Core_Block_Template implements
    Mage_Widget_Block_Interface
{
    protected $_htmlTemplate = 'sandy_marketplace/vendor/widget/view.phtml';

    /**
     * Prepare a for widget
     *
     * @access protected
     * @return Sandy_Marketplace_Block_Vendor_Widget_View
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $vendorId = $this->getData('vendor_id');
        if ($vendorId) {
            $vendor = Mage::getModel('sandy_marketplace/vendor')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($vendorId);
            if ($vendor->getStatus()) {
                $this->setCurrentVendor($vendor);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
