(function( $ ){
	$.fn.reviewPassword = function( options ) {  

    var settings = $.extend(true, {
      'crackSpeed' : 200000000,
      'tipSpace': 3,
      'preventWeakSubmit': false,
	  'minValues': {
		'size': 8,
		'uniqueChars': 4,
		'letters': 3,
		'numbers': 1,
		'specChars': 1,
		'altCase': 0
	  }
    }, options);
	
	var internals = {
		tests : [
		{
		"test": function ( str ) {return {'passed':str.length>=settings.minValues.size, 'found':false}; },
		"error": "La contrase&ntilde;a es muy corta.",
		"details": "Usa al menos "+settings.minValues.size+" caracteres.",
		"type": "size",
		"size": 0
		},
		
		{
		"test": function ( str ) {
			var chars = new Object();
			uniq = 0;
			for(x = 0; x < str.length; x++) {
				i =  str.charAt(x);
				if (isNaN(chars[i])) {
					chars[i] =1 ;
					uniq++;
				}
			}
			return {'passed':uniq>=settings.minValues.uniqueChars, 'found':false};
		},
		"error": "La contrase&ntilde;a es demasiado uniforme.",
		"details": "Usa al menos "+settings.minValues.uniqueChars+" caracteres &uacute;nicos.",
		"type": "uniqueChars",
		"size": 0
		},
		
		{
		"test": /[A-Za-z]/g,
		"error": "Agrega m&aacute;s letras.",
		"details": "Usa al menos "+settings.minValues.letters+" letras.",
		"type": "letters",
		"size": 26
		},
		
		{
		"test": /\d/g,
		"error": "Agrega m&aacute;s n&uacute;meros para fortalecer tu contrase&ntilde;a.",
		"details": "Ejemplo: 'DuBEn748' en lugar de 'DuBEn'.",
		"type": "numbers",
		"size": 10
		},
		
		{
		"test": /[^A-Za-z0-9]/g,
		"error": "Agrega caracteres especiales para fortalecer tu contrase&ntilde;a.",
		"details": "Ejemplo: 'aLkyj%637*' en lugar de 'aLkyj637'.",
		"type": "specChars",
		"size":30
		},
		
		{
		"test": /[a-z][A-Z]|[A-Z][a-z]/g,
		"error": "Usa may&uacute;sculas y min&uacute;sculas.",
		"details": "Ejemplo: 'TAmBoRin$31E' en lugar de 'tamborin$31e'.",
		"type": "altCase",
		"size": 26
		}
		
		],
		init: function ( options, passField ) {
			this.meta = this.buildPlugin();
			this.passField = passField;
			passField.keyup($.proxy(this.review, this));
			passField.focusin($.proxy(function (evt) {this.meta.plugin.fadeIn(100); this.passField.trigger('keyup');}, this));
			passField.focusout($.proxy(function (evt) {this.meta.plugin.fadeOut(100)}, this));
			if (settings.preventWeakSubmit) {
					passField.closest('form').submit($.proxy(function () {
						if (!this.data("reviewData").approved) {
							this.focus();
							return false;
							}
						},this.passField));
				}
		},
		buildPlugin: function () {
			var meta = {"global":{} ,"level":{} , "stats":{}, "error": {}};
			meta.plugin = $( '<div/>', { 'class':'passTip', 'style':"display:none;"});
			meta.plugin.append($('<div/>', { 'class':'tri'}));
				var container = $('<div/>', { 'class':'container round'});
				container.append($('<strong/>').html("Fortaleza de Contrase&ntilde;a:"));
					var qmeter = $('<div/>', { 'class':'qmeter round'});
					meta.level.bar = $('<div/>', { 'class':'level round weak', style:'width:0%'});
					meta.level.status = $('<span/>', {'class':'status'}).html("Weak");
					qmeter.append(meta.level.bar).append(meta.level.status);
				container.append(qmeter);
				container.append($('<b/>').html("Fortaleza (entrop&iacute;a): "));
				meta.stats.entropy = $('<span/>').html("0 bits.");
				container.append(meta.stats.entropy);
				container.append($('<br/>'));
				container.append($('<b/>').html("Tiempo para violarla: "));
				meta.stats.bruteTime = $('<span/>').html("0 segundos.");
				container.append(meta.stats.bruteTime);
					var err = $('<div/>', { 'class':'error round'});
					meta.error.data = $('<span/>');
					meta.error.details = $('<i/>');
					err.append(meta.error.data).append($('<br/>')).append(meta.error.details);
				container.append(err);
			meta.plugin.append(container);
			$("body").append(meta.plugin);
			return meta;
		},
		update: function () {
			var stat = this.meta.level.status;
			var bar = this.meta.level.bar;
			var entropy = this.data.entropy;
			
			this.meta.plugin.css({
				"left": this.passField.offset().left+this.passField.width()+ settings.tipSpace,
				"top": this.passField.offset().top-27
			});
			this.meta.stats.entropy.html(this.data.entropy+(this.data.entropy==1? " bit." :" bits."));
			this.meta.stats.bruteTime.html(this.data.bruteTime+".");
			if (!this.data.ftest) 
				this.meta.error.data.closest("div").hide();
				else {
					this.meta.error.data.closest("div").show();
					this.meta.error.data.html(this.data.ftest.error);
					this.meta.error.details.html(this.data.ftest.details);
				}
			q = this.data.quality*15 + this.data.entropy;
			q = q<200?q:200;
			combined = [
					{
					"q": 50,
					"cls": "vweak",
					"caption": "Muy D&eacute;bil"
					},
					{
					"q": 90,
					"cls": "weak",
					"caption": "D&eacute;bil"
					},
					{
					"q": 130,
					"cls": "good",
					"caption": "Moderada"
					},
					{
					"q": 160,
					"cls": "strong",
					"caption": "Fuerte"
					},
					{
					"q": Infinity,
					"cls": "excellent",
					"caption": "Muy Fuerte"
					},
				];
			for (i=0; i<5; i++) {
					stage = combined[i];
					if (q<stage.q) {
							stat.html(stage.caption);
							bar.attr('class', 'level round '+stage.cls);
							break;
						}
				}
			if (this.data.quality==6) bar.animate({"width":"100%"}, 150); else bar.animate({"width":q/2+"%"}, 150);
			this.passField.data("reviewData", {
					approved: q>90,
					data: this.data
				});
		} ,
		humanTime: function ( seconds ) {
			var num = 0;
			unit = "";
			if (seconds<1) { return "Menos de un segundo"}
			else if (seconds<60) { num = Math.round (seconds); unit = " segundo"}
			else if (seconds<60*60) { num =  Math.round (seconds / 60); unit = " minuto"}
			else if (seconds<60*60*24) { num =  Math.round (seconds / (60*60)); unit = " hora"}
			else if (seconds<60*60*24*30) { num =  Math.round (seconds / (60*60*24)); unit = " dia"}
			else if (seconds<60*60*24*30*12) { num =  Math.round(seconds/(60*60*24*30)) ; unit = " mes"}
			else if (seconds<60*60*24*30*12*1000) { num =  Math.round(seconds/(60*60*24*30*12)) ; unit = " a&ntilde;o"}
			else { return "M&aacute;s de mil a&ntilde;os"}
			unit = num==1? unit: unit+"s";
			return num + unit;
		},
		review: function ( event ) {
			this.data = {ftest:false};
			var password = this.passField.val();
			var matchPat = function ( str, pat, count ) {
				var matches = str.match(pat);
				if (!isNaN(matches)) matches = [];
				if (matches.length>=count) return {'passed': true, 'found': matches.length>0}; else return {'passed': false};
			}
			var symbPool = 0;
			$.each(this.tests, $.proxy(function ( c, v ) {
				var result = (typeof(v.test) == "function")? v.test(password) : matchPat(password, v.test, settings.minValues[v.type]);
				if (result.found) symbPool = symbPool + v.size; 
				if (!result.passed) 
					this.ftest = {
							error: v.error,
							details: v.details,
							type: v.type,
							num: c
						};
				
			}, this.data));
			var crackSeconds = Math.pow(symbPool,password.length) / (settings.crackSpeed*2);
			this.data.bruteTime = this.humanTime(crackSeconds);
			var entropy = Math.round(password.length* (Math.log(symbPool)/Math.log(2)));
			this.data.entropy = (isNaN(entropy) || entropy==-Infinity)? 0: entropy;
			this.data.quality =  this.data.ftest? (this.tests.length-this.data.ftest.num-1): this.tests.length;
			this.update();
		}
	};
	
	internals.tests = internals.tests.reverse();
	internals.init(settings, this);
  };
})( jQuery );
;