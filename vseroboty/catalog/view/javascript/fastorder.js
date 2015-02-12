jQuery(document).ready(function(){
    jQuery.fn.center = function () {
        var w = jQuery(window);
        var opera = (jQuery.browser.opera) ? 0.85 : 1;
        this.css("position", "absolute");
        this.css("top", ((w.height() - this.height()) / 2 + w.scrollTop()) * opera + "px");
        this.css("left", (w.width() - this.width()) / 2 + w.scrollLeft() + "px");
        return this
    };
 var THEMEURL = $.config.url+"/catalog/view/theme/default/image/";
 var BlockHTML = '<div id="close_w"><a href="javascript:void(0);"><img src="'+ THEMEURL +'close.gif" border="0" id="close_remind" /></a></div><div class="fast_order_desc">Мгновенный заказ</div><div class="msg_error" id="auth_err"></div><div class="msg_ok" id="auth_ok"> </div><form onsubmit="return false;"><input id="pID" name="productID" value="" type="hidden" /><div class="elem"><div class="call">Имя</div><div class="block overflow"><span class="field"><input class="required" type="text" name="name" id="name" value="" /></span></div></div><div class="elem"><div class="call">Телефон</div><div class="block overflow"><span class="field"><input class="required" type="text" id="phone" name="phone" value="" /></span></div></div>' +
 '<div class="elem"><div class="call">E-mail</div><div class="block overflow"><span class="field"><input type="text" name="email" id="email" value="" /></span></div></div>'+
 '<div class="elem"><div class="call">Код купона</div><div class="block overflow"><span class="field"><input type="text" name="code" value="" /></span></div></div>'+
 '<div class="elem"><div class="call"></div><div class="block overflow"><span class="field"><input type="button" id="go" class="button" value="Отправить" /><input id="data_url" type="hidden" name="url" value="" /><input id="product" type="hidden" name="product" value="" /></span></div></div></form>';


  jQuery('body').append('<div id="overlay"></div>');
  jQuery('body').append('<div id="loading"></div>');
  jQuery('body').append('<div id="fast_order"></div>');

	var getReq = function(){
	 				var i = 0; reqel = new Array();
	 				jQuery('.required').each(function(){reqel[i] = jQuery(this).attr('name');i++;});
	 				return reqel;
	};

	function ShowImage2(){
		var yScroll = jQuery.browser.opera? window.innerHeight : jQuery(window).height();
		//Опредиляем высоту окна
	  var windowHeight = jQuery.browser.opera? window.innerWidth : jQuery(window).width();
		//Вертикальная позиция popup
		yScroll = 0;
	  var posTop = Math.round((windowHeight/2) + yScroll);

	   // alert( 'yScroll = ' + yScroll + '   windowHeight = ' + windowHeight  + ' hight not ie = ' + window.innerHeight);

	  //показываем индикатор загрузки
	  jQuery('#loading').css({display:'block',top:posTop+'px'});
	  //прячем всплывающее "окно" если оно не скрыто
	  jQuery('#fast_order').css('display','none');

		//после того как картинка загрузится
		jQuery('#loading').css('display','none');//убираем индикатор загрузки

		jQuery('#fast_order').center();
		jQuery('#overlay').css({//растягиваем фоновый слой
						width:		jQuery(window).width(),
						height:		jQuery(document).height(),
						opacity:  0.5
					}).fadeIn(5,function() {//показываем затемненный фон;
		//после этого плавное появление дива #popup с нашей картинкой
		jQuery('#fast_order').fadeIn().css('opacity','1');
		}).click(function(){jQuery('#close_w a').click();});


	}


	var closeForm = function(){ jQuery('#close_w a').click();  };

	jQuery('.fastorder').live("click",function(){
		var url = jQuery(this).attr('data-url');
		var name = jQuery(this).attr('data-name');
		jQuery('#fast_order').html(BlockHTML);
		jQuery('#pID').val(jQuery(this).parents('form').attr('rel'));
		jQuery('#fast_order').liveValidation({
		  required: getReq(),
		  fields: {
			  "name": /^\S.*$/,
			  "phone": /^[+]?[0-9\\-]+(?:\\([0-9]+\\))?[0-9\\-]+[0-9]{1}$/
		   }
		});
		setUtils();
		ShowImage2();
		jQuery('#fast_order #data_url').val(url);
		jQuery('#fast_order #product').val(name);
		jQuery('#fast_order .fast_order_desc').html('Мгновенный заказ <span>'+name+'</span>');
		
	});

var setUtils = function(){
			  // закрываем
			jQuery('#close_w a').click(function(){
				jQuery('#overlay').fadeOut();
				jQuery('.msg_error').hide();jQuery('#fast_order').hide();

			});

			jQuery('#go').click(function(){
					jQuery('.msg_error').hide();

				var check = true;
				if(!jQuery("#name").val()) {check = false;};
				
				
				if(jQuery("#email").val() != "")
				{
				if(!/^\w+[a-zA-Z0-9_.-]*@{1}\w{1}[a-zA-Z0-9_.-]*\.{1}\w{2,4}$/.test(jQuery("#email").val())){ check = false;};
				}
				
				
				
				if(!/^[+]?[0-9\\-]+(?:\\([0-9]+\\))?[0-9\\-]+[0-9]{1}$/.test(jQuery("#phone").val())){ check = false;};
				
			
				
			    BlockHTML = jQuery('#fast_order').html();
				var img_src = jQuery('#close_w img').attr('src');
				jQuery('#close_w img').ajaxStart(function(){
					jQuery('#close_w img').attr('src', THEMEURL + 'loading1.gif');
				});

				jQuery.ajax({
					type: "POST",
					caсhe: false,
					url: 'index.php?route=module/fastorder',
					data: jQuery('#fast_order form').serialize(),
					success:function(data){
						if ( data ) {
							jQuery('#fast_order form').html('<div class="succes">Спасибо за ваш заказ.<br /> Мы свяжемся с Вами в ближайшее время.</div>');
							jQuery('#close_w img').attr('src', img_src);
							setTimeout(closeForm, 3000);

						} else {
							// отображаем ошибку
							jQuery('#fast_order').css('height','280px');
							jQuery('#auth_ok').hide();
							jQuery('#auth_err').html('При отправке произошла ошибка').show();
						};
					}
				  });
			  });
};

});