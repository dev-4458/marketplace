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
 * Vendor admin grid block
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Adminhtml_Vendor_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('vendorGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Sandy_Marketplace_Block_Adminhtml_Vendor_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('sandy_marketplace/vendor')
            ->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('phone')
            ->addAttributeToSelect('businesstype')
            ->addAttributeToSelect('gender')
            ->addAttributeToSelect('status')
            ->addAttributeToSelect('url_key');
        
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $store = $this->_getStore();
        $collection->joinAttribute(
            'shopurl', 
            'sandy_marketplace_vendor/shopurl', 
            'entity_id', 
            null, 
            'inner', 
            $adminStore
        );
        if ($store->getId()) {
            $collection->joinAttribute(
                'sandy_marketplace_vendor_shopurl', 
                'sandy_marketplace_vendor/shopurl', 
                'entity_id', 
                null, 
                'inner', 
                $store->getId()
            );
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Sandy_Marketplace_Block_Adminhtml_Vendor_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('sandy_marketplace')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'shopurl',
            array(
                'header'    => Mage::helper('sandy_marketplace')->__('Shop URL'),
                'align'     => 'left',
                'index'     => 'shopurl',
            )
        );
        
        if ($this->_getStore()->getId()) {
            $this->addColumn(
                'sandy_marketplace_vendor_shopurl', 
                array(
                    'header'    => Mage::helper('sandy_marketplace')->__('Shop URL in %s', $this->_getStore()->getName()),
                    'align'     => 'left',
                    'index'     => 'sandy_marketplace_vendor_shopurl',
                )
            );
        }

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('sandy_marketplace')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('sandy_marketplace')->__('Enabled'),
                    '0' => Mage::helper('sandy_marketplace')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'name',
            array(
                'header' => Mage::helper('sandy_marketplace')->__('Vendor Name'),
                'index'  => 'name',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'phone',
            array(
                'header' => Mage::helper('sandy_marketplace')->__('Phone'),
                'index'  => 'phone',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'businesstype',
            array(
                'header' => Mage::helper('sandy_marketplace')->__('Business Type'),
                'index'  => 'businesstype',
                'type'  => 'options',
                'options' => Mage::helper('sandy_marketplace')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('sandy_marketplace_vendor', 'businesstype')->getSource()->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'gender',
            array(
                'header' => Mage::helper('sandy_marketplace')->__('Gender'),
                'index'  => 'gender',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('sandy_marketplace')->__('Yes'),
                    '0' => Mage::helper('sandy_marketplace')->__('No'),
                )

            )
        );
        $this->addColumn(
            'url_key',
            array(
                'header' => Mage::helper('sandy_marketplace')->__('URL key'),
                'index'  => 'url_key',
            )
        );
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('sandy_marketplace')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('sandy_marketplace')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('sandy_marketplace')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('sandy_marketplace')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('sandy_marketplace')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sandy_marketplace')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('sandy_marketplace')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * get the selected store
     *
     * @access protected
     * @return Mage_Core_Model_Store
     * @author Ultimate Module Creator
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Sandy_Marketplace_Block_Adminhtml_Vendor_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('vendor');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('sandy_marketplace')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('sandy_marketplace')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('sandy_marketplace')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sandy_marketplace')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('sandy_marketplace')->__('Enabled'),
                            '0' => Mage::helper('sandy_marketplace')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'businesstype',
            array(
                'label'      => Mage::helper('sandy_marketplace')->__('Change Business Type'),
                'url'        => $this->getUrl('*/*/massBusinesstype', array('_current'=>true)),
                'additional' => array(
                    'flag_businesstype' => array(
                        'name'   => 'flag_businesstype',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sandy_marketplace')->__('Business Type'),
                        'values' => Mage::getModel('eav/config')->getAttribute('sandy_marketplace_vendor', 'businesstype')
                            ->getSource()->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'gender',
            array(
                'label'      => Mage::helper('sandy_marketplace')->__('Change Gender'),
                'url'        => $this->getUrl('*/*/massGender', array('_current'=>true)),
                'additional' => array(
                    'flag_gender' => array(
                        'name'   => 'flag_gender',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sandy_marketplace')->__('Gender'),
                        'values' => array(
                                '1' => Mage::helper('sandy_marketplace')->__('Yes'),
                                '0' => Mage::helper('sandy_marketplace')->__('No'),
                            )

                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Sandy_Marketplace_Model_Vendor
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
