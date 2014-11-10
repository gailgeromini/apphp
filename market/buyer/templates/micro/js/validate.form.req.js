/*
 * @author : Tom
 * form validate script (build 1.0)
 */
	function checkReq(){
	var xmlhttp;
	var result;
	var fn_exec;

	this.params = "";
	this.isFisrt = 0;
	this.setParameter = setParameter;
	this.executeFunction = executeFunction;
	this.requestServer = requestServer;
	this.stop = stop;
	function setParameter(key,parameter){
		this.params += "&"+key+"="+parameter;
	}
	function executeFunction(fn){
		fn_exec = fn;
	}
	function requestServer(target,method){
		xmlhttp = GetXmlHttpObject();
		if (xmlhttp == false){
			alert ("Browser does not support HTTP Request");
			return;
		}
		xmlhttp.onreadystatechange = stateChanged;
		xmlhttp.open(method,target,true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send(this.params);
		this.params = "";
	}

	function GetXmlHttpObject(){
		if (window.XMLHttpRequest){
			return new XMLHttpRequest();
		}
		if (window.ActiveXObject){
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
		if(window.ActiveXObject){
			return new ActiveXObject("Msxml2.XMLHTTP");
		}
		if(window.ActiveXObject){
			return  new ActiveXObject("Microsoft.XMLDOM");
		}
		return false;
	}

	function stateChanged(){
		if (xmlhttp.readyState == 4 && xmlhttp.status==200){
			result = xmlhttp.responseText;
			if(fn_exec != ""){
				eval(fn_exec)(result);
			}
		}
		if(xmlhttp.readyState == 4 && xmlhttp.status == 500){
			stop();
			alert("500 Internal Server Error");
		}
	}
	function stop()
	{
		xmlhttp.abort();
	}
}
//Public
//Validate system
function Validate(){
	var ajaxCheckReq = new checkReq();
	var isOk = true;
	this.validInputReq = validInputReq;
	this.validEmailReq = validEmailReq;
	this.validPassReq = validPassReq;
	this.validCompare = validCompare;
	var messageidI_p;
	var messageLetterI_p;
	var fieldI_p;
	var cssError_p;
	var cssSuccess_p;
	//private
	//call server process
	function executeServ(id,vlue,name,target,fieldI,messageidI,messageLetterI,messageLetterII){
		fieldI_p = fieldI;
		messageidI_p = messageidI;
		messageLetterI_p = messageLetterI;
		messageLetterII_p= messageLetterII;
		cssError_p ="error";
		cssSuccess_p ="success";
		ajaxCheckReq.setParameter(name,vlue);
		ajaxCheckReq.executeFunction(responeResult);
		ajaxCheckReq.requestServer(target,'POST');
	}
	//private
	//status process server
	function responeResult(pr){
		if(pr == false){
			$(fieldI_p).fadeTo(200,1.1,function(){
				$(fieldI_p).removeClass(cssSuccess_p);
				$(fieldI_p).addClass(cssError_p);
			});
			$(messageidI_p).fadeTo(200,1.1,function(){
				$(this).html(messageLetterI_p).removeClass(cssSuccess_p).fadeTo(900,1);
				$(this).html(messageLetterI_p).addClass(cssError_p).fadeTo(900,1);
			
			});
		}
		if(pr == true){
			$(fieldI_p).fadeTo(200,1.1,function(){
				$(fieldI_p).removeClass(cssError_p);
				$(fieldI_p).addClass(cssSuccess_p);
			});
			$(messageidI_p).fadeTo(200,1.1,function(){
				$(this).html(messageLetterII_p).addClass(cssSuccess_p).fadeTo(900,1);
			})
		}
	}
	//public
	//valid input required
	function validInputReq(id,vlue,name,target,msgzero,msgcondition,condition,fieldI,messageidI,messageLetterI,messageLetterII){
		this.value = vlue;
		var cssError_p ="error";
		var cssSuccess_p ="success";
		var val = vlue.replace(/^\s+|\s+$/g,"");//trim
	    if(eval(val.length) == 0) {
	    	$(messageidI).fadeTo(200,1.1,function(){
	    		$(fieldI).removeClass(cssSuccess_p);
				$(fieldI).addClass(cssError_p);
	    		$(this).html(messageLetterI).removeClass(cssSuccess_p).fadeTo(900,1);
	    		$(this).html(msgzero).addClass(cssError_p).fadeTo(900,1);
	    	});
	    	return false;
	    }
	    else{
	    	if(eval(val.length) < condition){
		    	$(messageidI).fadeTo(200,1.1,function(){
		    		$(fieldI).removeClass(cssSuccess_p);
					$(fieldI).addClass(cssError_p);
		    		$(this).html(msgcondition).addClass(cssError_p).fadeTo(900,1);
		    	});
	    		return false;
	    	}
	    	else{
	    		isOk;
	    	}
		}
		if(isOk == true){
			executeServ(id,vlue,name,target,fieldI,messageidI,messageLetterI,messageLetterII);
		}
	}
	//public
	//valid email required
	function validEmailReq(id,vlue,name,target,msgzero,mgsemail,msgcondition,condition,fieldI,messageidI,messageLetterI,messageLetterII){
		var cssError_p ="error";
		var cssSuccess_p ="success";
		var val = vlue.replace(/^\s+|\s+$/g,"");//trim
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	    if(eval(val.length) == 0) {
	    	$(messageidI).fadeTo(200,1.1,function(){
	    		$(fieldI).removeClass(cssSuccess_p);
				$(fieldI).addClass(cssError_p);
	    		$(this).html(messageLetterI).removeClass(cssSuccess_p).fadeTo(900,1);
	    		$(this).html(msgzero).addClass(cssError_p).fadeTo(900,1);
	    	});
	    	return false;
	    }
	    else if(vlue.length < condition){
	    	$(messageidI).fadeTo(200,1.1,function(){
	    		$(fieldI).removeClass(cssSuccess_p);
				$(fieldI).addClass(cssError_p);
	    		$(this).html(msgcondition).addClass(cssError_p).fadeTo(900,1);
	    	});
    		return false;
		}
		else{
			if(!emailReg.test(vlue)){
				$(messageidI).fadeTo(200,1.1,function(){
		    		$(fieldI).removeClass(cssSuccess_p);
					$(fieldI).addClass(cssError_p);
		    		$(this).html(mgsemail).addClass(cssError_p).fadeTo(900,1);
		    	});
	    		return false;
				
			}
			else{
				isOk;
			}
		}
	    if(isOk == true){
			executeServ(id,vlue,name,target,fieldI,messageidI,messageLetterI,messageLetterII);
		}
	}
	//public
	//valid password required
	function validPassReq(id,vlue,name,vlueCofirm,fieldI,messageidI,msgzero,msgcondition,condition){
		var vluesame= document.getElementById(vlueCofirm).value;
		var cssError_p ="error";
		var val = vlue.replace(/^\s+|\s+$/g,"");//trim
	    if(eval(val.length) == 0) {
	    	$(messageidI).fadeTo(200,1.1,function(){
				$(fieldI).addClass(cssError_p);
	    		$(this).html(msgzero).addClass(cssError_p).fadeTo(900,1);
	    	});
	    	return false;
	    }
	    else if(eval(val.length) < condition){
	    	$(messageidI).fadeTo(200,1.1,function(){
				$(fieldI).addClass(cssError_p);
				$(this).html(msgcondition).addClass(cssError_p).fadeTo(900,1);
	    	});
    		return false;
		}
		else if(val!=vluesame){
				$(messageidI).fadeTo(200,1.1,function(){
					$(fieldI).addClass(cssError_p);
					$(this).html('Your entered re-password must be same').addClass(cssError_p).fadeTo(900,1);
				});
				return false;
		}
		else if(val == vluesame){
			$(messageidI).fadeTo(200,0.1,function(){
				$(fieldI).removeClass(cssError_p);
				$(this).html('').fadeTo(900,1);
			});
			isOk;
		}
		else{
			$(messageidI).fadeTo(200,0.1,function(){
				$(fieldI).removeClass(cssError_p);
				$(this).html('').fadeTo(900,1);
			});
		}
	}
	
	function validCompare(id,vlue,name,fieldI,messageidI,msgzero,msgcondition,condition){
		var cssError_p ="error";
		var val = vlue.replace(/^\s+|\s+$/g,"");//trim
	    if(eval(val.length) == 0) {
	    	$(messageidI).fadeTo(200,1.1,function(){
				$(fieldI).addClass(cssError_p);
	    		$(this).html(msgzero).addClass(cssError_p).fadeTo(900,1);
	    	});
	    	return false;
	    }
	    else if(eval(val.length) < condition){
	    	$(messageidI).fadeTo(200,1.1,function(){
				$(fieldI).addClass(cssError_p);
				$(this).html(msgcondition).addClass(cssError_p).fadeTo(900,1);
	    	});
    		return false;
		}
	    else if(eval(val.match( /^[A-Za-z][\w]*$/))){
	    	
	    	$(messageidI).fadeTo(200,1.1,function(){
				$(fieldI).addClass(cssError_p);
				$(this).html(msgcondition).addClass(cssError_p).fadeTo(900,1);
			});

	    	return false;
		}
		else{
			$(messageidI).fadeTo(200,0.1,function(){
				$(fieldI).removeClass(cssError_p);
				$(this).html('').fadeTo(900,1);
			});
		}
	}
		
}