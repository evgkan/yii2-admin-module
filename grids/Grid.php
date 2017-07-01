<?php


namespace asdfstudio\admin\grids;


use Yii;
use yii\grid\GridView;

class Grid extends GridView {

    /**
     * @inheritdoc
     */
    public function init() {
        if (empty($this->columns)) {
            $this->columns = $this->columns();
        }
        if (!isset($this->columns['actions'])) {
            $this->columns['actions'] = ['class' => 'asdfstudio\admin\grids\ActionColumn'];
        }
        parent::init();
    }

    /**
     * List of grid columns
     *
     * @return array
     */
    public function columns()
    {
        $columns = [];
        $models  = $this->dataProvider->getModels();
        $model   = reset($models);
        if (is_array($model) || is_object($model)) {
            foreach ($model as $name => $value) {
                $columns[] = $name;
            }
        }
        return $columns;
    }
}
