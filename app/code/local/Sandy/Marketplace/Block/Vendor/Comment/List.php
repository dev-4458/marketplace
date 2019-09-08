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
 * Vendor comment list block
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author Ultimate Module Creator
 */
class Sandy_Marketplace_Block_Vendor_Comment_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $vendor = $this->getVendor();
        $comments = Mage::getResourceModel('sandy_marketplace/vendor_comment_collection')
            ->addFieldToFilter('vendor_id', $vendor->getId())
            ->addStoreFilter(Mage::app()->getStore())
             ->addFieldToFilter('status', 1);
        $comments->setOrder('created_at', 'asc');
        $this->setComments($comments);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Sandy_Marketplace_Block_Vendor_Comment_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'sandy_marketplace.vendor.html.pager'
        )
        ->setCollection($this->getComments());
        $this->setChild('pager', $pager);
        $this->getComments()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    /**
     * get the current vendor
     *
     * @access protected
     * @return Sandy_Marketplace_Model_Vendor
     * @author Ultimate Module Creator
     */
    public function getVendor()
    {
        return Mage::registry('current_vendor');
    }
}
