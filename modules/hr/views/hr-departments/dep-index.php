<?php

use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\widgets\Pjax;
use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $tree */

$confirmDeleteMessage = Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?');

$this->title = Yii::t('app', 'Hr Organisations and Hr Departments');
$this->params['breadcrumbs'][] = $this->title;
$create = Yii::t('app', 'Create');
?>
    <style>
        .btn-outline-info:hover > i, .btn-outline-success:hover > i, .btn-outline-danger:hover > i, .btn-outline-primary:hover > i {
            color: #ffffff;
        !important;
        }

        .btn-outline-info:hover {
            background-color: #8f79fc;
        }

        .btn-outline-success:hover {
            background-color: #1bc5bd;
        }

        .btn-outline-danger:hover {
            background-color: #f16767;
        }

        .btn-outline-primary:hover {
            background-color: #3699ff;
        }

        .btn-sm-button {
            background-color: #ffffff;
        !important;
            line-height: 12px;
            border-radius: 4px;
        }

        .btn-outline-info > i {
            color: #8950fc;
        }

        .btn-outline-success > i {
            color: #1bc5bd;
        }

        .btn-outline-primary > i {
            color: #3699ff;
        }

        .btn-outline-danger > i {
            color: #f16767;
        }

        .btn-outline-primary {
            border: 1px solid #3699ff;
        }

        .btn-outline-info {
            border: 1px solid #8950fc;
        }
    </style>

<?php Pjax::begin(['id' => 'department_pjax']); ?>

    <div class="row">
        <div class="col-md-3">
            <div class="card" style="border: 1px solid green;overflow-x: scroll; min-height: 75vh">
                <div class="card-body">
                    <div class="knopka text-left">
                        <br>
                        <button class="btn btn-xs btn-outline-info department-create"
                                style="border: 1px solid #8950fc;" disabled="disabled"
                                href="<?php echo Url::to(['hr-departments/create']) ?>" ><i class="fa fa-plus"></i></button>

                        <?php if(Yii::$app->user->can("super-admin")):?>
                            <button class="btn btn-xs btn-outline-success tree-create" style="border: 1px solid #1bc5bd;"
                                href="<?php echo Url::to(['hr-departments/create']) ?>"><i
                                class="fa fa-tree"></i></button>
                        <?php endif;?>

                        <button class="btn btn-xs btn-outline-danger delete-tree disabled-danger-delete"
                                disabled="disabled"
                                href="<?php echo Url::to(['hr-departments/delete']) ?>"><i class="fa fa-trash"></i>
                        </button>
                        <button class="btn btn-xs btn-outline-primary department-update"
                                disabled="disabled"
                                style="border: 1px solid #3699ff;" href="<?php echo Url::to(['hr-departments/update']); ?>">
                            <i class="fa fa-pencil-alt"></i></button>
                        <button class="btn btn-xs btn-outline-warning"
                                style="border: 1px solid #ffaf36;" href="#" onclick="window.location.reload()">
                            <i class="fa fa-retweet"></i></button>
                    </div>

                    <br>
                    <div id="kt_tree_1" class="tree-demo">
                        <?php echo $tree ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card" style="border: 1px solid green;overflow-x: scroll; min-height: 75vh">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tap-address-one" data-toggle="tab" href="#shift" role="tab"
                               aria-controls="profile" aria-selected="false"><?php echo Yii::t('app', 'Shifts') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="equipments-tab" data-toggle="tab" href="#equipment" role="tab"
                               aria-controls="equipments" aria-selected="false"><?php echo Yii::t('app', 'Equipments') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="products-tab" data-toggle="tab" href="#product" role="tab"
                               aria-controls="products" aria-selected="false"><?php echo Yii::t('app', 'Products') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="defects-tab" data-toggle="tab" href="#defect" role="tab"
                               aria-controls="defects" aria-selected="false"><?php echo Yii::t('app', 'Defects') ?></a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active show" id="shift" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <button href="<?php echo Url::to(['hr-department-rel-shifts/create']) ?>"
                                            class="btn btn-xs-button  btn-outline-success text-sm shifts-create"
                                            style="border: 1px solid #1bc84d;padding: 3px 6px; "><i class="fa fa-plus" style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
                                    </button>
                                </div>
                                <div class="col-md-8">&nbsp;</div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover attorney-table table-bordered"
                                           id="table-shifts">
                                        <thead>
                                        <tr>
                                            <th class=""><?php echo Yii::t('app', "№") ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Hr Department') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Shift Name') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Start Time') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'End Time') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Status') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Permission') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="equipment" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <button href="<?php echo Url::to(['hr-department-rel-equipment/create']) ?>"
                                            class="btn btn-xs-button  btn-outline-success text-sm equipments-create"
                                            style="border: 1px solid #1bc84d;padding: 3px 6px; "><i class="fa fa-plus" style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
                                    </button>
                                </div>
                                <div class="col-md-8">&nbsp;</div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover attorney-table table-bordered"
                                           id="table-equipments">
                                        <thead>
                                        <tr>
                                            <th class=""><?php echo Yii::t('app', "№") ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Hr Department') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Equipment Name') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Equipment Type') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Status') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Permission') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="product" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <button href="<?php echo Url::to(['hr-department-rel-product/create']) ?>"
                                            class="btn btn-xs-button  btn-outline-success text-sm products-create"
                                            style="border: 1px solid #1bc84d;padding: 3px 6px; "><i class="fa fa-plus" style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
                                    </button>
                                </div>
                                <div class="col-md-8">&nbsp;</div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover attorney-table table-bordered"
                                           id="table-products">
                                        <thead>
                                        <tr>
                                            <th class=""><?php echo Yii::t('app', "№") ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Hr Department') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Product Name') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Part Number') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Status') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Permission') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="defect" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <button href="<?php echo Url::to(['hr-department-rel-defects/create']) ?>"
                                            class="btn btn-xs-button  btn-outline-success text-sm defects-create"
                                            style="border: 1px solid #1bc84d;padding: 3px 6px; "><i class="fa fa-plus" style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
                                    </button>
                                </div>
                                <div class="col-md-8">&nbsp;</div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover attorney-table table-bordered"
                                           id="table-defects">
                                        <thead>
                                        <tr>
                                            <th class=""><?php echo Yii::t('app', "№") ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Hr Department') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Defect Name') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Defect Type') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Status') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Permission') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php Pjax::end(); ?>
    <!-- Modal -->
    <div class="modal fade " id="view-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

