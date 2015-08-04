<script type="text/javascript">
/*----------------------------------------------------------
 Global Variable
----------------------------------------------------------*/
// var curUserAuth = "<?php echo (int)Yii::app()->user->getState('userAuth'); ?>";
/*----------------------------------------------------------
 Events
----------------------------------------------------------*/
$(document).ready(function() {
    $('#DUserFormInput_dmID').trigger('change');
    //#7675
    /*if($('#DUserFormInput_userAuth').val() == 30 || $('#DUserFormInput_userAuth').val() == 90){
    	  $('#medicalAssociation').removeClass('hide');
          $('#municipalOffice').removeClass('hide');
    }*/
        
});

$('#btn-search').click(function() {
    $('#user-search-form').submit();
    return false;
});

$('#btn-confirm').click(function() {
	//#7675
	/*if($("#medicalAssociation").hasClass("hide")){
		$("#DUserFormInput_medicalAssociation").attr("checked", false);
		$("#DUserFormInput_municipalOffice").attr("checked", false);
	}*/
    var dmID = $('#DUserFormInput_dmID').val();
    if(!checkDomainIsHarmony(dmID)){
        document.getElementById('DUserFormInput_postalAddress').value = document.getElementById('DUserFormInput_postalAddress_text').value;
    }else {
        document.getElementById('DUserFormInput_postalAddress').value = document.getElementById('DUserFormInput_postalAddress_dropdown').value;
    }        
    var isInsert = $('#insert').val();
	if($("#dialog_flg").val() == '1' && isInsert == '1'){
		showDialogEquipment();
	}
	else{
	    $('#cert_flag').val('0');
	    $('#user-form').submit();
	}
    return false;
});

$('#backToIndex').click(function() {
    var url = "<?php echo Yii::app()->createUrl('user/default/setKeepState'); ?>";
    $('.null').load(url, function() {
        window.location = "<?php echo Yii::app()->createUrl('user'); ?>";
    })
    
    return false;
});
$('#btn-cert-create, #btn-cert-create-ex').click(function() {
	if($("#dialog_flg").val() == '1'){
		$('#cert_flag').val('1');
		showDialogEquipment();
	}
	else{
	    $('#cert_flag').val('1');
	    $('#user-form').submit();
	}
    return false;
});
//when enter input
$('#user-search-form input').keyup(function(e) {
    if (e.which == 13) {
        $('#btn-search').trigger('click');
    }
})

$(document).on('click', '.deleteData', function(){
    deleteData(this);
    return false;
});

$('#DUserFormInput_dmID').change(function(){
	if('select' == $(this).prop("tagName").toLowerCase()){
	<?php //#9197	141219
	if (isset($model) && ($model->getScenario() != 'update')) { ?>
	var cur_opt = $(this).find(":selected");
	if($(cur_opt).attr('proimgdm')=='1') $('#profileImage').show();
	else $('#profileImage').hide();
	<?php } ?>
	}
	
    var dmID = $(this).val();
    var institutionID = $('#t_institutionID').val();
    
    setInstitutionList(dmID, institutionID);
    //////////////////// check storage active domain ///////////////////////
    var arr = '<?php echo json_encode(Yii::app()->params['storage_active_dommain']); ?>';
    var s = "<JSON-String>";
    var arr_storage_active_dm = '';
    if ($.browser.msie  && parseInt($.browser.version, 10) === 8) {
    	arr_storage_active_dm =	eval('(' + arr + ')');
    }
    else arr_storage_active_dm = JSON.parse(arr);
    var check = false;
    var isInsert = $('#insert').val();
    for(var i=0;i<arr_storage_active_dm.length;i++){
        if(arr_storage_active_dm[i] == dmID){
            check = true;
            break;
        }   
    }
    if(check == false){
        $("#storagePathEnableRow").hide();
        $("#storagePathRow").hide();
    }
    else{
    	var user_auth = $('#user_auth').val();
    	if(user_auth != 20){
    	 $("#storagePathEnableRow").show();
         $("#storagePathRow").show();
    	}
    }
    if(isInsert == '1'){
	    $('#DUserFormInput_storagePath').val('');
	    $('#DUserFormInput_storagePathEnable').attr('checked', false);
    }
});

