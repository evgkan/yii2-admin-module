<?php

namespace asdfstudio\admin\models\menu;


use yii\base\Object;

/**
 * Class Category
 * @package asdfstudio\admin\models\menu
 */
class Category extends Object
{
    use ItemsCollectionTrait;

    /**
     * @var Category label
     */
    public $label;

    /**
     * Render category into array
     * @return array
     */
    public function toArray()
    {
        $res = ['label' => $this->label, 'items' => []];

        foreach ($this->items as $item) {
            $res['items'][] = $item->toArray();
        }

        return $res;
    }
}
