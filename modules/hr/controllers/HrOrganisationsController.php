<?php


namespace app\modules\hr\controllers;

use app\modules\hr\models\HrOrganisations;
use kartik\tree\controllers\NodeController;
use kartik\tree\TreeSecurity;
use kartik\tree\TreeView;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class HrOrganisationsController extends NodeController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
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
        return $this->render('index', [
            'query' => HrOrganisations::find()->addOrderBy('root, lft')
        ]);
    }

    /**
     * @return \yii\web\Response
     * @throws ErrorException
     * @throws InvalidConfigException
     */
    public function actionSave()
    {
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
                $successMsg = Yii::t('app', 'The {node} was successfully created.', $nodeTitles);
                $errorMsg = Yii::t('app', 'Error while creating the {node}. Please try again later.', $nodeTitles);
            } else {
                $tag = explode("\\", $treeClass);
                $tag = array_pop($tag);
                $id = $post[$tag][$keyAttr];
                $node = $treeClass::findOne($id);
                $successMsg = Yii::t('app', 'Saved the {node} details successfully.', $nodeTitles);
                $errorMsg = Yii::t('app', 'Error while saving the {node}. Please try again later.', $nodeTitles);
            }
            $node->activeOrig = $node->active;
            $isNewRecord = $node->isNewRecord;
            $node->load($post);
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
            } else {
                $errorMsg = '<ul style="margin:0"><li>' . implode('</li><li>', $node->getFirstErrors()) . '</li></ul>';
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

}