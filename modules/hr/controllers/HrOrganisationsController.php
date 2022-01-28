<?php


namespace app\modules\hr\controllers;


use app\modules\hr\models\HrDepartments;
use kartik\tree\controllers\NodeController;
use kartik\tree\models\Tree;
use kartik\tree\Module;
use kartik\tree\TreeSecurity;
use kartik\tree\TreeView;
use Yii;
use yii\base\ErrorException;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;
use yii\web\View;

class HrDepartmentsController extends NodeController
{
    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    /*public function beforeAction($action)
    {
        if (Yii::$app->authManager->getPermission(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)) {
            if (!P::can(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)) {
                throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
            }
        }

        return parent::beforeAction($action);
    }*/

    public function actionIndex()
    {

        return $this->render('index');
    }

    /**
     * Saves a node once form is submitted
     * @throws InvalidConfigException
     * @throws ErrorException
     */
    public function actionSave()
    {
        /**
         * @var Module $module
         * @var Tree $node
         * @var Tree $parent
         * @var \yii\web\Session $session
         */
        $post = Yii::$app->request->post();
        static::checkValidRequest(false, !isset($post['treeNodeModify']));
        $data = static::getPostData();
        $parentKey = ArrayHelper::getValue($data, 'parentKey', null);
        $treeNodeModify = ArrayHelper::getValue($data, 'treeNodeModify', null);
        $currUrl = ArrayHelper::getValue($data, 'currUrl', '');
        $treeClass = TreeSecurity::getModelClass($data);
        $module = TreeView::module();
        $keyAttr = $module->dataStructure['keyAttribute'];
        $nodeTitles = TreeSecurity::getNodeTitles($data);

        if ($treeNodeModify) {
            $node = new $treeClass;
            $successMsg = Yii::t('app', "Muvaffaqiyatli yaratildi");//Yii::t('app', 'The {node} was successfully created.', $nodeTitles);
            $errorMsg = Yii::t('app', "Yaratilmadi!");//Yii::t('app', 'Error while creating the {node}. Please try again later.', $nodeTitles);
        } else {
            $tag = explode("\\", $treeClass);
            $tag = array_pop($tag);
            $id = $post[$tag][$keyAttr];
            $node = $treeClass::findOne($id);
            $successMsg = Yii::t('app', "Ma'lumotlar saqlandi.");//Yii::t('app', 'Saved the {node} details successfully.', $nodeTitles);
            $errorMsg = Yii::t("app", "Ma'lumotni saqlashda xatolik yuz berdi");
            Yii::t('app', 'Error while saving the {node}. Please try again later.', $nodeTitles);
        }
        $node->activeOrig = $node->active;
        $isNewRecord = $node->isNewRecord;
        Yii::debug($node->type, 'root tree'); // TODO: delete
        $node->load($post);

        // node is root?
        $isRoot = $isNewRecord
            ? $parentKey === TreeView::ROOT_KEY
            : $node->type === HrDepartments::TYPE_ORGANIZATION;

        Yii::debug($isRoot, 'is root');
        Yii::debug($parentKey, 'parent key');
        Yii::debug($node->type, 'node type');
        Yii::debug($id, 'id');

        if ($node !== null) {
            if ($isRoot) {
                $infoModel = HrOrganizationInfo::findOne(['department_id' => $id]);
                if ($infoModel === null) {
                    $infoModel = new HrOrganizationInfo();
                }
                /***************** tashkilot fayllari mavjudmi? *********** */
                if ($node->attachments) {

                }

                /***************** end tashkilot fayllari mavjudmi? *********** */

            } elseif (($infoModel = HrDepartmentsInfo::findOne(['department_id' => $id])) === null) {
                $infoModel = new HrDepartmentsInfo();
            }
        } else {
            $infoModel = $isRoot ? new HrOrganizationInfo() : new HrDepartmentsInfo();
        }

        // new file model
//        $fileModel = new Attachments();
        $uploadForm = new UploadForm(['scenario' => UploadForm::SCENARIO_UPLOAD_FILE]);
        $uploadForm->file = UploadedFile::getInstance($uploadForm, 'file');
        Yii::debug($uploadForm->file, 'file upload');
        Yii::debug($infoModel, 'info model');
        $infoModel->load($post);

        $errors = $success = false;
        if (Yii::$app->has('session')) {
            $session = Yii::$app->session;
        }
        if ($treeNodeModify) {
            if ($parentKey == TreeView::ROOT_KEY) {
                $node->makeRoot();
            } else {
                $parent = $treeClass::findOne($parentKey);
                if ($parent->isChildAllowed()) {
                    $node->appendTo($parent);
                } else {
                    $errorMsg = Yii::t('app', 'You cannot add children under this {node}.', $nodeTitles);
                    if (Yii::$app->has('session')) {
                        $session->setFlash('error', $errorMsg);
                    } else {
                        throw new ErrorException("Error saving {node}!\n{$errorMsg}", $nodeTitles);
                    }
                    return $this->redirect($currUrl);
                }
            }
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            /*// bo'lim yoki tashkilotligini o'rnatish
            if ($isNewRecord) {
                if ($parentKey == TreeView::ROOT_KEY) {
                    $node->type = HrDepartments::TYPE_ORGANIZATION;
                } else {
                    $node->type = HrDepartments::TYPE_DEPARTMENT;
                }
            }*/

            if ($node->save()) {
                // check if active status was changed
                if (!$isNewRecord && $node->activeOrig != $node->active) {
                    if ($node->active) {
                        $success = $node->activateNode(false);
                        $errors = $node->nodeActivationErrors;
                    } else {
                        $success = $node->removeNode(true, false); // only deactivate the node(s)
                        $errors = $node->nodeRemovalErrors;
                    }
                } else {
                    $success = true;
                }
                if (!empty($errors)) {
                    $success = false;
                    $errorMsg = "<ul style='padding:0'>\n";
                    foreach ($errors as $err) {
                        $errorMsg .= "<li>" . Yii::t('app', "{node} # {id} - '{name}': {error}",
                                $err + $nodeTitles) . "</li>\n";
                    }
                    $errorMsg .= "</ul>";
                }

                //save info model
                if ($isNewRecord) {
                    $infoModel->department_id = $node->id;
                }

                if ($infoModel->save()) {

                    /******************** save image model ************************/
                    if ($uploadForm->file) {
                        /* $fileModel->name = $uploadForm->file->baseName . '.' . $uploadForm->file->extension;
                         $fileModel->md5_hash = md5_file($uploadForm->file->tempName);
                         $fileModel->size = $uploadForm->file->size;
                         $fileModel->extension = $uploadForm->file->extension;
                         $fileModel->path = UploadForm::getMd5FilePath($fileModel->md5_hash . '.' . $uploadForm->file->extension);
                         $fileModel->status = 1;
                         // Tashkilotning barcha faylini saqlash
                         if (($savedNode = HrDepartments::findOne(['id' => $node->id])) !== null
                             && $fileModel->validate()
                             && $savedNode->link('attachments', $fileModel)
                             && $uploadForm->upload()) {
                             $transaction->commit();
                         } else {
                             $transaction->rollBack();
                         }
                         Yii::debug($fileModel->getErrors(), 'file model val.');*/
                    }
                    /******************** end save image model ********************/

                    $transaction->commit();
                } else {
                    Yii::debug($infoModel->getErrors(), 'info model has error');
                    $transaction->rollBack();
                }
            } else {
                $errorMsg = '<ul style="margin:0"><li>' . implode('</li><li>', $node->getFirstErrors()) . '</li></ul>';
                $transaction->rollBack();
            }
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), 'exception');
            $transaction->rollBack();
        }

        if (Yii::$app->has('session')) {
            $session->set(ArrayHelper::getValue($post, 'nodeSelected', 'kvNodeId'), $node->{$keyAttr});
            if ($success) {
                $session->setFlash('success', $successMsg);
            } else {
                $session->setFlash('error', $errorMsg);
            }
        } elseif (!$success) {
            throw new ErrorException("Error saving {node}!\n{$errorMsg}", $nodeTitles);
        }
        return $this->redirect($currUrl);
    }

    /**
     * View, create, or update a tree node via ajax
     *
     * @return mixed json encoded response
     */
    public function actionManage()
    {
        static::checkValidRequest();
        $data = static::getPostData();
        $nodeTitles = TreeSecurity::getNodeTitles($data);
        $callback = function () use ($data, $nodeTitles) {
            $id = ArrayHelper::getValue($data, 'id', null);
            $parentKey = ArrayHelper::getValue($data, 'parentKey', '');
            $parsedData = TreeSecurity::parseManageData($data);
            $out = $parsedData['out'];
            $oldHash = $parsedData['oldHash'];
            $newHash = $parsedData['newHash'];
            /**
             * @var Module $module
             * @var Tree $treeClass
             * @var Tree $node
             */
            $treeClass = $out['treeClass'];
            if (!isset($id) || empty($id)) {
                $node = new $treeClass;
                $node->initDefaults();

                // info model
                $infoModel = $parentKey == TreeView::ROOT_KEY ? new HrOrganizationInfo() : new HrDepartmentsInfo();
            } else {
                $node = $treeClass::findOne($id);

                // oldindan
                if ($node !== null) {
                    if ($node->type === HrDepartments::TYPE_ORGANIZATION && ($infoModel = HrOrganizationInfo::findOne(['department_id' => $id])) === null) {
                        $infoModel = new HrOrganizationInfo();
                    } elseif ($node->type === HrDepartments::TYPE_DEPARTMENT && ($infoModel = HrDepartmentsInfo::findOne(['department_id' => $id])) === null) {
                        $infoModel = new HrDepartmentsInfo();
                    }
                } else {
                    $infoModel = $parentKey == TreeView::ROOT_KEY ? new HrOrganizationInfo() : new HrDepartmentsInfo();
                }

                Yii::debug($infoModel, 'infoModel'); // TODO: bu qatorni o'chirish kerak
            }
            // upload model
//            $fileModel = new Attachments();
            $uploadForm = new UploadForm();

            $module = TreeView::module();
            $params = $module->treeStructure + $module->dataStructure + [
                    'node' => $node,
                    'infoModel' => $infoModel,
                    'uploadForm' => $uploadForm,
                    'parentKey' => $parentKey,
                    'treeManageHash' => $newHash,
                    'treeRemoveHash' => ArrayHelper::getValue($data, 'treeRemoveHash', ''),
                    'treeMoveHash' => ArrayHelper::getValue($data, 'treeMoveHash', ''),
                ] + $out;
            if (!empty($data['nodeViewParams'])) {
                $params = ArrayHelper::merge($params, unserialize($data['nodeViewParams']));
            }
            if (!empty($module->unsetAjaxBundles)) {
                $cb = function ($e) use ($module) {
                    foreach ($module->unsetAjaxBundles as $bundle) {
                        unset($e->sender->assetBundles[$bundle]);
                    }
                };
                Event::on(View::class, View::EVENT_AFTER_RENDER, $cb);
            }
            TreeSecurity::checkSignature('manage', $oldHash, $newHash);
            return $this->renderAjax($out['nodeView'], ['params' => $params]);
        };
        return self::process(
            $callback,
            Yii::t('app', 'Error while viewing the {node}. Please try again later.', $nodeTitles),
            null
        );
    }

    /**
     * Remove a tree node
     */
    public function actionRemove()
    {
        static::checkValidRequest();
        $data = static::getPostData();
        $nodeTitles = TreeSecurity::getNodeTitles($data);
        $callback = function () use ($data) {
            $id = ArrayHelper::getValue($data, 'id', null);
            $parsedData = TreeSecurity::parseRemoveData($data);
            $out = $parsedData['out'];
            $oldHash = $parsedData['oldHash'];
            $newHash = $parsedData['newHash'];
            /**
             * @var Tree $treeClass
             * @var Tree $node
             */
            $treeClass = $out['treeClass'];
            TreeSecurity::checkSignature('remove', $oldHash, $newHash);
            /**
             * @var Tree $node
             */
            $node = $treeClass::findOne($id);
            return $node->removeNode($out['softDelete']);
        };
        return self::process(
            $callback,
            Yii::t('app', 'Error removing the {node}. Please try again later.', $nodeTitles),
            Yii::t('app', 'The {node} was removed successfully.', $nodeTitles)
        );
    }

    /**
     * Move a tree node
     */
    public function actionMove()
    {
        static::checkValidRequest();
        $data = static::getPostData();
        $dir = ArrayHelper::getValue($data, 'dir', null);
        $idFrom = ArrayHelper::getValue($data, 'idFrom', null);
        $idTo = ArrayHelper::getValue($data, 'idTo', null);
        $parsedData = TreeSecurity::parseMoveData($data);
        /**
         * @var Tree $treeClass
         * @var Tree $node
         */
        $treeClass = $parsedData['out']['treeClass'];
        $nodeTitles = TreeSecurity::getNodeTitles($data);
        /**
         * @var Tree $nodeFrom
         * @var Tree $nodeTo
         */
        $nodeFrom = $treeClass::findOne($idFrom);
        $nodeTo = $treeClass::findOne($idTo);
        $isMovable = $nodeFrom->isMovable($dir);
        $errorMsg = $isMovable ?
            Yii::t('app', 'Error while moving the {node}. Please try again later.', $nodeTitles) :
            Yii::t('app', 'The selected {node} cannot be moved.', $nodeTitles);
        $callback = function () use ($dir, $parsedData, $isMovable, $nodeFrom, $nodeTo, $nodeTitles) {
            $out = $parsedData['out'];
            $oldHash = $parsedData['oldHash'];
            $newHash = $parsedData['newHash'];
            if (!empty($nodeFrom) && !empty($nodeTo)) {
                TreeSecurity::checkSignature('move', $oldHash, $newHash);
                if (!$isMovable || ($dir !== 'u' && $dir !== 'd' && $dir !== 'l' && $dir !== 'r')) {
                    return false;
                }
                if ($dir === 'r') {
                    $nodeFrom->appendTo($nodeTo);
                } else {
                    if ($dir === 'l' && $nodeTo->isRoot() && $out['allowNewRoots']) {
                        $nodeFrom->makeRoot();
                    } elseif ($nodeTo->isRoot()) {
                        throw new \Exception(Yii::t('app',
                            'Cannot move root level {nodes} before or after other root level {nodes}.', $nodeTitles));
                    } elseif ($dir == 'u') {
                        $nodeFrom->insertBefore($nodeTo);
                    } else {
                        $nodeFrom->insertAfter($nodeTo);
                    }
                }
                return $nodeFrom->save();
            }
            return true;
        };
        return self::process($callback, $errorMsg, Yii::t('app', 'The {node} was moved successfully.', $nodeTitles));
    }
}