<?php

class Etailer_TrademeCategory_Model_Observer
{
    public function lockCategories(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $category = $event->getCategory();

        if ((bool) $category->getData('trademe_category_number') && !$category->getData('trademe_category_is_leaf')) {
          $category->setProductsReadonly(true);
        }

      return $this;
    }
}
