var CL = function(d) {
	console.log(d);
}
var AL = function(d) {
	alert(d);
}

jQuery.fn.serializeObject = function() {
	//https://github.com/gillesruppert/jquery-serializeObject
	var o = {};
	var a = this.serializeArray();
	for (var i = 0, l = a.length; i < l; i++) {
		var item = a[i];
		var name = item.name;
		var value = item.value != null ? item.value : '';
		if (o[name] !== undefined) {
			if (!o[name].push) {
				o[name] = [o[name]];
			}
			o[name].push(value);
		}
		else {
			o[name] = value ;
		}
	}
	return o;
};

var showLoader = function() {
	$('body').append('<div class="loader"><div class="spinner"></div></div>');
}

var removeLoader = function() {
	$('.loader').remove();
}

var contype = function(xhr) {
	var ct = xhr.getResponseHeader('content-type') || '';
	if (ct.indexOf('json') > -1) {
		return 'json';
	} else if (ct.indexOf('html') > -1) {
		return 'html';
	} else {
		return '';
	}
}

var respond = function(res) {
	cod = (typeof res.cod !== 'undefined') ? res.cod : '-';
	msg = (typeof res.msg !== 'undefined') ? res.msg : 'Terjadi Kesalahan Silahkan Mengulangi Proses Kembali.';
	link = (typeof res.link !== 'undefined') ? res.link : 'warning';
	
	//Alert And Link
	if(cod === 0)			
	{
		goAlert({
			msg: msg,
			callback : function(ok) {
				if(ok) {
					window.location = link;
				}
			}
		});
	}
	//Link
	else if(cod === 1)
	{
		window.location = link;
	}
	//Popup Validation
	else if(cod === 2)
	{
		goAlert({
			msg: msg,
			type: link
		});
		return false;
	}
}

$(document).on({ // sscn.bkn.go.id
	ajaxStart : function() {
		timeOut = setTimeout(function() {
			showLoader();
		}, 200);
	},
	ajaxStop : function() {
		clearTimeout(timeOut);
		removeLoader();
	},
	ajaxError : function(e, x, settings, exception) {
		var message;
		var statusErrorMap = {
			'400' : "Server understood the request, but request content was invalid.",
			'401' : "Unauthorized access. Session is invalid.",
			'403' : "Forbidden resource can't be accessed.",
			'500' : "Internal server error.",
			'503' : "Service unavailable."
		};
		if (x.status) {
			message = statusErrorMap[x.status];
			if (!message) {
				message = " Error HTTP Status " + x.status + "\n.";
			}
		} else if (exception == 'parsererror') {
			message = "Error.\nParsing JSON Request failed.";
		} else if (exception == 'timeout') {
			message = "Request Time out.";
		} else if (exception == 'abort') {
			message = "Request was aborted by the server";
		} else {
			message = "Terjadi Kesalahan Silahkan Mengulangi Proses Kembali\n.";
		}
		goAlert({msg: message, type: 'warning'});
		removeLoader();
	}
});

$(function() {
	$(document).off('click', "input[name='i-check[]']");
	$(document).on('click',  "input[name='i-check[]']", function(e) {
		if($(this).is(':checked') == false) {
			var checkboxes = $(this).closest('table').find('.check-all');
			checkboxes.prop('checked', false).change();
		}
	});
	
	$(document).off('click', 'btn-hspk');
	$(document).on('click', '.btn-hspk', function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		if(typeof id === 'undefined' || id == '' || id === false) return false;
		modalHSPK = new BootstrapDialog({
			title: 'Informasi HSPK',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/dashboard/hspk/'+id)
		});
		modalHSPK.open();
		
		return false;
	});
	
	$(document).off('click', 'btn-lookup-unit');
	$(document).on('click', '.btn-lookup-unit', function(e) {
		e.preventDefault();
		var data = {
			setid : $(this).data('setid'),
			setkd : $(this).data('setkd'),
			setnm : $(this).data('setnm')
		}
		
		modalLookupUnit = new BootstrapDialog({
			title: 'Lookup Unit',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/unit/', data)
		});
		modalLookupUnit.open();
		
		return false;
	});
	
	//penambahan lookup untuk cetak laporan matrik 5.1 per perangkat daerah dengan penggabungan laporan untuk opd yang memiliki UPTD maupun blud
	$(document).off('click', 'btn-lookup-unit-opd-blud-uptd');
	$(document).on('click', '.btn-lookup-unit-opd-blud-uptd', function(e) {
		e.preventDefault();
		var data = {
			setid : $(this).data('setid'),
			setkd : $(this).data('setkd'),
			setnm : $(this).data('setnm')
		}

		modalLookupUnitUptdBlud = new BootstrapDialog({
			title: 'Lookup Unit',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/unit_uptd_blud/', data)
		});
		modalLookupUnitUptdBlud.open();

		return false;
	});
});

