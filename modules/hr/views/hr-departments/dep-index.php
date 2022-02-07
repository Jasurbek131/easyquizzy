<?php


use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $tree */

$confirmDeleteMessage = Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?');

$this->title = Yii::t('app', 'Hr Departments');
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
        <div class="col-md-4">
            <div class="card" style="border: 1px solid green;overflow-x: scroll">
                <div class="card-body">
                    <div class="knopka text-center">
                        <br>
                        <button class="btn btn-xs btn-outline-info department-create"
                                style="border: 1px solid #8950fc;" id="kt_demo_panel_toggle" disabled="disabled"
                                href="<?= Url::to(['hr-departments/create']) ?>" ><i class="fa fa-plus"></i></button>
                        <button class="btn btn-xs btn-outline-success tree" style="border: 1px solid #1bc5bd;"
                                id="kt_demo_panel_toggle_root" href="<?= Url::to(['hr-departments/create']) ?>"><i
                                class="fa fa-tree"></i></button>
                        <button class="btn btn-xs btn-outline-danger delete-tree disabled-danger-delete"
                                disabled="disabled"
                                href="<?= Url::to(['hr-departments/delete']) ?>"><i class="fa fa-trash"></i>
                        </button>
                        <button class="btn btn-xs btn-outline-primary update-dialog update-tree-elements"
                                disabled="disabled"
                                style="border: 1px solid #3699ff;" href="<?= Url::to(['hr-departments/update']); ?>">
                            <i class="fa fa-pencil-alt"></i></button>
                    </div>

                    <br>
                    <div id="kt_tree_1" class="tree-demo">
                        <?php echo $tree ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link" id="tap-address-one" data-toggle="tab" href="#tab-one" role="tab"
                       aria-controls="profile" aria-selected="false"><?php echo Yii::t('app', 'Shifts') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="equipments-tab" data-toggle="tab" href="#equipments" role="tab"
                       aria-controls="equipments" aria-selected="false"><?php echo Yii::t('app', 'Equipments') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="products-tab" data-toggle="tab" href="#products" role="tab"
                       aria-controls="products" aria-selected="false"><?php echo Yii::t('app', 'Products') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="defects-tab" data-toggle="tab" href="#defects" role="tab"
                       aria-controls="defects" aria-selected="false"><?php echo Yii::t('app', 'Defects') ?></a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade in active" id="tab-one" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                        <div class="col-md-4">
                            <button href="<?php echo Url::to(['/references/shifts/create']) ?>"
                                    class="btn btn-xs-button  btn-outline-success text-sm shifts-create"
                                    style="border: 1px solid #1bc5bd;padding: 3px 6px; "><i class="fa fa-plus" style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
                            </button>
                        </div>
                        <div class="col-md-8">&nbsp;</div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover attorney-table"
                                   id="table-shifts">
                                <thead>
                                <tr>
                                    <th class=""><?= Yii::t('app', "№") ?></th>
                                    <th class=""><?= Yii::t('app', 'Hr Department') ?></th>
                                    <th class=""><?= Yii::t('app', 'Shift Name') ?></th>
                                    <th class=""><?= Yii::t('app', 'Start Time') ?></th>
                                    <th class=""><?= Yii::t('app', 'End Time') ?></th>
                                    <th class=""><?= Yii::t('app', 'Status') ?></th>
                                    <th class=""><?= Yii::t('app', 'Action') ?></th>
                                </tr>
                                </thead>
                                <tbody id="address-data"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade in active" id="equipments" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                        <div class="col-md-4">
                            <button href="<?php echo Url::to(['/references/equipments/create']) ?>"
                                    class="btn btn-xs-button  btn-outline-success text-sm equipmants-create"
                                    style="border: 1px solid #1bc5bd;padding: 3px 6px; "><i class="fa fa-plus" style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
                            </button>
                        </div>
                        <div class="col-md-8">&nbsp;</div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover attorney-table"
                                   id="table-equipments">
                                <thead>
                                <tr>
                                    <th class=""><?= Yii::t('app', "№") ?></th>
                                    <th class=""><?= Yii::t('app', 'Hr Department') ?></th>
                                    <th class=""><?= Yii::t('app', 'Equipment Name') ?></th>
                                    <th class=""><?= Yii::t('app', 'Status') ?></th>
                                    <th class=""><?= Yii::t('app', 'Action') ?></th>
                                </tr>
                                </thead>
                                <tbody id="address-data"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade in active" id="products" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                        <div class="col-md-4">
                            <button href="<?php echo Url::to(['/references/products/create']) ?>"
                                    class="btn btn-xs-button  btn-outline-success text-sm products-create"
                                    style="border: 1px solid #1bc5bd;padding: 3px 6px; "><i class="fa fa-plus" style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
                            </button>
                        </div>
                        <div class="col-md-8">&nbsp;</div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover attorney-table"
                                   id="table-products">
                                <thead>
                                <tr>
                                    <th class=""><?= Yii::t('app', "№") ?></th>
                                    <th class=""><?= Yii::t('app', 'Hr Department') ?></th>
                                    <th class=""><?= Yii::t('app', 'Product Name') ?></th>
                                    <th class=""><?= Yii::t('app', 'Status') ?></th>
                                    <th class=""><?= Yii::t('app', 'Action') ?></th>
                                </tr>
                                </thead>
                                <tbody id="address-data"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade in active" id="products" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                        <div class="col-md-4">
                            <button href="<?php echo Url::to(['/references/defects/create']) ?>"
                                    class="btn btn-xs-button  btn-outline-success text-sm defects-create"
                                    style="border: 1px solid #1bc5bd;padding: 3px 6px; "><i class="fa fa-plus" style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
                            </button>
                        </div>
                        <div class="col-md-8">&nbsp;</div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover attorney-table"
                                   id="table-defects">
                                <thead>
                                <tr>
                                    <th class=""><?= Yii::t('app', "№") ?></th>
                                    <th class=""><?= Yii::t('app', 'Hr Department') ?></th>
                                    <th class=""><?= Yii::t('app', 'Defect Name') ?></th>
                                    <th class=""><?= Yii::t('app', 'Status') ?></th>
                                    <th class=""><?= Yii::t('app', 'Action') ?></th>
                                </tr>
                                </thead>
                                <tbody id="address-data"></tbody>
                            </table>
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

