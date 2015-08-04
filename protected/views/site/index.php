

<div id="content">
    <div class="column full">
        <!-- message -->
        <?php
            if (Yii::app()->user->hasFlash('deleted'))
              Yii::app()->clientScript->registerScript('show-message-success', "showMessageSuccess(1);");
            if (Yii::app()->user->hasFlash('created'))          
                Yii::app()->clientScript->registerScript('show-message-success', "showMessageSuccess(2);");            
            if (Yii::app()->user->hasFlash('updated'))
              Yii::app()->clientScript->registerScript('show-message-success', "showMessageSuccess(3);");
            if (Yii::app()->user->hasFlash('cert_success'))
            	Yii::app()->clientScript->registerScript('show-message-success', "showMessageSuccess(4);");
            if (Yii::app()->user->hasFlash('cert_fail'))
            	Yii::app()->clientScript->registerScript('show-message-success', "showMessageSuccess(5);");
        ?>
        <span style="display:none;" class="message message-success" id="message-success"></span>
        <!-- //message -->
        <div class="bigbutton-area">
            <div align="center" class="grid width100 fr">
                <a class="bigbutton bigbutton-height white themed" href="<?php echo Yii::app()->createUrl('/create') ?>">
                    <span class="icon_text addnew bigbutton-height"></span>新　　規　　登　　録
                </a>
            </div>
        </div>
        <div class="clear"></div>
        <div class="title-bar">
            <div class="grid width40 fl">
                <span class="title">■ユーザーアカウント一覧</span>
            </div>

        </div>
    </div>
    
    <?php
        $this->renderPartial('_search', array(
            'formModel' => $model,
        ));
    ?>

    <div class="clear"></div>

    <?php
    $this->renderPartial('_list', array(
        'model' => $model,
        'data' => $data,
    ));
    ?>

</div>

<?php $this->renderPartial('_script'); ?>

<script language="javascript">
$(document).ready(function() {
	// LocalStorage初期化
	//SetPassInErrorNum(0);        
});
</script>