<?php

use app\components\PermissionHelper as P;
use app\widgets\ModalWindow\ModalWindow;
use leandrogehlen\treegrid\TreeGrid;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\hr\models\HrDepartmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$confirmDeleteMessage = Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?');

$this->title = Yii::t('app', 'Hr Departments');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="toquv-departments-index">
        <div class="row">
            <div class="col-lg-12">
                    <span class="pull-left">
                        <?= Html::button('<i class="fa fa-plus"></i> ',
                            ['value' => Url::to(['save']), 'class' => 'create-dialog btn btn-sm btn-primary']) ?>
                    </span>
            </div>
            <br>
            <br>
        </div>
        <div class="row">
            <?php Pjax::begin(['id' => 'department_pjax']); ?>
            <div class="col-md-4 right-block">
                <?php
                echo TreeGrid::widget([
                    'id' => 'tree',
                    'dataProvider' => $dataProvider,
                    'options' => [
                        'class' => "table",
                    ],
                    'keyColumnName' => 'id',
                    'showOnEmpty' => false,
                    'parentColumnName' => 'parent',
                    'showHeader' => false,
                    'rowOptions' => function ($model, $key, $index, $grid) {
                        $parent = \app\modules\hr\models\HrDepartments::getList($model->id);
                        return [
                            'onclick' => "getMusteriAddress({$model->id});",
                            'data-parent-id' => $model->id
                        ];
                    },
                    'pluginOptions' => [
                        'initialState' => 'collapsed',
                    ],
                    'columns' => [
                        [
                            'attribute' => 'name',
                            'options' => [
                                'style' => 'white-space: nowrap;',
                            ]
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update}{delete}',
                            'visibleButtons' => [
                                'view' => P::can('hr-departments/view'),
                                'update' => P::can('hr-departments/update'),
                                'delete' => P::can('hr-departments/delete')
                            ],
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a('<span class="fa fa-pencil"></span>', $url, [
                                        'title' => Yii::t('app', 'lead-update'),
                                        'data-form-id' => $model->id,
                                        'class' => "update-dialog btn btn-xs btn-primary mr1",
                                        'style' => [
                                            "display" => "none"
                                        ],
                                    ]);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<span class="fa fa-trash"></span>', $url, [
                                        'title' => Yii::t('app', 'lead-delete'),
                                        'class' => "btn btn-xs btn-danger delete-dialog",
                                        'style' => [
                                            "display" => "none"
                                        ],
                                        'data-form-id' => $model->id,
                                    ]);
                                }
                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                if ($action === 'update') {
                                    return "#";
                                }
                                if ($action === 'delete') {
                                    return "#";
                                }
                            }
                        ],
                    ],
                ]); ?>
            </div>
            <div class="col-md-8 left-block">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><?= Yii::t('app', "Shifts") ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?= Yii::t('app', "Equipments") ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?= Yii::t('app', "Products") ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?= Yii::t('app', "Defects") ?></a>
                    </li>
                </ul>
                <br>
                <div id="list-musteri-info">
                        <span class="pull-left">
                            <span class="pull-left">
                                <?= Html::button('<i class="fa fa-plus"></i> ',
                                    ['id' => "add-shifts", 'class' => 'btn-success']) ?>
                            </span>
                        </span>
                            <?php /*Modal::begin([
                                'id' => "toquv-department-musteri-address-modal",
                                'options' => [
                                ],
                                'header' => Yii::t('app', 'Toquv Departments Musteri Address'),
                            ]);
                            Modal::end(); */?>
                    <br>
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th><?= Yii::t('app', "Name") ?></th>
                            <th><?= Yii::t('app', "Start Name") ?></th>
                            <th><?= Yii::t('app', "End Name") ?></th>
                            <th><?= Yii::t('app', "Status") ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="musteri-table-list"></tbody>
                    </table>
                </div>
            </div>
            <?php Pjax::end(); ?>
        </div>
        <?php
        echo ModalWindow::widget([
            'model' => 'hr-departments',
            'modal_id' => 'hr-departments-modal',
            'modal_header' => '<h3>' . Yii::t('app', 'Hr Departments') . '</h3>',
            'active_from_class' => 'customAjaxForm',
            'update_button' => 'update-dialog',
            'create_button' => 'create-dialog',
            'delete_button' => 'delete-dialog',
            'modal_size' => 'modal-md',
            'grid_ajax' => 'department_pjax',
            'confirm_message' => $confirmDeleteMessage
        ]);
        ?>
    </div>
    <div class="modal md"  id="modalView" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

