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
 * Vendor front contrller
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_VendorController extends Mage_Core_Controller_Front_Action
{

    /**
      * default action
      *
      * @access public
      * @return void
      * @author Ultimate Module Creator
      */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('sandy_marketplace/vendor')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('sandy_marketplace')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'vendors',
                    array(
                        'label' => Mage::helper('sandy_marketplace')->__('Vendors'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('sandy_marketplace/vendor')->getVendorsUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('sandy_marketplace/vendor/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('sandy_marketplace/vendor/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('sandy_marketplace/vendor/meta_description'));
        }
        $this->renderLayout();
    }

    /**
     * init Vendor
     *
     * @access protected
     * @return Sandy_Marketplace_Model_Vendor
     * @author Ultimate Module Creator
     */
    protected function _initVendor()
    {
        $vendorId   = $this->getRequest()->getParam('id', 0);
        $vendor     = Mage::getModel('sandy_marketplace/vendor')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($vendorId);
        if (!$vendor->getId()) {
            return false;
        } elseif (!$vendor->getStatus()) {
            return false;
        }
        return $vendor;
    }

    /**
     * view vendor action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function viewAction()
    {
        $vendor = $this->_initVendor();
        if (!$vendor) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_vendor', $vendor);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('marketplace-vendor marketplace-vendor' . $vendor->getId());
        }
        if (Mage::helper('sandy_marketplace/vendor')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('sandy_marketplace')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'vendors',
                    array(
                        'label' => Mage::helper('sandy_marketplace')->__('Vendors'),
                        'link'  => Mage::helper('sandy_marketplace/vendor')->getVendorsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'vendor',
                    array(
                        'label' => $vendor->getShopurl(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $vendor->getVendorUrl());
        }
        if ($headBlock) {
            if ($vendor->getMetaTitle()) {
                $headBlock->setTitle($vendor->getMetaTitle());
            } else {
                $headBlock->setTitle($vendor->getShopurl());
            }
            $headBlock->setKeywords($vendor->getMetaKeywords());
            $headBlock->setDescription($vendor->getMetaDescription());
        }
        $this->renderLayout();
    }

    /**
     * vendors rss list action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function rssAction()
    {
        if (Mage::helper('sandy_marketplace/vendor')->isRssEnabled()) {
            $this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
            $this->loadLayout(false);
            $this->renderLayout();
        } else {
            $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
            $this->getResponse()->setHeader('Status', '404 File not found');
            $this->_forward('nofeed', 'index', 'rss');
        }
    }

    /**
     * Submit new comment action
     * @access public
     * @author Ultimate Module Creator
     */
    public function commentpostAction()
    {
        $data   = $this->getRequest()->getPost();
        $vendor = $this->_initVendor();
        $session    = Mage::getSingleton('core/session');
        if ($vendor) {
            if ($vendor->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() ||
                    Mage::getStoreConfigFlag('sandy_marketplace/vendor/allow_guest_comment'))) {
                    $comment  = Mage::getModel('sandy_marketplace/vendor_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setVendorId($vendor->getId())
                                ->setStatus(Sandy_Marketplace_Model_Vendor_Comment::STATUS_PENDING)
                                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                ->setStores(array(Mage::app()->getStore()->getId()))
                                ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        } catch (Exception $e) {
                            $session->setVendorCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    } else {
                        $session->setVendorCommentData($data);
                        if (is_array($validate)) {
                            foreach ($validate as $errorMessage) {
                                $session->addError($errorMessage);
                            }
                        } else {
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                } else {
                    $session->addError($this->__('Guest comments are not allowed'));
                }
            } else {
                $session->addError($this->__('This vendor does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }
}