$(function() {
	flatpickr.localize(flatpickr.l10ns.id);
	numeral.register('locale', 'id', {
		delimiters: {
			thousands: '.',
			decimal: ','
		},
		abbreviations: {
			thousand: 'K',
			million: 'M',
			billion: 'B',
			trillion: 'T'
		},
		currency: {
			symbol: 'Rp. '
		}
	});
	numeral.locale('id');
});

var goAlert = function(i) {
	var msg = (typeof i.msg !== 'undefined') ? i.msg : i,
		title = (typeof i.title !== 'undefined') ? i.title : '',
		type = (typeof i.type !== 'undefined') ? i.type : 'primary',
		callback = (typeof i.callback !== 'undefined') ? i.callback : null;
	
	BootstrapDialog.alert({
		type: 'type-' + type,
		title: title,
		message: function(dialog) {
			(title == '') ? dialog.getModalHeader().hide() : '';
			dialog.getModalFooter().find('button').removeClass('btn-default').addClass('btn-' + type);
			return msg;
		},
		closable: true,
		callback: callback
	});
}

var goConfirm = function(i) {
	
	var msg = (typeof i.msg !== 'undefined') ? i.msg : i,
		title = (typeof i.title !== 'undefined') ? i.title : '',
		type = (typeof i.type !== 'undefined') ? i.type : 'primary',
		callback = (typeof i.callback !== 'undefined') ? i.callback : null;
	
	return BootstrapDialog.confirm({
		type: 'type-' + type,
		title: title,
		message: function(dialog) {
			(title == '') ? dialog.getModalHeader().hide() : '';
			return msg;
		},
		btnOKClass: 'btn-' + type,
		callback: callback
	});	
}

var getVal = function(id) {
	return $(id).val().trim();
}

var isEmpty = function(s) {
	return (typeof s === 'string' && s.trim().length === 0);
}

var updateNum = function(block) {
	var block = (typeof block !== 'undefined') ? block : '';
	
	$(block + ' .rp').each(function() {
		$(this).text(numeral($(this).text().replace(/\./g, ',')).format('$0,0'));
		$(this).val(numeral($(this).val().replace(/\./g, ',')).format('$0,0'));
	});
	$(block + ' .rp2d').each(function() {
		$(this).text(numeral($(this).text().replace(/\./g, ',')).format('$0,0.00'));
		$(this).val(numeral($(this).val().replace(/\./g, ',')).format('$0,0.00'));
	});
	
	$(block + ' .nu').each(function() {
		$(this).text(numeral($(this).text().replace(/\./g, ',')).format('0,0'));
		$(this).val(numeral($(this).val().replace(/\./g, ',')).format('0,0'));
	});
	$(block + ' .nu2d').each(function() {
		$(this).text(numeral($(this).text().replace(/\./g, ',')).format('0,0.00'));
		$(this).val(numeral($(this).val().replace(/\./g, ',')).format('0,0.00'));
	});
	$(block + ' .nu4d').each(function() {
		$(this).text(numeral($(this).text().replace(/\./g, ',')).format('0,0.0000'));
		$(this).val(numeral($(this).val().replace(/\./g, ',')).format('0,0.0000'));
	});
}

var updateMask = function(block) {
	var block = (typeof block !== 'undefined') ? block : '';
	
	$(block + '.mask-nu').inputmask('nu');
	$(block + '.mask-nu2d').inputmask('nu2d');
	$(block + '.mask-nu4d').inputmask('nu4d');
}

var updateSelect = function(block) {
	var block = (typeof block !== 'undefined') ? block : '';
	
	$(block + '.selectpicker').selectpicker();
}

var updateDate = function(block) {
	var block = (typeof block !== 'undefined') ? block : '';
	
	$(block + '.flatpickr').flatpickr({
		dateFormat: 'd-m-Y',
		wrap: true
	});
}

var updateDateP = function(block) {
	var block = (typeof block !== 'undefined') ? block : '';
	
	$(block + '.flatpickr').flatpickr({
		dateFormat: 'Y-m-d',
		wrap: true
	});
}

