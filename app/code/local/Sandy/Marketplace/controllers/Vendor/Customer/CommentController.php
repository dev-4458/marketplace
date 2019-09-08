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
 * Vendor comments controller
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Vendor_Customer_CommentController extends Mage_Core_Controller_Front_Action
{
    /**
     * Action predispatch
     * Check customer authentication for some actions
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    /**
     * List comments
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('sandy_marketplace/vendor_customer_comment/');
        }
        if ($block = $this->getLayout()->getBlock('vendor_customer_comment_list')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('My Vendor Comments'));

        $this->renderLayout();
    }

    /**
     * View comment
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function viewAction()
    {
        $commentId = $this->getRequest()->getParam('id');
        $comment = Mage::getModel('sandy_marketplace/vendor_comment')->load($commentId);
        if (!$comment->getId() ||
            $comment->getCustomerId() != Mage::getSingleton('customer/session')->getCustomerId() ||
            $comment->getStatus() != Sandy_Marketplace_Model_Vendor_Comment::STATUS_APPROVED) {
            $this->_forward('no-route');
            return;
        }
        $vendor = Mage::getModel('sandy_marketplace/vendor')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($comment->getVendorId());
        if (!$vendor->getId() || $vendor->getStatus() != 1) {
            $this->_forward('no-route');
            return;
        }
        $stores = array(Mage::app()->getStore()->getId(), 0);
        if (count(array_intersect($stores, $comment->getStoreId())) == 0) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_comment', $comment);
        Mage::register('current_vendor', $vendor);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('sandy_marketplace/vendor_customer_comment/');
        }
        if ($block = $this->getLayout()->getBlock('customer_vendor_comment')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Vendor Comments'));
        $this->renderLayout();
    }
}
