<?php

use app\modules\hr\models\HrDepartmentsInfo;
use app\modules\hr\models\HrOrganizationInfo;
use kartik\form\ActiveForm;
use kartik\tree\Module;
use kartik\tree\TreeView;
use kartik\tree\models\Tree;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var Tree $node
 * @var \app\modules\hr\models\HrOrganisations $infoModel
 * @var \app\modules\hr\models\UploadForm $uploadForm
 * @var ActiveForm $form
 * @var array $formOptions
 * @var string $keyAttribute
 * @var string $nameAttribute
 * @var string $iconAttribute
 * @var string $iconTypeAttribute
 * @var array|string $iconsList
 * @var string $formAction
 * @var array $breadcrumbs
 * @var array $nodeAddlViews
 * @var mixed $currUrl
 * @var boolean $isAdmin
 * @var boolean $showIDAttribute
 * @var boolean $showNameAttribute
 * @var boolean $showFormButtons
 * @var boolean $allowNewRoots
 * @var string $nodeSelected
 * @var string $nodeTitle
 * @var string $nodeTitlePlural
 * @var array $params
 * @var string $keyField
 * @var string $nodeView
 * @var string $nodeAddlViews
 * @var array $nodeViewButtonLabels
 * @var string $noNodesMessage
 * @var boolean $softDelete
 * @var string $modelClass
 * @var string $defaultBtnCss
 * @var string $treeManageHash
 * @var string $treeSaveHash
 * @var string $treeRemoveHash
 * @var string $treeMoveHash
 * @var string $hideCssClass
 */

?>

<div class="row">
    <div class="col-sm-12">
<!--        --><?php //echo $form->field($node, 'token')->textInput(); ?>
    </div>
</div>

<?php if ($infoModel !== null): ?>
    <?php /*if ($infoModel instanceof HrDepartmentsInfo): */?><!--
        <div class="row">
            <div class="col-sm-4">
                <?/*= $form->field($infoModel, 'tel')->textInput() */?>
            </div>
            <div class="col-sm-4">
                <?/*= $form->field($infoModel, 'address')->textarea(['rows' => 4]) */?>
            </div>
        </div>
    --><?php /*elseif ($infoModel instanceof HrOrganizationInfo): */?>
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($infoModel, 'tel')->textInput() ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($infoModel, 'address')->textarea(['rows' => 4]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($infoModel, 'add_info')->textarea(['rows' => 4]) ?>
            </div>
        </div>
            <!--<div class="row">
                <div class="col-sm-12">
                    <?/*= $form->field($uploadForm, 'file')->widget(FileInput::classname(), [
//                'options' => ['accept' => 'image/*'],
                    ]); */?>
                </div>
            </div>-->
<?php endif; ?>
