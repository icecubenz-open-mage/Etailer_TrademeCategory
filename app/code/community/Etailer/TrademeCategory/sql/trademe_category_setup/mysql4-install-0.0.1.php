<?php
$installer = $this;
$installer->startSetup();

$groupName = 'Trade Me Category';
$attributeSetId = $installer->getDefaultAttributeSetId(Mage_Catalog_Model_Category::ENTITY);
$installer->addAttributeGroup(Mage_Catalog_Model_Category::ENTITY, $attributeSetId, $groupName);

$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'trademe_category_name', array(
    'default'          => '',
    'input'            => 'text',
    'type'             => 'varchar',
    'label'            => 'Name',
    'visible'          => true,
    'required'         => false,
    'visible_on_front' => false,
    'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'group'            => $groupName
));

$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'trademe_category_number', array(
  'default'          => '',
  'input'            => 'text',
  'type'             => 'varchar',
  'label'            => 'Number',
  'visible'          => true,
  'required'         => false,
  'visible_on_front' => false,
  'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
  'group'            => $groupName
));

$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'trademe_category_path', array(
  'default'          => '',
  'input'            => 'text',
  'type'             => 'varchar',
  'label'            => 'Path',
  'visible'          => true,
  'required'         => false,
  'visible_on_front' => false,
  'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
  'group'            => $groupName
));

$installer->endSetup();