<?php


$this->registerJsVar("toquvDepartmentMusteriAddressUrl", "/" . Yii::$app->language . "/toquv/toquv-department-musteri-address/");
$tempSelectMenuMessage = Yii::t('app', "Avval bo'lim tanlang");
$js = <<<JS
var oldSelectedElementClass;
$("body div header nav a.sidebar-toggle").click();
$('body').delegate('#add-shifts','click', function(e){
       $('#modalView').modal('show');
       let parent = $(document).find("."+oldSelectedElementClass);
        console.log(oldSelectedElementClass);
        if ( oldSelectedElementClass === undefined ) {
            alert("$tempSelectMenuMessage");
        } else {
            $("#toquv-department-musteri-address-modal .modal-body").load(
                toquvDepartmentMusteriAddressUrl + "create", 
                {
                    parent_id: parent.attr("data-parent-id")
                },
                function(){
                    $("#toquv-department-musteri-address-modal").modal("show");
                }
            );
        }
   });
function getMusteriAddress(id){
    $.ajax({
        url: "get-musteri-address",
        type: "post",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(response){
            $(document).find("tr[data-parent-id="+id+"]").addClass("bg-primary");
            $(document).find("tr[data-parent-id="+id+"] td:nth-child(2) a").show();
            $("#musteri-table-list").html();
            let items_count = response.length;
            let items_list = "";
            for (let i=0;i < items_count;i++) {
                items_list+= "<tr>";
                items_list+= "<td>" + response[i].physical_location + "</td>";
                items_list+= "<td>" + response[i].legal_location + "</td>";
                items_list+= "<td>" + response[i].status + "</td>";
                items_list+= "<td>" + response[i].phone + "</td>";
                items_list+= "<td>" + response[i].email + "</td>";
                items_list+= "<td>"+
                    "<div class='btn btn-primary btn-sm' onclick='updateMusteriAddress(" + response[i].id + ", " + id + ");'><i class='fa fa-edit'></i></div>" +
                    "<div class='btn btn-info btn-sm'onclick='viewMusteriAddress(" + response[i].id + ", " + id + ");'><i class='fa fa-eye'></i></div>" +
                    "<div class='btn btn-danger btn-sm'onclick='deleteMusteriAddress(" + response[i].id + ", " + id + ");'><i class='fa fa-trash'></i></div>" +
                    "</td>";
                items_list+= "</tr>";
            }
            $("#musteri-table-list").html(items_list);
        },
        error: function(response){
            console.log(response);
        },
    });
    $(document).find("." + oldSelectedElementClass).removeClass("bg-primary");
    $(document).find("." + oldSelectedElementClass + " td:nth-child(2) a").hide();
    oldSelectedElementClass = $(document).find("tr[data-parent-id='"+id+"']").attr('class');
    oldSelectedElementClass = oldSelectedElementClass.split(" ").join(".");
}

$(document).on('click', '#add-dialog-musteri-address', function(){
    let parent = $(document).find("."+oldSelectedElementClass);
    console.log(oldSelectedElementClass);
    if ( oldSelectedElementClass === undefined ) {
        alert("$tempSelectMenuMessage");
    } else {
        $("#toquv-department-musteri-address-modal .modal-body").load(
            toquvDepartmentMusteriAddressUrl + "create", 
            {
                parent_id: parent.attr("data-parent-id")
            },
            function(){
                $("#toquv-department-musteri-address-modal").modal("show");
            }
        );
    }
});


