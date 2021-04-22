<?php
$installer = $this;
$installer->startSetup();

$groupName = 'Trade Me Category';

$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'trademe_category_is_leaf', array(
  'default_value_yesno' => '0',
  'input'               => 'select',
  'type'                => 'int',
  'label'               => 'Is Leaf?',
  'comment'             => 'i.e. has no children',
  'source'              => 'eav/entity_attribute_source_boolean',
  'visible'             => true,
  'required'            => false,
  'visible_on_front'    => false,
  'global'              => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
  'group'               => $groupName
));

$installer->endSetup();
