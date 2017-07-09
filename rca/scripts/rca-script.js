jQuery('.rca-form-date').datepicker({
	'dateFormat': 'dd-mm-yy',
	'defaultDate': +1,
	'dayNames': ['Duminica', 'Luni', 'Marti', 'Miercuri', 'Joi', 'Vineri', 'Sambata'],
	'monthNames': ['Ianuarie', 'Februarie', 'Martie', 'Aprilie', 'Mai', 'Iunie', 'Iulie', 'August', 'Septembrie', 'Octombrie', 'Noiembrie', 'Decembrie'],
	'minDate': +1
	});
jQuery('#rca-state').change(function(e){
	var v = jQuery(e.target).val();
	if (v == 'in vederea inmatricularii' || v == 'in vederea inregistrarii') {
		jQuery('.rca-form-date').datepicker('option', 'minDate', 0);

		jQuery('.rca-form-regno-a').data('invalid', 0);
		jQuery('.rca-form-regno-b').data('invalid', 0);
		jQuery('.rca-form-regno-c').data('invalid', 0);
		}
	else
		jQuery('.rca-form-date').datepicker('option', 'minDate', +1);
	});
jQuery('.rca-form-front li').click(function(e){
	jQuery('.rca-form-car').animate({'left': '0px'}, 400, 'swing', function(){
		});
	});

jQuery('#rca-vehicleopts,#rca-manufacturer').change(function(e){
	jQuery.post('/wp-content/plugins/rca/ajax/model.php', {'v':jQuery('#rca-vehicleopts').val(),'m':jQuery('#rca-manufacturer').val()}, function(d){
		var s = jQuery('#rca-model');
		s[0].options.length = 1;
		var m = JSON.parse (d);
		if (m.error) {
			s[0].options[s[0].options.length] = new Option ('MODELUL NU E LISTAT:', -2);
			s[0].selectedIndex = 1;
			if (jQuery('#rca-model-new').length > 0) return;
			var i = jQuery('<input />', {'type': 'text', 'name': 'new_model'});
			i = jQuery('<div />', {'id': 'rca-model-new', 'class': 'form-item'}).append(i);
			i.insertAfter(s.parent());
			return; }
		jQuery.each(m, function(n,i){
			s[0].options[s[0].options.length] = new Option (i[0], n);
			jQuery(s[0].options[s[0].options.length-1]).data('cylinder', i[1]).data('power', i[2]).data('mass', i[3]).data('seats', i[4]);
			jQuery('#rca-model-new').remove();
			});
		s[0].options[s[0].options.length] = new Option ('MODELUL NU E LISTAT:', -2);
		});
	if (this.id == 'rca-manufacturer') {
		if (this.options[this.selectedIndex].value == -2) {
			if (jQuery('#rca-manufacturer-new').length > 0) return;
			var i = jQuery('<input />', {'type': 'text', 'name': 'new_manufacturer'});
			i = jQuery('<div />', {'id': 'rca-manufacturer-new', 'class': 'form-item'}).append(i);
			i.insertAfter(jQuery(this).parent());
			}
		else {
			jQuery('#rca-manufacturer-new').remove ();
			}
		}
	});
jQuery('#rca-ccounty').change(function(e){
	jQuery.post('/wp-content/plugins/rca/ajax/city.php', {'c':jQuery('#rca-ccounty').val()}, function(d){
		var s = jQuery('#rca-ccity');
		s[0].options.length = 1;
		var m = JSON.parse (d);
		if (m.error) { return; }
		jQuery.each(m, function(n,i){
			s[0].options[s[0].options.length] = new Option (i, n);
			});
		});
	});
jQuery('#rca-pcounty').change(function(e){
	jQuery.post('/wp-content/plugins/rca/ajax/city.php', {'c':jQuery('#rca-pcounty').val()}, function(d){
		var s = jQuery('#rca-pcity');
		s[0].options.length = 1;
		var m = JSON.parse (d);
		if (m.error) { return; }
		jQuery.each(m, function(n,i){
			s[0].options[s[0].options.length] = new Option (i, n);
			});
		});
	});

jQuery('#rca-vehicle').change(function(e){
	var s = jQuery('#rca-vehicleopts');
	var m = s.data('depmap')[this.options[this.selectedIndex].value];
	var f = 0;
	jQuery('option', s).each(function(n,o){
		if (m.indexOf(o.value) < 0)
			jQuery(o).hide();
		else {
			if (!f) jQuery(o).prop('selected',true), f = 1;
			jQuery(o).show();
			}
		});
	});

jQuery('#rca-model').change(function(e){
	var o = jQuery(this.options[this.selectedIndex]);

	if (o[0].value == -2) {
		var i = jQuery('<input />', {'type': 'text', 'name': 'new_model'});
		i = jQuery('<div />', {'id': 'rca-model-new', 'class': 'form-item'}).append(i);
		i.insertAfter(jQuery(this).parent());
		return;
		}
	jQuery('#rca-model-new').remove();

	jQuery('#rca-cylinder').val(o.data('cylinder'));
	jQuery('#rca-power').val(o.data('power'));
	});

