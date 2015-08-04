<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="en">

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">
        <link rel="stylesheet" type="text/css" href="css/jquery/jquery.ui.all.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="css/table_data.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="css/lightbox/style.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="css/style_blue.css" title="style_blue" media="screen"/>
        <link rel="alternate stylesheet" type="text/css" href="css/style_green.css" title="style_green" media="screen" />
      <link rel="alternate stylesheet" type="text/css" href="css/style_red.css" title="style_red" media="screen" />
        <link rel="alternate stylesheet" type="text/css" href="css/style_purple.css" title="style_purple" media="screen" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'ホーム', 'url'=>array('/site/index')),
				//array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				//array('label'=>'Contact', 'url'=>array('/site/contact')),
				//array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				//array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>
<div >
    <p class="copyright fr">Copyright 2015 Spice.Co.Ltd  All rights reserved.</p>
<!--
    <ul class="skinner fr">
        <li class="fl"><a href="#" rel="style_blue" class="styleswitch skin skin_blue fl"></a></li>
        <li class="fl"><a href="#" rel="style_green" class="styleswitch skin skin_green fl"></a></li>
        <li class="fl"><a href="#" rel="style_red" class="styleswitch skin skin_red fl"></a></li>
        <li class="fl"><a href="#" rel="style_purple" class="styleswitch skin skin_purple fl"></a></li>
        <li class="clear"></li>
    </ul>
-->
</div>


</body>
</html>
