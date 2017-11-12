<?php

namespace app\controllers;

use app\models\Event;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\User;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister()
    {
        $user = new User();
        if($user -> load(Yii::$app->request->post())){
            $user->permission = 2;
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
            $user->signUp();
            return $this->render('reg_confirm');
        }
        return $this->render("register", ["user"=>$user]);
    }

    public function actionView()
    {
        $user = User::find()->where(['email' => Yii::$app->user->identity->email])->one();
        if($user -> load(Yii::$app->request->post())){
            $user->signUp();
        }
        return $this->render("view", ["user"=>$user]);
    }

    public function actionUpdate($id){
        $user = User::findOne(['id'=>$id]);
        if($user -> load(Yii::$app->request->post())){
            if ($user->signUp()){
                Yii::$app->getSession()->setFlash('success', 'A felhasználót adatait sikeresen frissítettem!');
            }else{
                Yii::$app->getSession()->setFlash('error', 'Belső hiba történt!');
            }
            return $this->render("view", ["user"=>$user]);
        }
        return $this->render('update',['model'=>$user]);
    }

    public function actionEvents()
    {
        $event = new Event();
        if($event -> load(Yii::$app->request->post()))
        {
            $event->save();
        }
        return $this->render("events", ["event"=>$event]);
    }
}
