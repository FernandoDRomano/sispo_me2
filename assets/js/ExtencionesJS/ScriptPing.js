var ScriptPing= `
		var map;
		var Stilo="";
		var infowindow=[];
		var marker=[];
		var locationGPS=[];
		var line=[];
		var imgsize = 52;
		function AddInfo(id){
			infowindow[id].open(map, marker[id]);
			map.setZoom(20);
			map.setCenter(marker[id].getPosition());
			map.panTo(marker[id].getPosition());
			
		}
		function PinToMap(Lat,Long,String,Imagen,cont){
			locationGPS[cont]  = { lat: parseFloat(Lat), lng: parseFloat(Long) };
			var pixelSizeAtZoom0 = 1;
			var maxPixelSize = 999999;
			var zoom = map.getZoom();
			var relativePixelSize = ((zoom*5)/100)*imgsize;
			if(relativePixelSize > maxPixelSize){
				relativePixelSize = maxPixelSize;
			}
			var image = {
				url: 'IMAGENES/'+Imagen,
				scaledSize: new google.maps.Size(relativePixelSize, relativePixelSize),
				origin: new google.maps.Point(0, 0),
				anchor: new google.maps.Point(relativePixelSize/2, relativePixelSize)
			};
			var shape = {
				coords: [1, 1, 1, relativePixelSize*2, relativePixelSize*2, relativePixelSize, relativePixelSize, 1],
				type: 'poly'
			};
			infowindow[cont] = new google.maps.InfoWindow;
			infowindow[cont].setContent('<center><p>'+String+'</p></center>');
			marker[cont] = new google.maps.Marker({position: locationGPS[cont],map: map,icon: image,shape: shape});
			marker[cont].id=cont;
			marker[cont].addListener('click', function() {
				AddInfo(this.id);
			});
			var lineSymbol = {
				path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
			};
			lineSymbol.strokeColor='#F00';
		}
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
			map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(//LEFT_TOP
			mapTypeControlDiv);
		}
		function initFullscreenControl(map) {
			var elementToSendFullscreen = map.getDiv().firstChild;
			var fullscreenControl = document.querySelector('.fullscreen-control');
			map.controls[google.maps.ControlPosition.RIGHT_TOP].push(//
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
	  
		var MapsIniciado = false;
		function initMap() {
			if(MapsIniciado){return}
			MapsIniciado = true;
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
					elementType: "all",
					stylers: [{"visibility": "off"}
					]
				},
				{
				  featureType: 'poi.park',
				  elementType: 'geometry',
				  stylers: [{color: '#263c3f'}]
				},
				{
				  featureType: 'poi.park',
				  elementType: 'labels.text.fill',
				  stylers: [{color: '#6b9a76'}]
				},
				{
				  featureType: 'road',
				  elementType: 'geometry',
				  stylers: [{color: '#5d5344'},{"weight": 2.0}]
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
				center: new google.maps.LatLng(-26.8543983,-65.2134077),
				disableDefaultUI: true,
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
			var DatosDeBusqueda = document.getElementById('floating-panel');
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(DatosDeBusqueda);
			
			map.addListener('zoom_changed', function() {
				if(map.getZoom()==20){document.querySelector('.ZoomUp').style="font-size:12px;color:#cdcdcd"}
				else{document.querySelector('.ZoomUp').style="font-size:12px;color:#0000FF"}
				if(map.getZoom()==1){document.querySelector('.ZoomDown').style="font-size:12px;color:#cdcdcd"}
				else{document.querySelector('.ZoomDown').style="font-size:12px;color:#0000FF"}
				var pixelSizeAtZoom0 = 1;
				var maxPixelSize = 999999;
				var zoom = map.getZoom();
				var relativePixelSize = ((zoom*5)/100)*52;
				if(relativePixelSize > maxPixelSize){
					relativePixelSize = maxPixelSize;
				}
				if(marker.length>0){
					for(var i = 0 ; i < marker.length ; i++){
						marker[i].setIcon(
							new google.maps.MarkerImage(
								marker[i].getIcon().url,
								null,
								null,
								null,
								new google.maps.Size(relativePixelSize, relativePixelSize) //changes the scale
							)
						);
						
					}
					
				}     
			});
			initZoomControl(map);
			initMapTypeControl(map);
			initFullscreenControl(map);
		}
	
		function deleteOverlays() {
			if (marker) {
				for (i in marker) {
					marker[i].infoWindow = null;
					marker[i].setMap(null);
				}
				marker.length = 0;
			}
		}
		
		function GeoCodificar(id,calle,localidad,Provincia,Fila) {
			deleteOverlays();
			if(localidad.toLowerCase()=="ca"){
				localidad = "Cordoba";
			}
			var Destino = calle+","+localidad+",Argentina";
			initMap();
			//alert(Fila);
			codeAddress(Destino,id,Fila);
			document.getElementById("NombreDeDestinoGPS").innerHTML = Destino;
		}

		function codeAddress(Destino,id,Fila) {
			var MainSelecGPS = document.getElementById("MainSelecGPS");
			MainSelecGPS.innerHTML = '';
			geocoder = new google.maps.Geocoder();
			var address = Destino;
			geocoder.geocode( { 'address': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					document.getElementById("NombreDeDestinoGPS").innerHTML = Destino;
					if(results.length>=0){
						for(var i = 0 ; i<results.length;i++){
							var Pais = "" ;
							var Privincia = "" ;
							var Localidad = "" ;
							var CalleNumero = "" ;
							var Ruta = "" ;
							var CodigoPostal = "" ;
							var FufijoDeCodigoPostal = "" ;
							var DomicilioNombre = "";
							var Latitud = results[i].geometry.location.lat();
							var Longitud = results[i].geometry.location.lng();
							for(var j = 0 ; j<results[i].address_components.length; j++){
								switch (results[i].address_components[j].types[0]) {
									case 'country':
										Pais = results[i].address_components[j].long_name;
									break;
									case 'locality':
										Localidad = results[i].address_components[j].long_name;
									break;
									case 'administrative_area_level_1':
										Privincia = results[i].address_components[j].long_name;
									break;
									case 'street_number':
										CalleNumero = results[i].address_components[j].long_name;
									break;
									case 'route':
										Ruta = results[i].address_components[j].long_name;
										
									break;
									case 'postal_code':
										CodigoPostal = results[i].address_components[j].long_name;
									break;
									case 'postal_code_suffix':
										FufijoDeCodigoPostal = results[i].address_components[j].long_name;
									break;
								}
							}
							if( Ruta != "" ){DomicilioNombre = Ruta;}
							if( CalleNumero!="" &&  Ruta != "" ){DomicilioNombre = DomicilioNombre + " " + CalleNumero;}
							else{if(CalleNumero!=""){DomicilioNombre = DomicilioNombre + CalleNumero;}}
							if( Localidad!="" && (CalleNumero !="" || Ruta != "") ){DomicilioNombre = DomicilioNombre + ", " + Localidad;}
							else{if(Localidad!=""){DomicilioNombre = DomicilioNombre + "" + Localidad;}}
							if( Privincia!="" ){DomicilioNombre = DomicilioNombre + ", " + Privincia;}
							if( Pais!="" ){DomicilioNombre = DomicilioNombre + ", " + Pais;}
							//alert(DomicilioNombre);
							var SeleccionDePin = document.createElement("div");
							SeleccionDePin.className = "col-md-12";
							//SeleccionDePin.name = "SeleccionDePin";
							SeleccionDePin.setAttribute("name","SeleccionDePin")
							SeleccionDePin.id = "SeleccionDePin";
							SeleccionDePin.Fila = Fila;
							SeleccionDePin.PiezaId = id;
							SeleccionDePin.Lat = Latitud;
							SeleccionDePin.Long = Longitud;
							
							var Pinbox = document.createElement("div");
							Pinbox.className = "Pin-box";
							Pinbox.setAttribute("style", "background-color:#5252bb");
							var radio = document.createElement("input");
							radio.type = "radio";
							radio.name = "radio";
							if(i==0){radio.setAttribute("checked","")};
							var b = document.createElement("b");
							b.innerHTML = DomicilioNombre;
							var hr = document.createElement("hr");
							hr.style="margin-top:1px;margin-bottom:6px;";
							Pinbox.appendChild(radio);
							Pinbox.appendChild(b);
							Pinbox.appendChild(hr);
							SeleccionDePin.appendChild(Pinbox);
							MainSelecGPS.appendChild(SeleccionDePin);
							PinToMap(Latitud,Longitud,DomicilioNombre,"sobre verde 32x32.png",i);
						}
					}
				} 
				else {
					alert("No Se Encontraron Resultados:");
					//alert("Geocode was not successful for the following reason: " + status);
				}
			});
		}
	
	
		function SeleccionarPin(div){
			var MainSelecGPS = document.getElementById("MainSelecGPS");
			var SeleccionDePins = document.getElementsByName("SeleccionDePin");
			var radio = document.getElementsByName("radio");
			var ItemSelected = -1 ; 
			for(var i = 0 ; i < radio.length ; i++ ){
				if(radio[i].checked){
					ItemSelected = i;
				}
			}
			if(ItemSelected>=0){
				for(var i = 0 ; i < SeleccionDePins.length ; i++ ){
					if(ItemSelected==i){
						//alert("id:" + SeleccionDePins[i].PiezaId + " Lat:" + SeleccionDePins[i].Lat + " Long:" + SeleccionDePins[i].Long );
						var Idiv =  document.getElementById(div);
						var tabla = Idiv.getElementsByTagName("TABLE");
						//alert(" tabla[0].rows.length:"+ tabla[0].rows.length +" SeleccionDePins.Fila:"+SeleccionDePins[i].Fila+"");
						tabla[0].rows[SeleccionDePins[i].Fila].hidden="true"; 
						filtro=["PiezaId", "Lat", "Long", "NoMemory"];
						filtroX=[ SeleccionDePins[i].PiezaId, SeleccionDePins[i].Lat, SeleccionDePins[i].Long ,NoMemory];
						AjaxParagrap(filtro,filtroX,"HTMLS/AjaxLatLongAPieza.php")
						
						$('#CalleAGPS').modal('hide');
						/*
							if (typeof $.bootstrapGrowl === "function") {
							$.bootstrapGrowl( "Ubicacion Actualizada" +"", {
								type: 'success',//danger
								align: 'center',
								width: 'auto'
							});
						}
						*/
					}
				}
			}
		}
		
		
		
	`;