<?php
namespace app\controllers;
use app\models\Post;
use app\models\Tag;
use app\models\Comment;
use app\models\CommentForm;
//use Codeception\Step\Comment;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
    /**
     * {@inheritdoc}
     */
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
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = Post::find()->where(['status'=>2]);
        $count = $query->count();
        $pageSize=10;
        $pagination = new Pagination(['totalCount' => $count, 'pageSize'=>$pageSize]);
        $posts = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $commentForm = new CommentForm();
        return $this->render('index',
            [
                'post'=>$posts,
                'pagination'=>$pagination,
                'commentForm'=>$commentForm
            ]);
    }



    public function actionView($id){
        $post = Post::findOne($id);
        $comments=$post->getArticleComments();
        $commentForm=new CommentForm();
        return $this->render('single',[
            'post'=>$post,
            'comments'=>$comments,
            'commentForm'=>$commentForm
        ]);
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
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
        Yii::$app->user->logout();
        return $this->goHome();
    }
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionFindtag($tagname){

        $tags=Tag::find()
            ->where(['name'=>$tagname])
            ->one();
        $posts=$tags->getPostsByTag()->select(['id','title','create_time'])
            ->where(['status'=>2])
            ->all();
        $pagination = new Pagination(['totalCount' => 10, 'pageSize'=>10]);
        return $this->render('index',
            [
                'post'=>$posts,
                'pagination'=>$pagination,
            ]);
    }
    public function  actionComment($id){
        $model = new CommentForm();
        //var_dump($id);
        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            if($model->saveComment($id))
            {
                Yii::$app->getSession()->setFlash('comment', 'Ваш комментарий будет добавлен после проверки администратором!');
                return $this->redirect(['site/view','id'=>$id]);
            }
            else{
                return $this->redirect(['error']);
            }
        }
    }
    public function getPoluparTags(){
        $tags=Tag::find()->limit(10)->all();
        return $tags;
    }
    public function getLastComments(){
        $comments=Comment::find()->where(['status'=>2])->orderBy('create_time','ASC')->limit(5)->all();
        return $comments;
    }
}