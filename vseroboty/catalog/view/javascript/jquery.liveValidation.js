jQuery.fn.liveValidation = function (conf, addedFields) {
	var THEMEURL = $.config.url+"/catalog/view/theme/default/image/";
	var config = jQuery.extend({
		validIco:		$.config.url+'/catalog/view/theme/default/image/Validation-valid.png',// src to valid icon
		invalidIco:		$.config.url+'/catalog/view/theme/default/image/Validation-invalid.png',// src to invalid ico
		valid:			'Valid',				// alt for valid icon
		invalid:		'Invalid',				// alt for invalid icon
		validClass:		'valid',				// valid class
		invalidClass:	'invalid',				// invalid class
		required:		[],						// json/array of required fields
		optional:		[], 					// json/array of optional fields
		fields:			{}						// json of fields and regexps
	}, conf);

	var fields = jQuery.extend({
		name: 			/^\S.*$/,				// name (at least one character)
		content: 		/^\S.*$/m,				// "content" (at least one character)
		url: 			/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/,	// url
		email: 			/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,	// email
		phone: 			/^[+]?[0-9\\-]+(?:\\([0-9]+\\))?[0-9\\-]+[0-9]{1}$/
	}, config.fields);

	if (THEMEURL) {
		config.validIco = THEMEURL + 'Validation-valid.png';
		config.invalidIco = THEMEURL + 'Validation-invalid.png';
	}

	fields.website = fields.url;
	fields.title = fields.author = fields.name;
	fields.message = fields.comment = fields.description = fields.content;

	var formControls = jQuery.merge(config.required, config.optional);

	if (!formControls.length) {
		return this;
	}

	for (var i in formControls) {
		if ( jQuery(':input[name="' + formControls[i] + '"]:not([disabled])').length ) {
			formControls[i] = ':input[name="' + formControls[i] + '"]:not([disabled])';
		} else
		  if ( jQuery('select[name="' + formControls[i] + '"]:not([disabled])').length ) {formControls[i] = 'select[name="' + formControls[i] + '"]:not([disabled])';
		} else
		  if (jQuery('textarea[name="' + formControls[i] + '"]:not([disabled])').length ) {formControls[i] = 'textarea[name="' + formControls[i] + '"]:not([disabled])';
		}

		//alert(formControls[i]);
	}

	formControls = formControls.join(',');

	return this.each(function () {
		jQuery(formControls, this).each(function () {
			var t			= jQuery(this);
			var isOptional	= false;
			var fieldName	= t.attr('name');
//alert(fieldName);
			for (var i in config.optional) {
				if (fieldName == config.optional[i]) {
					isOptional = true;
					break;
				}
			}

			if (t.is('.jquery-live-validation-on')) {
				return;
			}
			else {
				t.addClass('jquery-live-validation-on');
			}

			// Add (in)valid icon
			var imageType = isOptional ? 'valid' : 'invalid';
			var validator = jQuery('<img src="' + config[imageType + 'Ico'] + '" alt="' + config[imageType] + '" />').insertAfter(t.addClass(config[imageType + 'Class']));

			// This function is run now and on key up
			var validate = function () {
				var key = t.attr('name');
				var val = t.val();
				var tit = t.attr('title');
				val = tit == val ? '' : val;
//alert('name = ' + key + '----- val = ' + val);
				// Make sure the value matches
				if ((isOptional && val == '') || val.match(fields[key])) {
					// If it's not already valid
					if (validator.attr('alt') != config.valid) {
						validator.attr('src', config.validIco);
						validator.attr('alt', config.valid);
						t.removeClass(config.invalidClass).addClass(config.validClass);
					}
				}
				// It didn't validate
				else {
					// If it's not already invalid
					if (validator.attr('alt') != config.invalid) {
						validator.attr('src', config.invalidIco);
						validator.attr('alt', config.invalid);
						t.removeClass(config.validClass).addClass(config.invalidClass);
					}
				}
			};

			validate();

			var tID = 0;
			t.focus(function(){ tID = setInterval(validate,500); });
			t.blur(function(){ clearInterval(tID); validate(); });

		});

		// If form contains any invalid icon on submission, return false
		jQuery('form', this).submit(function () {
			return !jQuery(this).find('img[alt="' + config.invalid + '"]').length;
		});
	});
};
