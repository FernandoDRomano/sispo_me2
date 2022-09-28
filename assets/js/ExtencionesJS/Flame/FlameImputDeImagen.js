
	$(document).ready(function () {
		for(var i = 0 ; i < $("input.SubaDeImagenes").length ; i++){
			
			$("input.SubaDeImagenes")[i].addEventListener('change', function(){updateImageDisplay(this)});
		}
		
	});
	
	function updateImageDisplay(e) {
		var form = $(e).parent().parent()[0];//El Elemento 0 Indica Que Deja De Ser Un Elemento Jquery Y Pasa A Ser El Elemento JS
		var preview = form.querySelector('.preview');
		while(preview.firstChild) {
			preview.removeChild(preview.firstChild);
		}
		const curFiles = e.files;
		
		var ArraydAceptados = $(e)[0].accept.replace(/\./gi, '').split(",");
		//console.log(ArraydAceptados);
		
		if(curFiles.length === 0) {
			var id = $(e)[0].id;
			$("#Boton"+id).parent().parent().attr("style", "display:none;");
			const para = document.createElement('p');
			para.textContent = "";//Seleccione Archivo
			preview.appendChild(para);
		}else{
			//console.log($(e)[0].id);
			var id = $(e)[0].id;
			const list = document.createElement('ol');
			preview.appendChild(list);
			var i=0;
			for(const file of curFiles){
				//console.log(file);
				var inputfile = file.name;
				var arrayImagen = inputfile.split('.');
				var LenghtArrayImagen = arrayImagen.length;
				
				if(ArraydAceptados.includes(arrayImagen[LenghtArrayImagen-1])){
					$("#Boton"+id).parent().parent().attr("style", "display:block;");
					const listItem = document.createElement('li');
					const para = document.createElement('p');
					if(validFileType(file)){
						para.textContent = "Nombre De Archivo: " + file.name + ", Tamaño " + returnFileSize(file.size) + "";
						const image = document.createElement('img');
						image.name="image" + i;i++;
						image.src = URL.createObjectURL(file);
						image.style = "width: auto; max-width: 100%; height: auto;max-height: 200px;";// 
						listItem.appendChild(image);
						listItem.appendChild(para);
					}else{
						para.textContent = "Nombre De Archivo: " + file.name + ": No Es Un Archivo Valido. Actualize Su Seleccion.";
						listItem.appendChild(para);
					}
					list.appendChild(listItem);
				}else{
					$("#image_uploads")[0].files = null;
					if (typeof $.bootstrapGrowl === "function") {
						$.bootstrapGrowl( "<p>El Archivo Seleccionado No Es Compatible.</p><p><b>Se Acepta Archivo Con Extension ("+ $(e)[0].accept.replace(/\./gi, '') +").</b></p>", {
							type: 'danger',//danger
							align: 'center',
							width: 'auto'
						});
					}
				}
			}
		}
	}
	
	function validFileType(file) {
		const fileTypes = [
			'image/jpeg',
			'image/pjpeg',
			'image/png'
		];
		return fileTypes.includes(file.type);
	}

	function returnFileSize(number) {
		if(number < 1024) {
			return number + 'bytes';
		} else if(number >= 1024 && number < 1048576) {
			return (number/1024).toFixed(1) + 'KB';
		} else if(number >= 1048576) {
			return (number/1048576).toFixed(1) + 'MB';
		}
	}
	
	var isAdvancedUpload = function() {
		var div = document.createElement('div');
		return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
	}();
	
	
	
	$(document).ready(function () {
		$("form.SubaDeImagenes").submit(function(e){
			e.preventDefault();
		});
	});