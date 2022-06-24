<?php

use app\assets\SweetAlertAsset;
use app\modules\references\models\Defects;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\widgets\Pjax;

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
                                href="<?php echo Url::to(['hr-departments/create']) ?>"><i class="fa fa-plus"></i>
                        </button>

                        <?php if (Yii::$app->user->can("super-admin")): ?>
                            <button class="btn btn-xs btn-outline-success tree-create"
                                    style="border: 1px solid #1bc5bd;"
                                    href="<?php echo Url::to(['hr-departments/create']) ?>"><i
                                        class="fa fa-tree"></i></button>
                        <?php endif; ?>

                        <button class="btn btn-xs btn-outline-danger delete-tree disabled-danger-delete"
                                disabled="disabled"
                                href="<?php echo Url::to(['hr-departments/delete']) ?>"><i class="fa fa-trash"></i>
                        </button>
                        <button class="btn btn-xs btn-outline-primary department-update"
                                disabled="disabled"
                                style="border: 1px solid #3699ff;"
                                href="<?php echo Url::to(['hr-departments/update']); ?>">
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
                               aria-controls="equipments"
                               aria-selected="false"><?php echo Yii::t('app', 'Equipment Group') ?></a>
                        </li>
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" id="products-tab" data-toggle="tab" href="#product" role="tab"-->
<!--                               aria-controls="products"-->
<!--                               aria-selected="false">--><?php //echo Yii::t('app', 'Products') ?><!--</a>-->
<!--                        </li>-->
                        <li class="nav-item">
                            <a class="nav-link" id="defects-tab" data-toggle="tab" href="#defect" role="tab"
                               aria-controls="defects" aria-selected="false"><?php echo Yii::t('app', 'Defects') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="categories-tab" data-toggle="tab" href="#categories" role="tab"
                               aria-controls="categories" aria-selected="false"><?php echo Yii::t('app', "Categories") ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="deparea-tab" data-toggle="tab" href="#deparea" role="tab"
                               aria-controls="deparea" aria-selected="false"><?php echo Yii::t('app', "Confirmations") ?></a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active show" id="shift" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <button href="<?php echo Url::to(['hr-department-rel-shifts/create']) ?>"
                                            class="btn btn-xs-button  btn-outline-success text-sm shifts-create"
                                            style="border: 1px solid #1bc84d;padding: 3px 6px; "><i class="fa fa-plus"  style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
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
                                            style="border: 1px solid #1bc84d;padding: 3px 6px; "><i class="fa fa-plus"
                                                                                                    style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
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
                                            <th class=""><?php echo Yii::t('app', 'Equipment Group') ?></th>
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
                                            style="border: 1px solid #1bc84d;padding: 3px 6px; "><i class="fa fa-plus"
                                                                                                    style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
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
                                            style="border: 1px solid #1bc84d;padding: 3px 6px; "><i class="fa fa-plus"
                                                                                                    style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
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
                                            <th class=""><?php echo Yii::t('app', 'Status') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Permission') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <button href="<?php echo Url::to(['/plm/categories-rel-hr-department/create']) ?>"
                                            class="btn btn-xs-button  btn-outline-success text-sm categories-create"
                                            style="border: 1px solid #1bc84d;padding: 3px 6px; ">
                                        <i class="fa fa-plus"   style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
                                    </button>
                                </div>
                                <div class="col-md-8">&nbsp;</div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover attorney-table table-bordered"
                                           id="table-categories">
                                        <thead>
                                        <tr>
                                            <th class=""><?php echo Yii::t('app', "№") ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Hr Department') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Categories') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Permission') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="deparea" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <button href="<?php echo Url::to(['/plm/plm-sector-rel-hr-department/create']) ?>"
                                            class="btn btn-xs-button  btn-outline-success text-sm deparea-create"
                                            style="border: 1px solid #1bc84d;padding: 3px 6px; ">
                                        <i class="fa fa-plus"   style="font-size: 14px">&nbsp;<?php echo $create ?></i>&nbsp;
                                    </button>
                                </div>
                                <div class="col-md-8">&nbsp;</div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover attorney-table table-bordered"
                                           id="table-deparea">
                                        <thead>
                                        <tr>
                                            <th class=""><?php echo Yii::t('app', "№") ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Hr Department') ?></th>
                                            <th class=""><?php echo Yii::t('app', 'Confirmations') ?></th>
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

<?php

