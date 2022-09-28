	/****************************************************************************************************************/
	//Div Editable (Solo 1 Por Pagina)
	//Custon Select (Solo 1 Por Pagina)

	$(document).ready(function(){
		if(document.getElementById("myCustomSelect") != undefined){
			customSelect('#myCustomSelect', {statusSelector: '#custom-select-status'});
		}
	});
	const customSelect = function (element, overrides){
		const defaults = {
			inputSelector: 'input',
			listSelector: 'ul',
			optionSelector: 'li',
			statusSelector: '[aria-live="polite"]'
		};
		const options = Object.assign({},
			defaults, 
			overrides
		);
		const csSelector = document.querySelector(element)
		const csInput = csSelector.querySelector(options.inputSelector)
		const csList = csSelector.querySelector(options.listSelector)
		const csOptions = csList.querySelectorAll(options.optionSelector)
		const csStatus = document.querySelector(options.statusSelector)
		const aOptions = Array.from(csOptions)
		let csState = "initial"
		csSelector.setAttribute('role', 'combobox') 
		csSelector.setAttribute('aria-haspopup', 'listbox') 
		csSelector.setAttribute('aria-owns', 'custom-select-list')
		csInput.setAttribute('aria-autocomplete', 'both') 
		csInput.setAttribute('aria-controls', 'custom-select-list')
		csList.setAttribute('role', 'listbox') 
		var i = 0;
		csOptions.forEach(function(option) {
			option.setAttribute('role', 'option') 
			option.setAttribute('tabindex', -1)
			document.getElementsByTagName("body")[0].setAttribute('tabindex', -1)
			option.index=i;
			i++;
		})
		
		/*
		var evObj = document.createEvent('MouseEvents');
		evObj.initMouseEvent( 'click', true, true);
		document.getElementById("custom-select-input").dispatchEvent(evObj);
		*/
		
		var fireOnThis = document.getElementById('custom-select-input');
		var evObj = document.createEvent('HTMLEvents');
		evObj.initEvent( 'change', true, true);
		//fireOnThis.dispatchEvent(evObj);


		csSelector.addEventListener('click', function(e) {
			
			const currentFocus = findFocus()
			
			switch(csState) {
				case 'initial' :
					toggleList('Open')
					setState('opened')
					break
				case 'opened':
					//console.log(csState);
					if (currentFocus === csInput) {
						toggleList('Shut')
						setState('initial')
					} else if (currentFocus.tagName === 'LI') {
						makeChoice(currentFocus)
						toggleList('Shut')
						setState('closed')
					}
					fireOnThis.dispatchEvent(evObj);
					break
				case 'filtered':
					//console.log(csState);
					if (currentFocus.tagName === 'LI') {
						makeChoice(currentFocus)
						toggleList('Shut')
						setState('closed')
					}
					fireOnThis.dispatchEvent(evObj);
					break
				case 'closed':
					toggleList('Open')
					setState('filtered')
					break
				default:
			}
		})
		csSelector.addEventListener('keydown', function(e) {
			e.preventDefault()
		})
		csSelector.addEventListener('keyup', function(e) {
			e.preventDefault()
			doKeyAction(e.key)
		})
		document.addEventListener('click', function(e) {
			if (!e.target.closest('#myCustomSelect')) {
				toggleList('Shut')
				setState('initial')
			} 
		})
		function toggleList(whichWay) {
			if (whichWay === 'Open') {
				csList.classList.remove('hidden-all')
				csSelector.setAttribute('aria-expanded', 'true')
			} else {
				csList.classList.add('hidden-all')
				csSelector.setAttribute('aria-expanded', 'false')
			}
		}
		function findFocus() {
			const focusPoint = document.activeElement
			return focusPoint
		}
		function moveFocus(fromHere, toThere) {
			const aCurrentOptions = aOptions.filter(function(option) {
				if (option.style.display === '') {
					return true
				}
			})
			if (aCurrentOptions.length === 0) {
				return
			}
			if (toThere === 'input') {
				csInput.focus()
			}
			switch(fromHere) {
				case csInput:
					if (toThere === 'forward') {
						aCurrentOptions[0].focus()
					} else if (toThere === 'back') {
						aCurrentOptions[aCurrentOptions.length - 1].focus()
					}
					break
				case csOptions[0]: 
					if (toThere === 'forward') {
						aCurrentOptions[1].focus()
					} else if (toThere === 'back') {
						csInput.focus()
					}
					break
				case csOptions[csOptions.length - 1]:
					if (toThere === 'forward') {
						aCurrentOptions[0].focus()
					} else if (toThere === 'back') {
						aCurrentOptions[aCurrentOptions.length - 2].focus()
					}
					break
				default:
					const currentItem = findFocus()
					const whichOne = aCurrentOptions.indexOf(currentItem)
					if (toThere === 'forward') {
						const nextOne = aCurrentOptions[whichOne + 1]
						nextOne.focus()
					} else if (toThere === 'back' && whichOne > 0) {
						const previousOne = aCurrentOptions[whichOne - 1]
						previousOne.focus()
					} else {
						csInput.focus()
					}
					break
			}
		}
		function doFilter() {
			const terms = csInput.value
			const aFilteredOptions = aOptions.filter(function(option) {
				if (option.innerText.toUpperCase().startsWith(terms.toUpperCase())) {
					return true
				}
			})
			csOptions.forEach(option => option.style.display = "none")
			aFilteredOptions.forEach(function(option) {
				option.style.display = ""
			})
			setState('filtered')
			updateStatus(aFilteredOptions.length)
		}
		function updateStatus(howMany) {
			csStatus.textContent = howMany + " options available."
		}
		function makeChoice(whichOption) {
			const optionTitle = whichOption.querySelector('strong');
			csInput.value = optionTitle.textContent;
			csInput.opcion = whichOption.index;
			//console.log(whichOption.index);
			var Datos = whichOption.querySelectorAll('strong.data')[0];
			var Parrafo = document.createElement("p");
			Parrafo.innerHTML = "<font face=\"Times New Roman\">"+Datos.textContent+"<font>";
			document.getElementById("textBox").innerHTML = "";
			document.getElementById("textBox").appendChild(Parrafo);
			moveFocus(document.activeElement, 'input');
		}
		function setState(newState) {
			
			//alert(newState);
			switch (newState) {
				case 'initial': 
					csState = 'initial'
					break
				case 'opened': 
					csState = 'opened'
					break
				case 'filtered':
					csState = 'filtered'
					break
				case 'closed': 
					csState = 'closed'
			}
		}

		function doKeyAction(whichKey) {
			
			var fireOnThis = document.getElementById('custom-select-input');
			var evObj = document.createEvent('HTMLEvents');
			evObj.initEvent( 'change', true, true);
			
			const currentFocus = findFocus()
			switch(whichKey) {
				case 'Enter':
						console.log(csState);
					if (csState === 'initial') { 
						toggleList('Open')
						setState('opened')
					} else if (csState === 'opened' && currentFocus.tagName === 'LI') {
						makeChoice(currentFocus)
						toggleList('Shut')
						setState('closed')
						fireOnThis.dispatchEvent(evObj);
					} else if (csState === 'opened' && currentFocus === csInput) {
						toggleList('Shut')
						setState('closed')
					} else if (csState === 'filtered' && currentFocus.tagName === 'LI') {
						makeChoice(currentFocus)
						toggleList('Shut')
						setState('closed')
						fireOnThis.dispatchEvent(evObj);
					} else if (csState === 'filtered' && currentFocus === csInput) {
						toggleList('Open')
						setState('opened')
					} else {
						toggleList('Open')
						setState('filtered')
						fireOnThis.dispatchEvent(evObj);
					}
					break

				case 'Escape':
					if (csState === 'opened' || csState === 'filtered') {
						toggleList('Shut')
						setState('initial')
					}
					break

				case 'ArrowDown':
					if (csState === 'initial' || csState === 'closed') {
						toggleList('Open')
						moveFocus(csInput, 'forward')
						setState('opened')
					} else {
						toggleList('Open')
						moveFocus(currentFocus, 'forward')
					} 
					break
				case 'ArrowUp':
					if (csState === 'initial' || csState === 'closed') {
						toggleList('Open')
						moveFocus(csInput, 'back')
						setState('opened')
					} else {
						moveFocus(currentFocus, 'back')
					}
					break 
				default:
					if (csState === 'initial') {
						toggleList('Open')
						doFilter()
						setState('filtered')
					} else if (csState === 'opened') {
						doFilter()
						setState('filtered')
					} else if (csState === 'closed') {
						doFilter()
						setState('filtered')
					} else {
						doFilter()
					}
					break 
			}
		}
	};
	
	//Custon Select
	/***************************************************************************************/
