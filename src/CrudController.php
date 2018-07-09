<?php

namespace MaximNiko;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

abstract class CrudController extends Controller
{

    /** @var ActiveRecord $recordClass */
    protected $recordClass;

    /** @var Model $searchClass */
    protected $searchClass;

    /* @var Model $mainFormClass */
    protected $mainFormClass;

    protected $service;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        \yii\helpers\Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel = new $this->searchClass,
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $form = new $this->mainFormClass;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->create($form);
                //return $this->redirect(['view', 'id' => $this->service->create($form)->id]);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', compact('form'));
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $form = new $this->mainFormClass($model);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($model->id, $form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', compact('form', 'model'));
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (!$model = $this->recordClass::findOne($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;

    }
}
