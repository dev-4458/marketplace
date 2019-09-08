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
 * Vendor - product controller
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class Sandy_Marketplace_Adminhtml_Marketplace_Vendor_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * construct
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('Sandy_Marketplace');
    }

    /**
     * vendors in the catalog page
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function vendorsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.vendor')
            ->setProductVendors($this->getRequest()->getPost('product_vendors', null));
        $this->renderLayout();
    }

    /**
     * vendors grid in the catalog page
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function vendorsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.vendor')
            ->setProductVendors($this->getRequest()->getPost('product_vendors', null));
        $this->renderLayout();
    }
}
