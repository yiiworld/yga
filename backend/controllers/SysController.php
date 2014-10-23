<?php
/**
 * Created by PhpStorm.
 * User: olebar
 * Date: 2014/10/22
 * Time: 16:30:15
 */

namespace backend\controllers;

use common\components\MyHelper;
use kartik\widgets\ActiveForm;
use Yii;
use backend\models\TMenu;
use yii\web\Response;

class SysController extends BackendController
{
    /**
     * 菜单管理
     * @return string
     */
    public function actionMenu()
    {
        $list = TMenu::find()->where('level=1')->all();
        return $this->render('menu',[
            'list'=>$list,
        ]);
    }
    /*
     * 添加菜单
     */
    public function actionMenumange()
    {
        $params = Yii::$app->request->get();
        if($id = $_REQUEST['id'])
            $model = TMenu::findOne($id);
        else
        {
            $model = new TMenu();
            $model->loadDefaultValues();
            $model->parentid = $params['pid'];
            $model->level = $params['level']+1;
        }
        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            if($model->save())
            {
                Yii::$app->session->setFlash('success');
                return $this->redirect(['sys/menu']);
            }
        }
        return $this->render('menumange',[
            'model'=>$model,
            'plevel'=>$params['level']
        ]);
    }

    public function actionAjaxvalidate()
    {
        $model = new TMenu();
        if(Yii::$app->request->isAjax)
        {
            $model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model,'menuname');
        }
    }
} 