function updateMusteriAddress(id,parentId){
    let parent = $("."+oldSelectedElementClass  );
    $("#toquv-department-musteri-address-modal .modal-body").load(
        toquvDepartmentMusteriAddressUrl + "update?id=" + id, 
        function(){
            $("#toquv-department-musteri-address-modal").modal("show");
            getMusteriAddress(parentId);
        }
    );
}

function viewMusteriAddress(id,parentId){
    let parent = $("."+oldSelectedElementClass  );
    $("#toquv-department-musteri-address-modal .modal-body").load(
        toquvDepartmentMusteriAddressUrl + "view?id=" + id, 
        function(){
            $("#toquv-department-musteri-address-modal").modal("show");
            getMusteriAddress(parentId);
        }
    );
}

function deleteMusteriAddress(id,parentId){
    if ( confirm("{$confirmDeleteMessage}") ) {
        let parent = $("."+oldSelectedElementClass  );
        $("#toquv-department-musteri-address-modal .modal-body").load(
            toquvDepartmentMusteriAddressUrl + "delete?id="+id,
            {
              id: id
            },
            function(r){
                call_pnotify(r, "");
                $.pjax.reload({container: "#" + grid_ajax});
                console.log(parent);
                getMusteriAddress(parentId);
            }
        );
    }   
}

$("body").on("submit", ".customAjaxFormMusteri", function (e) {
    e.preventDefault();
    let array_model2 = [];
    let model_type2 = "ToquvDepartmentMusteriAddress";
    var self2 = $(this);
    var url2 = self2.attr("action");
    let check2 = true;
    let required2 = self2.find(".customRequired");
    $(required2).each(function (index, value){
        if($(this).val()==0||$(this).val()==null){
            e.preventDefault();
            $(this).css("border-color","red").parents('.form-group').addClass('has-error');
            $(this).focus();
            check2 = false;
        }
    });
    if(check2) {
        $(this).find("button[type=submit]").hide();
        // .attr("disabled", false); Bunda knopka 2 marta bosilsa 2 marta zapros ketyapti
        var data = $(this).serialize();
        $.ajax({
            url: url2,
            data: data,
            type: "POST",
            success: function (response) {
                if (response.status == 0) {
                    $('#toquv-department-musteri-address-modal').modal("hide");
                    call_pnotify('success', success_message);
                    // oldSelectedElementClass = undefined;
                    $.pjax.reload({container: "#" + grid_ajax});
                    getMusteriAddress($(document).find("#toquvdepartmentmusteriaddress-toquv_department_id").val());
                } else {
                    let tekst2 = (response.message) ? response.message : fail_message;
                    $.each(response.errors, function (key, val) {
                        self2.find(".field-" + model_type2.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).addClass("has-error");
                        console.log(".field-" + model_type2.toLowerCase().replace(/[_\W]+/g, "") + "-" + key);
                        self2.find(".field-" + model_type2.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).find(".help-block").html(val);

                        if (array_model2.length > 0) {
                            array_model2.forEach(function (index, value) {
                                self2.find(".field-" + index.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).addClass("has-error");
                                self2.find(".field-" + index.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).find(".help-block").html(val);
                            });
                        }
                    });

                    self2.find("button[type=submit]").show();
                    //.attr("disabled", false);
                    call_pnotify('fail', tekst2);
                }
            }
        });
    }else{
        call_pnotify('fail', "Barcha maydonlar to'ldirilmagan");
    }
});

JS;

$this->registerJs($js, View::POS_END);

$css = <<<CSS
.right-block {
    overflow-x: auto;
    /*width:100%;*/
    float: left;
}
.left-block{
   float: right;
}

#tree tr td {
    white-space: nowrap;
}
#tree tr td:nth-child(2) {
    width:70px
}

#tree .update-dialog,
#tree .delete-dialog {
    display: none;
}

#tree .bg-primary .update-dialog,
#tree .bg-primary .delete-dialog {
    display: inline-block;
}

CSS;
$this->registerCss($css);
?>