<?php /*= \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'toquv-department-musteri-address',
    'crud_name' => 'toquv-department-musteri-address',
    'modal_id' => 'toquv-department-musteri-address-modal',
    'modal_header' => '<h3>' . Yii::t('app', 'Toquv Department Musteri Address') . '</h3>',
    'active_from_class' => 'customAjaxFormMusteri',
    'update_button' => 'address-update-dialog',
    'create_button' => 'shifts-create',
    'view_button' => 'address-view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'department_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); */?>

<?php

$lang = Yii::$app->language;
//$urlToGetItems = Url::to(['department/get-items-ajax']);
$deb = Yii::$app->request->get('deb') ?? null;
$this->registerJsVar('message_active', Yii::t('app', "Active"));
$this->registerJsVar('message_inactive', Yii::t('app', "Inactive"));
$this->registerJsVar('message_delete_title', Yii::t('app', "Ishonchingiz komilmi?"));
$this->registerJsVar('message_deleted', Yii::t('app', "O'chilrildi"));
$this->registerJsVar('message_yes', Yii::t('app', "Ok"));
$this->registerJsVar('message_no', Yii::t('app', "Cancel"));
$this->registerJsVar('notSelectedDepartment', Yii::t('app', 'Bo\'lim tanlanmagan'));
$this->registerJsVar('nameLang', 'name_' . $lang);
$this->registerJsVar('modalHeaderNameDepartment', Yii::t('app', 'Hr Departments'));
$this->registerJsVar('modalHeaderNameShifts', Yii::t('app', 'Create Shifts'));
$this->registerJsVar('modalHeaderNameProducts', Yii::t('app', 'Create Products'));
$urlToGetItems = Url::to(['hr-departments/get-items-ajax']);
$urlShiftUpdate = Url::to(['hr-department-rel-shifts/update']);
$urlShiftDelete = Url::to(['hr-department-rel-shifts/delete']);
$urlEquipmentUpdate = Url::to(['hr-department-rel-equipment/update']);
$urlEquipmentDelete = Url::to(['hr-department-rel-equipment/delete']);
$urlProductUpdate = Url::to(['hr-department-rel-product/update']);
$urlProductDelete = Url::to(['hr-department-rel-product/delete']);
$urlDefectUpdate = Url::to(['hr-department-rel-defects/update']);
$urlDefectDelete = Url::to(['hr-department-rel-defects/delete']);
$this->registerJsVar('urlShiftUpdate',$urlShiftUpdate);
$this->registerJsVar('urlShiftDelete',$urlShiftDelete);
$this->registerJsVar('urlEquipmentUpdate',$urlEquipmentUpdate);
$this->registerJsVar('urlEquipmentDelete',$urlEquipmentDelete);
$this->registerJsVar('urlProductUpdate',$urlProductUpdate);
$this->registerJsVar('urlProductDelete',$urlProductDelete);
$this->registerJsVar('urlDefectsUpdate',$urlDefectUpdate);
$this->registerJsVar('urlDefectsDelete',$urlDefectDelete);
$this->registerJsVar('tempSelectMenuMessage', Yii::t('app', "Avval bo'lim tanlang"));
$this->registerJsVar('urlUserGroup', Url::to(['/admin/users-group']));
$this->registerJsVar('lang', $lang);
?>
<?php
$css = <<<CSS
    thead{
        background: #F9F9F9;
    }
    tr{
        margin-left: 10px;
    }
    table {
        border: 1px solid #F9F9F9!important;
    }
