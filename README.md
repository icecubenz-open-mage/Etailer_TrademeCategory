## Trade Me Category for OpenMage / Magento 1

This extension creates a category attribute for mapping to Trade Me categories.

Adds support for Trade Me Category Name, Number and Path per magento category (configured per store).

Trade Me category details can be found here: https://developer.trademe.co.nz/api-reference/catalogue-methods/retrieve-general-categories/

A shell script is included to create Magento categories from the Trade Me API with these mappings pre-filled.

```shell
$ cd shell
$ php -f trademe_category.php

Create Trade Me categories inside an existing category structure from the Trade Me public API:
https://developer.trademe.co.nz/api-reference/catalogue-methods/retrieve-general-categories/

WARNING: This doesn't de-duplicate. If the categories already exist it will make new ones anyway. URL keys may overlap.

Usage:  php -f trademe_category.php -- [options]

  --catid <id>             Target Magento category ID to create the structure under

  --tmjson <url>           Trade Me JSON url e.g. https://api.trademe.co.nz/v1/Categories.json
                           or https://api.trademe.co.nz/v1/Categories/0187-.json (for a subset)

  --active                 Set category to active? (default: false)

  --menu                   Set category to include in menu? (default: false)

  --exclude                Comma separated list of Trade Me category "Numbers" to exclude
                           (default: 0001-,0350-,5000-)

  help                     This help

```