$(function() {
	Inputmask.extendAliases({
		'nu': {
			alias: 'numeric',
			groupSeparator: '.',
			autoGroup: true,
			digitsOptional: true,
			allowPlus: false,
			placeholder: '',
			autoUnmask: true,
			onUnMask: function(maskedValue, unmaskedValue) {
				return unmaskedValue.replace(/\./g, '').replace(/\,/g, '.');
			}
		},
		'nu2d': {
			alias: 'numeric',
			groupSeparator: '.',
			radixPoint: ',',
			autoGroup: true,
			digits: 2,
			digitsOptional: true,
			allowPlus: false,
			placeholder: '',
			autoUnmask: true,
			onUnMask: function(maskedValue, unmaskedValue) {
				return unmaskedValue.replace(/\./g, '').replace(/\,/g, '.');
			}
		},
		'nu4d': {
			alias: 'numeric',
			groupSeparator: '.',
			radixPoint: ',',
			autoGroup: true,
			digits: 4,
			digitsOptional: true,
			allowPlus: false,
			placeholder: '',
			autoUnmask: true,
			onUnMask: function(maskedValue, unmaskedValue) {
				return unmaskedValue.replace(/\./g, '').replace(/\,/g, '.');
			}
		}
	});
});

// ===================== Library PhotoSwipe =====================
var initPhotoSwipeFromDOM=function(gallerySelector){var parseThumbnailElements=function(el){var thumbElements=el.childNodes,numNodes=thumbElements.length,items=[],figureEl,linkEl,size,item;for(var i=0;i<numNodes;i++){figureEl=thumbElements[i];if(figureEl.nodeType!==1){continue} linkEl=figureEl.children[0];size=linkEl.getAttribute('data-size').split('x');item={src:linkEl.getAttribute('href'),w:parseInt(size[0],10),h:parseInt(size[1],10)};if(figureEl.children.length>1){item.title=figureEl.children[1].innerHTML} if(linkEl.children.length>0){item.msrc=linkEl.children[0].getAttribute('src')} item.el=figureEl;items.push(item)} return items};var closest=function closest(el,fn){return el&&(fn(el)?el:closest(el.parentNode,fn))};var onThumbnailsClick=function(e){e=e||window.event;e.preventDefault?e.preventDefault():e.returnValue=!1;var eTarget=e.target||e.srcElement;var clickedListItem=closest(eTarget,function(el){return(el.tagName&&el.tagName.toUpperCase()==='FIGURE')});if(!clickedListItem){return} var clickedGallery=clickedListItem.parentNode,childNodes=clickedListItem.parentNode.childNodes,numChildNodes=childNodes.length,nodeIndex=0,index;for(var i=0;i<numChildNodes;i++){if(childNodes[i].nodeType!==1){continue} if(childNodes[i]===clickedListItem){index=nodeIndex;break} nodeIndex++} if(index>=0){openPhotoSwipe(index,clickedGallery)} return!1};var photoswipeParseHash=function(){var hash=window.location.hash.substring(1),params={};if(hash.length<5){return params} var vars=hash.split('&');for(var i=0;i<vars.length;i++){if(!vars[i]){continue} var pair=vars[i].split('=');if(pair.length<2){continue} params[pair[0]]=pair[1]} if(params.gid){params.gid=parseInt(params.gid,10)} return params};var openPhotoSwipe=function(index,galleryElement,disableAnimation,fromURL){var pswpElement=document.querySelectorAll('.pswp')[0],gallery,options,items;items=parseThumbnailElements(galleryElement);options={galleryUID:galleryElement.getAttribute('data-pswp-uid'),getThumbBoundsFn:function(index){var thumbnail=items[index].el.getElementsByTagName('img')[0],pageYScroll=window.pageYOffset||document.documentElement.scrollTop,rect=thumbnail.getBoundingClientRect();return{x:rect.left,y:rect.top+pageYScroll,w:rect.width}}};if(fromURL){if(options.galleryPIDs){for(var j=0;j<items.length;j++){if(items[j].pid==index){options.index=j;break}}}else{options.index=parseInt(index,10)-1}}else{options.index=parseInt(index,10)} if(isNaN(options.index)){return} if(disableAnimation){options.showAnimationDuration=0} gallery=new PhotoSwipe(pswpElement,PhotoSwipeUI_Default,items,options);gallery.init()};var galleryElements=document.querySelectorAll(gallerySelector);for(var i=0,l=galleryElements.length;i<l;i++){galleryElements[i].setAttribute('data-pswp-uid',i+1);galleryElements[i].onclick=onThumbnailsClick} var hashData=photoswipeParseHash();if(hashData.pid&&hashData.gid){openPhotoSwipe(hashData.pid,galleryElements[hashData.gid-1],!0,!0)}}

/* ================================ Unused ======================================== */