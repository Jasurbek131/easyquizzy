use yii\filters\VerbFilter;
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
        return $this->render('index', [
            'query' => HrOrganisations::find()->addOrderBy('root, lft')
        ]);
     * @return \yii\web\Response
     * @throws InvalidConfigException
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
                    $parent = $treeClass::findOne($parentKey);
                    if ($parent->isChildAllowed()) {
                        $node->appendTo($parent);
                        $errorMsg = Yii::t('app', 'You cannot add children under this {node}.', $nodeTitles);
                        if (Yii::$app->has('session')) {
                            $session->setFlash('error', $errorMsg);
                        } else {
                            throw new ErrorException("Error saving {node}!\n{$errorMsg}", $nodeTitles);
                        }
                        return $this->redirect($currUrl);
            if (Yii::$app->has('session')) {
                $session->set(ArrayHelper::getValue($post, 'nodeSelected', 'kvNodeId'), $node->{$keyAttr});
                if ($success) {
                    $session->setFlash('success', $successMsg);
                    $session->setFlash('error', $errorMsg);
            } elseif (!$success) {
                throw new ErrorException("Error saving {node}!\n{$errorMsg}", $nodeTitles);
            return $this->redirect($currUrl);