<?/*= \app\widgets\ModalWindow\ModalWindow::widget([
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
$this->registerJsVar('tempSelectMenuMessage', Yii::t('app', "Avval bo'lim tanlang"));
$this->registerJsVar('urlUserGroup', Url::to(['/admin/users-group']));
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
CSS;
$this->registerCss($css);
?>
<?php $this->registerCssFile('/js/jstree-vakate/themes/default/style.min.css') ?>
<?php
$js = <<<JS
    /* */
    // 6 create an when the DOM is ready
    $('#kt_tree_1').jstree();

$('#kt_tree_1').on("changed.jstree", function (e, data) {
    let musteriId = JSON.parse(data.node.li_attr['data-jstree']).musteri_id ?? -1;
    $("#tree-department-create").attr("disabled")
  $(".delete-tree").attr('data-id', data.node.li_attr.value ?? -1);
  // $(".delete-tree").attr('data-musteri-id', musteriId);
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
$('body').delegate('#kt_demo_panel_toggle', 'click', function(e) {
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
});

//tree elements update
$('body').delegate('.update-tree-elements', 'click', function(e) {
    e.preventDefault();
    var id = $('.delete-tree').attr('data-id');
    let url=$(this).attr('href') + '?id=' + id;
    $('#view-modal .modal-body').load(url, function(){
      $('#view-modal').modal("show");
    });
});

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
$('body').delegate('.delete-license-department', 'click', function(e) {
    e.preventDefault();
    let url=$(this).attr('href');
    let item = $(this).parents('tr');
    $.ajax({
        url:url,
        data:{},
        type:'GET',
        success: function(response){
            if(response){
                $(item).remove();
            }
        }
    });
});

$('body').delegate('.department-create,.shifts-create ,.equipments-create','.defects-create', 'click', function(e) {
    e.preventDefault();
    var departmentId = $('.delete-tree').attr('data-id');
    var id = $(this).attr('data-form-id');
    console.log("departmentId",departmentId)
    if(departmentId === undefined){
        swal({
            icon: 'error',
            title: notSelectedDepartment,
            showConfirmButton:false
        });
    }else{
        let url=$(this).attr('href');
        // if(id != undefined){
        //     url = url +"?id="+id+"&department_id="+departmentId;
        // }else{
        // }
        console.log("department", departmentId)
        // url = url+"?department_id="+departmentId;
        // $('#view-modal .modal-body').load(url, function(){
        //     $('#view-modal').modal("show");
        //     // $('#exampleModalCenterTitle').html(modalHeaderNameShifts);
        // });
    }
});

// Ajax yordamida tablelarni toldirish uchun
$('body').delegate('li', 'click', function(e) {
//create, update va view larni modal yordamida chiqarish uchun
    let id = $(this).val();
    if ($(this).find('.jstree-anchor').attr('aria-selected') == 'true') {
        $.ajax({
            url:'{$urlToGetItems}',
            data:{id:id},
            type:'GET',
            success: function(response){
                $('tbody').html('');
                if(response){
                    let delete_tree = response['delete'];
                    let shifts = response['shifts'];
                    let usersGroup = response['users_group'];
                    
                    if ( delete_tree ) {
                        $('.delete-tree').attr('disabled', true);
                    } else {
                        $('.delete-tree').attr('disabled', false);
                    }
                    
                    shifts.map(function(item,index) {
                         var status_check;
                        if (item['status'] == 1) {
                            status_check = 'success';
                            item['status'] = 'Active';
                        } else {
                            status_check = 'warning';
                            item['status'] = 'Inactive';
                        }
                        let td_shifts = '<tr>' +
                             '<td>'+ (index*1+1) +'</td>' +
                             '<td>'+ item['dep_name'] +'</td>' +
                             '<td>'+ item['shift_name'] +'</td>' +
                             '<td>'+ item['start_time'] +'</td>' +
                             '<td>'+ item['end_time'] +'</td>' +
                             '<td>'+ item['status_name'] +'</td>' +
                             '<td>';
                        if (item['status'] == 'Active') {
                            td_shifts += '<button href="/references/shifts/update?id=' + item['id'] + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs mr-1 btn-outline-success shifts-update-dialog" ><i class="fa fa-pencil-alt"></i></button>';
                        }    
                        td_shifts += '<button href="/references/shifts/view?id=' + item['id'] + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs btn-outline-info shifts-view-dialog" data-toggle="modal" data-target="#exampleModalCustomScrollable"><i class="fa fa-eye"></i></button>' +
                             '</td>' +
                          '</tr>'; 
                         $('#table-shifts').find('tbody').append(td_shifts);
                     });
                    
                     // usersGroup.map(function(item,index) {
                     //    var status_check;
                     //    if (item['status'] == 1) {
                     //        status_check = 'success';
                     //        item['status'] = 'Active';
                     //    } else {
                     //        status_check = 'warning';
                     //        item['status'] = 'Inactive';
                     //    }
                     //    let tr_user_goup = '<tr>' +
                     //         '<td>'+ (index*1+1) +'</td>' +
                     //         '<td>'+ item['user_fio'] +'</td>' +
                     //         '<td>'+ item['fish'] +'</td>' +
                     //         '<td><p class="btn btn-xs btn-'+ status_check +'">'+ item['status'] +'</p></td>' +
                     //         '<td>'+ item['name'] +'</td>' +
                     //        '<td>';
                     //            if (item['status'] == 'Active') {
                     //                tr_user_goup += '<button href="'+urlUserGroup+'/update" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs mr-1 btn-outline-primary user-group-update" ><i class="fa fa-pencil"></i></button>';
                     //            }    
                     //            tr_user_goup += '<button href="'+urlUserGroup+'/view" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs btn-outline-info users-group-view" "><i class="fa fa-eye"></i></button>' +
                     //         '</td>' +
                     //      '</tr>';
                     //     $('#hr_employee-users_group').find('tbody').append(tr_user_goup); 
                     // });
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

    $('body').delegate('.button-users-group', 'click', function(e) {
        e.preventDefault();
        let musteriId = $('.delete-tree').attr('data-musteri-id');
        let departmentId = $('.delete-tree').attr('data-id');
        let form = $('#users-group');
        let actionUrl = form.attr('action');
        $.ajax({
            url: actionUrl,
            data: form.serialize(),
            type:'POST',
            success: function(response){
                $('tbody').html('');
                if(response.status == 0){
                    call_pnotify('success',response.message);
                }else{
                    call_pnotify('fail',response.message);
                }
            }
        });
    });
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
    
    
    
    $("body").on("submit", ".departmentsAjaxForm", function (e) {
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
});
JS;
$this->registerJs($js);
$this->registerJsFile('/js/jstree-vakate/jstree.min.js', ['depends' => JqueryAsset::class]);
\app\assets\SweetAlertAsset::register($this);
?>