.btn > i{
  padding: 3px;
  font-size: 20px;
}

.disabled-danger-delete{
    border: 1px solid #cd868e; 
    cursor: default;!important;
}
.disabled-danger-delete >i {
    color: #cd868e;
     padding: 3px;
}
.btn-icon{
    width: 25px;   
     align-items: center;
    padding: 0px;

    font-size: 10px;
    background: #ffffff;
    margin-left: 3px;
}
.btn-icon>i{
    font-size: 14px;
}
.shifts-create{
    margin:5px 0 5px 0;
}
.equipments-create{
   margin:5px 0 5px 0;
}
.products-create{
  margin:5px 0 5px 0;
}
.defects-create{
      margin:5px 0 5px 0;
}
CSS;
$this->registerCss($css);
?>
<?php $this->registerCssFile('/js/jstree-vakate/themes/default/style.min.css') ?>
<?php
$js = <<<JS
    /* */
    $('#kt_tree_1').jstree();

$('#kt_tree_1').on("changed.jstree", function (e, data) {
    $('#loading').css('display','block');
    $(".department-create").attr("disabled",false);
    $(".department-update").attr("disabled",false);
    $(".tree-create").attr("disabled",true);
    $('#loading').css('display','none');
  $(".delete-tree").attr('data-id', data.node.li_attr.value);
});

$('button').on('click', function () {
  $('#kt_tree_1').jstree(true).select_node('child_node_1');
  $('#kt_tree_1').jstree('select_node', 'child_node_1');
  $.jstree.reference('#kt_tree_1').select_node('child_node_1');
});
    
if ('{$deb}' != ''){
    var value = $('#kt_tree_1 li');
    value.each(function(index, item) {
        if ($(item).attr('aria-selected') == 'true') {
            $(item).attr('aria-selected', 'false');
        }
        if ($(item).val() == '{$deb}'*1) {
            $(item).attr('aria-selected', 'true');
            $('.jstree-icon').css({'color':'#0c5460'});
            $('.jstree-anchor').css({'background-color':'white','color':'black'});
            $(item).find('a:first').css({'background-color':'#0c5460','color':'white'});
            $(item).find('a:first').find('.jstree-icon').css({'color':'white'});
        }
    });
} else {
    var value = $('#kt_tree_1 li');
    value.each(function(index, item) {
        if ($(item).attr('aria-selected') == 'true') {
            $('.jstree-icon').css({'color':'#0c5460'});
            $('.jstree-anchor').css({'background-color':'white','color':'black'});
            $(item).find('a:first').css({'background-color':'#0c5460','color':'white'});
            $(item).find('a:first').find('.jstree-icon').css({'color':'white'});
        }
    });
}

$('body').delegate('.jstree-anchor', 'click', function() {
    $('.jstree-icon').css({'color':'#0c5460'});
    $('.jstree-anchor').css({'background-color':'white','color':'black'});
    $(this).css({'background-color':'#0c5460','color':'white'});
    $(this).find('.jstree-icon').css({'color':'white'});
});

