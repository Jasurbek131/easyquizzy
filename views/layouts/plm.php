<?php
/* @var $this \yii\web\View */

/* @var $content string */

use app\components\Menu\CustomMenu;
use app\components\Permission\PermissionHelper as P;
use app\widgets\Alert;
use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>
    <title>Dataprizma-Plm <?= Html::encode($this->title) ?></title>
    <noembed><?= Html::encode($this->title) ?></noembed>
    <?php $this->head() ?>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
</head>
<body class="hold-transition sidebar-mini skin-blue <?= isset($this->params['bodyClass']) ? $this->params['bodyClass'] : "" ?>">
<!--sidebar-mini-expand-feature fixed-->
<?php $this->beginBody() ?>
<!-- Site wrapper -->
<div class="wrapper">
    <div id="loading" <?= Yii::$app->request->isAjax ? 'style="display:none"' : '' ?>>
        <!-- begin overlay tags -->
        <div class="overlay-body show"></div>
        <div class="spanner-body show">
            <div class="center__block">
                <div class="loader-ajax"></div>
                <p class="spanner-text"><?php echo Yii::t('app', 'Iltimos kuting!..') ?></p>
            </div>
        </div>
        <!-- end overlay tags -->
    </div>


    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Contact</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block">
                    <form class="form-inline">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                   aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>

            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-comments"></i>
                    <span class="badge badge-danger navbar-badge">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Brad Diesel
                                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">Call me whenever you can...</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    John Pierce
                                    <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">I got your message bro</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Nora Silvester
                                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">The subject goes here</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                </div>
            </li>

            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 friend requests
                        <span class="float-right text-muted text-sm">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 new reports
                        <span class="float-right text-muted text-sm">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>

            <!-- Language Dropdown Menu -->
            <li class="nav-item dropdown">
                <?= \app\widgets\MultiLang\MultiLang::widget(['cssClass' => 'pull-right language']); ?>
            </li>

            <!-- User Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                    <span><?php echo Yii::$app->user->identity->username; ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#">
                        <?php
                        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                            . Html::submitButton(
                                'Chiqish (' . Yii::$app->user->identity->username . ')',
                                ['class' => 'btn btn-link logout',]
                            )
                            . Html::endForm()
                        ?>
                    </a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#"
                   role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>

        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a class="brand-link">
            <img src="/web/img/noimage.png" alt="Logo" class="brand-image img-circle elevation-3">
            <span class="brand-text font-weight-light">PLM</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="/web/img/user.jfif" class="img-circle" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?=Yii::$app->user->identity->username?></a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <?php
                $module = Yii::$app->controller->module->id;
                $controller = Yii::$app->controller->id;
                $action = Yii::$app->controller->action->id;
                $slug = Yii::$app->request->get('slug');

                echo CustomMenu::widget([
                    'options' => [
                        'class' => 'nav nav-pills nav-sidebar flex-column tree',
                        'data-widget' => 'treeview',
                        'role' => "menu",
                        'data-accordion' => "false"
                    ],
                    'items' => [
                        [
                            'label' => Yii::t('app', 'Administrator'),
                            'url' => ['#'],
                            'options' => ['class' => 'nav-item'],
                            'template' => '<a href="{url}" class="{linkClass}"><i class="nav-icon fas fa-user"></i><p>{label}<i class="fas fa-angle-left right"></i></p></a>',
                            'visible' => true,
                            'items' => [
                                [
                                    'label' => Yii::t('app', 'Users'),
                                    'url' => '/admin/users/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'users' && $action == 'index',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-tasks nav-icon"></i><p>{label}</p></a>',
                                ],
                                [
                                    'label' => Yii::t('app', 'Roles'),
                                    'url' => '/admin/auth-item/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'auth-item' && $action == 'index',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-tasks nav-icon"></i><p>{label}</p></a>',
//                                        'visible' => P::can('auth-item/index'),
                                ],
                                [
                                    'label' => Yii::t('app', 'Permissions'),
                                    'url' => '/admin/auth-item/permissions',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'auth-item' && $action == 'permissions',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-tasks nav-icon"></i><p>{label}</p></a>',
//                                        'visible' => P::can('auth-item/permissions'),

                                ],
                            ],
                        ],
                        [
                            'label' => Yii::t('app', 'Hr'),
                            'url' => ['#'],
                            'options' => ['class' => 'nav-item'],
                            'template' => '<a href="{url}" class="{linkClass}"><i class="nav-icon fas fa-users"></i><p>{label}<i class="fas fa-angle-left right"></i></p></a>',
                            'visible' => true,
                            'items' => [
                                [
                                    'label' => Yii::t('app', 'Hr Departments'),
                                    'url' => '/hr/hr-departments/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'hr-departments' && $action == 'index',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-building nav-icon"></i><p>{label}</p></a>',
                                ],
                                [
                                    'label' => Yii::t('app', 'Hr Employees'),
                                    'url' => '/hr/hr-employee/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'hr-employee' && $action == 'index',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-user nav-icon"></i><p>{label}</p></a>',
                                ],
                                [
                                    'label' => Yii::t('app', 'Hr Positions'),
                                    'url' => '/hr/hr-positions/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'hr-positions' && $action == 'index',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-pager nav-icon"></i><p>{label}</p></a>',
                                ],
                            ],
                        ],
                        [
                            'label' => Yii::t('app', 'References'),
                            'url' => ['#'],
                            'options' => ['class' => 'nav-item'],
                            'template' => '<a href="{url}" class="{linkClass}"><i class="nav-icon fa fa-book"></i><p>{label}<i class="fas fa-angle-left right"></i></p></a>',
                            'visible' => true,
                            'items' => [
                                [
                                    'label' => Yii::t('app', 'Shifts'),
                                    'url' => '/references/shifts/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'shifts' && $action  == 'index',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-pager nav-icon"></i><p>{label}</p></a>',
                                ],
                                [
                                    'label' => Yii::t('app', 'Time Types List'),
                                    'url' => '/references/time-types-list/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'time-types-list' && $action  == 'index',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-calendar nav-icon"></i><p>{label}</p></a>',
                                ],
                                [
                                    'label' => Yii::t('app', 'Products'),
                                    'url' => '/references/products/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'products' && $action  == 'index',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-shopping-bag nav-icon"></i><p>{label}</p></a>',
                                ],
                                [
                                    'label' => Yii::t('app', 'Equipment Types'),
                                    'url' => '/references/equipment-types/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'equipment-types' && $action  == 'index',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-shopping-bag nav-icon"></i><p>{label}</p></a>',
                                ],
                                [
                                    'label' => Yii::t('app', 'Equipments'),
                                    'url' => '/references/equipments/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'equipments' && $action  == 'index',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-shopping-bag nav-icon"></i><p>{label}</p></a>',
                                ],
//                                [
//                                    'label' => Yii::t('app', 'Equipment Group'),
//                                    'url' => '/references/equipment-group/index',
//                                    'options' => ['class' => 'nav-item'],
//                                    'active' => $controller == 'equipment-group' && $action  == 'index',
//                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-shopping-bag nav-icon"></i><p>{label}</p></a>',
//                                ],
                                [
                                    'label' => Yii::t('app', 'Product Lifecycle'),
                                    'url' => '/references/product-lifecycle/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'product-lifecycle' && $action  == 'index',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-shopping-bag nav-icon"></i><p>{label}</p></a>',
                                ],
                                [
                                    'label' => Yii::t('app', 'Defects'),
                                    'url' => '/references/defects/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'defects' && $action  == 'index',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-exclamation nav-icon"></i><p>{label}</p></a>',
                                ],
                            ],
                        ],
                        [
                            'label' => Yii::t('app', 'Plm'),
                            'url' => ['#'],
                            'options' => ['class' => 'nav-item'],
                            'template' => '<a href="{url}" class="{linkClass}"><i class="nav-icon fas fa-file-alt"></i><p>{label}<i class="fas fa-angle-left right"></i></p></a>',
                            'visible' => true,
                            'items' => [
                                [
                                    'label' => Yii::t('app', 'Plm Documents'),
                                    'url' => '/plm/plm-documents/document/index',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'plm-documents' && $action == 'document',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-file nav-icon"></i><p>{label}</p></a>',
                                ],
                            ],
                        ],
                        [
                            'label' => Yii::t('app', 'Report'),
                            'url' => ['#'],
                            'options' => ['class' => 'nav-item'],
                            'template' => '<a href="{url}" class="{linkClass}"><i class="nav-icon fas fa-file-alt"></i><p>{label}<i class="fas fa-angle-left right"></i></p></a>',
                            'visible' => true,
                            'items' => [
                                [
                                    'label' => Yii::t('app', 'Documents'),
                                    'url' => '/plm/plm-report/document',
                                    'options' => ['class' => 'nav-item'],
                                    'active' => $controller == 'plm-report' && $action == 'document',
                                    'template' => '<a href="{url}" class="{linkClass}"><i class="fa fa-file nav-icon"></i><p>{label}</p></a>',
                                ],
                            ],
                        ],
                    ],

                    'linkTemplate' => '<a href="{url}"><i class="fas fa-circle-o"></i> {label}</a>',
                    'submenuTemplate' => "\n<ul class='nav nav-treeview'>\n{items}\n</ul>\n",
                    'encodeLabels' => false,
                    'activateParents' => true,
                ]);
                ?>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-header card" style="margin-bottom: 0!important;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="m-0 text-dark text-title-all ml-1"><i class="fa fa-spin fa-spinner text-info"></i></h6>
                    </div>
                    <div class="col-sm-6">
                        <?php try {
                            echo \yii\widgets\Breadcrumbs::widget([
                                'options' => [
                                    'class' => 'breadcrumb breadcrumb float-sm-right',
                                ],
                                'tag' => 'ul',
                                'activeItemTemplate' => "<li>&nbsp;/&nbsp;{link}</li>\n",
                                'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>",
                                'links' => $this->params['breadcrumbs'] ?? [],
                            ]);
                        } catch (Exception $e) {
                            Yii::info('Error Breadcrumbs Main ' . $e->getMessage(), 'widgets');
                        } ?>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>

        <?php
        $message = Alert::widget();
        if (!empty($message)): ?>
        <div class="row">
            <div class="col-12" style="padding: 5px 15px 0 15px;">
                <?= $message ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12" style="padding: 5px 15px 10px 15px;">
                <?= $content ?>
            </div>
        </div>
    </div>
    <footer class="main-footer no-print">
        <strong class="text-orange">&copy; Dataprizma-PLM </strong> <?= date('Y') ?>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<script>
    window.onload = function () {
        document.getElementById("loading").style.display = "none";
    }
