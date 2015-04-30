<?php


namespace asdfstudio\admin\grids;


use asdfstudio\admin\base\Entity;
use Yii;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

class Grid extends GridView
{
    /**
     * Current entity
     *
     * @var Entity
     */
    public $entity = null;

    /**
     * @inheritdoc
     */
    public function init() {
        if (empty($this->columns)) {
            $this->columns = $this->columns();
        }
        $this->columns = array_merge(
                $this->columns, 
                $this->actions()
        );
        parent::init();
    }

    /**
     * List of grid columns
     * @return array
     */
    public function columns()
    {
        $columns = [];
        $models = $this->dataProvider->getModels();
        $model = reset($models);
        if (is_array($model) || is_object($model)) {
            foreach ($model as $name => $value) {
                $columns[] = $name;
            }
        }
        return $columns;
    }

    /**
     * List of grid actions
     * @return array
     */
    public function actions()
    {
        $entity = \Yii::$app->getRequest()->getQueryParam('entity', null);
        $primaryKey = $this->entity->primaryKey();
        $template = '';
        if (in_array('view', $this->entity->buttons())) $template .= '{view}';
        if (in_array('update', $this->entity->buttons())) $template .= '{update}';
        if (in_array('delete', $this->entity->buttons())) $template .= '{delete}';
        return [
            [
                'class' => ActionColumn::className(),
                'template' => $template,
                'buttons' => [
                    'view' => function($url, $model, $key) use ($entity, $primaryKey) {
                        return Html::a(
                            Yii::t('admin', 'View'),
                            ['manage/view', 'entity' => $entity, 'id' => $model->{$primaryKey}],
                            ['class' => 'btn btn-primary']
                        );
                    },
                    'update' => function($url, $model, $key) use ($entity, $primaryKey) {
                        return Html::a(
                            Yii::t('admin', 'Edit'),
                            ['manage/update', 'entity' => $entity, 'id' => $model->{$primaryKey}],
                            ['class' => 'btn btn-warning']
                        );
                    },
                    'delete' => function($url, $model, $key) use ($entity, $primaryKey) {
                        return Html::a(
                            Yii::t('admin', 'Delete'),
                            ['manage/delete', 'entity' => $entity, 'id' => $model->{$primaryKey}],
                            ['class' => 'btn btn-danger', 'data' => [
                                'confirm' => Yii::t('admin', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],]
                        );
                    },
                ],
            ]
        ];
    }
}