// tree create
/*$('body').delegate('#kt_demo_panel_toggle', 'click', function(e) {
    let url=$(this).attr('href');
    var departmentId = $('.delete-tree').attr('data-id');
     if(departmentId === undefined){
        swal({
            icon: 'error',
            title: notSelectedDepartment,
            showConfirmButton:false
        });
    }else{
         url = url+"?department_id="+departmentId;
         $('#view-modal .modal-body').load(url, function(){
              $('#view-modal .modal-title').html(modalHeaderNameDepartment);
              $('#view-modal').modal("show");
        });
    }
    
});

//tree-root create
$('body').delegate('#kt_demo_panel_toggle_root', 'click', function(e) {
    e.preventDefault();
    $(".delete-tree").attr('data-id', '');
    var value = $('#kt_tree_1 li');
    value.each(function(index, item) {
        if ($(item).attr('aria-selected') == 'true') {
            $(item).attr('aria-selected', 'false');
            $('.jstree-icon').css({'color':'#0c5460'});
            $('.jstree-anchor').css({'background-color':'white','color':'black','border-color': 'white'});
            $(item).css({'background-color':'white','color':'#0C5460'});
            $(item).find('.jstree-icon').css({'color':'white'});
        }
    });
    $('#kt_demo_panel_toggle').click(); 
});*/

/*//tree elements update
$('body').delegate('.update-tree-elements', 'click', function(e) {
    e.preventDefault();
    var id = $('.delete-tree').attr('data-id');
    let url=$(this).attr('href') + '?id=' + id;
    $('#view-modal .modal-body').load(url, function(){
      $('#view-modal').modal("show");
    });
});*/

// delete tree
$('body').delegate('.delete-tree', 'click', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var value = $('#kt_tree_1 li');
    var id = $(this).attr('data-id');
    if(id > 0){
        swal({
            title: message_delete_title,
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: message_yes,
            cancelButtonText: message_no,
            closeOnConfirm: false,
            buttons: {
                 cancel: {
                    text: "Cancel",
                    closeModal: true,
                    visible: true, 
                    value: false
                  },
                confirm: {
                    text: "Ok",
                    closeModal: false,
                    visible: true,
                    value: true
                },
            }
        }).then((result) => {
            if (result) {
                $.ajax({
                    url:url,
                    data:{id:id},
                    type:'GET',
                    dataType: 'json',
                    success: function(response){
                        if(response.status){
                            swal(message_deleted,'','success');
                            $('#kt_tree_1').jstree(true).settings.core.data = response.result;
                            $('#kt_tree_1').jstree(true).refresh();
                        }               
                    }
                });
            }
        })
    }
});

// delete license department
$('body').delegate('.tree-create', 'click', function(e) {
    e.preventDefault();
    var departmentId = $('.delete-tree').attr('data-id');
    $('#loading').css('display','block');
    let url = $(this).attr('href');
    if(departmentId === undefined){
        departmentId = 0;
    }
    url = url+"?department_id="+departmentId;
    $('#view-modal .modal-body').load(url, function(){
            $('#view-modal').modal("show");
            $('#loading').css('display','none');
        });
});

$('body').delegate('.department-create,.department-update,.shifts-create,.shifts-update,.equipments-create,.equipments-update,.products-create,.products-update,.defects-create,.defects-update', 'click', function(e) {
    e.preventDefault();
    $(".tree-create").attr("disabled",true);
    var departmentId = $('.delete-tree').attr('data-id');
    var id = $(this).attr('data-form-id');
    $('#loading').css('display','block');
    if(departmentId === undefined){
        swal({
            icon: 'error',
            title: notSelectedDepartment,
            showConfirmButton:false
        });
        $('#loading').css('display','none');
    }else{
        let url = $(this).attr('href');
        if(id != undefined){
            url = url +"?id="+id+"&department_id="+departmentId;
        }else{
            url = url+"?department_id="+departmentId;
        }
        $('#view-modal .modal-body').load(url, function(){
            $('#view-modal').modal("show");
            $('#loading').css('display','none');
        });
    }
});
//Ajax yordamida itemlarni o'chirish

$('body').delegate('.shifts-delete,.equipments-delete,.products-delete,.defects-delete','click',function(e){
    var id = $(this).attr('data-form-id');
    let url = $(this).attr('href');
    if(id != undefined){
        url = url +"?id="+id;
        $('#loading').css('display','block');
        $.ajax({
            url:url,
            data:{id:id},
            type:'POST',
            success: function(response){
                if(response.status == 0){
                    $('#loading').css('display','none');
                    call_pnotify('success',response.message);
                    window.location.reload();
                }else {
                    call_pnotify('fail',response.message);
                }
            }
        });
    }else{
        $('#loading').css('display','none');
        alert("Ma'lumot Id si mavjud emas");
    }
});


