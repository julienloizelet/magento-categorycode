<?php
$installer = $this;

$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getAttributeGroupId($entityTypeId, $attributeSetId, 'General Information');

/**************************** Attribute Creation **********************************/

$installer->removeAttribute('catalog_category', 'okaeli_category_code');

$installer->addAttribute(
    'catalog_category', 'okaeli_category_code', array(
                        'group'                => 'General Information',
                        'type'              => 'varchar',
                        'backend'           => '',
                        'label'             => 'Okaeli Category Code',
                        'input'             => 'text',
                        'class'             => 'okaeli-category-code',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => true,
                        'default'           => '',
                        'note'              => 'Unique code of category (do not change)',
                        'unique'            => true
    )
);

$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'code',
    '100'
);

$installer->endSetup();