jQuery('.rca-form input[name="rcatype"]').change(function(e){
	if (this.value == 'person') {
		jQuery('.rca-form-car,.rca-form-person,.rca-form-quote').slideDown();
		jQuery('.rca-form-company,.rca-form-leasing').slideUp();
		}
	if (this.value == 'company') {
		jQuery('.rca-form-car,.rca-form-company,.rca-form-quote').slideDown();
		jQuery('.rca-form-person,.rca-form-leasing').slideUp();
		}
	if (this.value == 'leasing-person') {
		jQuery('.rca-form-car,.rca-form-person,.rca-form-leasing,.rca-form-quote').slideDown();
		jQuery('.rca-form-company').slideUp();
		}
	if (this.value == 'leasing-company') {
		jQuery('.rca-form-car,.rca-form-compnay,.rca-form-leasing,.rca-form-quote').slideDown();
		jQuery('.rca-form-person').slideUp();
		}
	});

jQuery('.rca-form input').change(function(e){
	var i = jQuery(this);
	var d = i.data('validate');
	if (!d) return true;
	d = d[0] ? d[0] : d;
	if (!d.type) return true;
	var s = parseInt(jQuery(window).scrollTop());
	jQuery('.rca-form-alert')[0].style.top = (s - 50) + 'px';
	if (d.type == 'inarray') {
		if (d.data.indexOf(i.val().toUpperCase()) < 0) {
			i.data('invalid', 1);
			jQuery('.rca-form-alert h2').text (d.head);
			jQuery('.rca-form-alert p').text (d.text.replace('%s', i.val()));
			jQuery('.rca-form-alert-wrap').fadeIn(400, function(){
				});
			i.val('');
			return false;
			}
		i.data('invalid', 0);
		return true;
		}
	if (d.type == 'number') {
		if (d.data.min && (d.data.min > i.val())) {
			i.data('invalid', 1);
			jQuery('.rca-form-alert h2').text (d.head);
			jQuery('.rca-form-alert p').text (d.text.replace('%s', i.val()));
			jQuery('.rca-form-alert-wrap').fadeIn(400, function(){
				});
			i.val('');
			return false;
			}
		if (d.data.max && (d.data.max < i.val())) {
			i.data('invalid', 1);
			jQuery('.rca-form-alert h2').text (d.head);
			jQuery('.rca-form-alert p').text (d.text.replace('%s', i.val()));
			jQuery('.rca-form-alert-wrap').fadeIn(400, function(){
				});
			i.val('');
			return false;
			}
		i.data('invalid', 0);
		return true;
		}
	if (d.type == 'string') {
		if (d.data.lenmin && (d.data.lenmin > i.val().length)) {
			i.data('invalid', 1);
			jQuery('.rca-form-alert h2').text (d.head);
			jQuery('.rca-form-alert p').text (d.text.replace('%s', i.val()));
			jQuery('.rca-form-alert-wrap').fadeIn(400, function(){
				});
			i.val('');
			return false;
			}
		if (d.data.lenmax && (d.data.lenmax < i.val().length)) {
			i.data('invalid', 1);
			jQuery('.rca-form-alert h2').text (d.head);
			jQuery('.rca-form-alert p').text (d.text.replace('%s', i.val()));
			jQuery('.rca-form-alert-wrap').fadeIn(400, function(){
				});
			i.val('');
			return false;
			}
		if (d.data.regexp && i.val() && (i.val().length != new RegExp (d.data.regexp).exec(i.val())[0].length)) {
			i.data('invalid', 1);
			jQuery('.rca-form-alert h2').text (d.head);
			jQuery('.rca-form-alert p').text (d.text.replace('%s', i.val()));
			jQuery('.rca-form-alert-wrap').fadeIn(400, function(){
				});
			i.val('');
			return false;
			}
		i.data('invalid', 0);
		return true;
		}
	});

