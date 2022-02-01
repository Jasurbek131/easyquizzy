<?php
namespace app\widgets\ModalWindow;
use yii\bootstrap4\Modal;
use Yii;
use yii\helpers\Url;

/**
 * @var $modal_id
 * @var $modal_size
 * @var $modal_options
 * @var $crud_name
 * @var $model
 * @var $update_button
 * @var $view_button
 * @var $delete_button
 * @var $copy_button
 * @var $save_button
 * @var $confirm_message
 * @var $fail_message
 * @var $success_message
 * @var $grid_ajax
 * @var $active_from_class
 * @var $create_button
 * @var $array_model
 * @var $pretty_url
 * @var $file_upload
 * @var $modal_header
 */
?>

<?php Modal::begin([
    'id' => $modal_id,
    'size' => $modal_size,
    'options' => $modal_options
]); ?>

<?php Modal::end();
?>
<?php

// module name
$mn = isset($module_name) ?  '/' . $module_name . '/' : '';

// Links
$widget_update = Url::to([$mn . $crud_name.'/update']);
$widget_delete = Url::to([$mn . $crud_name.'/delete']);
$widget_create = Url::to([$mn . $crud_name.'/create']);
$widget_view = Url::to([$mn . $crud_name.'/view']);
$widget_copy   = Url::to([$mn . $crud_name.'/copy']);
$widget_save   = Url::to([$mn . $crud_name.'/save']);

$this->registerJsVar('model_type',$model);
// Update button

$this->registerJsVar('widget_update',$widget_update);
$this->registerJsVar('update_button',$update_button);

// View button
$this->registerJsVar('widget_view',$widget_view);
$this->registerJsVar('view_button',$view_button);

// Delete button
$this->registerJsVar('widget_delete',$widget_delete);
$this->registerJsVar('delete_button',$delete_button);

// Copy button
$this->registerJsVar('copy_button',$copy_button);
$this->registerJsVar('widget_copy',$widget_copy);

// Save button
// Save button
$this->registerJsVar('save_button',$save_button);
$this->registerJsVar('widget_save',$widget_save);

// Messages
$this->registerJsVar('confirm_message',$confirm_message);
$this->registerJsVar('fail_message',$fail_message);
$this->registerJsVar('success_message',$success_message);

// Id of pjax for reload
$this->registerJsVar('grid_ajax',$grid_ajax);
$this->registerJsVar('active_from_class',$active_from_class);
$this->registerJsVar('modal_id',$modal_id);

// Create button
$this->registerJsVar('widget_create',$widget_create);
$this->registerJsVar('create_button',$create_button);

$this->registerJsVar('array_model',$array_model);
$this->registerJsVar('pretty_url',$pretty_url);
$this->registerJsVar('file_upload',$file_upload);
$this->registerJsVar('modal_header',$modal_header);


?>
<?php $this->registerJsFile(
    Yii::$app->request->baseUrl.'/js/widget_ModalWindow/modal-window-widget.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);
$css = <<< CSS
    .loader { margin: 0; width: 65px; } .loader:before { content: ''; display: block; padding-top: 100%; } .circular { -webkit-animation: rotate 2s linear infinite; animation: rotate 2s linear infinite; height: 100%; -webkit-transform-origin: center center; transform-origin: center center; width: 100%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto; } .path { stroke-dasharray: 1, 200; stroke-dashoffset: 0; -webkit-animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite; animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite; stroke-linecap: round; } @-webkit-keyframes rotate { 100% { -webkit-transform: rotate(360deg); transform: rotate(360deg); } } @keyframes rotate { 100% { -webkit-transform: rotate(360deg); transform: rotate(360deg); } } @-webkit-keyframes dash { 0% { stroke-dasharray: 1, 200; stroke-dashoffset: 0; } 50% { stroke-dasharray: 89, 200; stroke-dashoffset: -35px; } 100% { stroke-dasharray: 89, 200; stroke-dashoffset: -124px; } } @keyframes dash { 0% { stroke-dasharray: 1, 200; stroke-dashoffset: 0; } 50% { stroke-dasharray: 89, 200; stroke-dashoffset: -35px; } 100% { stroke-dasharray: 89, 200; stroke-dashoffset: -124px; } } @-webkit-keyframes color { 100%, 0% { stroke: #d62d20; } 40% { stroke: #0057e7; } 66% { stroke: #008744; } 80%, 90% { stroke: #ffa700; } } @keyframes color { 100%, 0% { stroke: #d62d20; } 40% { stroke: #0057e7; } 66% { stroke: #008744; } 80%, 90% { stroke: #ffa700; } }
CSS;
$this->registerCss($css);
?>
