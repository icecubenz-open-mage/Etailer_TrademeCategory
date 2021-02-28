<?php
$installer = $this;
$installer->startSetup();

$groupName = 'Trade Me Category';

$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'trademe_category_aob', array(
  'default'          => '',
  'input'            => 'select',
  'type'             => 'int',
  'source'           => 'trademe_category/category_attribute_source_areaofbusiness',
  'label'            => 'Area of Business',
  'visible'          => true,
  'required'         => false,
  'visible_on_front' => false,
  'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
  'group'            => $groupName
));

$installer->endSetup();
