<?php

use kartik\tree\Module;
use app\modules\hr\models\HrOrganisations;
use kartik\tree\TreeView;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Hr Organisations');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="hr-departments-index">

    <?= TreeView::widget([
        // single query fetch to render the tree
        // use the Product model you have in the previous step
        'query' => HrOrganisations::getOrganisationsList(),
        'headingOptions' => ['label' => Yii::t('app','Departments')],
        'fontAwesome' => true,     // optional
        'isAdmin' => false,         // optional (toggle to enable admin mode)
        'displayValue' => 1,        // initial display value
        'softDelete' => false,       // defaults to true
        'cacheSettings' => [
            'enableCache' => true   // defaults to true
        ],
        'showIDAttribute' => false, // if set true show id attribute
        'topRootAsHeading' => true,
        'rootOptions' => ['label'=>'<span class="text-primary">'.Yii::t('app', 'Organizations and departments').'</span>'],
        'iconEditSettings'=> [
            'show' => 'none',
        ],
        /*'nodeAddlViews' => [
            Module::VIEW_PART_2 => '@app/modules/hr/views/hr-departments/_view-part2',
        ],*/
        'nodeActions' => [
            Module::NODE_MANAGE => Url::to(['/hr/hr-organisations/manage']),
            Module::NODE_SAVE => Url::to(['/hr/hr-organisations/save']),
            Module::NODE_REMOVE => Url::to(['/hr/hr-organisations/remove']),
            Module::NODE_MOVE => Url::to(['/hr/hr-organisations/move']),
        ],
        'nodeView' => '@kvtree/views/_form',
        'clientMessages' => [
            'invalidCreateNode' => Yii::t('app', "Could not create department or organization"),
            'emptyNode' => Yii::t('app', '(New)'),
            'removeNode' => Yii::t('app', 'Siz rostdan ham bu bo\'lim yoki tashkilotni o\'chirmoqchimisiz?'),
            'nodeRemoved' => Yii::t('app', "Bo'lim yoki tashkilot o'chirildi."),
            'nodeRemoveError' => Yii::t('app', "Xatolik yuz berdi!"),
            'nodeNewMove' => Yii::t('app', 'Cannot move this node as the node details are not saved yet.'),
            'nodeTop' => Yii::t('app', 'Already at top-most node in the hierarchy.'),
            'nodeBottom' => Yii::t('app', 'Already at bottom-most node in the hierarchy.'),
            'nodeLeft' => Yii::t('app', 'Already at left-most node in the hierarchy.'),
            'nodeRight' => Yii::t('app', 'Already at right-most node in the hierarchy.'),
            'emptyNodeRemoved' => Yii::t('app', 'The untitled node was removed.'),
            'selectNode' => Yii::t('app', 'Select a node by clicking on one of the tree items.'),
        ],
        'emptyNodeMsg' => Yii::t('app', 'Select afrom the toolbar to view the organization or department'),
        'toolbar' => [
            TreeView::BTN_CREATE => [
                'icon' => 'plus',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', "Bo'lim qo'shish"), 'disabled' => true]
            ],
            TreeView::BTN_CREATE_ROOT => [
                'icon' => 'building-o',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', "Tashkilot qo'shish")]
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
                'icon' => 'refresh',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Refresh')],
                'url' => Yii::$app->request->url
            ],
        ],
        'searchOptions' => [
            'class' => 'form-control input-sm',
            'placeholder' => 'Qidiruv...'
        ]
    ]); ?>

</div>
