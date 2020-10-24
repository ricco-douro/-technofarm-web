var AjaxObj = new Object();
AjaxObj.showMessage=1;
AjaxObj.Message='';
AjaxObj.favouriteId = 0;
AjaxObj.Request = function(url, callbackMethod)
{
	AjaxObj.request = AjaxObj.createRequestObject();
	AjaxObj.request.onreadystatechange = callbackMethod;
	AjaxObj.request.open("POST", url, true);
	AjaxObj.request.send(url);
}

AjaxObj.setMessage = function (message)
{
	AjaxObj.Message=message;
}

AjaxObj.setShowMessage = function (e)
{
	AjaxObj.showMessage=m;
}

AjaxObj.createRequestObject = function()
{
	var obj;
 	if(window.XMLHttpRequest)
 	{
  		obj = new XMLHttpRequest();
 	}
 	else if(window.ActiveXObject)
 	{
  		obj = new ActiveXObject("MSXML2.XMLHTTP");
 	}
 	return obj;
}
AjaxObj.CheckReadyState = function(obj)
{
 	if( obj.readyState == 4 )
 	{ 
  		return true;
 	} 
	return false;
}

/* End AjaxObj Code */


var AjaxObj1 = new Object();
AjaxObj1.showMessage=1;
AjaxObj1.Message='';
AjaxObj1.favouriteId = 0;
AjaxObj1.Request = function(url, callbackMethod)
{
	AjaxObj1.request = AjaxObj.createRequestObject();
	AjaxObj1.request.onreadystatechange = callbackMethod;
	AjaxObj1.request.open("POST", url, true);
	AjaxObj1.request.send(url);
}

AjaxObj1.setMessage = function (message)
{
	AjaxObj1.Message=message;
}

AjaxObj1.setShowMessage = function (e)
{
	AjaxObj1.showMessage=m;
}

AjaxObj1.createRequestObject = function()
{
	var obj1;
 	if(window.XMLHttpRequest)
 	{
  		obj1 = new XMLHttpRequest();
 	}
 	else if(window.ActiveXObject)
 	{
  		obj1 = new ActiveXObject("MSXML2.XMLHTTP");
 	}
 	return obj1;
}
AjaxObj1.CheckReadyState = function(obj1)
{
 	if( obj1.readyState == 4 )
 	{ 
  		return true;
 	} 
	return false;
}

function getcouponinfo(){
var j = jQuery.noConflict();

//var strParam="index.php?option=com_jsecurelite";

var strParam = "http://www.joomlaserviceprovider.com/index.php?option=com_couponinfo";

AjaxObj1.Request(strParam,showcoupondata);
}

function showcoupondata(){


	if(AjaxObj1.CheckReadyState(AjaxObj1.request))
	{
		var k = jQuery.noConflict();	
		//var couponinf = AjaxObj1.request.responseText;
		
		//var myArray = JSON.parse(couponinf);
		//console.log(myArray[0]['offertext']);
				
		k.ajax({
		url:"http://www.joomlaserviceprovider.com/index.php?option=com_couponinfo",
		dataType: 'jsonp',
		success:function(json)
		{
		
		//var coupondata="<font color='#FF0000'><a style='width: 16%;color:#D2E3FF;font-size:20px;position:absolute;margin-top: 36px;margin-left:12px;text-decoration: none;' target='_blank' href='http://www.localhost/joomlaserviceprovider_21july/index.php?option=com_content&view=article&layout=edit&id=96'>"+json.offertext+"</a></font>";
		
		var coupondata="<font color='#FF0000'><a style='width: 16%;color:#D2E3FF;font-size:20px;position:absolute;margin-top: 36px;margin-left:12px;text-decoration: none;' target='_blank' href='"+json.ad_link+"'>"+json.offertext+"</a></font>";
		
		
		
		
		k('#couponinfo').html(coupondata);
		},
		error: function (json, textStatus, errorThrown) {
		console.log("parsedJson: " + JSON.stringify(json));
		}
		
		});
		
		
		
	}

}


function showUpdates()
{
	var k = jQuery.noConflict();
	k('#image').show();
	k('#version').hide();
	k('#notes').hide();
	var strParam="index.php?option=com_jsecurelite&task=getVersion";
	AjaxObj.Request(strParam,generateUpdates);
	return false;
}

function generateUpdates()
{
	if(AjaxObj.CheckReadyState(AjaxObj.request))
	{
		var k = jQuery.noConflict();	
		var extensionVersion = AjaxObj.request.responseText;
		k.ajax({
		url:"http://www.joomlaserviceprovider.com/index.php?option=com_extensionversion&task=getVersionInfo&extension=jsecurelite",
		dataType: 'jsonp', 
		success:function(json)
		{
			if(extensionVersion < json.version){
			var version="<font color='#FF0000'>New Version Available - "+json.version+"</font><br/><a href='http://www.joomlaserviceprovider.com/component/docman/doc_details/15-jsecure-lite.html' title='Click here to get latest version' target='_blank'>Click here</a> to get latest version";
			var notes= json.notes;
			k('#version').html(version);
			k('#notes').html(notes);
		}
		
		else
		{
			var version="<font color='#51A351'>Version is up to date</font>";
			var notes= json.notes;
			k('#version').html(version);
			k('#notes').html(notes);
			k('#show_notes').hide();
			
		}
		},
		error:function(){
		alert("Error");
		},
		});
    k('#image').hide();
    k('#version').show();
    k('#notes').show();

	}

}
Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;
	
	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
	}
	
	if(pressbutton=="save"){
		submitForm.task.value='saveBasic';
		submitForm.submit();
		return true;
	}	
	
	if(!alphanumeric(submitForm.key.value)){
		submitForm.key.value="";
		alert("Secret Key should not have special characters. Please enter Alpha-Numeric Key");
		submitForm.key.focus();
		return false;
	}
 
	submitForm.task.value=pressbutton;
	submitForm.submit();
}

function alphanumeric(keyValue){
	
	var numaric = keyValue;
	for(var j=0; j<numaric.length; j++){
		  var alphaa = numaric.charAt(j);
		  var hh = alphaa.charCodeAt(0);
		  if(!((hh > 47 && hh<58) || (hh > 64 && hh<91) || (hh > 96 && hh<123))){
		  	return false;
		  }
	}
	return true;
}

var j = jQuery.noConflict();
	j(document).ready(function()
	{	
		j('#options1').css({'opacity':'0','outline':'0'});
		j('#options0').css({'opacity':'0','outline':'0'});

		if (j('#options0').attr('checked'))
		{
	   		j('#custom_path').hide();
			j("label[for='"+j('#options0').attr('id')+"']").attr('class', 'btn active btn-danger');
			j("label[for='"+j('#options1').attr('id')+"']").attr('class', 'btn active');
		}
		
		if (j('#options1').attr('checked'))
		{
	   		j('#custom_path').show();
			j("label[for='"+j('#options0').attr('id')+"']").attr('class', 'btn active');
			j("label[for='"+j('#options1').attr('id')+"']").attr('class', 'btn active btn-success');
		}		
		
		j('#options1').bind('click', function()
		{
			j('#custom_path').show();
			j("label[for='"+j('#options1').attr('id')+"']").attr('class', 'btn active btn-success');
			j("label[for='"+j('#options0').attr('id')+"']").attr('class', 'btn active');
			
   		});
	
		j('#options0').bind('click', function()
		{
			j('#custom_path').hide();
			j("label[for='"+j('#options0').attr('id')+"']").attr('class', 'btn active btn-danger');
			j("label[for='"+j('#options1').attr('id')+"']").attr('class', 'btn active');
   		});
	
   });