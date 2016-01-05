<?php

class SiteController extends Controller
{

    function SiteController( $id, CWebModule $module=NULL)
    {
        parent::__construct($id,$module);
        //$this->bg_image = "http://www.prguitarman.com/comics/poptart1red1.gif";
        $this->bg_image = "http://{$_SERVER['HTTP_HOST']}/logo/logo.gif";
        //$this->bg_image = "http://{$_SERVER['HTTP_HOST']}/logo/nyan_tracy.gif";
        //$this->layout='//site/nyan_layout';
    }
    
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'login' actions
				'actions'=>array('login'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform every action
				'users'=>array('@'),
			),
		);
	}
    
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
        $this->bg_image = null;
        $this->layout='//site/nyan_layout';
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
    
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
        $this->render('login',array('model'=>$model));
    }

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
    
    function actionTetris()
    {
        $this->render('tetris');
    }
    
    function actionSnake()
    {
        $this->render('snake');
    }
    
    function actionChat()
    {
        $this->render('chat');
    }
    
    function actionPostChat()
    {
        if ( isset($_REQUEST['content']) )
        {
            $txt = trim ($_REQUEST['content']);
            if ( strlen($txt) > 0 )
            {
                $model = new Chat;
                $model->user = Yii::app()->user->name;
                $model->content = $txt;
                $model->save(false);
            }
        }
    }
    
    function actionRawChat($last)
    {
        $this->renderPartial('chat-raw',array('last'=>$last));
    }
    
    function actionNyan()
    {
        $this->layout='//layouts/column1';
        $this->render('nyan',array(
            'css'=>'
                top:0;
                left:0;
                width:100%;
                height:100%;
                min-height:300px;
                ',
            'music' => true
        ));
    }
    
    function actionTest()
    {
        $this->render("test");
    }
    
    function actionForecast()
    {
        $this->render("forecast");
    }
}
