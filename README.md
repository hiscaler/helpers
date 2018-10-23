# About
PHP Helper Class
- ArrayHelper
    * removeEmpty(&$arr, $trim = true, $charList = null)
    * getColumn($array, $name)
    * toHashmap($arr, $keyField, $valueField = null)
    * groupBy($arr, $key)
    * toTree($arr, $key, $parentKey = 'parent_id', $childrenKey = 'children', &$refs = null)
    * treeToArray($tree, $childrenKey = 'children')
    * sortByCol($array, $keyname, $dir = SORT_ASC)
    * sortByMultiCols($rowset, $args)
    * arrayDiffAssocRecursive($a, $b)
- DatetimeHelper
- IdentityCardHelper
- ImageHelper
- IpLocationHelper
- StringHelper
- TreeFormatHelper
- UrlHelper
- UtilHelper

## Install
If you use composer, input follow command in you CLI.

```php
composer require "yadjet/helpers:dev-master" 
```