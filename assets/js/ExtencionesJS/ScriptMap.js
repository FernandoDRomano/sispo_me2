var ScriptMap = `
	jQuery(document).ready(function() {
		EndLoading();
		NumeroSolido("HDR");
		EndLoading();
		setTimeout(function(){
		}, 3000);
	});
	function AjaxGetLatitudLongitudPiezasEstados(DataNombre,Data,Ajax){
		Loading();
		var paragrap = document.getElementById("Paragrap");
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				EndLoading();
				var Resultado = this.responseText.trim();
				if(Resultado=="NULL"){
					$.bootstrapGrowl("Datos No Encontrados.", {
						type: 'danger',
						align: 'center',
						width: 'auto'
					});
					EndLoading();
					return(false);
				}else{
					GeoRes = this.responseText.replace(/(\\r\\n|\\n|\\r)/gm,"");
					$.bootstrapGrowl("Datos Encontrados Actualizando Mapa.", {
						type: 'success',
						align: 'center',
						width: 'auto'
					});
					//alert(GeoRes);
					StringToPointPiezasEstados(GeoRes);
					EndLoading();
				}
			}else{
				if (this.readyState == 4 && this.status != 200){
					if(Ajax.indexOf(".php")>=0){
						EndLoading();
						//window.location="403forbidden";
						$.bootstrapGrowl("Documento:" + Ajax +" No Encontrado.", {
						type: 'danger',//danger
						align: 'center',
						width: 'auto'});
					}else{
						Ajax=Ajax+".php";
						AjaxGetLatitudLongitudPiezasEstados(DataNombre,Data,Ajax);
					}
				}
			}
		};
		DataText="";
		if(DataNombre.length>0){
			for(var cont=0;cont<=DataNombre.length;cont++){
				if(cont == 0){
					DataText="?"+DataNombre[cont]+"="+Data[cont];
				}else{
					DataText=DataText+"&"+DataNombre[cont]+"="+Data[cont];
				}
			}
		}
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText+'&'+'DateNumber='+DateNumber;
		}
		xhttp.open("GET", Ajax+DataText
		, true);
		$.bootstrapGrowl("Buscando Puntos De Inicio Para GeoCodificar", {
			type: 'success',//danger
			align: 'center',
			width: 'auto'
		});
		xhttp.send();
	}
	
	
	function AjaxGetLatitudLongitudCuadrante(DataNombre,Data,Ajax){
		Loading();
		var paragrap = document.getElementById("Paragrap");
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200){
				EndLoading();
				var Resultado = this.responseText.trim();
				if(Resultado=="NULL"){
					$.bootstrapGrowl("Datos No Encontrados.", {
						type: 'danger',
						align: 'center',
						width: 'auto'
					});
					EndLoading();
					return(false);
				}else{
					GeoRes = this.responseText.replace(/(\\r\\n|\\n|\\r)/gm,"");
					$.bootstrapGrowl("Datos Encontrados Actualizando Mapa.", {
						type: 'success',
						align: 'center',
						width: 'auto'
					});
					StringToPointSeguimiento(GeoRes);
					EndLoading();
				}
			}else{
				if (this.readyState == 4 && this.status != 200){
					
					
					
					if(Ajax.indexOf(".php")>=0){
						EndLoading();
						//window.location="403forbidden";
						$.bootstrapGrowl("Documento:" + Ajax +" No Encontrado.", {
						type: 'danger',//danger
						align: 'center',
						width: 'auto'});
					}else{
						Ajax=Ajax+".php";
						AjaxGetLatitudLongitudCuadrante(DataNombre,Data,Ajax);
					}
				}
			}
		};
		DataText="";
		if(DataNombre.length>0){
			for(var cont=0;cont<=DataNombre.length;cont++){
				if(cont == 0){
					DataText="?"+DataNombre[cont]+"="+Data[cont];
				}else{
					DataText=DataText+"&"+DataNombre[cont]+"="+Data[cont];
				}
			}
		}
		var date = new Date();var DateNumber = date.getTime();
		if(DataText==''){
			DataText+'?'+'DateNumber='+DateNumber;
		}else{
			DataText+'&'+'DateNumber='+DateNumber;
		}
		xhttp.open("GET", Ajax+DataText
		, true);
		$.bootstrapGrowl("Buscando Puntos De Inicio Para GeoCodificar", {
			type: 'success',//danger
			align: 'center',
			width: 'auto'
		});
		xhttp.send();
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var labelIndex = 0;
	function addMarker(location, map){
		var marker = new google.maps.Marker({
			position: location,
			//label: { text: labels }, 
			label:{
				text: Usuario,
				color: '#00F',
			} ,
			labelClass: "labels",
			map: map
		});
	}
	var map;
	var Stilo="";
	function initZoomControl(map) {
		document.querySelector('.zoom-control-in').onclick = function() {
			map.setZoom(map.getZoom() + 1);
		};
		document.querySelector('.zoom-control-out').onclick = function() {
			if(map.getZoom()==1){
			}else{
				map.setZoom(map.getZoom() - 1);
			}
		};
		map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(document.querySelector('.zoom-control'));
	}
	
	function initMap() {
		var NEW_ZEALAND_BOUNDS = {
			north: -8.918651173446666,
			south: -56.60240540501339,
			west: -76.727079575,
			east: -51.414579575000005,
		};
		var styledMapType = new google.maps.StyledMapType(
            [
			{elementType: 'geometry', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
            {
              featureType: 'administrative.locality',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
				featureType: 'poi',
				//elementType: 'labels.text.fill',
				//stylers: [{color: '#d59563'}]//#d59563
				elementType: "all",
				stylers: [{"visibility": "off"}
				]
            },
            {
              featureType: 'poi.park',
              elementType: 'geometry',
              stylers: [{color: '#263c3f'}]//
            },
			
            {
              featureType: 'poi.park',
              elementType: 'labels.text.fill',
              stylers: [{color: '#6b9a76'}]//Parque
            },
            {
              featureType: 'road',
              elementType: 'geometry',
              stylers: [{color: '#5d5344'},{"weight": 2.0}]//{"weight": 1.2}
            },
            {
              featureType: 'road',
              elementType: 'geometry.stroke',
              stylers: [{color: '#212a37'},{"weight": 2.0}]
            },
            {
              featureType: 'road',
              elementType: 'labels.text.fill',
              stylers: [{color: '#ffffff'},{lightness: 24}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry',
              stylers: [{color: '#746855'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry.stroke',
              stylers: [{color: '#1f2835'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'labels.text.fill',
              stylers: [{color: '#f3d19c'}]
            },
            {
              featureType: 'transit',
              elementType: 'geometry',
              stylers: [{color: '#2f3948'}]
            },
            {
              featureType: 'transit.station',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'water',
              elementType: 'geometry',
              stylers: [{color: '#17263c'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.fill',
              stylers: [{color: '#515c6d'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.stroke',
              stylers: [{color: '#17263c'}]
            }
          ],
            {name: 'Nocturno'});
			
			
			
		var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
		map = new google.maps.Map(document.getElementById('mapa'), {
			zoom: 12,
			center: new google.maps.LatLng(-26.8543983,-65.2134077),//-26.7748559,-65.1982015
			//mapTypeId: 'Nocturno',//hybrid//terrain
			disableDefaultUI: true,
			/*
			restriction: {
				latLngBounds: NEW_ZEALAND_BOUNDS,
				strictBounds: false,
			},
			*/
			mapTypeControlOptions: {
				mapTypeIds: ['roadmap', 'satellite', 'hybrid', 'terrain',
				'Nocturno']
			}
            
		});
		map.mapTypes.set('Nocturno', styledMapType);
        map.setMapTypeId('Nocturno');
		
		directionsDisplay.setMap(map);
		var onChangeHandler = function() {
			calculateAndDisplayRoute(directionsService, directionsDisplay);
		};
		document.getElementById('start').addEventListener('change', onChangeHandler);
		document.getElementById('end').addEventListener('change', onChangeHandler);
		
		/*
		var coordsDiv = document.getElementById('Coordenadas');
		map.controls[google.maps.ControlPosition.TOP_CENTER].push(coordsDiv);
		*/
		var DatosDeBusqueda = document.getElementById('floating-panel');
		map.controls[google.maps.ControlPosition.TOP_CENTER].push(DatosDeBusqueda);
		//
		var Buscar = document.getElementById('floating-panel-left');
		map.controls[google.maps.ControlPosition.TOP_CENTER].push(Buscar);
		
		var Camino = document.getElementById('floating-panel-bottom');
		map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(Camino);
		
		
		/*
		map.addListener('mousemove', function(event) {
			coordsDiv.textContent =
			'lat: ' + event.latLng.lat() + ', ' +//Math.round(
			'lng: ' + event.latLng.lng();//
		});
		*/
		
		/*
		map.addListener('zoom_changed', function() {
			//infowindow.setContent('Zoom: ' + map.getZoom());
			alert(map.getZoom());
		});
		*/

/*
		var markerImage = new google.maps.MarkerImage(
			'IMAGENES/avatar1lila.png',
			new google.maps.Size(52,52), //size
			null, //origin
			null, //anchor
			new google.maps.Size(52,52) //scale
		);

		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(-26.8543983,-65.2134077),
			map: map,
			icon: markerImage //set the markers icon to the MarkerImage
		});
*/
		map.addListener('zoom_changed', function() {
			if(map.getZoom()==20){document.querySelector('.ZoomUp').style="font-size:12px;color:#cdcdcd"}
			else{document.querySelector('.ZoomUp').style="font-size:12px;color:#0000FF"}
			if(map.getZoom()==1){document.querySelector('.ZoomDown').style="font-size:12px;color:#cdcdcd"}
			else{document.querySelector('.ZoomDown').style="font-size:12px;color:#0000FF"}
			
			var pixelSizeAtZoom0 = 1; //the size of the icon at zoom level 0
			var maxPixelSize = 999999; //restricts the maximum size of the icon, otherwise the browser will choke at higher zoom levels trying to scale an image to millions of pixels
			var zoom = map.getZoom();
			var relativePixelSize = ((zoom*5)/100)*52 //Math.round(pixelSizeAtZoom0*Math.pow(2,zoom)); // use 2 to the power of current zoom to calculate relative pixel size.  Base of exponent is 2 because relative size should double every time you zoom in
				
				if(relativePixelSize > maxPixelSize) //restrict the maximum size of the icon
				relativePixelSize = maxPixelSize;
				
				//alert(relativePixelSize);
			if(marker.length>0){
				//alert("marker>0");
				for(var i = 0 ; i < marker.length ; i++){
					//change the size of the icon
					marker[i].setIcon(
						new google.maps.MarkerImage(
							marker[i].getIcon().url, //marker's same icon graphic
							null,//size
							null,//origin
							null, //anchor
							new google.maps.Size(relativePixelSize, relativePixelSize) //changes the scale
						)
					);
					
				}
				
			}     
		});
	
		initZoomControl(map);
		initMapTypeControl(map);
		initFullscreenControl(map);
		/*
		google.maps.event.addDomListener(mapDiv, 'click', function() {
			//window.alert('Map was clicked!');
		});
		*/
	}
	
	
	
	
	
	
	
	
	
	
	function calculateAndDisplayRoute(directionsService, directionsDisplay) {
		directionsService.route({
			origin: document.getElementById('start').value,
			destination: document.getElementById('end').value,
			travelMode: 'DRIVING'
		}, function(response, status) {
		if (status === 'OK') {
			directionsDisplay.setDirections(response);
		} else {
			window.alert('Directions request failed due to ' + status);
		}
		});
	}
	
	
	
	function project(latLng) {
		var siny = Math.sin(latLng.lat() * Math.PI / 180);
		siny = Math.min(Math.max(siny, -0.9999), 0.9999);
		return new google.maps.Point(
		TILE_SIZE * (0.5 + latLng.lng() / 360),
		TILE_SIZE * (0.5 - Math.log((1 + siny) / (1 - siny)) / (4 * Math.PI)));
	}
	
	
	var TILE_SIZE = 256;
	function createInfoWindowContent(latLng, zoom) {
		var scale = 1 << zoom;
		var worldCoordinate = project(latLng);
		var pixelCoordinate = new google.maps.Point(
			Math.floor(worldCoordinate.x * scale),
			Math.floor(worldCoordinate.y * scale));
		var tileCoordinate = new google.maps.Point(
			Math.floor(worldCoordinate.x * scale / TILE_SIZE),
			Math.floor(worldCoordinate.y * scale / TILE_SIZE));
		return [
			'Tucuman VMM Diagonal y 16',
			'LatLng: ' + latLng,
			'Zoom level: ' + zoom,
			'World Coordinate: ' + worldCoordinate,
			'Pixel Coordinate: ' + pixelCoordinate,
			'Tile Coordinate: ' + tileCoordinate
		].join('<br>');
	}
	function CargarGPS(){
		var FechaDesde = document.getElementById("FechaDesde");
		var FechaHasta = document.getElementById("FechaHasta");
		filtro = ["UserId","FechaDesde","FechaHasta","Sucursal","NoMemory"];
		filtroX = [UserId,FechaDesde.value,FechaHasta.value,"4",NoMemory];
		//AjaxGetLatitudLongitudCuadrante(filtro,filtroX,"HTMLS/AjaxGetLatitudLongitudCuadrante.php");
		//AjaxGetLatitudLongitudPiezasEstados(filtro,filtroX,"HTMLS/AjaxGetLatitudLongitudCuadrante.php");
		AjaxGetLatitudLongitudPiezasEstados(filtro,filtroX,"HTMLS/AjaxGetLatitudLongitudPiezasEstados.php");
		
		
		/*
		var VMMDiagonal = new google.maps.LatLng(-26.7748559,-65.1982015);
		var coordInfoWindow = new google.maps.InfoWindow();
		coordInfoWindow.setContent(createInfoWindowContent(VMMDiagonal, map.getZoom()));
		coordInfoWindow.setPosition(VMMDiagonal);
		coordInfoWindow.open(map);
		*/
	}
	var infowindow=[];
	var marker=[];
	var locationGPS=[];
	var line=[];
	var imgsize = 52;
	
	
	function StringToPoint(ArrayGPS){
		ArrayGPS=ArrayGPS.split(";");
		var UsuarioPre='';
		for(var cont=0;cont<ArrayGPS.length;cont++){
			var ArrayGPSItem=ArrayGPS[cont].split("|");
			Usuario=ArrayGPSItem[3];
			//MapDoomicilio=ArrayGPSItem[5];
			if(ArrayGPSItem[1] < 0){
				ArrayGPSItem[1] = ArrayGPSItem[1].slice(0, 3) + "." + ArrayGPSItem[1].slice(3);
			}else{
				ArrayGPSItem[1] = ArrayGPSItem[1].slice(0, 2) + "." + ArrayGPSItem[1].slice(2);
			}
			if(ArrayGPSItem[2] < 0){
				ArrayGPSItem[2]= ArrayGPSItem[2].slice(0, 3) + "." + ArrayGPSItem[2].slice(3);
			}else{
				ArrayGPSItem[2]= ArrayGPSItem[2].slice(0, 2) + "." + ArrayGPSItem[2].slice(2);
			}
			
			locationGPS[cont]  = { lat: parseFloat(ArrayGPSItem[1]), lng: parseFloat(ArrayGPSItem[2]) };
			//addMarker(locationGPS, map);
			
			
			var pixelSizeAtZoom0 = 1; //the size of the icon at zoom level 0
			var maxPixelSize = 999999; //restricts the maximum size of the icon, otherwise the browser will choke at higher zoom levels trying to scale an image to millions of pixels
			var zoom = map.getZoom();
			var relativePixelSize = ((zoom*5)/100)*imgsize //Math.round(pixelSizeAtZoom0*Math.pow(2,zoom)); // use 2 to the power of current zoom to calculate relative pixel size.  Base of exponent is 2 because relative size should double every time you zoom in				
			if(relativePixelSize > maxPixelSize) //restrict the maximum size of the icon
			relativePixelSize = maxPixelSize;
			
			if(cont==ArrayGPS.length-1){
				var image = {
					url: 'IMAGENES/avatar1lila.png',
					//size: new google.maps.Size(512, 512),
					scaledSize: new google.maps.Size(relativePixelSize, relativePixelSize),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)
				};
			}else{
				var image = {
					url: 'IMAGENES/avatar2.png',
					//size: new google.maps.Size(512, 512),
					scaledSize: new google.maps.Size(relativePixelSize, relativePixelSize),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)
				};
			}
			
			var shape = {
				coords: [1, 1, 1, relativePixelSize*2, relativePixelSize*2, relativePixelSize, relativePixelSize, 1],
				type: 'poly'
			};
			
			infowindow[cont] = new google.maps.InfoWindow;
			infowindow[cont].setContent('<center><p>'+Usuario+'</p></center><p>'+parseFloat(ArrayGPSItem[1])+','+parseFloat(ArrayGPSItem[2])+'<center></p>' + '<p>Fecha Y Hora:'+ArrayGPSItem[4] + '</p></center>' + '<p>Parado:'+(ArrayGPSItem[5]*5) + ' Segundos </p></center>');
			marker[cont] = new google.maps.Marker({position: locationGPS[cont],map: map,icon: image,shape: shape});
			marker[cont].id=cont;
			marker[cont].addListener('click', function() {
				AddInfo(this.id);
			});
			
			var lineSymbol = {
				path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
			};
			lineSymbol.strokeColor='#F00';
			if(cont>0 && Usuario == UsuarioPre){
				
				line[cont] = new google.maps.Polyline({
					path: [locationGPS[cont-1], locationGPS[cont]],
					icons: [
						{
							icon: lineSymbol,
							offset: '0%'
							}, {
							icon: lineSymbol,
							offset: '50%'
							}, {
							icon: lineSymbol,
							offset: '100%'
						}
					],
					strokeColor: '#00cc00',
					geodesic: true,
					strokeOpacity: 0.7,
					strokeWeight: 2,
					map: map
				});
			}
			UsuarioPre=Usuario;
		}
		GeoRes="";
		ArrayGPS="";
		ArrayGPSItem="";
	}
	
	function StringToPointPiezasEstados(ArrayGPS){
		ArrayGPS=ArrayGPS.split(";");
		var UsuarioPre='';
		for(var cont=0;cont<ArrayGPS.length;cont++){
			var ArrayGPSItem=ArrayGPS[cont].split("|");
			
			var PiezaId = ArrayGPSItem[3];
			var Barcode = ArrayGPSItem[4];
			var Estado = ArrayGPSItem[6];
			var Destinatario = ArrayGPSItem[7];
			Usuario=ArrayGPSItem[3];
			//MapDoomicilio=ArrayGPSItem[5];
			if(ArrayGPSItem[1] < 0){
				ArrayGPSItem[1] = ArrayGPSItem[1].slice(0, 3) + "." + ArrayGPSItem[1].slice(3);
			}else{
				ArrayGPSItem[1] = ArrayGPSItem[1].slice(0, 2) + "." + ArrayGPSItem[1].slice(2);
			}
			if(ArrayGPSItem[2] < 0){
				ArrayGPSItem[2]= ArrayGPSItem[2].slice(0, 3) + "." + ArrayGPSItem[2].slice(3);
			}else{
				ArrayGPSItem[2]= ArrayGPSItem[2].slice(0, 2) + "." + ArrayGPSItem[2].slice(2);
			}
			
			locationGPS[cont]  = { lat: parseFloat(ArrayGPSItem[1]), lng: parseFloat(ArrayGPSItem[2]) };
			//addMarker(locationGPS, map);
			
			
			var pixelSizeAtZoom0 = 1; //the size of the icon at zoom level 0
			var maxPixelSize = 999999; //restricts the maximum size of the icon, otherwise the browser will choke at higher zoom levels trying to scale an image to millions of pixels
			var zoom = map.getZoom();
			var relativePixelSize = ((zoom*5)/100)*imgsize //Math.round(pixelSizeAtZoom0*Math.pow(2,zoom)); // use 2 to the power of current zoom to calculate relative pixel size.  Base of exponent is 2 because relative size should double every time you zoom in				
			if(relativePixelSize > maxPixelSize) //restrict the maximum size of the icon
			relativePixelSize = maxPixelSize;
			
			if( (ArrayGPSItem[8].indexOf("Final") >=0) && (Estado=='8' || Estado=='7') ){
				var image = {
					//url: 'IMAGENES/avatar1lila.png',
					url: 'IMAGENES/sobre verde 32x32.png',
					//size: new google.maps.Size(512, 512),
					scaledSize: new google.maps.Size(relativePixelSize, relativePixelSize),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)//anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)//
				};
			}else{
				if( (ArrayGPSItem[8].indexOf("Final") >=0)){
					var image = {
						url: 'IMAGENES/sobre rojo 32x32.png',
						//size: new google.maps.Size(512, 512),
						scaledSize: new google.maps.Size(relativePixelSize, relativePixelSize),
						origin: new google.maps.Point(0, 0),
						anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)//anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)//
					};
				}else{
					var image = {
						url: 'IMAGENES/sobre amarillo 32x32.png',
						//size: new google.maps.Size(512, 512),
						scaledSize: new google.maps.Size(relativePixelSize, relativePixelSize),
						origin: new google.maps.Point(0, 0),
						anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)//anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)//
					};
				}
			}
			/*
			if(cont==ArrayGPS.length-1){
				var image = {
					url: 'IMAGENES/avatar1lila.png',
					//size: new google.maps.Size(512, 512),
					scaledSize: new google.maps.Size(relativePixelSize, relativePixelSize),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)
				};
			}else{
				var image = {
					url: 'IMAGENES/avatar2.png',
					//size: new google.maps.Size(512, 512),
					scaledSize: new google.maps.Size(relativePixelSize, relativePixelSize),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)
				};
			}
			*/
			var shape = {
				coords: [1, 1, 1, relativePixelSize*2, relativePixelSize*2, relativePixelSize, relativePixelSize, 1],
				type: 'poly'
			};
			PiezaId = ArrayGPSItem[3];
			var Barcode = ArrayGPSItem[4];
			var Estado = ArrayGPSItem[6];
			var TextoEstado = ArrayGPSItem[8];
			var Destinatario = ArrayGPSItem[7];
			
			infowindow[cont] = new google.maps.InfoWindow;
			infowindow[cont].setContent('<center><p>Pieza:'+PiezaId+'</p></center><p>' +
			'<center><p>Barcode:'+Barcode+'</p></center><p>' +
			'<center><p>Estado:'+TextoEstado+'</p></center><p>' +
			//parseFloat(ArrayGPSItem[1])+','+parseFloat(ArrayGPSItem[2])+
			'<center></p>' + 'Destinatario:' + Destinatario + '</p></center><p>' +
			'<center></p>' + '<p>Fecha Y Hora:' + ArrayGPSItem[5] + '</p></center><p>'  );
			marker[cont] = new google.maps.Marker({position: locationGPS[cont],map: map,icon: image,shape: shape});
			marker[cont].id=cont;
			marker[cont].addListener('click', function() {
				AddInfo(this.id);
			});
			
			var lineSymbol = {
				path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
			};
			lineSymbol.strokeColor='#F00';
			/*
			if(cont>0 && Usuario == UsuarioPre){
				
				line[cont] = new google.maps.Polyline({
					path: [locationGPS[cont-1], locationGPS[cont]],
					icons: [
						{
							icon: lineSymbol,
							offset: '0%'
							}, {
							icon: lineSymbol,
							offset: '50%'
							}, {
							icon: lineSymbol,
							offset: '100%'
						}
					],
					strokeColor: '#00cc00',
					geodesic: true,
					strokeOpacity: 0.7,
					strokeWeight: 2,
					map: map
				});
			}
			*/
			UsuarioPre=Usuario;
		}
		GeoRes="";
		ArrayGPS="";
		ArrayGPSItem="";
	}
	
	function StringToPointSeguimiento(ArrayGPS){
		ArrayGPS=ArrayGPS.split(";");
		var UsuarioPre='';
		for(var cont=0;cont<ArrayGPS.length;cont++){
			var ArrayGPSItem=ArrayGPS[cont].split("|");
			Usuario=ArrayGPSItem[3];
			//MapDoomicilio=ArrayGPSItem[5];
			if(ArrayGPSItem[1] < 0){
				ArrayGPSItem[1] = ArrayGPSItem[1].slice(0, 3) + "." + ArrayGPSItem[1].slice(3);
			}else{
				ArrayGPSItem[1] = ArrayGPSItem[1].slice(0, 2) + "." + ArrayGPSItem[1].slice(2);
			}
			if(ArrayGPSItem[2] < 0){
				ArrayGPSItem[2]= ArrayGPSItem[2].slice(0, 3) + "." + ArrayGPSItem[2].slice(3);
			}else{
				ArrayGPSItem[2]= ArrayGPSItem[2].slice(0, 2) + "." + ArrayGPSItem[2].slice(2);
			}
			
			locationGPS[cont]  = { lat: parseFloat(ArrayGPSItem[1]), lng: parseFloat(ArrayGPSItem[2]) };
			//addMarker(locationGPS, map);
			
			
			var pixelSizeAtZoom0 = 1; //the size of the icon at zoom level 0
			var maxPixelSize = 999999; //restricts the maximum size of the icon, otherwise the browser will choke at higher zoom levels trying to scale an image to millions of pixels
			var zoom = map.getZoom();
			var relativePixelSize = ((zoom*5)/100)*imgsize //Math.round(pixelSizeAtZoom0*Math.pow(2,zoom)); // use 2 to the power of current zoom to calculate relative pixel size.  Base of exponent is 2 because relative size should double every time you zoom in				
			if(relativePixelSize > maxPixelSize) //restrict the maximum size of the icon
			relativePixelSize = maxPixelSize;
			
			if(cont==ArrayGPS.length-1){
				var image = {
					url: 'IMAGENES/avatar1lila.png',
					//size: new google.maps.Size(512, 512),
					scaledSize: new google.maps.Size(relativePixelSize, relativePixelSize),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)
				};
			}else{
				var image = {
					url: 'IMAGENES/avatar2.png',
					//size: new google.maps.Size(512, 512),
					scaledSize: new google.maps.Size(relativePixelSize, relativePixelSize),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)
				};
			}
			
			var shape = {
				coords: [1, 1, 1, relativePixelSize*2, relativePixelSize*2, relativePixelSize, relativePixelSize, 1],
				type: 'poly'
			};
			
			infowindow[cont] = new google.maps.InfoWindow;
			infowindow[cont].setContent('<center><p>'+Usuario+'</p></center><p>'+parseFloat(ArrayGPSItem[1])+','+parseFloat(ArrayGPSItem[2])+'<center></p>' + '<p>Fecha Y Hora:'+ArrayGPSItem[4] + '</p></center>' + '<p>Parado:'+(ArrayGPSItem[5]*5) + ' Segundos </p></center>');
			marker[cont] = new google.maps.Marker({position: locationGPS[cont],map: map,icon: image,shape: shape});
			marker[cont].id=cont;
			marker[cont].addListener('click', function() {
				AddInfo(this.id);
			});
			
			var lineSymbol = {
				path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
			};
			lineSymbol.strokeColor='#F00';
			if(cont>0 && Usuario == UsuarioPre){
				
				line[cont] = new google.maps.Polyline({
					path: [locationGPS[cont-1], locationGPS[cont]],
					icons: [
						{
							icon: lineSymbol,
							offset: '0%'
							}, {
							icon: lineSymbol,
							offset: '50%'
							}, {
							icon: lineSymbol,
							offset: '100%'
						}
					],
					strokeColor: '#00cc00',
					geodesic: true,
					strokeOpacity: 0.7,
					strokeWeight: 2,
					map: map
				});
			}
			UsuarioPre=Usuario;
		}
		GeoRes="";
		ArrayGPS="";
		ArrayGPSItem="";
	}
	function AddInfo(id){
		infowindow[id].open(map, marker[id]);
		map.setZoom(20);
        map.setCenter(marker[id].getPosition());
		map.panTo(marker[id].getPosition());
		
	}var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
	function initMapTypeControl(map) {
        var mapTypeControlDiv = document.querySelector('.maptype-control');
        document.querySelector('.maptype-control-map').onclick = function() {
          mapTypeControlDiv.classList.add('maptype-control-is-map');
          mapTypeControlDiv.classList.remove('maptype-control-is-satellite');
          mapTypeControlDiv.classList.remove('maptype-control-is-Nocturno');
          map.setMapTypeId('roadmap');
        };
        document.querySelector('.maptype-control-satellite').onclick =
            function() {
          mapTypeControlDiv.classList.remove('maptype-control-is-map');
          mapTypeControlDiv.classList.remove('maptype-control-is-Nocturno');
          mapTypeControlDiv.classList.add('maptype-control-is-satellite');
          map.setMapTypeId('hybrid');
        };
		
		document.querySelector('.maptype-control-Nocturno').onclick =
            function() {
          mapTypeControlDiv.classList.remove('maptype-control-is-map');
          mapTypeControlDiv.classList.remove('maptype-control-is-satellite');
          mapTypeControlDiv.classList.add('maptype-control-is-Nocturno');
          map.setMapTypeId('Nocturno');
        };
		
        map.controls[google.maps.ControlPosition.LEFT_TOP].push(
            mapTypeControlDiv);
      }

      function initFullscreenControl(map) {
        var elementToSendFullscreen = map.getDiv().firstChild;
        var fullscreenControl = document.querySelector('.fullscreen-control');
        map.controls[google.maps.ControlPosition.RIGHT_TOP].push(
            fullscreenControl);


        fullscreenControl.onclick = function() {
          if (isFullscreen(elementToSendFullscreen)) {
            exitFullscreen();
          } else {
            requestFullscreen(elementToSendFullscreen);
          }
        };

        document.onwebkitfullscreenchange =
        document.onmsfullscreenchange =
        document.onmozfullscreenchange =
        document.onfullscreenchange = function() {
          if (isFullscreen(elementToSendFullscreen)) {
            fullscreenControl.classList.add('is-fullscreen');
          } else {
            fullscreenControl.classList.remove('is-fullscreen');
          }
        };
      }

      function isFullscreen(element) {
        return (document.fullscreenElement ||
                document.webkitFullscreenElement ||
                document.mozFullScreenElement ||
                document.msFullscreenElement) == element;
      }
      function requestFullscreen(element) {
        if (element.requestFullscreen) {
          element.requestFullscreen();
        } else if (element.webkitRequestFullScreen) {
          element.webkitRequestFullScreen();
        } else if (element.mozRequestFullScreen) {
          element.mozRequestFullScreen();
        } else if (element.msRequestFullScreen) {
          element.msRequestFullScreen();
        }
      }
      function exitFullscreen() {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
          document.webkitExitFullscreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.msCancelFullScreen) {
          document.msCancelFullScreen();
        }
      }
	  initMap();
`;