/*
Name: sales-drive-script
Dependencies: flat-ui
Version: 0.1
Footer: true
*/

// Some general UI pack related JS
// Extend JS String with repeat method
String.prototype.repeat = function (num) {return new Array(num + 1).join(this);};

(function ($) {
var $rpc = '/salesdrive/wp-content/plugins/wp-salesdrive/rpc/index.php';
$('.file-control').each(function(n,i){
	$('.file-upload', i).on('click', i, function(ev){
		ev.preventDefault();
		$('[type="file"]', ev.data).click().on('change', ev.data, function(ev){
			console.log(ev.data);
			$('[type="text"]', ev.data).val(this.value);
			$('.file-clear', ev.data).show();
			});
		});
	$('.file-clear', i).on('click', i, function(ev){
		ev.preventDefault();
		$('[type="text"]', ev.data).val('');
		$('.file-clear', ev.data).hide();
		}).hide();
	$('[type="text"]', i).on('keydown', function(ev){
		ev.preventDefault();
		});
	});
$('.sd-goto-meeting').click(function(ev){
	ev.preventDefault();
	this.disabled = 'disabled';
	$('.sd-character-list').addClass('hidden');
	$('.sd-character-chat').removeClass('hidden');
	});
$('.sd-message-send').click(function(ev){
	ev.preventDefault();
	var btn = $(this);
	var form = btn.closest('form');
	var chat = $('.sd-message-chat');
	var fd = new FormData (form[0]);
	if (!fd.get('question')) return;
	btn[0].disabled = true;

	$.ajax({
		'url': $rpc.replace('index.php', 'conversation.php'),
		'data': fd,
		'processData': false,
		'contentType': false,
		'type': 'POST',
		'success': function (data){
			console.log (data);
			var json = JSON.parse(data);
			if (json.error == 1) {
				return;
				}
			var msgs = $('.sd-message-list');
			var form = msgs.closest('form');
			var chat = $('.sd-message-chat');
			if (json.answer)
				chat.append ('<div class="row"><div class="col-lg-10"><div class="sd-chat left"><div class="arrow"></div><h3 class="sd-chat-title">' + json.answer.player_name + ':</h3><div class="sd-chat-content"><p>' + json.answer.player_question + '</p></div></div></div><div class="col-lg-2"><img src="/salesdrive/wp-content/themes/wp-salesdrive/assets/img/user.png" alt="" title="" class="img-rounded img-responsive" /></div></div>');

			$('.sd-character-image .sd-transparent.sd-' + json.answer.character_state).animate({'opacity': 1.0}, 1000);
			chat.animate({'scrollTop':chat[0].scrollHeight - chat[0].clientHeight}, 1000);

			setTimeout ((function(){
				if (json.answer)
					this.chat.append ('<div class="row"><div class="col-lg-2"><img src="' + json.answer.character_image + '" alt="" title="" class="img-rounded img-responsive" /></div><div class="col-lg-10"><div class="sd-chat right"><div class="arrow"></div><h3 class="sd-chat-title">' + this.json.answer.character_name + ':</h3><div class="sd-chat-content"><p>' + this.json.answer.character_answer + '</p></div></div></div></div>');

				$('.sd-character-image .sd-transparent.sd-' + this.json.answer.character_state).animate({'opacity': 0}, 1000);
				this.msgs.empty ();
				if (json.answer.id != this.json.questions[0].parent) {
					$('.sd-message-goup', form)[0].disabled = false;
					$('.sd-message-goup', form).on('click', this.json.questions, function(ev){
						ev.preventDefault ();
						var btn = $(this);
						var form = btn.closest('form');
						var msgs = $('.sd-message-list', form);
						for (var c = 0; c<ev.data.length; c++)
							msgs.append('<label class="radio"><input type="radio" name="question" value="' + ev.data[c].id + '" data-toggle="radio" />' + ev.data[c].player_question + '</label>');
						btn[0].disabled = true;
						$('.sd-message-send', form)[0].disabled = false;
						$('[data-toggle="radio"]', msgs).radiocheck();
						});
					}
				else {
					for (var c = 0; c<this.json.questions.length; c++)
						this.msgs.append('<label class="radio"><input type="radio" name="question" value="' + this.json.questions[c].id + '" data-toggle="radio" />' + this.json.questions[c].player_question + '</label>');
						$('[data-toggle="radio"]', this.msgs).radiocheck();
					$('.sd-message-send', form)[0].disabled = false;
					}
				this.chat.animate({'scrollTop':this.chat[0].scrollHeight - this.chat[0].clientHeight}, 1000);
				}).bind({'msgs':msgs, 'json':json, 'form':form, 'chat':chat}), 1000 * parseInt(json.answer.character_delay));
			}
		});
	});
$('.sd-question-read button').click(function(ev){
	ev.preventDefault ();
	var btn = $(this);
	var fd = new FormData (btn.closest('form')[0]);
	if (btn.hasClass ('sd-cancel')) {
		$('.sd-question-create').removeClass('hidden');
		$('.sd-question-update').addClass('hidden');
		}
	if (btn.hasClass ('sd-create')) {
		var node = {
			'label': fd.get('player_question'),
			'data': {
				'player_question':	fd.get('player_question'),
				'character_answer':	fd.get('character_answer'),
				'character_state':	fd.get('character_state'),
				'character_delay':	fd.get('character_delay'),
				'player_score':		fd.get('player_score'),
				'allow_purchase':	fd.get('allow_purchase') == 'on'
				},
			'id': Math.random()
			};
		$('.sd-tree').tree('appendNode', node);
		}
	if (btn.hasClass ('sd-update')) {
		var nid = fd.get('question');
		var tree = $('.sd-tree');
		var oldn = tree.tree('getNodeById', nid);
		var newn = {
			'label': fd.get('player_question'),
			'data': {
				'player_question':	fd.get('player_question'),
				'character_answer':	fd.get('character_answer'),
				'character_state':	fd.get('character_state'),
				'character_delay':	fd.get('character_delay'),
				'player_score':		fd.get('player_score'),
				'allow_purchase':	fd.get('allow_purchase') == 'on'
				},
			'id': nid
			};

		$('.sd-tree').tree('updateNode', oldn, newn);
		$('.sd-question-create').removeClass('hidden');
		$('.sd-question-update').addClass('hidden');
		}
	});
$('.sd-question-delete button').click(function(ev){
	ev.preventDefault ();
	var btn = $(this);
	var nid = btn.closest('.sd-question-delete').data('node-id');
	if (btn.hasClass ('sd-no')) {
		}
	if (btn.hasClass ('sd-yes')) {
		var tree = $('.sd-tree');
		var node = tree.tree('getNodeById', nid);
		tree.tree('removeNode', node);
		}
	$('.sd-question-delete').addClass('hidden').data('node-id', null);
	$('.sd-question-read').removeClass('hidden');
	});
$('.sd-tree').tree({
	dragAndDrop: true,
	autoOpen: false,
	openedIcon: $('<i></i>', {'class': 'fui-window'}),
	closedIcon: $('<i></i>', {'class': 'fui-windows'}),
	onCreateLi: function(node, $li){
		$li.find('.jqtree-element')
		.append($('<div></div>', {'class': 'jqtree-buttons'})
		.append($('<button></button>', {'class': 'btn btn-sm btn-info sd-update', 'html':'Edit'}).data('node-id', node.id))
		.append($('<button></button>', {'class': 'btn btn-sm btn-danger sd-delete', 'html':'Delete'}).data('node-id', node.id)));
		},
	data: []
	})
.on('click', '.sd-update', function(ev){
	var nid = $(ev.target).data('node-id');
	var node = $('.sd-tree').tree('getNodeById', nid);
	$('.sd-question-create').addClass('hidden');
	$('.sd-question-update').removeClass('hidden');

	var form = $('.sd-question-update').closest('form');
	$('[name="question"]', form).val(nid);
	$('[name="player_question"]', form).val(node.data.player_question);
	$('[name="character_answer"]', form).val(node.data.character_answer);
	$('[name="character_state"]', form).filter('[value="' + node.data.character_state + '"]').prop('checked', true);
	$('[name="character_delay"]', form).val(node.data.character_delay);
	$('[name="player_score"]', form).val(node.data.player_score);
	$('[name="allow_purchase"]', form).bootstrapSwitch ('state', node.data.allow_purchase);
	})
.on('click', '.sd-delete', function(ev){
	var nid = $(ev.target).data('node-id');
	var node = $('.sd-tree').tree('getNodeById', nid);

	$('.sd-question-delete .sd-question').text(node.name);

	$('.sd-question-read').addClass('hidden');
	$('.sd-question-delete').removeClass('hidden').data('node-id', nid);
	});
if ($('.sd-tree').length) {
	var cid = $('input[name="character"]').val();
	var fd = new FormData ();
	fd.append ('object', 'SD_Conversation');
	fd.append ('object_id', cid);
	fd.append ('action', 'read');
	fd.append ('key', 'tree');
	$.ajax({
		'url': $rpc,
		'data': fd,
		'processData': false,
		'contentType': false,
		'type': 'POST',
		'success': function (data){
			var json = JSON.parse(data);
			$('.sd-tree').tree('loadData', json);
			}
		});
	}
$('.sd-tree-btn').on('click', function(ev){
	var btn = $(this);
	var cid = $('input[name="character"]').val();
	if (btn.hasClass('sd-cancel')) {
		var fd = new FormData ();
		fd.append ('object', 'SD_Conversation');
		fd.append ('object_id', cid);
		fd.append ('action', 'read');
		fd.append ('key', 'tree');
		$.ajax({
			'url': $rpc,
			'data': fd,
			'processData': false,
			'contentType': false,
			'type': 'POST',
			'success': function (data){
				var json = JSON.parse(data);
				$('.sd-tree').tree('loadData', json);
				}
			});
		}
	if (btn.hasClass('sd-update')) {
		var tree = $('.sd-tree').tree('toJson');
		var fd = new FormData ();
		fd.append ('object', 'SD_Conversation');
		fd.append ('object_id', cid);
		fd.append ('action', 'update');
		fd.append ('key', 'tree');
		fd.append ('value', tree);
		$.ajax({
			'url': $rpc,
			'data': fd,
			'processData': false,
			'contentType': false,
			'type': 'POST',
			'success': function (data){
				console.log (data);
				}
			});
		}
	});
$('.sd-help i').on('click', function(ev){
	var win = $('.sd-help-window', $(this).closest('.sd-help'));
	var par = win.closest('.row > div');
	win.width(par.width() - 44);
	win[0].style.left = (2 - win.parent().position().left) + 'px';
	$('.arrow', win)[0].style.left = (win.parent().position().left - 4) + 'px';

	var fd = new FormData ();
	fd.append ('object', 'SD_Help');
	fd.append ('object_id', $(this).parent().data('message-id'));
	fd.append ('action', 'read');
	fd.append ('key', 'message');
	$.ajax({
		'url': $rpc,
		'data': fd,
		'processData': false,
		'contentType': false,
		'type': 'POST',
		'success': (function (data){
			this.show ();
			}).bind(win)
		});
	});
$('.sd-help-window .close').on('mousedown', function(ev){
	ev.stopPropagation ();
	ev.preventDefault ();
	var win = $(this).parent();
	win.hide ();
	});
$('li.sd-scenario span.badge').on('click', function(ev){
	ev.stopPropagation ();
	ev.preventDefault ();
	var btn = $(this);
	var sid = btn.data('scenario-id');
	if (btn.hasClass('sd-update')) {
		$('.sd-scenario-create').hide ();
		$('.sd-scenario-delete').hide ();
		$('input[type="hidden"]', $('.sd-scenario-update').show ()).val (sid);
		}
	if (btn.hasClass('sd-delete')) {
		$('.sd-scenario-create').hide ();
		$('.sd-scenario-update').hide ();
		$('input[type="hidden"]', $('.sd-scenario-delete').show ()).val (sid);
		}
	});
$('.sd-scenario-update .sd-cancel, .sd-scenario-delete .sd-no').on('click', function(ev){
	ev.preventDefault ();
	$('.sd-scenario-update').hide ();
	$('.sd-scenario-delete').hide ();
	$('.sd-scenario-create').show ();
	});

$('.sd-quality-create').on('click', function(ev){
	ev.preventDefault ();
	$('.sd-add-column', $(this).closest('.sd-product')).removeClass('hidden');
	});
$('.sd-add-column .sd-cancel').on('click', function(ev){
	ev.preventDefault ();
	$('.sd-add-column', $(this).closest('.sd-product')).addClass('hidden');
	});
$('.sd-message-read a.sd-message-update').on('click', function(ev){
	ev.preventDefault ();
	var help = $(ev.target).parent().parent();
	$('div.sd-message-read', help).hide ();
	$('div.sd-message-update', help).show ();
	});
$('.sd-message-update .sd-cancel').on('click', function(ev){
	ev.preventDefault ();
	var help = $(ev.target).closest('.sd-message-update').parent();
	$('div.sd-message-read', help).show ();
	$('div.sd-message-update', help).hide ();
	});
$('.sd-message-update .sd-update').on('click', function(ev){
	ev.preventDefault ();
	var help = $(ev.target).closest('.sd-message-update');
	var text = $('textarea', help).val();
	var read = $('.sd-message-read', help.parent());
	var fd = new FormData ();

	fd.append('object', help.data('message'));
	fd.append('object_id', help.data('message-id'));
	fd.append('action', 'update');
	fd.append('key', 'text');
	fd.append('value', text);

	$('span', read).text(text);

	$.ajax({
		'url': $rpc,
		'data': fd,
		'processData': false,
		'contentType': false,
		'type': 'POST',
		'success': (function (data){
			this.hide ();
			$('.sd-message-read', this.parent()).show ();
			}).bind(help)
		});
	});

$('a.sd-update').on('click', function(ev){
	ev.preventDefault ();
	var btn = $(this);
	var form = btn.closest('form');
	$('div.sd-update', form).removeClass('hidden');
	});
$('div.sd-update a.sd-cancel').on('click', function(ev){
	ev.preventDefault ();
	var btn = $(this);
	btn.closest('div.sd-update').addClass('hidden');
	});
$('a.sd-delete').on('click', function(ev){
	ev.preventDefault ();
	var btn = $(this);
	var form = btn.closest('form');
	$('div.sd-delete', form).removeClass('hidden');
	});
$('div.sd-delete a.sd-cancel').on('click', function(ev){
	ev.preventDefault ();
	var btn = $(this);
	btn.closest('div.sd-delete').addClass('hidden');
	});

$('.sd-integer, .sd-number').on('change', function(ev){
	this.value = parseInt(this.value);
	this.value = isNaN(this.value) ? 0 : this.value;
	});
$('.sd-float, .sd-percent').on('change', function(ev){
	this.value = parseFloat(this.value);
	this.value = isNaN(this.value) ? 0.00 : this.value;
	this.value = (parseFloat(this.value)).toFixed(2);
	});
$('.sd-hints a.sd-create, .sd-hints a.sd-delete').on('click', function(ev){
	ev.preventDefault ();
	var row = $(ev.target).closest('.row');
	var win = row.prev ();
	if (!win.hasClass('hidden')) return;
	win.removeClass('hidden');
	row.addClass('hidden');
	var hid = $(ev.target).data('hint-id');
	if (hid !== null)
		$('.sd-hints input[type="hidden"]').val(hid);
	});
$('.sd-hints a.sd-cancel').on('click', function(ev){
	ev.preventDefault ();
	var row = $(ev.target).closest('.row');
	var win = row.next ();
	if (!win.hasClass('hidden')) return;
	win.removeClass('hidden');
	row.addClass('hidden');
	$('.sd-hints input[type="hidden"]').val(-1);
	});
$('.sd-quality-delete').on('click', function(ev){
	ev.preventDefault();
	var next = $(ev.target).next();
	if (next.hasClass('hidden')) {
		next.removeClass('hidden');
		$('input[name="quality"]', $(ev.target).closest('form')).val($(ev.target).data('quality'));
		}
	$(ev.target).addClass('hidden');
	});
$('.sd-quality-cancel').on('click', function(ev){
	ev.preventDefault();
	var row = $(ev.target).closest('.row');
	var prev = row.prev();
	row.addClass('hidden');
	$('input[name="quality"]', $(ev.target).closest('form')).val('');
	if (prev.hasClass('hidden'))
		prev.removeClass('hidden');
	});
$('a.sd-confirm').on('click', function(ev){
	ev.preventDefault ();
	var top = $(ev.target).closest('.row');
	var row = top.next ();
	if (row.hasClass('hidden'))
		row.removeClass('hidden');
	if (!top.hasClass('hidden'))
		top.addClass('hidden');
	});
$('.sd-confirm .sd-cancel').on('click', function(ev){
	ev.preventDefault ();
	var top = $(ev.target).closest('.row');
	var row = top.prev ();
	if (row.hasClass('hidden'))
		row.removeClass('hidden');
	if (!top.hasClass('hidden'))
		top.addClass('hidden');
	});
$('select.sd-select-product').on('change', function(ev){
	var prd = $(this);
	var qlt = $('select.sd-select-quality', prd.closest('.row'));
	var fd = new FormData ();
	fd.append ('product', prd.val());
	$.ajax({
		'url': $rpc.replace ('index.php', 'qualities.php'),
		'data': fd,
		'processData': false,
		'contentType': false,
		'type': 'POST',
		'success': (function (data){
			var json = JSON.parse(data);
			if (json.error == 1) {
				return;
				}
			this.empty ();
			var first = null;
			for (var key in json) {
				if (json.hasOwnProperty(key))
					this.append('<option value="' + key + '" data-quantity="' + json[key].quantity + '" data-purchasable-quantitity="' + json[key].purchasable_quantity + '" data-purchased-unit-cost="' + json[key].purchased_unit_cost + '">' + json[key].name + '</option>');
				if (first === null)
					first = json[key];
				}
			this.select2();

			var form = this.closest('form');
			$('input[name="price"]', form).val(first.purchased_unit_cost);
			$('input[name="quantity"]', form).data('max', first.quantity).val(0);
			$('input[name="acquire"]', form).data('max', first.purchasable_quantity);
			$('input[name="remaining"]', form).val(first.quantity);

			}).bind(qlt)
		});

	});
$('select.sd-select-quality').on('change', function(ev){
	var opt = $(this.options[this.selectedIndex]);
	var form = this.closest('form');
	$('input[name="price"]', form).val(opt.data('purchased-unit-cost'));
	$('input[name="quantity"]', form).data('max', opt.data('quantity')).val(0);
	$('input[name="acquire"]', form).data('max', opt.data('purchasable-quantity'));
	$('input[name="remaining"]', form).val(opt.data('quantity'));
	});
$('.sd-poll .sd-up-vote, .sd-poll .sd-down-vote').on('click', function(ev){
	ev.preventDefault();
	var btn = $(this);
	var inp = $('input', btn.closest('.sd-poll-item'));
	if (btn.hasClass('sd-up-vote')) inp.val (parseInt(inp.val()) + 1);
	if (btn.hasClass('sd-down-vote')) inp.val (parseInt(inp.val()) - 1);
	$('span', btn.closest('.sd-poll-item')).text(inp.val());
	});
$('.nav-tabs a').on('click', function(ev){
	ev.preventDefault();
	$(this).tab('show');
	});
$('input[data-filter="limit"]').on('keyup', function(ev){
	var inp = $(this);
	var val = parseInt (inp.val());
	if (inp.data('min') && (val < inp.data('min')))
		inp.val(inp.data('min'));
	if (inp.data('max') && (val > inp.data('max')))
		inp.val(inp.data('max'));
	});
$('input[data-filter="constant"]').on('keyup', function(ev){
	var inp = $(this);
	var val = parseInt (inp.val());
	val = isNaN(val) ? 0 : val;
	if (!inp.data('link')) return;
	if (!inp.data('max')) return;
	var max = inp.data('max');
	var lnk = $('[data-name="' + inp.data('link') + '"]', inp.closest('form'));

	if (val > max) val = max;
	if (val < 0) val = 0;
	lnk.html(max - val);
	inp.val(val);
	});
$timer = function () {
	var fd = new FormData ();

	$.ajax({
		'url': $rpc.replace('index.php', 'timer.php'),
		'data': fd,
		'processData': false,
		'contentType': false,
		'type': 'POST',
		'success': (function (data){
			console.log (data);
			var json = JSON.parse(data);
			if (json !== false) {
				var clk = json.presentation ? $('h4.sd-timer') : $('.sd-timer');
				var txt = '';
				if (json.conversation) {
					txt += json.conversation.down + ' / ';
					if (json.conversation.alarm) {
						window.location.assign (window.location.href.replace (/\?meet=(.*)/g, '?leave=$1'));
						}
					}
				if (json.presentation) {
					txt += json.presentation.down;
					if (json.presentation.alarm) {
						$('button[name="submit_vote"]').click ();
						}
					}
				if (json.global) {
					txt += '<span>' + json.global.down + '</span>';
					if (json.global.alarm) {
						window.location.assign (window.location.href.replace (/\?(.*)/g, '?submit=true'));
						}
					}
				clk.html(txt);
				}
			window.setTimeout($timer, 800);
			})
		});
	};
if ($('.sd-timer').length > 0) {
	$timer();
	}
$votes = function () {
	var fd = new FormData ();
	fd.append ('player', $('.sd-autoload-votes').data('player'));

	$.ajax({
		'url': $rpc.replace('index.php', 'votes.php'),
		'data': fd,
		'processData': false,
		'contentType': false,
		'type': 'POST',
		'success': (function (data){
			console.log (data);
			var json = JSON.parse(data);
			var vote = $('.sd-autoload-votes');
			var c;
			if (json.error || (json.length == 0)) {
				}
			else {
				vote.empty();
				for (c = 0; c<json.length; c++) {
					vote.append ('<div class="row"><div class="col-lg-8">' + json[c].name + ':</div><div class="col-lg-4"><span class="form-control">' + json[c].value + '</span></div></div>');
					}
				if (vote.next().hasClass('hidden'))
					vote.next().removeClass('hidden');
				}
			window.setTimeout($votes, 2000);
			})
		});
	}
if ($('.sd-autoload-votes').length > 0) {
	$votes ();
	}
$('.sd-quotation-read .sd-update').on('click', function(ev){
	ev.preventDefault();
	var btn = $(this);
	var cnt = btn.closest('.sd-quotation-read');
	var frm = cnt.prev();
	$('.sd-negotiate-submit').attr('disabled', 'disabled');

	if (frm.hasClass('hidden')) {
		frm.removeClass('hidden');
		cnt.addClass('hidden');
		}
	});
$('.sd-quotation-item > form .sd-cancel').on('click', function(ev){
	ev.preventDefault();
	var btn = $(this);
	var frm = btn.closest('form');
	var cnt = frm.next();
	if (cnt.hasClass('hidden')) {
		cnt.removeClass('hidden');
		frm.addClass('hidden');
		}
	});
$('.sd-quotation-read .sd-delete').on('click', function(ev){
	ev.preventDefault();
	var btn = $(this);
	var red = btn.closest('.sd-quotation-read');
	var frm = $('form', red);
	if (frm.hasClass('hidden')) {
		frm.removeClass('hidden');
		}
	});
$('.sd-quotation-delete .sd-cancel').on('click', function(ev){
	ev.preventDefault();
	var btn = $(this);
	var frm = btn.closest('form');
	if (!frm.hasClass('hidden')) {
		frm.addClass('hidden');
		}
	});
$('.sd-negotiate-update').on('click', function(ev){
	ev.preventDefault();
	var btn = $(this);
	var frm = btn.closest('form');
	var fd = new FormData(frm[0]);

	$.ajax({
		'url': $rpc.replace('index.php', 'negotiate.php'),
		'data': fd,
		'processData': false,
		'contentType': false,
		'type': 'POST',
		'success': (function (data){
			console.log (data);
			var json = JSON.parse (data);
			if (json.html) {
				var cnt = $('.sd-quotation-text', this);
				cnt.empty ();
				cnt.append (json.html);
				$('form', this).addClass('hidden');
				$('.sd-quotation-read', this).removeClass('hidden');
				$('.sd-negotiate-submit').removeAttr('disabled');
				}
			}).bind(btn.closest('.sd-quotation-item'))
		});
	});
$('.sd-negotiate-submit').on('click', function(ev){
	ev.preventDefault ();
	var btn = $(this);
	var fd = new FormData();
	fd.append ('negotiation_submit', true);

	$('.sd-transparent.sd-thinking').animate({'opacity': 1.0}, 1000);

	$.ajax({
		'url': $rpc.replace('index.php', 'negotiate.php'),
		'data': fd,
		'processData': false,
		'contentType': false,
		'type': 'POST',
		'success': function (data){
			console.log (data);
			var json = JSON.parse (data);
			if (json.error) return;
			if (json.timer) {
				window.setTimeout((function(){
					$('.sd-transparent.sd-thinking').animate({'opacity': 0}, 1000, (function(){
						var items = $('.sd-counter-offer-item');
						var c;
						for (c = 0; c<items.length; c++)
							$(items[c]).empty().append(this.json.counter[c]);
						}).bind({'json':this.json}));
					}).bind({'json':json}), json.timer * 1000);
				}
			if (json.reload) {
				window.location.reload ();
				}
			}
		});
	});
// Add segments to a slider
  $.fn.addSliderSegments = function (amount, orientation) {
    return this.each(function () {
      if (orientation === 'vertical') {
        var output = '';
        var i;
        for (i = 1; i <= amount - 2; i++) {
          output += '<div class="ui-slider-segment" style="top:' + 100 / (amount - 1) * i + '%;"></div>';
        }
        $(this).prepend(output);
      } else {
        var segmentGap = 100 / (amount - 1) + '%';
        var segment = '<div class="ui-slider-segment" style="margin-left: ' + segmentGap + ';"></div>';
        $(this).prepend(segment.repeat(amount - 2));
      }
    });
  };

  $(function () {

    // Todo list
    $('.todo').on('click', 'li', function () {
      $(this).toggleClass('todo-done');
    });

    // Custom Selects
    if ($('[data-toggle="select"]').length) {
      $('[data-toggle="select"]').select2();
    }

    // Checkboxes and Radio buttons
    $('[data-toggle="checkbox"]').radiocheck();
    $('[data-toggle="radio"]').radiocheck();

    // Tooltips
    $('[data-toggle=tooltip]').tooltip('show');

    // jQuery UI Sliders
    var $slider = $('.ui-slider');
    if ($slider.length > 0) {
      $slider.slider({
        min: $slider.data('min'),
        max: $slider.data('max'),
        value: $('input', $slider).val(),
        orientation: 'horizontal',
        range: 'min',
	change: function(ev){
		var $slider = $(ev.target);
		$('input', $slider).val($slider.slider('value'));
		}
      }).addSliderSegments($slider.slider('option').max);
    }

    var $verticalSlider = $('#vertical-slider');
    if ($verticalSlider.length) {
      $verticalSlider.slider({
        min: 1,
        max: 5,
        value: 3,
        orientation: 'vertical',
        range: 'min'
      }).addSliderSegments($verticalSlider.slider('option').max, 'vertical');
    }

    // Focus state for append/prepend inputs
    $('.input-group').on('focus', '.form-control', function () {
      $(this).closest('.input-group, .form-group').addClass('focus');
    }).on('blur', '.form-control', function () {
      $(this).closest('.input-group, .form-group').removeClass('focus');
    });

    // Make pagination demo work
    $('.pagination').on('click', 'a', function () {
      $(this).parent().siblings('li').removeClass('active').end().addClass('active');
    });

    $('.btn-group').on('click', 'a', function () {
      $(this).siblings().removeClass('active').end().addClass('active');
    });

    // Disable link clicks to prevent page scrolling
    $(document).on('click', 'a[href="#fakelink"]', function (e) {
      e.preventDefault();
    });

    // Switches
    if ($('[data-toggle="switch"]').length) {
      $('[data-toggle="switch"]').bootstrapSwitch();
    }

    // make code pretty
    window.prettyPrint && prettyPrint();

  });

})(jQuery);
