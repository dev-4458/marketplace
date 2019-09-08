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
 * Marketplace module install script
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('sandy_marketplace/vendor'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Entity ID'
    )
    ->addColumn(
        'entity_type_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Entity Type ID'
    )
    ->addColumn(
        'attribute_set_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Attribute Set ID'
    )

    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null, array(),
        'Creation Time'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Update Time'
    )
    ->addIndex(
        $this->getIdxName(
            'sandy_marketplace/vendor',
            array('entity_type_id')
        ),
        array('entity_type_id')
    )
    ->addIndex(
        $this->getIdxName(
            'sandy_marketplace/vendor',
            array('attribute_set_id')
        ),
        array('attribute_set_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'sandy_marketplace/vendor',
            'attribute_set_id',
            'eav/attribute_set',
            'attribute_set_id'
        ),
        'attribute_set_id',
        $this->getTable('eav/attribute_set'),
        'attribute_set_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'sandy_marketplace/vendor',
            'entity_type_id',
            'eav/entity_type',
            'entity_type_id'
        ),
        'entity_type_id',
        $this->getTable('eav/entity_type'),
        'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Vendor Table');
$this->getConnection()->createTable($table);

$vendorEav = array();
$vendorEav['int'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length'    => null,
    'comment'   => 'Vendor Datetime Attribute Backend Table'
);

$vendorEav['varchar'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 255,
    'comment'   => 'Vendor Varchar Attribute Backend Table'
);

$vendorEav['text'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => '64k',
    'comment'   => 'Vendor Text Attribute Backend Table'
);

$vendorEav['datetime'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DATETIME,
    'length'    => null,
    'comment'   => 'Vendor Datetime Attribute Backend Table'
);

$vendorEav['decimal'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'length'    => '12,4',
    'comment'   => 'Vendor Datetime Attribute Backend Table'
);

