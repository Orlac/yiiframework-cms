<?php
// Side bar menu
$this->widget('zii.widgets.CMenu', array(
	'id' => 'main-nav',
    'items' => array(
					// dashboard
        			 array( 
							'label' => Yii::t('adminglobal', 'Dashboard'), 
							'url' => array('index/index'), 
							'linkOptions' => array( 'class' => 'nav-top-item no-submenu' ) 
						  ),
					 // System
					 array( 
							'label' => Yii::t('adminglobal', 'System'), 
							'url' => array('system'),
							'visible' => ( Yii::app()->user->checkAccess('op_settings_view_settings') || Yii::app()->user->checkAccess('op_lang_translate') ), 
							'linkOptions' => array( 'class' => 'nav-top-item' ),
							'items' => array(
											array( 
													'label' => Yii::t('adminglobal', 'Manage Settings'), 
													'visible' => Yii::app()->user->checkAccess('op_settings_view_settings'),
													'url' => array('settings/index'),
												 ),
											array( 
													'label' => Yii::t('adminglobal', 'Manage Languages'), 
													'visible' => Yii::app()->user->checkAccess('op_lang_translate'),
													'url' => array('languages/index'),
												 ),	
											),	
					 	 ),
    				 // Management
					 array( 
							'label' => Yii::t('adminglobal', 'Management'), 
							'url' => array('management'), 
							'visible' => ( Yii::app()->user->checkAccess('op_users_add_users') || Yii::app()->user->checkAccess('op_roles_add_auth') ),
							'linkOptions' => array( 'class' => 'nav-top-item' ),
							'items' => array(
											array( 
													'label' => Yii::t('adminglobal', 'Manage Members'), 
													'visible' => Yii::app()->user->checkAccess('op_users_add_users'),
													'url' => array('members/index'),
												 ),
											array( 
													'label' => Yii::t('adminglobal', 'Roles, Tasks & Operations'), 
													'visible' => Yii::app()->user->checkAccess('op_roles_add_auth'),
													'url' => array('roles/index'),
												 ),
											),
						  ),
					 // Documentation	
					 array( 
								'label' => Yii::t('adminglobal', 'Documentation'), 
								'url' => array('documentation'), 
								'visible' => ( Yii::app()->user->checkAccess('op_doc_edit_docs') || Yii::app()->user->checkAccess('op_doc_manage_comments') ),
								'linkOptions' => array( 'class' => 'nav-top-item' ),
								'items' => array(
												array( 
														'label' => Yii::t('adminglobal', 'Manage Documentation'), 
														'url' => array('documentation/index'),
														'visible' => Yii::app()->user->checkAccess('op_doc_edit_docs'),
													 ),
												array( 
														'label' => Yii::t('adminglobal', 'Manage Comments'), 
														'url' => array('documentation/comments'),
														'visible' => Yii::app()->user->checkAccess('op_doc_manage_comments'),
													 ),
												),
							  ),
					 // Custom Pages		
					 array( 
									'label' => Yii::t('adminglobal', 'Custom Pages'), 
									'url' => array('custompages'), 
									'visible' => Yii::app()->user->checkAccess('op_custompages_managepages'),
									'linkOptions' => array( 'class' => 'nav-top-item' ),
									'items' => array(
													array( 
															'label' => Yii::t('adminglobal', 'Manage Pages'), 
															'url' => array('custompages/index'),
															'visible' => Yii::app()->user->checkAccess('op_custompages_managepages'),
														 ),
													),
					 	  ),
					// Tutorials		
					 array( 
									'label' => Yii::t('adminglobal', 'Tutorials'), 
									'url' => array('tutorials'), 
									'visible' => ( Yii::app()->user->checkAccess('op_tutorials_comments') || Yii::app()->user->checkAccess('op_tutorials_manage') ),
									'linkOptions' => array( 'class' => 'nav-top-item' ),
									'items' => array(
													array( 
															'label' => Yii::t('adminglobal', 'Manage Tutorials'), 
															'url' => array('tutorials/index'),
															'visible' => Yii::app()->user->checkAccess('op_tutorials_manage'),
														 ),
													array( 
															'label' => Yii::t('adminglobal', 'Manage Comments'), 
															'url' => array('tutorials/comments'),
															'visible' => Yii::app()->user->checkAccess('op_tutorials_comments'),
														 ),	
													),
					 	  ),
					// Extensions		
					 array( 
									'label' => Yii::t('adminglobal', 'Extensions'), 
									'url' => array('extensions'), 
									'linkOptions' => array( 'class' => 'nav-top-item' ),
									'items' => array(
													array( 
															'label' => Yii::t('adminglobal', 'Manage Extensions'), 
															'url' => array('extensions/index'),
														 ),
													array( 
															'label' => Yii::t('adminglobal', 'Manage Comments'), 
															'url' => array('extensions/comments'),
														 ),	
													),
					 	  ),
					// Blog		
				 	array( 
									'label' => Yii::t('adminglobal', 'Blog'), 
									'url' => array('blog'), 
									'linkOptions' => array( 'class' => 'nav-top-item' ),
									'items' => array(
													array( 
															'label' => Yii::t('adminglobal', 'Manage Blog'), 
															'url' => array('blog/index'),
														 ),
													array( 
															'label' => Yii::t('adminglobal', 'Manage Comments'), 
															'url' => array('blog/comments'),
														 ),	
													),
					 	  ),
					// Widgets		
					 /*array( 
									'label' => Yii::t('adminglobal', 'Widgets'), 
									'url' => array('widgets'), 
									'linkOptions' => array( 'class' => 'nav-top-item' ),
									'items' => array(
													array( 
															'label' => Yii::t('adminglobal', 'Manage Widgets'), 
															'url' => array('widgets/index'),
														 ),
													),
					 	  ),*/
					// Newsletter		
					 array( 
									'label' => Yii::t('adminglobal', 'Newsletter'), 
									'url' => array('newsletter'), 
									'linkOptions' => array( 'class' => 'nav-top-item' ),
									'items' => array(
													array( 
															'label' => Yii::t('adminglobal', 'Manage Newsletters'), 
															'url' => array('newsletter/index'),
														 ),
													),
					 	  ),
					// Reports		
					 /*array( 
									'label' => Yii::t('adminglobal', 'Reports'), 
									'url' => array('reports'), 
									'linkOptions' => array( 'class' => 'nav-top-item' ),
									'items' => array(
													array( 
															'label' => Yii::t('adminglobal', 'Manage Reports'), 
															'url' => array('reports/index'),
														 ),
													),
					 	  ),*/
					// Contact Us		
					 array( 
									'label' => Yii::t('adminglobal', 'Contact Us'), 
									'url' => array('contactus'), 
									'linkOptions' => array( 'class' => 'nav-top-item' ),
									'items' => array(
													array( 
															'label' => Yii::t('adminglobal', 'Contact Us'), 
															'url' => array('contactus/index'),
														 ),
													),
					 	  ),
					// sharer		
					array( 
										'label' => Yii::t('adminglobal', 'Sharer'), 
										'url' => array('sharer'), 
										'linkOptions' => array( 'class' => 'nav-top-item' ),
										'items' => array(
														array( 
																'label' => Yii::t('adminglobal', 'Sharer'), 
																'url' => array('sharer/index'),
															 ),
														),
						 	  ),												
					),
));
?>