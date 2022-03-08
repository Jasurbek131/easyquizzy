<?php

use app\components\CustomTreeView\CustomTreeView;
use app\modules\hr\models\HrDepartments;
use kartik\tree\Module;
use kartik\tree\TreeView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $query HrDepartments */

$this->title = Yii::t('app', 'Tashkilot tuzilmasi');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>

<div>
    <?php echo CustomTreeView::widget([
        'query' => $query,
        'headingOptions' => ['label' => Yii::t('app', $this->title)],
        'fontAwesome' => true,     // optional
        'isAdmin' => false,         // optional (toggle to enable admin mode)
        'displayValue' => 1,        // initial display value
        'softDelete' => false,       // defaults to true
        'cacheSettings' => [
            'enableCache' => true   // defaults to true
        ],
        'showFormButtons' => false,
        'showIDAttribute' => false, // if set true show id attribute
        'emptyNodeMsg' => "Ma'lumot topilmadi",
        'nodeActions' => [
            Module::NODE_MANAGE => Url::to(['/treemanager/node/manage']),
            Module::NODE_SAVE => Url::to(['/hr/hr-departments/save']),
            Module::NODE_REMOVE => Url::to(['/treemanager/node/remove']),
            Module::NODE_MOVE => Url::to(['/treemanager/node/move']),
        ],
        'nodeAddlViews' => [
            Module::VIEW_PART_2 => '@app/modules/hr/views/hr-departments/_view-part2',
        ],
        'nodeView' => '@app/components/CustomTreeView/views/view',
        'rootOptions' => ['label' => '<span class="text-primary">' . Yii::t('app', 'Sektorlar') . '</span>'],
        'topRootAsHeading' => true,
        'iconEditSettings' => [
            'show' => 'none',
        ],
        'defaultChildNodeIcon' => '<i class="fa fa-square"></i>',
        'defaultParentNodeOpenIcon' => '<i class="fa fa-square"></i>',
        'clientMessages' => [
            'invalidCreateNode' => Yii::t('app', 'Cannot create node. Parent node is not saved or is invalid.'),
            'emptyNode' => Yii::t('app', '(new)'),
            'removeNode' => Yii::t('app', 'Are you sure you want to remove this node?'),
            'nodeRemoved' => Yii::t('app', 'The node was removed successfully.'),
            'nodeRemoveError' => Yii::t('app', 'Error while removing the node. Please try again later.'),
            'nodeNewMove' => Yii::t('app', 'Cannot move this node as the node details are not saved yet.'),
            'nodeTop' => Yii::t('app', 'Already at top-most node in the hierarchy.'),
            'nodeBottom' => Yii::t('app', 'Already at bottom-most node in the hierarchy.'),
            'nodeLeft' => Yii::t('app', 'Already at left-most node in the hierarchy.'),
            'nodeRight' => Yii::t('app', 'Already at right-most node in the hierarchy.'),
            'emptyNodeRemoved' => Yii::t('app', 'The untitled node was removed.'),
            'selectNode' => Yii::t('app', 'Select a node by clicking on one of the tree items.'),
        ],
        'toolbar' => [
            TreeView::BTN_CREATE => [
                'icon' => 'plus',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', "Sektor qo'shish"), 'disabled' => true]
            ],
            TreeView::BTN_CREATE_ROOT => [
                'icon' => 'building',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', "Create"), 'disabled' => false]
            ],
            TreeView::BTN_REMOVE => [
                'icon' => 'trash',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Delete'), 'disabled' => true]
            ],
            TreeView::BTN_SEPARATOR,
            TreeView::BTN_MOVE_UP => [
                'icon' => 'arrow-up',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Move Up'), 'disabled' => true]
            ],
            TreeView::BTN_MOVE_DOWN => [
                'icon' => 'arrow-down',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Move Down'), 'disabled' => true]
            ],
            TreeView::BTN_MOVE_LEFT => [
                'icon' => 'arrow-left',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Move Left'), 'disabled' => true]
            ],
            TreeView::BTN_MOVE_RIGHT => [
                'icon' => 'arrow-right',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Move Right'), 'disabled' => true]
            ],
            TreeView::BTN_SEPARATOR,
            TreeView::BTN_REFRESH => [
                'icon' => 'retweet',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Refresh')],
                'url' => Yii::$app->request->url
            ],
        ],
        'searchOptions' => [
            'class' => 'form-control input-sm',
            'placeholder' => Yii::t("app","Search")
        ]
    ]); ?>

</div>
<?php
$this->registerCss("
    .select2-selection__clear{
        top:0!important
    }
    #department-area-name{
        font-size: 30px;
    }
");
?>