</script>
<?php
$css = <<<CSS
#loading{
    z-index: 999999;
}
.sidebar-mini:not(.sidebar-mini-expand-feature).sidebar-collapse .sidebar-menu>li:hover>.treeview-menu{
    top:40px!important;
}
#snow{
    position:fixed;
    top: 0;
    left: 0;
    width: 0;
    display:block;
    text-align:center;
    height: 120vh;
    color: #FFF;
    z-index: 999;
}
.spanner-body{
    position:fixed;
    top: 0;
    left: 0;
    background: rgba(0,0,0,0.6);
    width: 100%;
    display:block;
    text-align:center;
    height: 100vh;
    color: #FFF;
    z-index: 10000;
    visibility: hidden;
}
.center__block{
    position: absolute;
    top: 30%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.overlay-body{
    position: fixed;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    visibility: hidden;
}
.spanner-body .spanner-text{
    font-size: 35px;
}
.loader-ajax,
.loader-ajax:before,
.loader-ajax:after {
    border-radius: 50%;
    width: 2.5em;
    height: 2.5em;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
    -webkit-animation: load7 1.8s infinite ease-in-out;
    animation: load7 1.8s infinite ease-in-out;
}
.loader-ajax {
    color: #ffffff;
    font-size: 14px;
    margin: 80px auto;
    position: relative;
    text-indent: -9999em;
    -webkit-transform: translateZ(0);
    -ms-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-animation-delay: -0.16s;
    animation-delay: -0.16s;
}
.loader-ajax:before,
.loader-ajax:after {
    content: '';
    position: absolute;
    top: 0;
}
.loader-ajax:before {
    left: -3.5em;
    -webkit-animation-delay: -0.32s;
    animation-delay: -0.32s;
}
.loader-ajax:after {
    left: 3.5em;
}
@-webkit-keyframes load7 {
    0%,
    80%,
    100% {
        box-shadow: 0 2.5em 0 -1.3em;
    }
    40% {
        box-shadow: 0 2.5em 0 0;
    }
}
@keyframes load7 {
    0%,
    80%,
    100% {
        box-shadow: 0 2.5em 0 -1.3em;
    }
    40% {
        box-shadow: 0 2.5em 0 0;
    }
}

.show{
    visibility: visible;
}

.spanner-body, .overlay-body{
    opacity: 0;
    -webkit-transition: all 0.3s;
    -moz-transition: all 0.3s;
    transition: all 0.3s;
}

.spanner-body.show, .overlay.show {
    opacity: 1
}
CSS;
$this->registerCss($css);
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