$('#DUserFormInput_storagePathEnable').click(function(){
    if ( $(this).is(':checked') && (!$('#DUserFormInput_storagePathEnable').prop('defaultChecked'))) {
        $('#DUserFormInput_storagePath').removeAttr('readonly');
    } else {
        $('#DUserFormInput_storagePath').attr('readonly', 'readonly');
    }
});


$('#DUserFormInput_userAuth').change(function(){
    var myVar = $(this).val();

    // control otherDomainIDs
    <?php if (isset($model) && $model->getScenario() == 'update') : ?>
    if ( myVar == 30 ) {
        $('#otherDomainIDs').removeClass('hide');
    } else {
        $('#otherDomainIDs').addClass('hide');
    }
    <?php endif ?>
    //#7675
    /*if ( myVar == 30 || myVar == 90) {
        $('#medicalAssociation').removeClass('hide');
        $('#municipalOffice').removeClass('hide');
    } else {
        $('#medicalAssociation').addClass('hide');
        $('#municipalOffice').addClass('hide');
    }*/
    // .control otherDomainIDs
});


/*----------------------------------------------------------
Show messages
----------------------------------------------------------*/
function showMessageSuccess(type) {
    var content = "";
    var str_result = "<?php echo Yii::app()->user->getFlash('result');?>";
    var err_msg = "<?php echo Yii::app()->user->getFlash('error_message');?>";    
    
    //Harmony 連携connent
    var content_ok = "<br>Harmony連携OK";
    var content_ng = "<font color='red'><br>Harmony連携NG<br>";
    var content_ng2 = "</font>";
    switch (type) {
        case 1: content = "<?php echo Yii::t('msg', 'success.delete') ?>"; break; //deleted
        case 2: content = "<?php echo Yii::t('msg', 'success.create') ?>"; break; //created
        case 3: content = "<?php echo Yii::t('msg', 'success.update') ?>"; break; //updated
        case 4: content = "<?php echo Yii::t('msg', 'certfiticate.success') ?>"; break; //create cert client ok
        case 5: content = "<?php echo Yii::t('msg', 'certfiticate.fail') ?>"; break; //create cert client NG
    }
    if(str_result){
        if(str_result == "OK"){
            content = content.concat(content_ok);        
        }else{
            content = content.concat(content_ng);        
            content = content.concat(err_msg);
            content = content.concat(content_ng2);
        } 
    }
    $('#message-success').html(content);
    $('#message-success').show();

    setTimeout(function() {
        $("#message-success").slideUp();
    }, 50000);
}
function showDialogEquipment(){
	var cert = $('#cert_flag').val();
    var isInsert = $('#insert').val();
    if(	(cert == '1' || isInsert == '1') && 
		($('#equi_dialog_open').val()==undefined || $('#equi_dialog_open').val()=='')
	){ // show dialog when insert or choose create cert at edit window
        $('#cert_flag').val('');
        url = '<?php echo Yii::app()->createUrl('user/equipment/view') ?>';
        $.post(url, null, function(data){
        	$("#diaglog_equipment").empty();
        	var config = {
                width: 500, height: 300,
                resize: false,
                modal: true,
                closeOnEscape: false,
                buttons: {
                	"キャンセル": function(){
                	    $('#equi_dialog_open').val('');
                    	$("#dialog_flg").val('1');
                    	$("#diaglog_equipment").dialog('close');
                    },
                    "OK": function(){
//                        var str = '';
//                        var str1 = '';
//                        var str2 = '';
//                        if($("#Equipment_note").val() == ''){
//	                        str1 = '備考を入力してください。';
//                        }
//                        if($("#Equipment_equipment").val() == ''){
//                        	str2 = '\n端末種別を入力してください。';
//                        }
//						str = str1.concat(str2);
//                        if(str != ''){
//	                        alert(str);
//                        }
//                        else{
							$("#equi_dialog_open").val('1');
                        	$("#diaglog_equipment").dialog('close');
                        	$('#cert_flag').val('1');	
                    		$('#DUserFormInput_certNote').val($("#Equipment_note").val());
                    		$('#DUserFormInput_certTerminal_type').val($("#Equipment_equipment").val());
                    		$('#user-form').submit();
//                        }
                    }
                }
            };

			$("#diaglog_equipment").html(data);
			$("#diaglog_equipment").dialog(config);
			$("#diaglog_equipment").dialog('open');
			$(".ui-dialog-titlebar-close").remove();
    	});   
    }
    else{
    	answer = true;
		if(answer==true && $('#DUserFormInput_storagePathEnable').prop('defaultChecked') && (!$('#DUserFormInput_storagePathEnable').is(':checked'))){
			answer = confirm('<?php echo Yii::t('msg','user.confirm.delete.storagepath')?>');
		}
		if(answer==true && (!$('#DUserFormInput_storagePathEnable').prop('defaultChecked')) && (!$('#DUserFormInput_storagePathEnable').is(':checked'))) 
			$('#DUserFormInput_storagePath').val('');
    	return (answer) ? true : false;
    }
}

