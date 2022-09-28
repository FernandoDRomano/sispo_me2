function LogOut(url){
	AjaxLogOut("AjaxLogOut.php")
	window.location.replace(url);
}

function ExtraerDataDeJSONParaPost(DataAjax){
	console.log(DataAjax);
	DataText="";
	var keys = Object.keys(DataAjax);
	var values = Object.values(DataAjax);
	for(var Conti = 0; Conti < keys.length; Conti++){
		if(Conti == 0){
			DataText="?"+keys[Conti]+"="+values[Conti];
		}else{
			DataText=DataText+"&"+keys[Conti]+"="+values[Conti];
		}
	}
	return (DataText);
}

function loadXMLDoc(aURL,ElementoDeCarga,Script,Post = null){
	var Scripts = document.getElementById("FuncionesTemporales");
	if (typeof(Scripts) != 'undefined' && Scripts != null){
		Scripts.remove();
	}
	var xmlhttp;
	if (window.XMLHttpRequest){
		xmlhttp = new XMLHttpRequest();
	}else{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
			var div = document.getElementById (ElementoDeCarga);
			div.innerHTML = xmlhttp.responseText;
				var oScript = document.createElement("script");
				oScript.id = "FuncionesTemporales"; 
				var oScriptText = document.createTextNode(Script);
				oScript.appendChild(oScriptText);
				document.body.appendChild(oScript);
			EndLoading();
		}
	}
	if(Post){
		if(Post.PostMenu){
			var post = ExtraerDataDeJSONParaPost(Post);
			//alert(post);
		}else{
			var Post = JSON.parse(`{
				"PostMenu":"false"
			}`);
			var post = ExtraerDataDeJSONParaPost(Post);
			//alert(post);
		}
		xmlhttp.open("GET",aURL+post,true);
		xmlhttp.send();
	}else{
		//ERRORES SI NO EXISTE EL POST
		//alert("ERRORES SI NO EXISTE EL POST");
		xmlhttp.open("GET",aURL,true);
		xmlhttp.send();
	}
	
}




