<?php

namespace app\controllers;


use app\models\Post;
use app\models\Tag;
use app\models\Comment;
use app\models\CommentForm;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;



class UserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout = 'admin';
        return $this->render('index');
    }




    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'admin';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $loginForm = new LoginForm();
        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
            return $this->goBack();
        }
        $loginForm->setPassword( '') ;
        return $this->render('login', [
            'model' => $loginForm,
        ]);
    }
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        $this->layout = 'admin';
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