// Ajax yordamida tablelarni toldirish uchun
$('body').delegate('li', 'click', function(e) {
//create, update va view larni modal yordamida chiqarish uchun
    let id = $(this).val();
    if ($(this).find('.jstree-anchor').attr('aria-selected') == 'true') {
        $('#loading').css('display','block');
        $.ajax({
            url:'{$urlToGetItems}',
            data:{id:id},
            type:'GET',
            success: function(response){
                $('tbody').html('');
                if(response){
                    let delete_tree = response['delete'];
                    let shifts = response['shifts'];
                    let equipments = response['equipments'];
                    let products = response['products'];
                    let defects = response['defects'];
                    let tr_class = "";
                    if ( delete_tree ) {
                        $('.delete-tree').attr('disabled', true);
                    } else {
                        $('.delete-tree').attr('disabled', false);
                    }
                    shifts.map(function(item,index) {
                        if (item['status'] == 1) {
                            item['status'] = 'Active';
                            tr_class = "background:#F4F6F9";
                        } else {
                            item['status'] = 'Inactive';
                            tr_class = "background:#FFC5C3";
                        }
                        let td_shifts = '<tr style="' + tr_class+ '">' +
                             '<td>'+ (index*1+1) +'</td>' +
                             '<td>'+ item['dep_name'] +'</td>' +
                             '<td>'+ item['shift_name'] +'</td>' +
                             '<td>'+ item['start_time'] +'</td>' +
                             '<td>'+ item['end_time'] +'</td>' +
                             '<td>'+ item['status_name'] +'</td>' +
                             '<td>';
                        if (item['status'] == 'Active') {
                            td_shifts += '<a href="' + urlShiftUpdate + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs mr-1 btn-outline-success shifts-update" ><i class="fa fa-pencil-alt"></i></a>';
                            td_shifts += '<a href="' + urlShiftDelete + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs btn-outline-danger shifts-delete" data-toggle="modal" data-target="#exampleModalCustomScrollable"><i class="fa fa-trash"></i></a>';
                        }    
                        td_shifts += '</td></tr>'; 
                         $('#table-shifts').find('tbody').append(td_shifts);
                     });
                    equipments.map(function(item,index) {
                        if (item['status'] == 1) {
                            item['status'] = 'Active';
                        } else {
                            item['status'] = 'Inactive';
                        }
                        let td_equipments = '<tr>' +
                             '<td>'+ (index*1+1) +'</td>' +
                             '<td>'+ item['dep_name'] +'</td>' +
                             '<td>'+ item['equipment_name'] +'</td>' +
                             '<td>'+ item['equipment_type_time'] +'</td>' +
                             '<td>'+ item['status_name'] +'</td>' +
                             '<td>';
                        if (item['status'] == 'Active') {
                            td_equipments += '<a href="' + urlEquipmentUpdate + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs mr-1 btn-outline-success equipments-update" ><i class="fa fa-pencil-alt"></i></a>';
                        }    
                        td_equipments += '<a href="' + urlEquipmentDelete + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs btn-outline-info equipments-delete" data-toggle="modal" data-target="#exampleModalCustomScrollable"><i class="fa fa-trash"></i></a>' +
                             '</td>' +
                        '</tr>'; 
                         $('#table-equipments').find('tbody').append(td_equipments);
                    });
                    products.map(function(item,index) {
                        if (item['status'] == 1) {
                            item['status'] = 'Active';
                        } else {
                            item['status'] = 'Inactive';
                        }
                        let td_products = '<tr>' +
                             '<td>'+ (index*1+1) +'</td>' +
                             '<td>'+ item['dep_name'] +'</td>' +
                             '<td>'+ item['product_name'] +'</td>' +
                             '<td>'+ item['part_number'] +'</td>' +
                             '<td>'+ item['status_name'] +'</td>' +
                             '<td>';
                        if (item['status'] == 'Active') {
                            td_products += '<a href="' + urlProductUpdate + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs mr-1 btn-outline-success products-update" ><i class="fa fa-pencil-alt"></i></a>';
                        }    
                        td_products += '<a href="' + urlProductDelete + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs btn-outline-info products-delete" data-toggle="modal" data-target="#exampleModalCustomScrollable"><i class="fa fa-trash"></i></a>' +
                             '</td>' +
                         '</tr>'; 
                         $('#table-products').find('tbody').append(td_products);
                    });
                    defects.map(function(item,index) {
                        if (item['status'] == 1) {
                            item['status'] = 'Active';
                        } else {
                            item['status'] = 'Inactive';
                        }
                        let td_defects = '<tr>' +
                             '<td>'+ (index*1+1) +'</td>' +
                             '<td>'+ item['dep_name'] +'</td>' +
                             '<td>'+ item['defect_name'] +'</td>' +
                             '<td>'+ item['defect_type'] +'</td>' +
                             '<td>'+ item['status_name'] +'</td>' +
                             '<td>';
                        if (item['status'] == 'Active') {
                            td_defects += '<a href="' + urlDefectsUpdate + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs mr-1 btn-outline-success defects-update" ><i class="fa fa-pencil-alt"></i></button>';
                        }    
                        td_defects += '<a href="' + urlDefectsDelete + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs btn-outline-info defects-delete" data-toggle="modal" data-target="#exampleModalCustomScrollable"><i class="fa fa-trash"></i></button>' +
                             '</td>' +
                          '</tr>'; 
                         $('#table-defects').find('tbody').append(td_defects);
                    });
                    $('#loading').css('display','none');
                }               
            }
        });
    }
});