function showConfirmSave(form, data, hasError) {
    if (! hasError) {
    	return showDialogEquipment();
    }
    else{
        $('#equi_dialog_open').val('');
    }
}

/*----------------------------------------------------------
Check
----------------------------------------------------------*/
function checkIsNumeric(value) {
    if (isNaN(value))
        return false;
    else
        return true;
}

function deleteData(obj) {
    answer = confirm('<?php echo Yii::t('msg', 'delete.confim') ?>');

    if (answer) {
        urlDelete = $(obj).attr('href');
        urlIndex = '<?php echo $this->createUrl('index'); ?>';

        $.post(urlDelete, '', function(){
            window.location = urlIndex;
        });
    }
}

function showStoragePath() {
    // var userAuth = $('#DUserFormInput_userAuth').val().trim();
    
    // if ( userAuth === "30" || userAuth === "90") {
    //     $('#storagePathEnableRow, #storagePathRow').removeClass('hide');
    // } else {
    //     if ( ! $('#storagePathEnableRow').hasClass('hide') ) {
    //         $('#storagePathEnableRow').addClass('hide');
    //         $('#DUserFormInput_storagePathEnable').removeAttr('checked');
    //         $('#DUserFormInput_storagePath').attr('readonly', 'readonly');
    //     }
    //     if ( ! $('#storagePathRow').hasClass('hide') ) {
    //         $('#storagePathRow').addClass('hide');
    //         $('#DUserFormInput_storagePath').val('');
    //     }
    // }

    // 3126#note-5 DatLQ 20140124
    // 3742#note-1 DatLQ 20140124
    // if ( userAuth !== "" && (curUserAuth == 30 || curUserAuth == 90 ) ) {
    //     $('#storagePathEnableRow, #storagePathRow').removeClass('hide');
    // } else {
    //     $('#storagePathEnableRow, #storagePathRow').addClass('hide');
    //     $('#DUserFormInput_storagePathEnable').removeAttr('checked');
    //     $('#DUserFormInput_storagePath').val('');
    // }
    
    return true;
}

/*----------------------------------------------------------
Get ajax data
----------------------------------------------------------*/
function setInstitutionList(dmID, institutionID) {
    url = '<?php echo Yii::app()->createUrl('user/default/ajaxGetInstitutionList/dmID') ?>/' + dmID
        + '/institutionID/' + institutionID;
    $('#DUserFormInput_institutionID').load(url);
}
function showEmailAliasDialog() {
    $("#diaglog_email_alias").html('<iframe id="modalIframeId" width="100%" height="100%" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="no" allowtransparency="true" />');
    $("#diaglog_email_alias").dialog({
        width: 500,
        height: 250
    });
    $("#modalIframeId").attr("src","<?php echo Yii::app()->createUrl( 'user/emailAlias/index' ) ?>");
    return false;
}