foreach ($vendorEav as $type => $options) {
    $table = $this->getConnection()
        ->newTable($this->getTable(array('sandy_marketplace/vendor', $type)))
        ->addColumn(
            'value_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
            ),
            'Value ID'
        )
        ->addColumn(
            'entity_type_id',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Entity Type ID'
        )
        ->addColumn(
            'attribute_id',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Attribute ID'
        )
        ->addColumn(
            'store_id',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Store ID'
        )
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Entity ID'
        )
        ->addColumn(
            'value',
            $options['type'],
            $options['length'], array(),
            'Value'
        )
        ->addIndex(
            $this->getIdxName(
                array('sandy_marketplace/vendor', $type),
                array('entity_id', 'attribute_id', 'store_id'),
                Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
            ),
            array('entity_id', 'attribute_id', 'store_id'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->addIndex(
            $this->getIdxName(
                array('sandy_marketplace/vendor', $type),
                array('store_id')
            ),
            array('store_id')
        )
        ->addIndex(
            $this->getIdxName(
                array('sandy_marketplace/vendor', $type),
                array('entity_id')
            ),
            array('entity_id')
        )
        ->addIndex(
            $this->getIdxName(
                array('sandy_marketplace/vendor', $type),
                array('attribute_id')
            ),
            array('attribute_id')
        )
        ->addForeignKey(
            $this->getFkName(
                array('sandy_marketplace/vendor', $type),
                'attribute_id',
                'eav/attribute',
                'attribute_id'
            ),
            'attribute_id',
            $this->getTable('eav/attribute'),
            'attribute_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->addForeignKey(
            $this->getFkName(
                array('sandy_marketplace/vendor', $type),
                'entity_id',
                'sandy_marketplace/vendor',
                'entity_id'
            ),
            'entity_id',
            $this->getTable('sandy_marketplace/vendor'),
            'entity_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->addForeignKey(
            $this->getFkName(
                array('sandy_marketplace/vendor', $type),
                'store_id',
                'core/store',
                'store_id'
            ),
            'store_id',
            $this->getTable('core/store'),
            'store_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment($options['comment']);
    $this->getConnection()->createTable($table);
}
$table = $this->getConnection()
    ->newTable($this->getTable('sandy_marketplace/vendor_product'))
    ->addColumn(
        'rel_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Relation ID'
    )
    ->addColumn(
        'vendor_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Vendor ID'
    )
    ->addColumn(
        'product_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Product ID'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'  => false,
            'default'   => '0',
        ),
        'Position'
    )
    ->addIndex(
        $this->getIdxName(
            'sandy_marketplace/vendor_product',
            array('product_id')
        ),
        array('product_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'sandy_marketplace/vendor_product',
            'vendor_id',
            'sandy_marketplace/vendor',
            'entity_id'
        ),
        'vendor_id',
        $this->getTable('sandy_marketplace/vendor'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'sandy_marketplace/vendor_product',
            'product_id',
            'catalog/product',
            'entity_id'
        ),
        'product_id',
        $this->getTable('catalog/product'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'sandy_marketplace/vendor_product',
            array('vendor_id', 'product_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('vendor_id', 'product_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Vendor to Product Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('sandy_marketplace/vendor_comment'))
    ->addColumn(
        'comment_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Vendor Comment ID'
    )
    ->addColumn(
        'vendor_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array('nullable' => false),
        'Vendor ID'
    )
    ->addColumn(
        'title',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        255,
        array('nullable' => false),
        'Comment Title'
    )
    ->addColumn(
        'comment',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        '64k',
        array('nullable' => false),
        'Comment'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array('nullable' => false),
        'Comment status'
    )
    ->addColumn(
        'customer_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array('nullable' => true),
        'Customer id'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        255,
        array('nullable' => false),
        'Customer name'
    )
    ->addColumn(
        'email',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        255,
        array('nullable' => false),
        'Customer email'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Vendor Comment Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Vendor Comment Creation Time'
    )
    ->addForeignKey(
        $this->getFkName(
            'sandy_marketplace/vendor_comment',
            'vendor_id',
            'sandy_marketplace/vendor',
            'entity_id'
        ),
        'vendor_id',
        $this->getTable('sandy_marketplace/vendor'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'sandy_marketplace/vendor_comment',
            'customer_id',
            'customer/entity',
            'entity_id'
        ),
        'customer_id',
        $this->getTable('customer/entity'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Vendor Comments Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('sandy_marketplace/vendor_comment_store'))
    ->addColumn(
        'comment_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable'  => false,
            'primary'   => true,
        ),
        'Comment ID'
    )
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Store ID'
    )
    ->addIndex(
        $this->getIdxName(
            'sandy_marketplace/vendor_comment_store',
            array('store_id')
        ),
        array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'sandy_marketplace/vendor_comment_store',
            'comment_id',
            'sandy_marketplace/vendor_comment',
            'comment_id'
        ),
        'comment_id',
        $this->getTable('sandy_marketplace/vendor_comment'),
        'comment_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'sandy_marketplace/vendor_comment_store',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $this->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Vendors Comments To Store Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('sandy_marketplace/eav_attribute'))
    ->addColumn(
        'attribute_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Attribute ID'
    )
    ->addColumn(
        'is_global',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Attribute scope'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Attribute position'
    )
    ->addColumn(
        'is_wysiwyg_enabled',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Attribute uses WYSIWYG'
    )
    ->addColumn(
        'is_visible',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Attribute is visible'
    )
    ->setComment('Marketplace attribute table');
$this->getConnection()->createTable($table);

$this->installEntities();
$this->addAttribute(
    'catalog_product',
    'id_vendor',
    array(
        'group'             => 'General',
        'backend'           => '',
        'frontend'          => '',
        'class'             => '',
        'default'           => '',
        'label'             => 'Vendor',
        'input'             => 'select',
        'type'              => 'int',
        'source'            => 'sandy_marketplace/vendor_source',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'is_visible'        => 1,
        'required'          => 0,
        'searchable'        => 0,
        'filterable'        => 0,
        'unique'            => 0,
        'comparable'        => 0,
        'visible_on_front'  => 0,
        'user_defined'      => 1,
    )
);

$this->endSetup();
