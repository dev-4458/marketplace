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
 * Vendor admin controller
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Adminhtml_Marketplace_VendorController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Sandy_Marketplace');
    }

    /**
     * init the vendor
     *
     * @access protected 
     * @return Sandy_Marketplace_Model_Vendor
     * @author Ultimate Module Creator
     */
    protected function _initVendor()
    {
        $this->_title($this->__('Multi Marketplace'))
             ->_title($this->__('Manage Vendors'));

        $vendorId  = (int) $this->getRequest()->getParam('id');
        $vendor    = Mage::getModel('sandy_marketplace/vendor')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($vendorId) {
            $vendor->load($vendorId);
        }
        Mage::register('current_vendor', $vendor);
        return $vendor;
    }

    /**
     * default action for vendor controller
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->_title($this->__('Multi Marketplace'))
             ->_title($this->__('Manage Vendors'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new vendor action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * edit vendor action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $vendorId  = (int) $this->getRequest()->getParam('id');
        $vendor    = $this->_initVendor();
        if ($vendorId && !$vendor->getId()) {
            $this->_getSession()->addError(
                Mage::helper('sandy_marketplace')->__('This vendor no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getVendorData(true)) {
            $vendor->setData($data);
        }
        $this->_title($vendor->getShopurl());
        Mage::dispatchEvent(
            'sandy_marketplace_vendor_edit_action',
            array('vendor' => $vendor)
        );
        $this->loadLayout();
        if ($vendor->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('sandy_marketplace')->__('Default Values'))
                    ->setWebsiteIds($vendor->getWebsiteIds())
                    ->setSwitchUrl(
                        $this->getUrl(
                            '*/*/*',
                            array(
                                '_current'=>true,
                                'active_tab'=>null,
                                'tab' => null,
                                'store'=>null
                            )
                        )
                    );
            }
        } else {
            $this->getLayout()->getBlock('left')->unsetChild('store_switcher');
        }
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * save vendor action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $vendorId   = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $vendor     = $this->_initVendor();
            $vendorData = $this->getRequest()->getPost('vendor', array());
            $vendor->addData($vendorData);
            $vendor->setAttributeSetId($vendor->getDefaultAttributeSetId());
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $vendor->setProductsData(
                        Mage::helper('adminhtml/js')->decodeGridSerializedInput($products)
                    );
                }
            if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                foreach ($useDefaults as $attributeCode) {
                    $vendor->setData($attributeCode, false);
                }
            }
            try {
                $vendor->save();
                $vendorId = $vendor->getId();
                $this->_getSession()->addSuccess(
                    Mage::helper('sandy_marketplace')->__('Vendor was saved')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage())
                    ->setVendorData($vendorData);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(
                    Mage::helper('sandy_marketplace')->__('Error saving vendor')
                )
                ->setVendorData($vendorData);
                $redirectBack = true;
            }
        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => $vendorId,
                    '_current'=>true
                )
            );
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }

    /**
     * delete vendor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $vendor = Mage::getModel('sandy_marketplace/vendor')->load($id);
            try {
                $vendor->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('sandy_marketplace')->__('The vendors has been deleted.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store')))
        );
    }

    /**
     * mass delete vendors
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $vendorIds = $this->getRequest()->getParam('vendor');
        if (!is_array($vendorIds)) {
            $this->_getSession()->addError($this->__('Please select vendors.'));
        } else {
            try {
                foreach ($vendorIds as $vendorId) {
                    $vendor = Mage::getSingleton('sandy_marketplace/vendor')->load($vendorId);
                    Mage::dispatchEvent(
                        'sandy_marketplace_controller_vendor_delete',
                        array('vendor' => $vendor)
                    );
                    $vendor->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('sandy_marketplace')->__('Total of %d record(s) have been deleted.', count($vendorIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $vendorIds = $this->getRequest()->getParam('vendor');
        if (!is_array($vendorIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sandy_marketplace')->__('Please select vendors.')
            );
        } else {
            try {
                foreach ($vendorIds as $vendorId) {
                $vendor = Mage::getSingleton('sandy_marketplace/vendor')->load($vendorId)
                    ->setStatus($this->getRequest()->getParam('status'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d vendors were successfully updated.', count($vendorIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sandy_marketplace')->__('There was an error updating vendors.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * restrict access
     *
     * @access protected
     * @return bool
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sandy_marketplace/vendor');
    }

    /**
     * Export vendors in CSV format
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'vendors.csv';
        $content    = $this->getLayout()->createBlock('sandy_marketplace/adminhtml_vendor_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export vendors in Excel format
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'vendor.xls';
        $content    = $this->getLayout()->createBlock('sandy_marketplace/adminhtml_vendor_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export vendors in XML format
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'vendor.xml';
        $content    = $this->getLayout()->createBlock('sandy_marketplace/adminhtml_vendor_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * wysiwyg editor action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function wysiwygAction()
    {
        $elementId     = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId       = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'sandy_marketplace/adminhtml_marketplace_helper_form_wysiwyg_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id'          => $storeId,
                'store_media_url'   => $storeMediaUrl,
            )
        );
        $this->getResponse()->setBody($content->toHtml());
    }

    /**
     * mass Business Type change
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massBusinesstypeAction()
    {
        $vendorIds = (array)$this->getRequest()->getParam('vendor');
        $storeId       = (int)$this->getRequest()->getParam('store', 0);
        $flag          = (int)$this->getRequest()->getParam('flag_businesstype');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($vendorIds as $vendorId) {
                $vendor = Mage::getSingleton('sandy_marketplace/vendor')
                    ->setStoreId($storeId)
                    ->load($vendorId);
                $vendor->setBusinesstype($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('sandy_marketplace')->__('Total of %d record(s) have been updated.', count($vendorIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('sandy_marketplace')->__('An error occurred while updating the vendors.')
            );
        }
        $this->_redirect('*/*/', array('store'=> $storeId));
    }

    /**
     * mass Gender change
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massGenderAction()
    {
        $vendorIds = (array)$this->getRequest()->getParam('vendor');
        $storeId       = (int)$this->getRequest()->getParam('store', 0);
        $flag          = (int)$this->getRequest()->getParam('flag_gender');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($vendorIds as $vendorId) {
                $vendor = Mage::getSingleton('sandy_marketplace/vendor')
                    ->setStoreId($storeId)
                    ->load($vendorId);
                $vendor->setGender($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('sandy_marketplace')->__('Total of %d record(s) have been updated.', count($vendorIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('sandy_marketplace')->__('An error occurred while updating the vendors.')
            );
        }
        $this->_redirect('*/*/', array('store'=> $storeId));
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsAction()
    {
        $this->_initVendor();
        $this->loadLayout();
        $this->getLayout()->getBlock('vendor.edit.tab.product')
            ->setVendorProducts($this->getRequest()->getPost('vendor_products', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsgridAction()
    {
        $this->_initVendor();
        $this->loadLayout();
        $this->getLayout()->getBlock('vendor.edit.tab.product')
            ->setVendorProducts($this->getRequest()->getPost('vendor_products', null));
        $this->renderLayout();
    }
}