function reloadFormUser(elem){
    document.getElementById("departmentName").style.display = 'none';
    document.getElementById("doctorNumber").style.display = 'none';  
    document.getElementById("userInstitutionNumber").style.display = 'none';   
    
    var dmID = elem.value;    
    if(!checkDomainIsHarmony(dmID)){
        //remove maxlenght
        document.getElementById("DUserFormInput_sn_langKanji").removeAttribute('maxLength');
        document.getElementById("DUserFormInput_givenName_langKanji").removeAttribute('maxLength');
        document.getElementById("DUserFormInput_sn_langKana").removeAttribute('maxLength');
        document.getElementById("DUserFormInput_givenName_langKana").removeAttribute('maxLength');
        document.getElementById("DUserFormInput_mail").removeAttribute('maxLength');
        document.getElementById("DUserFormInput_uid").removeAttribute('maxLength');
        document.getElementById("DUserFormInput_userPassword").removeAttribute('maxLength');
        document.getElementById("DUserFormInput_userPassword").removeAttribute('maxLength');
        document.getElementById("DUserFormInput_mobile").removeAttribute('maxLength');
        document.getElementById("DUserFormInput_telephoneNumber").removeAttribute('maxLength');
        document.getElementById("DUserFormInput_postalCode").removeAttribute('maxLength');     
        
        document.getElementById("addressCityTown").style.display = 'none';
        document.getElementById("addressVillage").style.display = 'none';
        document.getElementById("addressBuildingName").style.display = 'none';
        document.getElementById("DUserFormInput_postalAddress_dropdown").style.display = 'none';
        document.getElementById("DUserFormInput_postalAddress_text").style.display = '';
        document.getElementById('lblpostalAddress').innerHTML  = '住所';
    }else {
        //maxlength set     
        var maxlength = "";
        document.getElementById("DUserFormInput_sn_langKanji").maxLength = "32";
        maxlength = document.getElementById("DUserFormInput_sn_langKanji").value;
        if(maxlength.length > 32) document.getElementById('DUserFormInput_sn_langKanji').value = maxlength.substring(0,32);  
        
        document.getElementById("DUserFormInput_givenName_langKanji").maxLength = "32";
        maxlength = document.getElementById("DUserFormInput_givenName_langKanji").value;
        if(maxlength.length > 32) document.getElementById('DUserFormInput_givenName_langKanji').value = maxlength.substring(0,32); 
        
        document.getElementById("DUserFormInput_sn_langKana").maxLength = "32";
        maxlength = document.getElementById("DUserFormInput_sn_langKana").value;
        if(maxlength.length > 32) document.getElementById('DUserFormInput_sn_langKana').value = maxlength.substring(0,32);   
        
        document.getElementById("DUserFormInput_givenName_langKana").maxLength = "32";
        maxlength = document.getElementById("DUserFormInput_givenName_langKana").value;
        if(maxlength.length > 32) document.getElementById('DUserFormInput_givenName_langKana').value = maxlength.substring(0,32); 
        
        document.getElementById("DUserFormInput_mail").maxLength = "255";
        maxlength = document.getElementById("DUserFormInput_mail").value;
        if(maxlength.length > 255) document.getElementById('DUserFormInput_mail').value = maxlength.substring(0,255);         
        
        document.getElementById("DUserFormInput_uid").maxLength = "255";
        maxlength = document.getElementById("DUserFormInput_uid").value;
        if(maxlength.length > 255) document.getElementById('DUserFormInput_uid').value = maxlength.substring(0,255);    
        
        document.getElementById("DUserFormInput_userPassword").maxLength = "16";
        maxlength = document.getElementById("DUserFormInput_userPassword").value;
        if(maxlength.length > 16) document.getElementById('DUserFormInput_userPassword').value = maxlength.substring(0,16);          
        
        document.getElementById("DUserFormInput_mobile").maxLength = "20";
        maxlength = document.getElementById("DUserFormInput_mobile").value;
        if(maxlength.length > 20) document.getElementById('DUserFormInput_mobile').value = maxlength.substring(0,20); 
        
        document.getElementById("DUserFormInput_telephoneNumber").maxLength = "20";
        maxlength = document.getElementById("DUserFormInput_telephoneNumber").value;
        if(maxlength.length > 20) document.getElementById('DUserFormInput_telephoneNumber').value = maxlength.substring(0,20);         
        
        document.getElementById("DUserFormInput_postalCode").maxLength = "8";
        maxlength = document.getElementById("DUserFormInput_postalCode").value;
        if(maxlength.length > 8) document.getElementById('DUserFormInput_postalCode').value = maxlength.substring(0,8);  
        
        //display reset
        document.getElementById("addressCityTown").style.display = '';
        document.getElementById("addressVillage").style.display = '';
        document.getElementById("addressBuildingName").style.display = '';
        document.getElementById("DUserFormInput_postalAddress_dropdown").style.display = '';
        document.getElementById("DUserFormInput_postalAddress_text").style.display = 'none';   
        document.getElementById('lblpostalAddress').innerHTML  = '住所(都道府県)';
    }

    if(!checkDomainIsHpki(dmID)){
    	$("#medicalAssociation").hide();
    	$("#municipalOffice").hide();
    }else{
    	$("#medicalAssociation").show();
    	$("#municipalOffice").show();
    }
}
function showOptionHarmony(elem){  
    var dmID = document.getElementById("DUserFormInput_dmID").value;
    if(!checkDomainIsHarmony(dmID)){
        document.getElementById("departmentName").style.display = 'none';
        document.getElementById("doctorNumber").style.display = 'none';  
        document.getElementById("userInstitutionNumber").style.display = 'none';
    }else {
        if(elem.value == 1){
            document.getElementById('lbldoctorNumber').innerHTML  = '医籍番号<font color="red"> *　</font>';
            document.getElementById("departmentName").style.display = '';
            document.getElementById("doctorNumber").style.display = '';  
            document.getElementById("userInstitutionNumber").style.display = '';       
        }else if(elem.value == 2){
            document.getElementById('lbldoctorNumber').innerHTML  = '歯科医師名簿登録番号';            
            document.getElementById("departmentName").style.display = 'none';
            document.getElementById("doctorNumber").style.display = '';  
            document.getElementById("userInstitutionNumber").style.display = 'none';        
        }else if(elem.value == 7){
            document.getElementById('lbldoctorNumber').innerHTML  = '薬剤師名簿登録番号';            
            document.getElementById("departmentName").style.display = 'none';
            document.getElementById("doctorNumber").style.display = '';  
            document.getElementById("userInstitutionNumber").style.display = 'none';        
        }else {
            document.getElementById("departmentName").style.display = 'none';
            document.getElementById("doctorNumber").style.display = 'none';  
            document.getElementById("userInstitutionNumber").style.display = 'none';            
        }
        
    }  
    
}
function checkDomainIsHarmony(dmID) {
    var harmony_domain = '<?php echo json_encode(Yii::app()->params['harmony_domain']); ?>';
    harmony_domain = harmony_domain.replace("[","");
    harmony_domain = harmony_domain.replace("]","");
    var harmony_domain_arr = harmony_domain.split(',');
    for (var i = 0; i < harmony_domain_arr.length; i++) {
        if (harmony_domain_arr[i] == dmID) {
            return true;
        }
    }
    return false;
}
function checkDomainIsHpki(dmID) {
    var hpki_domain = '<?php echo json_encode(Yii::app()->params['hpki_domain']); ?>';
    hpki_domain = hpki_domain.replace("[","");
    hpki_domain = hpki_domain.replace("]","");
    var hpki_domain_arr = hpki_domain.split(',');
    for (var i = 0; i < hpki_domain_arr.length; i++) {
        if (hpki_domain_arr[i] == dmID) {
            return true;
        }
    }
    return false;
}
</script>