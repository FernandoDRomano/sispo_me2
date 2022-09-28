function FormForget(){
	$("#form1").css( "display", "none" );
	$("#forget").css( "display", "inline" );
}
function FormLogin(){
	$("#form1").css( "display", "inline" );
	$("#forget").css( "display", "none" );
}
jQuery(document).ready(function() {
		var Config = JSON.parse(`
	{
		"Elemento":"UserName",
		"ElementoTexto":"BoltTextUserName",
		"DigitosMinimos":"8",
		"TextoInicial":"Debe ingresar su nombre de usuario.",
		"TextoMenor":"8 digitos Minimo."
	}`);
	Texto(Config);
	//Texto("UserName","BoltTextUserName",8,"Debe ingresar su nombre de usuario.","8 digitos Minimo.");
	var Config = JSON.parse(`
	{
		"Elemento":"Password",
		"ElementoTexto":"BoltTextPassword",
		"DigitosMinimos":"8",
		"TextoInicial":"Debe ingresar su Password.",
		"TextoMenor":"8 digitos Minimo."
	}`);
	Texto(Config);
	//Texto("Password","BoltTextPassword",8,"Debe ingresar su Password.","8 digitos Minimo.");
});
jQuery(document).ready(function () {
	$("#form1").removeAttr("onkeypress");
	$("form.login-form").find('input[type="text"],input[type="password"]').removeAttr("keypress").keypress(function (e) {
		if (e.which == 13) {
			e.preventDefault();
			e.stopPropagation();
			eval($('form.login-form .form-actions a').attr('href'));
		}
	});
});
jQuery(document).ready(function() {
	$("#UserName").keyup(function(evento) {
		if(evento.key=="Enter"){
			$( "#Password" ).focus();
		}
	});
	$("#Password").keyup(function(evento){
		var dInput = evento.key;
		if(evento.key=="Enter"){
			Login();
		}
	});
	
	$("#email").keyup(function() {
		if(this.value.length > 0){
			if(validateEmail(email.value)){
				document.getElementById("Paragrapforget").innerHTML="";
				document.getElementById('Paragrapforget').style='';
				document.getElementById('Paragrapforget').style='color: rgb(255, 0, 0);font-size:12px;';
			}else{
				document.getElementById("Paragrapforget").innerHTML="El Email No Es Compatible";
				document.getElementById('Paragrapforget').style='';
				document.getElementById('Paragrapforget').style='color: rgb(255, 0, 0);font-size:12px;';
			}
		}else{
			document.getElementById("Paragrapforget").innerHTML="Ponga Su Email En El Campo Correspondiente";
			document.getElementById('Paragrapforget').style='';
			document.getElementById('Paragrapforget').style='color: rgb(255, 0, 0);font-size:12px;';
		}
	});
	
});
var theForm = document.forms['form1'];
if (!theForm) {
	document.getElementById("UserName").value = "";
	document.getElementById("Password").value = "";
	theForm = document.form1;
}
function Login(time) {
	if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
		var password = document.getElementById("Password");
		var user = document.getElementById("UserName");
		if(user.value.length >= 8 && password.value.length >= 8 ){
			AjaxMasterLogueo(user.value,password.value,time);
		}
	}
}
function RecuperarCuenta(time) {
	var email = document.getElementById("email");
	if(email.value.length>0){
		if(validateEmail(email.value)){
			Loading();
			AjaxMasterRecuperar(email.value,time);
		}else{
			document.getElementById("Paragrapforget").innerHTML="No Se Puede Recuperar Cuenta Con Email Incompatible";
			document.getElementById('Paragrapforget').style='';
			document.getElementById('Paragrapforget').style='color: rgb(255, 0, 255);font-size:12px;';
		}
	}else{
		document.getElementById("Paragrapforget").innerHTML="No Se Puede Recuperar Cuenta Sin email";
		document.getElementById('Paragrapforget').style='';
		document.getElementById('Paragrapforget').style='color: rgb(255, 0, 255);font-size:12px;';
	}
}
function validateEmail(email){
	var re = /\S+@\S+\.\S+/;
	return re.test(email);
}
function WebForm_OnSubmit() {
	if (typeof(ValidatorOnSubmit) == "function" && ValidatorOnSubmit() == false) return false;
	return true;
}
function AjaxMasterLogueo(user,pass,time){
	//alert(user+" "+pass);
	//var time = <?php echo json_encode($Time);?>;
	var Paragrap = document.getElementById("Paragrap");
	Loading();
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200){
			var Resultado = this.responseText.trim();
			EndLoading();
			eval(Resultado);
		}else{if(this.readyState == 4){
				//window.location="403forbidden";
			}
		}
	};
	user = user.replace(/[^a-zA-Z0-9  ññÑÑ°°??¡¡@@[[\]\]\+\+¨¨**!!""##$$%%&&//(())==,,..;;::__\-\-{{}}´´''¿¿]/g,'');
	pass = pass.replace(/[^a-zA-Z0-9  ññÑÑ°°??¡¡@@[[\]\]\+\+¨¨**!!""##$$%%&&//(())==,,..;;::__\-\-{{}}´´''¿¿]/g,'');
	user = encodeURIComponent(user);
	pass = encodeURIComponent(pass);
	xhttp.open("GET", "XMLHttpRequest/AjaxMasterLogueo.php"+
	"?Time="+
	time+
	"&user="+
	user+
	"&pass="+
	pass+
	"&NoMemory="+
	NoMemory
	, true);
	xhttp.send();
}
function AjaxMasterRecuperar(Email,time){
	//var time = <?php echo json_encode($Time);?>;
	var Paragrap = document.getElementById("Paragrap");
	Loading();
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200){
			var Resultado = this.responseText.trim();
			EndLoading();
			//console.log(Resultado);
			eval(Resultado);
		}else{if(this.readyState == 4){
				window.location="403forbidden";
			}
		}
	};
	Email = Email.replace(/[^a-z0-9  ññ°°??¡¡@@[[\]\]\+\+¨¨**!!""##$$%%&&//(())==,,..;;::__\-\-{{}}´´''¿¿]/g,'');
	Email = encodeURIComponent(Email);
	xhttp.open("GET", "XMLHttpRequest/AjaxMasterRecuperar.php"+
	"?Time="+
	time+
	"&Email="+
	Email+
	"&NoMemory="+
	NoMemory
	, true);
	console.log("XMLHttpRequest/AjaxMasterRecuperar.php"+"?Time="+time+"&Email="+Email+"&NoMemory="+NoMemory);
	xhttp.send();
}

function setVisible(selector, visible) {
	document.querySelector(selector).style.display = visible ? 'block' : 'none';
}
function Loading(){
	setVisible('#loading', true);
}
function EndLoading(){
	setVisible('#loading', false);
}
$(document).ajaxStop(function(){
	setVisible('#loading', false);
});
setVisible('#loading', false);
function GoUrl(url){
	window.location.href =(url);
	//window.location.replace(url);
}
