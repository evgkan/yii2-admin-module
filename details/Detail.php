<?php


namespace asdfstudio\admin\details;


use Closure;
use yii\widgets\DetailView;

class Detail extends DetailView {

    /**
     * @inheritdoc
     */
    protected function renderAttribute($attribute, $index) {
        if ($attribute['value'] instanceof Closure) {
            $attribute['value'] = call_user_func($attribute['value'], $this->model, $index);
        }
        return parent::renderAttribute($attribute, $index);
    }
}