$lang = Yii::$app->language;
$dep = Yii::$app->request->get('dep') ?? null;
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
//$urlProductUpdate = Url::to(['hr-department-rel-product/update']);
//$urlProductDelete = Url::to(['hr-department-rel-product/delete']);
$urlDefectUpdate = Url::to(['hr-department-rel-defects/update']);
$urlDefectDelete = Url::to(['hr-department-rel-defects/delete']);
$urlDepareaUpdate = Url::to(['/plm/plm-sector-rel-hr-department/update']);
$urlDepareaDelete = Url::to(['/plm/plm-sector-rel-hr-department/delete']);
$urlCategoriesUpdate = Url::to(['/plm/categories-rel-hr-department/update']);
$urlCategoriesDelete = Url::to(['/plm/categories-rel-hr-department/delete']);
$this->registerJsVar('urlShiftUpdate', $urlShiftUpdate);
$this->registerJsVar('urlShiftDelete', $urlShiftDelete);
$this->registerJsVar('urlEquipmentUpdate', $urlEquipmentUpdate);
$this->registerJsVar('urlEquipmentDelete', $urlEquipmentDelete);
//$this->registerJsVar('urlProductUpdate', $urlProductUpdate);
//$this->registerJsVar('urlProductDelete', $urlProductDelete);
$this->registerJsVar('urlDefectsUpdate', $urlDefectUpdate);
$this->registerJsVar('urlDefectsDelete', $urlDefectDelete);
$this->registerJsVar('urlDepareaUpdate', $urlDepareaUpdate);
$this->registerJsVar('urlDepareaDelete', $urlDepareaDelete);
$this->registerJsVar('urlCategoriesUpdate', $urlCategoriesUpdate);
$this->registerJsVar('urlCategoriesDelete', $urlCategoriesDelete);
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
$('#kt_tree_1').jstree();

$('#kt_tree_1').on("changed.jstree", function (e, data) {
    $('#loading').css('display','block');
    $(".department-create").attr("disabled",false);
    $(".department-update").attr("disabled",false);
    $(".tree-create").attr("disabled",true);
    $('#loading').css('display','none');
    if (data.node){
        $(".delete-tree").attr('data-id', data.node.li_attr.value);
    }
});

$('button').on('click', function () {
  $('#kt_tree_1').jstree(true).select_node('child_node_1');
  $('#kt_tree_1').jstree('select_node', 'child_node_1');
  $.jstree.reference('#kt_tree_1').select_node('child_node_1');
});

$('body').delegate('.jstree-anchor', 'click', function() {
    $('.jstree-icon').css({'color':'#0c5460'});
    $('.jstree-anchor').css({'background-color':'white','color':'black'});
    $(this).css({'background-color':'#0c5460','color':'white'});
    $(this).find('.jstree-icon').css({'color':'white'});
});

