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
 * Vendor admin attribute controller
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Adminhtml_Marketplace_Vendor_AttributeController extends Mage_Adminhtml_Controller_Action
{
    protected $_entityTypeId;

    /**
     * predispatch
     *
     * @accees public
     * @return void
     * @author Ultimate Module Creator
     */
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_entityTypeId = Mage::getModel('eav/entity')
            ->setType(Sandy_Marketplace_Model_Vendor::ENTITY)
            ->getTypeId();
    }

    /**
     * init action
     *
     * @accees protected
     * @return Sandy_Marketplace_Adminhtml_Vendor_AttributeController
     * @author Ultimate Module Creator
     */
    protected function _initAction()
    {
        $this->_title(Mage::helper('sandy_marketplace')->__('Vendor'))
             ->_title(Mage::helper('sandy_marketplace')->__('Attributes'))
             ->_title(Mage::helper('sandy_marketplace')->__('Manage Attributes'));

        $this->loadLayout()
            ->_setActiveMenu('sandy_marketplace/vendor_attributes')
            ->_addBreadcrumb(
                Mage::helper('sandy_marketplace')->__('Vendor'),
                Mage::helper('sandy_marketplace')->__('Vendor')
            )
            ->_addBreadcrumb(
                Mage::helper('sandy_marketplace')->__('Manage Vendor Attributes'),
                Mage::helper('sandy_marketplace')->__('Manage Vendor Attributes')
            );
        return $this;
    }

    /**
     * default action
     *
     * @accees public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    /**
     * add attribute action
     *
     * @accees public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * edit attribute action
     *
     * @accees public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('attribute_id');
        $model = Mage::getModel('sandy_marketplace/resource_eav_attribute')
            ->setEntityTypeId($this->_entityTypeId);
        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sandy_marketplace')->__('This vendor attribute no longer exists')
                );
                $this->_redirect('*/*/');
                return;
            }
            // entity type check
            if ($model->getEntityTypeId() != $this->_entityTypeId) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sandy_marketplace')->__('This vendor attribute cannot be edited.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getAttributeData(true);
        if (! empty($data)) {
            $model->addData($data);
        }
        Mage::register('entity_attribute', $model);
        $this->_initAction();
        $this->_title($id ? $model->getName() : Mage::helper('sandy_marketplace')->__('New Vendor Attribute'));
        $item = $id ? Mage::helper('sandy_marketplace')->__('Edit Vendor Attribute')
                    : Mage::helper('sandy_marketplace')->__('New Vendor Attribute');
        $this->_addBreadcrumb($item, $item);
        $this->renderLayout();
    }

    /**
     * validate attribute action
     *
     * @accees public
     * @return void
     * @author Ultimate Module Creator
     */
    public function validateAction()
    {
        $response = new Varien_Object();
        $response->setError(false);

        $attributeCode  = $this->getRequest()->getParam('attribute_code');
        $attributeId    = $this->getRequest()->getParam('attribute_id');
        $attribute      = Mage::getModel('sandy_marketplace/attribute')
            ->loadByCode($this->_entityTypeId, $attributeCode);
        if ($attribute->getId() && !$attributeId) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sandy_marketplace')->__('Attribute with the same code already exists')
            );
            $this->_initLayoutMessages('adminhtml/session');
            $response->setError(true);
            $response->setMessage($this->getLayout()->getMessagesBlock()->getGroupedHtml());
        }
        $this->getResponse()->setBody($response->toJson());
    }

    /**
     * Filter post data
     *
     * @access protected
     * @param array $data
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _filterPostData($data)
    {
        if ($data) {
            $helper = Mage::helper('sandy_marketplace');
            //labels
            foreach ($data['frontend_label'] as & $value) {
                if ($value) {
                    $value = $helper->stripTags($value);
                }
            }
            //options
            if (!empty($data['option']['value'])) {
                foreach ($data['option']['value'] as &$options) {
                    foreach ($options as &$label) {
                        $label = $helper->stripTags($label);
                    }
                }
            }
            //default value
            if (!empty($data['default_value'])) {
                $data['default_value'] = $helper->stripTags($data['default_value']);
            }
            if (!empty($data['default_value_text'])) {
                $data['default_value_text'] = $helper->stripTags($data['default_value_text']);
            }
            if (!empty($data['default_value_textarea'])) {
                $data['default_value_textarea'] = $helper->stripTags($data['default_value_textarea']);
            }
        }
        return $data;
    }

    /**
     * save attribute action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        if ($data) {
            $session      = Mage::getSingleton('adminhtml/session');
            $redirectBack = $this->getRequest()->getParam('back', false);
            $model        = Mage::getModel('sandy_marketplace/resource_eav_attribute');
            $helper       = Mage::helper('sandy_marketplace/vendor');
            $id           = $this->getRequest()->getParam('attribute_id');
            //validate attribute_code
            if (isset($data['attribute_code'])) {
                $validatorAttrCode = new Zend_Validate_Regex(array('pattern' => '/^[a-z_0-9]{1,255}$/'));
                if (!$validatorAttrCode->isValid($data['attribute_code'])) {
                    $session->addError(
                        Mage::helper('sandy_marketplace')->__(
                            'Attribute code is invalid. Please use only letters (a-z), numbers (0-9) or underscore(_) in this field, first character should be a letter.'
                        )
                    );
                    $this->_redirect('*/*/edit', array('attribute_id' => $id, '_current' => true));
                    return;
                }
            }
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $session->addError(
                        Mage::helper('sandy_marketplace')->__('This attribute no longer exists')
                    );
                    $this->_redirect('*/*/');
                    return;
                }

                // entity type check
                if ($model->getEntityTypeId() != $this->_entityTypeId) {
                    $session->addError(
                        Mage::helper('sandy_marketplace')->__('This attribute cannot be updated.')
                    );
                    $session->setAttributeData($data);
                    $this->_redirect('*/*/');
                    return;
                }

                $data['attribute_code']  = $model->getAttributeCode();
                $data['is_user_defined'] = $model->getIsUserDefined();
                $data['frontend_input']  = $model->getFrontendInput();
            } else {
                $data['source_model']  = $helper->getAttributeSourceModelByInputType($data['frontend_input']);
                $data['backend_model'] = $helper->getAttributeBackendModelByInputType($data['frontend_input']);
            }

            if (is_null($model->getIsUserDefined()) || $model->getIsUserDefined() != 0) {
                $data['backend_type'] = $model->getBackendTypeByInput($data['frontend_input']);
            }
            $defaultValueField = $model->getDefaultValueByInput($data['frontend_input']);
            if ($defaultValueField) {
                $data['default_value'] = $this->getRequest()->getParam($defaultValueField);
            }
            //filter
            $data = $this->_filterPostData($data);
            $model->addData($data);
            if (!$id) {
                $model->setEntityTypeId($this->_entityTypeId);
                $model->setIsUserDefined(1);
                $model->setIsVisible(1);
            }
            try {
                $model->save();
                $session->addSuccess(
                    Mage::helper('sandy_marketplace')->__('The vendor attribute has been saved.')
                );
                /**
                 * Clear translation cache because attribute labels are stored in translation
                 */
                Mage::app()->cleanCache(array(Mage_Core_Model_Translate::CACHE_TAG));
                $session->setAttributeData(false);
                if ($redirectBack) {
                    $this->_redirect('*/*/edit', array('attribute_id' => $model->getId(), '_current'=>true));
                } else {
                    $this->_redirect('*/*/', array());
                }
                return;
            } catch (Exception $e) {
                $session->addError($e->getMessage());
                $session->setAttributeData($data);
                $this->_redirect('*/*/edit', array('attribute_id' => $id, '_current' => true));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * delete attribute action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('attribute_id')) {
            $model = Mage::getModel('sandy_marketplace/resource_eav_attribute');
            // entity type check
            $model->load($id);
            if ($model->getEntityTypeId() != $this->_entityTypeId) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sandy_marketplace')->__('This attribute cannot be deleted.')
                );
                $this->_redirect('*/*/');
                return;
            }
            try {
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('sandy_marketplace')->__('The vendor attribute has been deleted.')
                );
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('attribute_id' => $this->getRequest()->getParam('attribute_id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('sandy_marketplace')->__('Unable to find an attribute to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * check access
     *
     * @access protected
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sandy_marketplace/vendor_attributes');
    }
}
