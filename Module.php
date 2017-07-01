<?php

namespace asdfstudio\admin;

use asdfstudio\admin\base\Entity;
use asdfstudio\admin\models\menu\Menu;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;

class Module extends \yii\base\Module implements BootstrapInterface
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'asdfstudio\admin\controllers';

    /**
     * URL prefix
     *
     * @var string
     */
    public $urlPrefix = '/admin';

    /**
     * Registered models
     *
     * @var array
     */
    public $entities = [];

    /**
     * Contains Class => Id for fast search
     *
     * @var array
     */
    public $entitiesClasses = [];

    /**
     * Asset bundle
     *
     * @var string
     */
    public $assetBundle = 'asdfstudio\admin\AdminAsset';

    /**
     * Top menu navigation
     * Example configuration
     *
     * ```php
     *  [
     *      [
     *          'label' => 'First item',
     *          'url' => ['index', 'param' => 'value']
     *      ],
     *      [
     *          'label' => 'Dropdown item',
     *          'items' => [
     *              ['label' => 'First child', 'url' => ['first']],
     *              ['label' => 'Second child', 'url' => ['second']],
     *          ]
     *      ]
     *  ]
     *
     * @var Menu
     */
    public $menu;

    /**
     * Sidebar menu navigation
     * Example configuration
     *
     * ```php
     *  [
     *      [
     *          'label' => 'First item',
     *          'url' => ['index', 'param' => 'value']
     *      ],
     *      [
     *          'label' => 'Dropdown item',
     *          'items' => [
     *              ['label' => 'First child', 'url' => ['first']],
     *              ['label' => 'Second child', 'url' => ['second']],
     *          ]
     *      ]
     *  ]
     * ```
     *
     * @var Menu
     */
    public $sidebar;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setViewPath(dirname(__FILE__) . '/views');

        $this->menu    = new Menu();
        $this->sidebar = new Menu();
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerRoutes([
            $this->urlPrefix . ''                                                                   => 'admin/admin/index',
            $this->urlPrefix . '/manage/<entity:[\w\d\._-]+>'                                       => 'admin/manage/index',
            $this->urlPrefix . '/manage/<entity:[\w\d\._-]+>/<action:[\w\d\._-]+>'                  => 'admin/manage/<action>',
            $this->urlPrefix . '/manage/<entity:[\w\d\._-]+>/<id:[\w\d\._-]+>'                      => 'admin/manage/view',
            $this->urlPrefix . '/manage/<entity:[\w\d\._-]+>/<id:[\w\d\._-]+>/<action:[\w\d\._-]+>' => 'admin/manage/<action>',

        ]);

        $this->registerTranslations();
    }

    /**
     * Register admin module routes
     *
     * @param array $rules
     */
    public function registerRoutes($rules)
    {
        Yii::$app->getUrlManager()->addRules($rules);
    }

    /**
     * Register model in admin dashboard
     *
     * @param string|Entity $entity
     * @param bool          $forceRegister
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function registerEntity($entity, $forceRegister = false)
    {
        $id = call_user_func([$entity, 'slug']);

        if (isset($this->entities[$id]) && !$forceRegister) {
            throw new InvalidConfigException(sprintf('Item with id "%s" already registered', $id));
        }

        $this->entities[$id] = new $entity([
            'id' => $id,
        ]);

        $this->entitiesClasses[$entity] = $id;
    }

    /**
     * Register controller in module. Needed for creating custom pages
     *
     * @param string $id
     * @param string $controller
     */
    public function registerController($id, $controller)
    {
        $this->controllerMap[$id] = [
            'class' => $controller,
        ];
    }

    /**
     * Register translations
     */
    protected function registerTranslations()
    {
        $i18n = Yii::$app->i18n;

        $i18n->translations['admin'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath'       => '@vendor/sgdot/yii2-admin-module/messages',
        ];
    }

    public function canRead()
    {
        return true;
    }

}
