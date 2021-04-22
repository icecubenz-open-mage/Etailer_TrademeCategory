<?php

class Etailer_TrademeCategory_Block_Catalog_Product_Edit_Tab_Categories extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
{
    /**
     * Returns array with configuration of current node
     *
     * @param Varien_Data_Tree_Node $node
     * @param int                   $level How deep is the node in the tree
     * @return array
     */
    protected function _getNodeJson($node, $level = 1)
    {
        $item = parent::_getNodeJson($node, $level);

        if ($this->_isParentSelectedCategory($node)) {
            $item['expanded'] = true;
        }

        if (in_array($node->getId(), $this->getCategoryIds())) {
            $item['checked'] = true;
        }

        $category = Mage::getModel('catalog/category')->load($node->getEntityId());
        if ($this->isReadonly() ||
            (bool) $category ->getData('trademe_category_number') && !$category ->getData('trademe_category_is_leaf')
        ) {
            $item['disabled'] = true;
        }

        return $item;
    }
}
