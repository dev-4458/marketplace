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
class Sandy_Marketplace_Model_Vendor_Api extends Mage_Api_Model_Resource_Abstract
{
    protected $_defaultAttributeList = array(
        'name', 
        'shopurl', 
        'phone', 
        'businesstype', 
        'birthdate', 
        'profilepic', 
        'description', 
        'gender', 
        'banner', 
        'status', 
        'url_key', 
        'in_rss', 
        'meta_title', 
        'meta_keywords', 
        'meta_description', 
        'allow_comment', 
        'created_at', 
        'updated_at', 
    );


    /**
     * init vendor
     *
     * @access protected
     * @param $vendorId
     * @return Sandy_Marketplace_Model_Vendor
     * @author      Sandy Infocom
     */
    protected function _initVendor($vendorId)
    {
        $vendor = Mage::getModel('sandy_marketplace/vendor')->load($vendorId);
        if (!$vendor->getId()) {
            $this->_fault('vendor_not_exists');
        }
        return $vendor;
    }

    /**
     * get vendors
     *
     * @access public
     * @param mixed $filters
     * @return array
     * @author Ultimate Module Creator
     */
    public function items($filters = null)
    {
        $collection = Mage::getModel('sandy_marketplace/vendor')->getCollection()
            ->addAttributeToSelect('*');
        $apiHelper = Mage::helper('api');
        $filters = $apiHelper->parseFilters($filters);
        try {
            foreach ($filters as $field => $value) {
                $collection->addFieldToFilter($field, $value);
            }
        } catch (Mage_Core_Exception $e) {
            $this->_fault('filters_invalid', $e->getMessage());
        }
        $result = array();
        foreach ($collection as $vendor) {
            $result[] = $this->_getApiData($vendor);
        }
        return $result;
    }

    /**
     * Add vendor
     *
     * @access public
     * @param array $data
     * @return array
     * @author Ultimate Module Creator
     */
    public function add($data)
    {
        try {
            if (is_null($data)) {
                throw new Exception(Mage::helper('sandy_marketplace')->__("Data cannot be null"));
            }
            $data = (array)$data;
            if (isset($data['additional_attributes']) && is_array($data['additional_attributes'])) {
                foreach ($data['additional_attributes'] as $key=>$value) {
                    $data[$key] = $value;
                }
                unset($data['additional_attributes']);
            }
            $data['attribute_set_id'] = Mage::getModel('sandy_marketplace/vendor')->getDefaultAttributeSetId();
            $vendor = Mage::getModel('sandy_marketplace/vendor')
                ->setData((array)$data)
                ->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        } catch (Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        return $vendor->getId();
    }

    /**
     * Change existing vendor information
     *
     * @access public
     * @param int $vendorId
     * @param array $data
     * @return bool
     * @author Ultimate Module Creator
     */
    public function update($vendorId, $data)
    {
        $vendor = $this->_initVendor($vendorId);
        try {
            $data = (array)$data;
            if (isset($data['additional_attributes']) && is_array($data['additional_attributes'])) {
                foreach ($data['additional_attributes'] as $key=>$value) {
                    $data[$key] = $value;
                }
                unset($data['additional_attributes']);
            }
            $vendor->addData($data);
            $vendor->save();
        }
        catch (Mage_Core_Exception $e) {
            $this->_fault('save_error', $e->getMessage());
        }

        return true;
    }

    /**
     * remove vendor
     *
     * @access public
     * @param int $vendorId
     * @return bool
     * @author Ultimate Module Creator
     */
    public function remove($vendorId)
    {
        $vendor = $this->_initVendor($vendorId);
        try {
            $vendor->delete();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('remove_error', $e->getMessage());
        }
        return true;
    }

    /**
     * get info
     *
     * @access public
     * @param int $vendorId
     * @return array
     * @author Ultimate Module Creator
     */
    public function info($vendorId)
    {
        $result = array();
        $vendor = $this->_initVendor($vendorId);
        $result = $this->_getApiData($vendor);
        //related products
        $result['products'] = array();
        $relatedProductsCollection = $vendor->getSelectedProductsCollection();
        foreach ($relatedProductsCollection as $product) {
            $result['products'][$product->getId()] = $product->getPosition();
        }
        return $result;
    }
    /**
     * Assign product to vendor
     *
     * @access public
     * @param int $vendorId
     * @param int $productId
     * @param int $position
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function assignProduct($vendorId, $productId, $position = null)
    {
        $vendor = $this->_initVendor($vendorId);
        $positions    = array();
        $products     = $vendor->getSelectedProducts();
        foreach ($products as $product) {
            $positions[$product->getId()] = array('position'=>$product->getPosition());
        }
        $product = Mage::getModel('catalog/product')->load($productId);
        if (!$product->getId()) {
            $this->_fault('product_not_exists');
        }
        $positions[$productId]['position'] = $position;
        $vendor->setProductsData($positions);
        try {
            $vendor->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        return true;
    }

    /**
     * remove product from vendor
     *
     * @access public
     * @param int $vendorId
     * @param int $productId
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function unassignProduct($vendorId, $productId)
    {
        $vendor = $this->_initVendor($vendorId);
        $positions    = array();
        $products     = $vendor->getSelectedProducts();
        foreach ($products as $product) {
            $positions[$product->getId()] = array('position'=>$product->getPosition());
        }
        unset($positions[$productId]);
        $vendor->setProductsData($positions);
        try {
            $vendor->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        return true;
    }

    /**
     * Get list of additional attributes which are not in default create/update list
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAdditionalAttributes()
    {
        $entity = Mage::getModel('eav/entity_type')->load('sandy_marketplace_vendor', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId());
        $result = array();
        foreach ($attributes as $attribute) {
            if (!in_array($attribute->getAttributeCode(), $this->_defaultAttributeList)) {
                if ($attribute->getIsGlobal() == Sandy_Marketplace_Model_Attribute::SCOPE_GLOBAL) {
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
     * get data for api
     *
     * @access protected
     * @param Sandy_Marketplace_Model_Vendor $vendor
     * @return array()
     * @author Ultimate Module Creator
     */
    protected function _getApiData(Sandy_Marketplace_Model_Vendor $vendor)
    {
        $data = array();
        $additional = array();
        $additionalAttributes = $this->getAdditionalAttributes();
        $additionalByCode = array();
        foreach ($additionalAttributes as $attribute) {
            $additionalByCode[] = $attribute['code'];
        }
        foreach ($vendor->getData() as $key=>$value) {
            if (!in_array($key, $additionalByCode)) {
                $data[$key] = $value;
            } else {
                $additional[] = array('key'=>$key, 'value'=>$value);
            }
        }
        if (!empty($additional)) {
            $data['additional_attributes'] = $additional;
        }
        return $data;
    }
}
