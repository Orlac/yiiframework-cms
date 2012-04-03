<?php
/**
 * custom pages controller Home page
 */
class CustompagesController extends SiteBaseController {
	/**
	 * Controller constructor
	 */
    public function init()
    {
        parent::init();
    }

	/**
	 * Index action
	 */
    public function actionIndex() 
	{
        if( isset($_GET['alias']) && ( $model = CustomPages::model()->find('alias=:alias AND language=:lang', array( ':lang' => Yii::app()->language,  ':alias' => $_GET['alias'] )) ) )
		{	
			// Page is active?
			if( !$model->status )
			{
				throw new CHttpException(404, Yii::t('error', 'Sorry, The page you were looking for was not found.'));
			}	
			
			// Check if the language is set for a certain language
			if( Yii::app()->language != $model->language )
			{
				// Error
				throw new CHttpException(404, Yii::t('error', 'Sorry, The page you were looking for was not found.'));
			}
			
			// Check if we can view it
			if( $model->visible )
			{
				$permcheck = false;
				
				if( count( explode(',', $model->visible) ) )
				{
					foreach(explode(',', $model->visible) as $perm)
					{
						if( Yii::app()->user->checkAccess($perm) )
						{
							$permcheck = true;
							break;
						}
					}
				}
				
				if( !$permcheck )
				{
					throw new CHttpException(403, Yii::t('error', 'Sorry, You are not authorized to view this page.'));
				}
			}
			
			// Add in the meta keys and description if any
			if( $model->metadesc )
			{
				Yii::app()->clientScript->registerMetaTag( $model->metadesc, 'description' );
			}
			
			if( $model->metakeys )
			{
				Yii::app()->clientScript->registerMetaTag( $model->metakeys, 'keywords' );
			}
			
			// Add page breadcrumb and title
			$this->pageTitle[] = Yii::t('custompages', $model->title);
			$this->breadcrumbs[ Yii::t('custompages', $model->title) ] = '';
			
			$this->render('page', array( 'model' => $model ));
		}
		else
		{
			throw new CHttpException(404, Yii::t('error', 'Sorry, The page you were looking for was not found.'));
		}
    }
}