var ScriptFiles= `
	
	$( window ).resize(function() {
		AddHeight();
	});

	var FicheroDeOrigen = "Banco/";
	var NombreFicheroDeOrigen = "Banco";
	var Directorio="files";
	var anio;var mes;var Dia;var Hora;var minuto;var segundo;
	var today = new Date();
	anio = today.getFullYear();
	mes = today.getMonth()+1;
	Dia = today.getDate();
	Hora = today.getHours();
	minuto = today.getMinutes();
	segundo = today.getSeconds();
	var FechaCompleta = anio+"-"+mes+"-"+Dia+" "+Hora+":"+minuto+":"+segundo;
	$(function(){
		var Folders = $('.Folders'),breadcrumbs = $('.breadcrumbs'),fileList = Folders.find('.uldata');
		$.get(FicheroDeOrigen+'scan.php?time='+FechaCompleta, function(data) {
		//$.get('http://zonificacion.sispo.com.ar/controltotal/FppTracking/AgregarArchivos/scan.php?time='+FechaCompleta, function(data) {
			var response = [data],currentPath = '',breadcrumbsUrls = [];
			var folders = [],files = [];
			$(window).on('hashchange', function(){
				goto(window.location.hash);
				AddHeight();
			}).trigger('hashchange');
			Folders.find('.search').click(function(){
				var search = $(this);
				search.find('span').hide();
				search.find('input[type=search]').show().focus();
			});
			Folders.find('input').on('input', function(e){
				folders = [];
				files = [];
				var value = this.value.trim();
				if(value != ""){
					var Memory = value;
					value = value.replace(/[^a-zA-Z0-9ñÑ_#=]/g,'');
					if(Memory != value){
						this.value = value;
						if (typeof $.bootstrapGrowl === "function") {
							$.bootstrapGrowl( "Caracter No Permitido", {
								type: 'danger',//danger
								align: 'center',
								width: 'auto'
							});
						}
						return;
						//window.location.hash = '' + value.trim();
					}
				}
				if(value.length) {
					Folders.addClass('searching');
					//alert(hash);
					//alert(hash);
					window.location.hash = 'search=' + value.trim();
				}
				else {
					Folders.removeClass('searching');
					window.location.hash = encodeURIComponent(currentPath);
				}
			})
			.on('keyup', function(e){
				var search = $(this);
				if(e.keyCode == 27) {
					search.trigger('focusout');
				}
			})
			.focusout(function(e){
				var search = $(this);
				if(!search.val().trim().length) {
					window.location.hash = encodeURIComponent(currentPath);
					search.hide();
					search.parent().find('span').show();
				}
			});
			fileList.on('click', 'li.folders', function(e){
				e.preventDefault();
				var nextDir = $(this).find('a.folders').attr('href');
				if(Folders.hasClass('searching')) {
					breadcrumbsUrls = generateBreadcrumbs(nextDir);
					Folders.removeClass('searching');
					Folders.find('input[type=search]').val('').hide();
					Folders.find('span').show();
				}
				else {
					breadcrumbsUrls.push(nextDir);
				}
				window.location.hash = encodeURIComponent(nextDir);
				currentPath = nextDir;
			});
			breadcrumbs.on('click', 'a', function(e){
				e.preventDefault();
				var index = breadcrumbs.find('a').index($(this)),
					nextDir = breadcrumbsUrls[index];
				breadcrumbsUrls.length = Number(index);
				window.location.hash = encodeURIComponent(nextDir);
			});
			function goto(hash) {
				hash = decodeURIComponent(hash).slice(1).split('=');
				if (hash.length) {
					var rendered = '';
					if (hash[0] === 'search') {
						Folders.addClass('searching');
						rendered = searchData(response, hash[1].toLowerCase());
						if (rendered.length) {
							currentPath = hash[0];
							render(rendered);
						}
						else {
							render(rendered);
						}
					}
					else if (hash[0].trim().length) {
						rendered = searchByPath(hash[0]);
						if (rendered.length) {
							currentPath = hash[0];
							breadcrumbsUrls = generateBreadcrumbs(hash[0]);
							Directorio=currentPath;
							render(rendered);
						}
						else {
							currentPath = hash[0];
							breadcrumbsUrls = generateBreadcrumbs(hash[0]);
							Directorio=currentPath;
							render(rendered);
						}
					}
					else {
						currentPath = data.path;
						breadcrumbsUrls.push(data.path);
						Directorio=data.path;
						render(searchByPath(data.path));
					}
				}
			}
			function generateBreadcrumbs(nextDir){
				var path = nextDir.split('/').slice(0);
				for(var i=1;i<path.length;i++){
					path[i] = path[i-1]+ '/' +path[i];
				}
				return path;
			}
			function searchByPath(dir) {
				var path = dir.split('/'),
					demo = response,
					flag = 0;
				for(var i=0;i<path.length;i++){
					for(var j=0;j<demo.length;j++){
						if(demo[j].name === path[i]){
							flag = 1;
							demo = demo[j].items;
							break;
						}
					}
				}
				demo = flag ? demo : [];
				return demo;
			}
			function searchData(data, searchTerms) {
				//searchTerms = searchTerms.replace('[',('\\\\'+'\\['));
				var TempsearchTerms = searchTerms.replace('[',('\\\\'+'\\['));
				var TempsearchTerms = searchTerms.replace('\\\\',('\\\\'+'\\\\'));
				var TempsearchTerms = searchTerms.replace('\\\\',('\\\\'+'\\\\'));
				data.forEach(function(d){
					//alert(TempsearchTerms);
					if(d.type === 'folder') {
						searchData(d.items,searchTerms);
						if(d.name.toLowerCase().match(TempsearchTerms)) {
							folders.push(d);
						}
					}
					else if(d.type === 'file') {
						if(d.name.toLowerCase().match(TempsearchTerms)) {
							files.push(d);
						}
					}
				});
				return {folders: folders, files: files};
			}
			function render(data) {
				var scannedFolders = [],
					scannedFiles = [];
				if(Array.isArray(data)) {
					data.forEach(function (d) {
						if (d.type === 'folder') {
							scannedFolders.push(d);
						}
						else if (d.type === 'file') {
							scannedFiles.push(d);
						}
					});
				}
				else if(typeof data === 'object') {
					scannedFolders = data.folders;
					scannedFiles = data.files;
				}
				//fileList.empty().hide();
				fileList.empty();
				if(!scannedFolders.length && !scannedFiles.length) {
					Folders.find('.nothingfound').show();
				}
				else {
					Folders.find('.nothingfound').hide();
				}
				if(scannedFolders.length) {
					scannedFolders.forEach(function(f) {
						var itemsLength = f.items.length,
							name = escapeHTML(f.name),
							icon = '<span class="icon folder"></span>';
						if(itemsLength) {
							//icon = '<span class="icon folder full"></span>';
							icon = '<img style="display: block;max-height: 90px;line-height: 3em;text-align: center;border-radius: 0.25em;color: #FFF;display: inline-block;margin: 0.9em 1.2em 0.8em 1.3em;position: relative;overflow: hidden;" src="IMAGENES/Folders.png" alt="">';
							
						}
						if(itemsLength == 1) {
							itemsLength += ' item';
						}
						else if(itemsLength > 1) {
							itemsLength += ' items';
						}
						else {
							itemsLength = 'Empty';
						}
						var folder = $('<li class="folders"><a href="'+ FicheroDeOrigen + f.path +'" title="'+ f.path +'" class="folders">'+icon+'<span class="name">' + name + '</span> <span class="details">' + itemsLength + '</span></a></li>');
						folder.appendTo(fileList);
					});
				}
				if(scannedFiles.length) {
					scannedFiles.forEach(function(f) {
						var fileSize = bytesToSize(f.size),
							name = escapeHTML(f.name),
							fileType = name.split('.'),
							icon = '<span class="icon file"></span>';
						fileType = fileType[fileType.length-1];
						
						switch (fileType.toLowerCase()) {
							case "xlsx":
								icon = '<img style="display: block;max-height: 90px;line-height: 3em;text-align: center;border-radius: 0.25em;color: #FFF;display: inline-block;margin: 0.9em 1.2em 0.8em 1.3em;position: relative;overflow: hidden;" src="IMAGENES/FileExel.png" alt="">';
							break;
							case "mp4":
								icon = '<img style="display: block;max-height: 90px;line-height: 3em;text-align: center;border-radius: 0.25em;color: #FFF;display: inline-block;margin: 0.9em 1.2em 0.8em 1.3em;position: relative;overflow: hidden;" src="IMAGENES/FileMovie.png" alt="">';
							break;
							//case valorN:
							//break;
							default:
								icon = '<span class="icon file f-'+fileType+'">.'+fileType+'</span>';
							break;
						}
						var file = $('<li class="files"><a href="'+ FicheroDeOrigen + f.path+'" target="_blank" title="'+ f.path +'" class="files">'+icon+'<span class="name">'+ name +'</span> <span class="details">'+fileSize+'</span></a></li>');
						file.appendTo(fileList);
					});
				}
				var url = '';
				if(Folders.hasClass('searching')){
					url = '<span>Search results: </span>';
					fileList.removeClass('animated');
				}
				else {
					fileList.addClass('animated');
					if(breadcrumbsUrls[0]==NombreFicheroDeOrigen){
						breadcrumbsUrls.shift();
					}
					breadcrumbsUrls.forEach(function (u, i) {
						var name = u.split('/');
						if (i !== breadcrumbsUrls.length - 1) {
							url += '<a href="'+u+'"><span class="folderName">' + name[name.length-1] + '</span></a> <span class="arrow">→</span> ';
						}
						else {
							url += '<span class="folderName">' + name[name.length-1] + '</span>';
						}
					});
				}
				breadcrumbs.text('').append(url);
				fileList.animate({'display':'inline-block'});
			}
			function escapeHTML(text) {
				return text.replace(/\&/g,'&amp;').replace(/\</g,'&lt;').replace(/\>/g,'&gt;');
			}
			function bytesToSize(bytes) {
				var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
				if (bytes == 0) return '0 Bytes';
				var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
				return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
			}
		});
	});
	`;