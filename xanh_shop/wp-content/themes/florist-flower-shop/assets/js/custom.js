function florist_flower_shop_menu_open_nav() {
	window.florist_flower_shop_responsiveMenu=true;
	jQuery(".sidenav").addClass('show');
}
function florist_flower_shop_menu_close_nav() {
	window.florist_flower_shop_responsiveMenu=false;
 	jQuery(".sidenav").removeClass('show');
}

jQuery(function($){
 	"use strict";
   	jQuery('.main-menu > ul').superfish({
		delay:       500,
		animation:   {opacity:'show',height:'show'},  
		speed:       'fast'
   	});
});

jQuery(document).ready(function () {
	window.florist_flower_shop_currentfocus=null;
  	florist_flower_shop_checkfocusdElement();
	var florist_flower_shop_body = document.querySelector('body');
	florist_flower_shop_body.addEventListener('keyup', florist_flower_shop_check_tab_press);
	var florist_flower_shop_gotoHome = false;
	var florist_flower_shop_gotoClose = false;
	window.florist_flower_shop_responsiveMenu=false;
 	function florist_flower_shop_checkfocusdElement(){
	 	if(window.florist_flower_shop_currentfocus=document.activeElement.className){
		 	window.florist_flower_shop_currentfocus=document.activeElement.className;
	 	}
 	}
 	function florist_flower_shop_check_tab_press(e) {
		"use strict";
		// pick passed event or global event object if passed one is empty
		e = e || event;
		var activeElement;

		if(window.innerWidth < 999){
		if (e.keyCode == 9) {
			if(window.florist_flower_shop_responsiveMenu){
			if (!e.shiftKey) {
				if(florist_flower_shop_gotoHome) {
					jQuery( ".main-menu ul:first li:first a:first-child" ).focus();
				}
			}
			if (jQuery("a.closebtn.mobile-menu").is(":focus")) {
				florist_flower_shop_gotoHome = true;
			} else {
				florist_flower_shop_gotoHome = false;
			}

		}else{

			if(window.florist_flower_shop_currentfocus=="responsivetoggle"){
				jQuery( "" ).focus();
			}}}
		}
		if (e.shiftKey && e.keyCode == 9) {
		if(window.innerWidth < 999){
			if(window.florist_flower_shop_currentfocus=="header-search"){
				jQuery(".responsivetoggle").focus();
			}else{
				if(window.florist_flower_shop_responsiveMenu){
				if(florist_flower_shop_gotoClose){
					jQuery("a.closebtn.mobile-menu").focus();
				}
				if (jQuery( ".main-menu ul:first li:first a:first-child" ).is(":focus")) {
					florist_flower_shop_gotoClose = true;
				} else {
					florist_flower_shop_gotoClose = false;
				}
			
			}else{

			if(window.florist_flower_shop_responsiveMenu){
			}}}}
		}
	 	florist_flower_shop_checkfocusdElement();
	}
});

jQuery('document').ready(function($){
	jQuery(window).load(function() {
	    setTimeout(function () {
    		jQuery("#preloader").fadeOut("slow");
	    },1000);
	})
});

jQuery(document).ready(function () {
	jQuery(window).scroll(function () {
	    if (jQuery(this).scrollTop() > 100) {
	        jQuery('.scrollup i').fadeIn();
	    } else {
	        jQuery('.scrollup i').fadeOut();
	    }
	});
	jQuery('.scrollup').click(function () {
	    jQuery("html, body").animate({
	        scrollTop: 0
	    }, 600);
	    return false;
	});
});