$(document).ready(function() {
    var value = $('#kt_tree_1 li');
    value.each(function(index, item) {
        if ($(item).attr('aria-selected') == 'true') {
            $(item).click()
        }
    });
});

    $('body').delegate('.button-save-form', 'click', function(e) {
        e.preventDefault();
        $('#loading').css('display','block');
        let formModal = $(this).parents('form');
        let actionUrl = formModal.attr('action');
        ajaxModalSave(formModal, actionUrl);
    });
    
function ajaxModalSave(formModal,actionUrl){
     $.ajax({
        url: actionUrl,
        data: formModal.serialize(),
        type:'POST',
        success: function(response){
            $('tbody').html('');
            if(response.status == 0){
                 $('#loading').css('display','none');
                 $('#view-modal').modal("hide");
                 call_pnotify('success',response.message);
                 window.location.reload();
            }else{
                $('#loading').css('display','none');
                call_pnotify('fail',response.message);
            }
        }
    });
}   
/*$("body").on("submit", ".departmentsAjaxForm", function (e) {
    e.preventDefault();
    var self = $(this);
    var url = self.attr("action");
    let model_type = "toquvdepartments";
    let check = true;
    let required = self.find(".customRequired");
    $(required).each(function (index, value){
        if($(this).val()==0||$(this).val()==null){
            e.preventDefault();
            $(this).css("border-color","red").parents('.form-group').addClass('has-error');
            $(this).focus();
            check = false;
        }
    });
    if(check) {
        $(this).find("button[type=submit]").hide();
        // .attr("disabled", false); Bunda knopka 2 marta bosilsa 2 marta zapros ketyapti
       var data = $(this).serialize();
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                success: function (response) {
                    if (response.status == 0) {
                        $.fn.eventSubmitSuccess(response);
                        $('#hided-modal').modal("hide");
                        if(response.message){
                            success_message = response.message;
                        }
                        call_pnotify('success', success_message);
                        location.reload();
                        // $.pjax.reload({container: "#department_pjax"});
                    } else {
                        $.fn.eventSubmitError(response);
                        let tekst = (response.message) ? response.message : fail_message;
                        let error = response.errors;
                        if(typeof error == 'object') {
                            $.each(error, function (key, val) {
                                self.find(".field-" + model_type.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).addClass("has-error");
                                self.find(".field-" + model_type.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).find(".help-block").html(val);
                                if (array_model.length > 0) {
                                    array_model.forEach(function (index, value) {
                                        self.find(".field-" + index.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).addClass("has-error");
                                        self.find(".field-" + index.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).find(".help-block").html(val);
                                    });
                                }
                            });
                        }else{
                            call_pnotify('fail',error);
                        }
                        self.find("button[type=submit]").show();
                        //.attr("disabled", false);
                        call_pnotify('fail', tekst);
                    }
                },
                error: function () {
                    console.log('ERROR at PHP side!!');
                    self.find("button[type=submit]").show();
                },
            });
    }else{
        call_pnotify('fail', "Barcha maydonlar to'ldirilmagan");
    }
});*/
    
function call_pnotify(status,message) {
    switch (status) {
        case 'success':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:message,type:'success'});
            break;
        case 'fail':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:message,type:'error'});
            break;
    }
}
JS;
$this->registerJs($js);
$this->registerJsFile('/js/jstree-vakate/jstree.min.js', ['depends' => JqueryAsset::class]);
\app\assets\SweetAlertAsset::register($this);
?>