var $RCACheckFields = function(){
	jQuery('.rca-form input').each(function(n,i){
		var i = jQuery(i);
		/** required fields cannot be empty */
		if (i.data('required')) {
			if (i.val()) i.data('invalid', 0); else { i.data('invalid', 1); return true; }
			}
		/** check for validity */
		var d = i.data('validate');
		if (!d) return true;
		d = d[0] ? d[0] : d;
		if (!d.type) return true;
		if (d.type == 'inarray') {
			if (d.data.indexOf(i.val().toUpperCase()) < 0) {
				i.data('invalid', 1);
				return false;
				}
			i.data('invalid', 0);
			return true;
			}
		if (d.type == 'number') {
			if (d.data.min && (d.data.min > i.val())) {
				i.data('invalid', 1);
				return false;
				}
			if (d.data.max && (d.data.max < i.val())) {
				i.data('invalid', 1);
				return false;
				}
			i.data('invalid', 0);
			return true;
			}
		if (d.type == 'string') {
			if (d.data.lenmin && (d.data.lenmin > i.val().length)) {
				i.data('invalid', 1);
				return false;
				}
			if (d.data.lenmax && (d.data.lenmax < i.val().length)) {
				i.data('invalid', 1);
				return false;
				}
			if (d.data.regexp && i.val() && (i.val().length != new RegExp (d.data.regexp).exec(i.val())[0].length)) {
				i.data('invalid', 1);
				return false;
				}
			i.data('invalid', 0);
			return true;
			}
		i.data('invalid', 0);
		return true;
		});
	jQuery('.rca-form select').each(function(n,s){
		var s = jQuery(s);
		if (s.data('required')) {
			if (s.val() == '-1') { s.data('invalid', 1); return true; }
			s.data('invalid', 0);
			}
		});
	var state = jQuery('#rca-state').val();
	if (state == 'in vederea inmatricularii' || state == 'in vederea inregistrarii') {
		jQuery('.rca-form-regno-a').data('invalid', 0);
		jQuery('.rca-form-regno-b').data('invalid', 0);
		jQuery('.rca-form-regno-c').data('invalid', 0);
		}
	};

jQuery('.rca-form .form-auto-hint .form-item *').focus(function(e){
	var r,t = jQuery('<div />', {'class': 'form-auto-example', 'css': {'display': 'none'}});
	jQuery(this).parent().parent().append(t);
	var r = jQuery(this).parent().parent().attr('rel').split(',');
	t.append(jQuery('<div />', {'css': {'top': r[0] + 'px', 'left': r[1] + 'px', 'width': r[2] + 'px'}}));
	t.append(jQuery('<div />', {'css': {'top': r[3] + 'px', 'left': r[4] + 'px', 'width': r[5] + 'px'}}));
	t.show('slow');
	});
jQuery('.rca-form .form-auto-hint .form-item *').blur(function(e){
	var t = jQuery('.form-auto-example', jQuery(this).parent().parent());
	t.hide('slow', function(){t.remove();});
	});

jQuery('.rca-form .form-hint-open').mouseenter(function(e){
	jQuery('.form-hint', jQuery(this).closest('.form-row')).show();
	}).mouseleave(function(e){
	jQuery('.form-hint', jQuery(this).closest('.form-row')).hide();
	});

jQuery('.rca-form-alert button').click(function(e){
	e.preventDefault();
	jQuery('.rca-form-alert-wrap').fadeOut();
	});

jQuery('.rca-form-submit').click(function(e){
	var s = parseInt(jQuery(window).scrollTop());
	jQuery('.rca-form-alert')[0].style.top = (s - 50) + 'px';

	var err = 0;
	var p = '.rca-form';
	var t = jQuery('.rca-form input[name="rcatype"]:checked').val();
	if (this.value == 'person') {
		jQuery('.rca-form-car,.rca-form-person,.rca-form-quote').slideDown();
		jQuery('.rca-form-company,.rca-form-leasing').slideUp();
		}
	if (this.value == 'company') {
		jQuery('.rca-form-car,.rca-form-company,.rca-form-quote').slideDown();
		jQuery('.rca-form-person,.rca-form-leasing').slideUp();
		}
	if (this.value == 'leasing-person') {
		jQuery('.rca-form-car,.rca-form-person,.rca-form-leasing,.rca-form-quote').slideDown();
		jQuery('.rca-form-company').slideUp();
		}
	if (this.value == 'leasing-company') {
		jQuery('.rca-form-car,.rca-form-compnay,.rca-form-leasing,.rca-form-quote').slideDown();
		jQuery('.rca-form-person').slideUp();
		}

	if (t == 'person') p = '.rca-form-car,.rca-form-person';
	if (t == 'company') p = '.rca-form-car,.rca-form-company';
	if (t == 'leasing-person') p = '.rca-form-car,.rca-form-person,.rca-form-leasing';
	if (t == 'leasing-company') p = '.rca-form-car,.rca-form-company,.rca-form-leasing';

	$RCACheckFields();

	jQuery('input[data-required="1"],select[data-required="1"]', jQuery(p)).each(function(n,i){
		if (jQuery(i).data('invalid')) {
			jQuery(i).closest('.form-row').addClass('rca-form-error');
			err++; return;
			}
		jQuery(i).closest('.form-row').removeClass('rca-form-error');
		});
	if (err > 0) {
		e.preventDefault();	
		jQuery('.rca-form-alert h2').text ('Generarea cotatiei a esuat!');
		jQuery('.rca-form-alert p').text ('Te rugam sa verifici informatiile din campurile marcate corespunzator! Multumim!');
		jQuery('.rca-form-alert-wrap').fadeIn(400, function(){
			});
		}
	});
