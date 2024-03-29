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
 * Vendor attribute API model
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Model_Vendor_Attribute_Api extends Mage_Catalog_Model_Api_Resource
{
    /**
     * Vendor entity type id
     *
     * @var int
     */
    protected $_entityTypeId;

    /**
     * Constructor. Initializes default values.
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->_storeIdSessionField = 'vendor_store_id';
        $this->_entityTypeId = Mage::getModel('eav/entity')->setType('sandy_marketplace_vendor')
            ->getTypeId();
    }

    /**
     * Retrieve attributes
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function items()
    {
        $attributes = Mage::getResourceModel('sandy_marketplace/vendor_attribute_collection');
        $result = array();
        foreach ($attributes as $attribute) {
            if ($this->_isAllowedAttribute($attribute)) {
                if (!$attribute->getId() || $attribute->getIsGlobal() == Sandy_Marketplace_Model_Attribute::SCOPE_GLOBAL) {
                    $scope = 'global';
                } elseif ($attribute->getIsGlobal() == Sandy_Marketplace_Model_Attribute::SCOPE_WEBSITE) {
                    $scope = 'website';
                } else {
                    $scope = 'store';
                }

                $result[] = array(
                    'attribute_id' => $attribute->getId(),
                    'code'         => $attribute->getAttributeCode(),
                    'type'         => $attribute->getFrontendInput(),
                    'required'     => $attribute->getIsRequired(),
                    'scope'        => $scope
                );
            }
        }
        return $result;
    }

    /**
     * Retrieve attribute options
     *
     * @access public
     * @param int $attributeId
     * @param string|int $store
     * @return array
     * @author Ultimate Module Creator
     */
    public function options($attributeId, $store = null)
    {
        $storeId = $this->_getStoreId($store);
        $attribute = Mage::getModel('sandy_marketplace/vendor')
            ->setStoreId($storeId)
            ->getResource()
            ->getAttribute($attributeId);
        if (!$attribute) {
            $this->_fault('not_exists');
        }
        $options = array();
        if ($attribute->usesSource()) {
            foreach ($attribute->getSource()->getAllOptions() as $optionId => $optionValue) {
                if (is_array($optionValue)) {
                    $options[] = $optionValue;
                } else {
                    $options[] = array(
                        'value' => $optionId,
                        'label' => $optionValue
                    );
                }
            }
        }
        return $options;
    }

    /**
     * Retrieve list of possible attribute types
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function types()
    {
        $types = Mage::getModel('eav/adminhtml_system_config_source_inputtype')->toOptionArray();
        $additionalTypes = array(
            array(
                'value' => 'image',
                'label' => Mage::helper('sandy_marketplace')->__('Image')
            ),
            array(
                'value' => 'file',
                'label' => Mage::helper('sandy_marketplace')->__('File')
            )
        );
        return array_merge($types, $additionalTypes);
    }

    /**
     * Create new product attribute
     *
     * @access public
     * @param array $data input data
     * @return integer
     * @author Ultimate Module Creator
     */
    public function create($data)
    {
        $model = Mage::getModel('sandy_marketplace/resource_eav_attribute');
        $helper = Mage::helper('sandy_marketplace/vendor');

        if (empty($data['attribute_code']) || !is_array($data['frontend_label'])) {
            $this->_fault('invalid_parameters');
        }

        //validate attribute_code
        if (!preg_match('/^[a-z][a-z_0-9]{0,254}$/', $data['attribute_code'])) {
            $this->_fault('invalid_code');
        }

        //validate frontend_input
        $allowedTypes = array();
        foreach ($this->types() as $type) {
            $allowedTypes[] = $type['value'];
        }
        if (!in_array($data['frontend_input'], $allowedTypes)) {
            $this->_fault('invalid_frontend_input');
        }

        $data['source_model'] = $helper->getAttributeSourceModelByInputType($data['frontend_input']);
        $data['backend_model'] = $helper->getAttributeBackendModelByInputType($data['frontend_input']);
        if (is_null($model->getIsUserDefined()) || $model->getIsUserDefined() != 0) {
            $data['backend_type'] = $model->getBackendTypeByInput($data['frontend_input']);
        }

        $this->_prepareDataForSave($data);

        $model->addData($data);
        $model->setEntityTypeId($this->_entityTypeId);
        $model->setIsUserDefined(1);
        $model->setIsVisible(1);

        try {
            $model->save();
            // clear translation cache because attribute labels are stored in translation
            Mage::app()->cleanCache(array(Mage_Core_Model_Translate::CACHE_TAG));
        } catch (Exception $e) {
            $this->_fault('unable_to_save', $e->getMessage());
        }

        return (int) $model->getId();
    }

    /**
     * Update product attribute
     *
     * @access public
     * @param string|integer $attribute attribute code or ID
     * @param array $data
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function update($attribute, $data)
    {
        $model = $this->_getAttribute($attribute);
        if ($model->getEntityTypeId() != $this->_entityTypeId) {
            $this->_fault('can_not_edit');
        }
        $data['attribute_code'] = $model->getAttributeCode();
        $data['is_user_defined'] = $model->getIsUserDefined();
        $data['frontend_input'] = $model->getFrontendInput();

        $this->_prepareDataForSave($data);

        $model->addData($data);
        try {
            $model->save();
            // clear translation cache because attribute labels are stored in translation
            Mage::app()->cleanCache(array(Mage_Core_Model_Translate::CACHE_TAG));
            return true;
        } catch (Exception $e) {
            $this->_fault('unable_to_save', $e->getMessage());
        }
    }

    /**
     * Remove attribute
     *
     * @access public
     * @param integer|string $attribute attribute ID or code
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function remove($attribute)
    {
        $model = $this->_getAttribute($attribute);
        if ($model->getEntityTypeId() != $this->_entityTypeId) {
            $this->_fault('can_not_delete');
        }
        try {
            $model->delete();
            return true;
        } catch (Exception $e) {
            $this->_fault('can_not_delete', $e->getMessage());
        }
    }

    /**
     * Get full information about attribute with list of options
     *
     * @access pubic
     * @param integer|string $attribute attribute ID or code
     * @return array
     * @author Ultimate Module Creator
     */
    public function info($attribute)
    {
        $model = $this->_getAttribute($attribute);
        if ($model->isScopeGlobal()) {
            $scope = 'global';
        } elseif ($model->isScopeWebsite()) {
            $scope = 'website';
        } else {
            $scope = 'store';
        }
        $frontendLabels = array(
            array(
                'store_id' => 0,
                'label'    => $model->getFrontendLabel()
            )
        );
        foreach ($model->getStoreLabels() as $storeId => $label) {
            $frontendLabels[] = array(
                'store_id' => $storeId,
                'label'    => $label
            );
        }
        $result = array(
            'attribute_id'   => $model->getId(),
            'attribute_code' => $model->getAttributeCode(),
            'frontend_input' => $model->getFrontendInput(),
            'default_value'  => $model->getDefaultValue(),
            'is_unique'      => $model->getIsUnique(),
            'is_required'    => $model->getIsRequired(),
            'frontend_label' => $frontendLabels
        );
        // set additional fields to different types
        switch ($model->getFrontendInput()) {
            case 'text':
                $result['additional_fields'] = array(
                    'frontend_class' => $model->getFrontendClass(),
                );
                break;
            case 'textarea':
                $result['additional_fields'] = array(
                    'is_wysiwyg_enabled' => $model->getIsWysiwygEnabled(),
                );
                break;
            case 'date':
            default:
                $result['additional_fields'] = array();
                break;
        }

        // set options
        $options = $this->options($model->getId());
        // remove empty first element
        if ($model->getFrontendInput() != 'boolean') {
            array_shift($options);
        }

        if (count($options) > 0) {
            $result['options'] = $options;
        }

        return $result;
    }

    /**
     * Add option to select or multiselect attribute
     *
     * @access public
     * @param  integer|string $attribute attribute ID or code
     * @param  array $data
     * @return bool
     * @author Ultimate Module Creator
     */
    public function addOption($attribute, $data)
    {
        $model = $this->_getAttribute($attribute);
        if (!$model->usesSource()) {
            $this->_fault('invalid_frontend_input');
        }

        /** @var $helperCatalog Mage_Catalog_Helper_Data */
        $helperCatalog = Mage::helper('catalog');

        $optionLabels = array();
        foreach ($data['label'] as $label) {
            $storeId = $label['store_id'];
            $labelText = $helperCatalog->stripTags($label['value']);
            if (is_array($storeId)) {
                foreach ($storeId as $multiStoreId) {
                    $optionLabels[$multiStoreId] = $labelText;
                }
            } else {
                $optionLabels[$storeId] = $labelText;
            }
        }
        $modelData = array(
            'option' => array(
                'value' => array(
                    'option_1' => $optionLabels
                ),
                'order' => array(
                    'option_1' => (int) $data['order']
                )
            )
        );
        $model->addData($modelData);
        try {
            $model->save();
        } catch (Exception $e) {
            $this->_fault('unable_to_add_option', $e->getMessage());
        }

        return true;
    }

    /**
     * Remove option from select or multiselect attribute
     *
     * @access public
     * @param  integer|string $attribute attribute ID or code
     * @param  integer $optionId option to remove ID
     * @return bool
     * @author Ultimate Module Creator
     */
    public function removeOption($attribute, $optionId)
    {
        $model = $this->_getAttribute($attribute);
        if (!$model->usesSource()) {
            $this->_fault('invalid_frontend_input');
        }
        $modelData = array(
            'option' => array(
                'value' => array(
                    $optionId => array()
                ),
                'delete' => array(
                    $optionId => '1'
                )
            )
        );
        $model->addData($modelData);
        try {
            $model->save();
        } catch (Exception $e) {
            $this->_fault('unable_to_remove_option', $e->getMessage());
        }

        return true;
    }

    /**
     * Prepare request input data for saving
     *
     * @access protected
     * @param array $data input data
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _prepareDataForSave(&$data)
    {
        /** @var $helperCatalog Mage_Catalog_Helper_Data */
        $helperCatalog = Mage::helper('catalog');

        if ($data['scope'] == 'global') {
            $data['is_global'] = Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL;
        } else if ($data['scope'] == 'website') {
            $data['is_global'] = Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE;
        } else {
            $data['is_global'] = Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE;
        }
        // set frontend labels array with store_id as keys
        if (isset($data['frontend_label']) && is_array($data['frontend_label'])) {
            $labels = array();
            foreach ($data['frontend_label'] as $label) {
                $storeId = $label['store_id'];
                $labelText = $helperCatalog->stripTags($label['label']);
                $labels[$storeId] = $labelText;
            }
            $data['frontend_label'] = $labels;
        }
        // set additional fields
        if (isset($data['additional_fields']) && is_array($data['additional_fields'])) {
            $data = array_merge($data, $data['additional_fields']);
            unset($data['additional_fields']);
        }
        //default value
        if (!empty($data['default_value'])) {
            $data['default_value'] = $helperCatalog->stripTags($data['default_value']);
        }
    }

    /**
     * Load model by attribute ID or code
     *
     * @param integer|string $attribute
     * @return Mage_Catalog_Model_Resource_Eav_Attribute
     * @author Ultimate Module Creator
     */
    protected function _getAttribute($attribute)
    {
        $model = Mage::getResourceModel('sandy_marketplace/eav_attribute')
            ->setEntityTypeId($this->_entityTypeId);

        if (is_numeric($attribute)) {
            $model->load(intval($attribute));
        } else {
            $model->load($attribute, 'attribute_code');
        }

        if (!$model->getId()) {
            $this->_fault('not_exists');
        }

        return $model;
    }
}