// delete tree
$('body').delegate('.delete-tree', 'click', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    var url = $(this).attr('href');
    var value = $('#kt_tree_1 li');
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
                    type:'POST',
                    success: function(response){
                        if(response.delete){
                            swal(response.message,'','success');
                            window.location.reload();
                            // $('#kt_tree_1').jstree(true).settings.core.data = response.result;
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

$('body').delegate('.categories-create, .categories-update, .department-create,.department-update,.shifts-create,.shifts-update,.equipments-create,.equipments-update,.defects-create,.defects-update,.deparea-create,.deparea-update', 'click', function(e) {
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

$('body').delegate('.shifts-delete,.equipments-delete,.products-delete,.defects-delete,.deparea-delete,.categories-delete','click',function(e){
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
    let id = $(this).val();
    if ($(this).find('.jstree-anchor').attr('aria-selected') == 'true') {
        $('#loading').css('display','block');
        ajaxSubmit(id);
        $('#loading').css('display','none');
    }
});

function ajaxSubmit(id){
     $.ajax({
            url: '{$urlToGetItems}',
            data: {id:id},
            type: 'GET',
            success: function(response){
                $('tbody').html('');
                if(response){
                    let delete_tree = response['delete'];
                    let shifts = response['shifts'];
                    let equipments = response['equipments'];
                    // let products = response['products'];
                    let defects = response['defects'];
                    let deparea = response['deparea'];
                    let categories = response['categories'];
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
                             '<td>'+ item['status_name'] +'</td>' +
                             '<td>';
                        if (item['status'] == 'Active') {
                            td_equipments += '<a href="' + urlEquipmentUpdate + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs mr-1 btn-outline-success equipments-update" ><i class="fa fa-pencil-alt"></i></a>';
                        }    
                        td_equipments += '<a href="' + urlEquipmentDelete + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs btn-outline-danger equipments-delete" data-toggle="modal" data-target="#exampleModalCustomScrollable"><i class="fa fa-trash"></i></a>' +
                             '</td>' +
                        '</tr>'; 
                         $('#table-equipments').find('tbody').append(td_equipments);
                    });
//                    products.map(function(item,index) {
//                        if (item['status'] == 1) {
//                            item['status'] = 'Active';
//                        } else {
//                            item['status'] = 'Inactive';
//                        }
//                        let td_products = '<tr>' +
//                             '<td>'+ (index*1+1) +'</td>' +
//                             '<td>'+ item['dep_name'] +'</td>' +
//                             '<td>'+ item['product_name'] +'</td>' +
//                             '<td>'+ item['part_number'] +'</td>' +
//                             '<td>'+ item['status_name'] +'</td>' +
//                             '<td>';
//                        if (item['status'] == 'Active') {
//                            td_products += '<a href="' + urlProductUpdate + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs mr-1 btn-outline-success products-update" ><i class="fa fa-pencil-alt"></i></a>';
//                        }    
//                        td_products += '<a href="' + urlProductDelete + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs btn-outline-danger products-delete" data-toggle="modal" data-target="#exampleModalCustomScrollable"><i class="fa fa-trash"></i></a>' +
//                             '</td>' +
//                         '</tr>'; 
//                         $('#table-products').find('tbody').append(td_products);
//                    });
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
                             '<td>'+ item['status_name'] +'</td>' +
                             '<td>';
                        if (item['status'] == 'Active') {
                            td_defects += '<a href="' + urlDefectsUpdate + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs mr-1 btn-outline-success defects-update" ><i class="fa fa-pencil-alt"></i></button>';
                        }    
                        td_defects += '<a href="' + urlDefectsDelete + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs btn-outline-danger defects-delete" data-toggle="modal" data-target="#exampleModalCustomScrollable"><i class="fa fa-trash"></i></button>' +
                             '</td>' +
                          '</tr>'; 
                         $('#table-defects').find('tbody').append(td_defects);
                    });
                    categories.map(function(item,index) {
                        if (item['status'] == 1) {
                            item['status'] = 'Active';
                        } else {
                            item['status'] = 'Inactive';
                        }
                        let td_deparea = '<tr>' +
                             '<td>'+ (index*1+1) +'</td>' +
                             '<td>'+ item['dep_name'] +'</td>' +
                             '<td>'+ item['category'] +'</td>' +
                             '<td>';
                        if (item['status'] == 'Active') {
                            td_deparea += '<a href="' + urlCategoriesUpdate + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs mr-1 btn-outline-success categories-update" ><i class="fa fa-pencil-alt"></i></button>';
                        }    
                        td_deparea += '<a href="' + urlCategoriesDelete + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs btn-outline-danger categories-delete" data-toggle="modal" data-target="#exampleModalCustomScrollable"><i class="fa fa-trash"></i></button>' +
                             '</td>' +
                          '</tr>'; 
                         $('#table-categories').find('tbody').append(td_deparea);
                    });
                    deparea.map(function(item,index) {
                        if (item['status'] == 1) {
                            item['status'] = 'Active';
                        } else {
                            item['status'] = 'Inactive';
                        }
                        let td_deparea = '<tr>' +
                             '<td>'+ (index*1+1) +'</td>' +
                             '<td>'+ item['dep_name'] +'</td>' +
                             '<td>'+ item['category'] +'</td>' +
                             '<td>';
                        if (item['status'] == 'Active') {
                            td_deparea += '<a href="' + urlDepareaUpdate + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs mr-1 btn-outline-success deparea-update" ><i class="fa fa-pencil-alt"></i></button>';
                        }    
                        td_deparea += '<a href="' + urlDepareaDelete + '" data-form-id="'+item['id'] +'" class="btn btn-icon btn-xs btn-outline-danger deparea-delete" data-toggle="modal" data-target="#exampleModalCustomScrollable"><i class="fa fa-trash"></i></button>' +
                             '</td>' +
                          '</tr>'; 
                         $('#table-deparea').find('tbody').append(td_deparea);
                    });
                }               
            }
        });
}

$(document).ready(function() {
    var value = $('#kt_tree_1 li');
    value.each(function(index, item) {
        if ($(item).find('.jstree-anchor').attr('aria-selected') == 'true') {
            $(item).click();
        }
    });
});

$('body').delegate('.button-save-form', 'click', function(e) {
    e.preventDefault();
    $('#loading').css('display','block');
    let formModal = $(this).parents('form');
    let actionUrl = formModal.attr('action');
    ajaxModalSave(formModal, actionUrl, false);
});
$('body').delegate('.button-save-department', 'click', function(e) {
    e.preventDefault();
    $('#loading').css('display','block');
    let formModal = $(this).parents('form');
    let actionUrl = formModal.attr('action');
    ajaxModalSave(formModal, actionUrl, true);
});
    
function ajaxModalSave(formModal,actionUrl, reload){
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
                 ajaxSubmit(response.hr_department_id);
                 if(reload){
                    window.location.reload();
                 }
            }else{
                $('#loading').css('display','none');
                call_pnotify('fail',response.message);
            }
        }
    });
}

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
SweetAlertAsset::register($this);
?>