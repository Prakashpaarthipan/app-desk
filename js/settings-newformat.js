var site_settings = '<div class="ts-button">'
        +'<span class="fa fa-cogs fa-spin"></span>'
    +'</div>'
    +'<div class="ts-body">'
	    +'<div class="ts-title">Themes</div>'
        +'<div class="ts-themes">';

        if(getCookie('assign_theme') == 'css/theme-default.css' || getCookie('assign_theme') == '' || getCookie('assign_theme') == null) {
            site_settings += '<a href="#" class="active" data-theme="css/theme-default.css"><img src="img/themes/default.jpg"/></a>';
        } else {
            site_settings += '<a href="#" data-theme="css/theme-default.css"><img src="img/themes/default.jpg"/></a>';
        }

        if(getCookie('assign_theme') == 'css/theme-brown.css') {
            site_settings += '<a href="#" class="active" data-theme="css/theme-brown.css"><img src="img/themes/brown.jpg"/></a>';
        } else {
            site_settings += '<a href="#" data-theme="css/theme-brown.css"><img src="img/themes/brown.jpg"/></a>';
        }

        if(getCookie('assign_theme') == 'css/theme-blue.css') {
            site_settings += '<a href="#" class="active" data-theme="css/theme-blue.css"><img src="img/themes/blue.jpg"/></a>';
        } else {
            site_settings += '<a href="#" data-theme="css/theme-blue.css"><img src="img/themes/blue.jpg"/></a>';
        }

        if(getCookie('assign_theme') == 'css/theme-black.css') {
            site_settings += '<a href="#" class="active" data-theme="css/theme-black.css"><img src="img/themes/black.jpg"/></a>';
        } else {
            site_settings += '<a href="#" data-theme="css/theme-black.css"><img src="img/themes/black.jpg"/></a>';
        }

    site_settings += '</div>'
        +'<div class="ts-title">Layout</div>'
        +'<div class="ts-row">'
            +'<label class="check"><input type="radio" class="iradio" name="st_layout_boxed" value="0" checked/> Full Width</label>'
        +'</div>'
        +'<div class="ts-row">'
            +'<label class="check"><input type="radio" class="iradio" name="st_layout_boxed" value="1"/> Boxed</label>'
        +'</div>'
        +'<div class="ts-title">Options</div>'
        +'<div class="ts-row">'
            +'<label class="check"><input type="checkbox" class="icheckbox" name="st_head_fixed" value="1" checked/> Fixed Header</label>'
        +'</div>'
        +'<div class="ts-row">'
            +'<label class="check"><input type="checkbox" class="icheckbox" name="st_sb_fixed" value="1" checked/> Fixed Sidebar</label>'
        +'</div>'
        +'<div class="ts-row">'
            +'<label class="check"><input type="checkbox" class="icheckbox" name="st_sb_scroll" value="1"/> Scroll Sidebar</label>'
        +'</div>'
        +'<div class="ts-row">'
            +'<label class="check"><input type="checkbox" class="icheckbox" name="st_sb_toggled" value="1"/> Toggled Navigation</label>'
        +'</div>'
    +'</div>';
    
var settings_block = document.createElement('div');
    settings_block.className = "theme-settings";
    settings_block.innerHTML = site_settings;
    document.body.appendChild(settings_block);

$(document).ready(function(){

    // alert("++"+getCookie('assign_theme')+"++");
    /* Default settings */
    var theme_settings = {
        st_head_fixed: 0,
        st_sb_fixed: 1,
        st_sb_scroll: 1,
        st_sb_right: 0,
        st_sb_custom: 0,
        st_sb_toggled: 0,
        st_layout_boxed: 0
    };
    /* End Default settings */
    
    set_settings(theme_settings,false);    
    
    $(".theme-settings input").on("ifClicked",function(){
        
        var input   = $(this);

        if(input.attr("name") != 'st_layout_boxed'){
                
            if(!input.prop("checked")){
                theme_settings[input.attr("name")] = input.val();
            }else{            
                theme_settings[input.attr("name")] = 0;
            }
            
        }else{
            theme_settings[input.attr("name")] = input.val();
        }

        /* Rules */
        if(input.attr("name") === 'st_sb_fixed'){
            if(theme_settings.st_sb_fixed == 1){
                theme_settings.st_sb_scroll = 1;
            }else{
                theme_settings.st_sb_scroll = 0;
            }
        }
        
        if(input.attr("name") === 'st_sb_scroll'){
            if(theme_settings.st_sb_scroll == 1 && theme_settings.st_layout_boxed == 0){
                theme_settings.st_sb_fixed = 1;
            }else if(theme_settings.st_sb_scroll == 1 && theme_settings.st_layout_boxed == 1){
                theme_settings.st_sb_fixed = -1;
            }else if(theme_settings.st_sb_scroll == 0 && theme_settings.st_layout_boxed == 1){
                theme_settings.st_sb_fixed = -1;
            }else{
                theme_settings.st_sb_fixed = 0;
            }
        }
        
        if(input.attr("name") === 'st_layout_boxed'){
            if(theme_settings.st_layout_boxed == 1){                
                theme_settings.st_head_fixed    = -1;
                theme_settings.st_sb_fixed      = -1;
                theme_settings.st_sb_scroll     = 1;
            }else{
                theme_settings.st_head_fixed    = 0;
                theme_settings.st_sb_fixed      = 1;
                theme_settings.st_sb_scroll     = 1;
            }
        }
        /* End Rules */
        
        set_settings(theme_settings,input.attr("name"));
    });
    
    /* Change Theme */
    $(".ts-themes a").click(function(){
        $(".ts-themes a").removeClass("active");
        $(this).addClass("active");
        $("#theme").attr("href",$(this).data("theme"));
        // setCookie('assign_theme', $(this).data("theme"), 365);
        // alert(getCookie('assign_theme'));
        return false;
    });
    /* END Change Theme */
    
    /* Open/Hide Settings */
    $(".ts-button").on("click",function(){
        $(".theme-settings").toggleClass("active");
    });
    /* End open/hide settings */
});

function set_settings(theme_settings,option){
    
    /* Start Header Fixed */
    if(theme_settings.st_head_fixed == 1)
        $(".page-container").addClass("page-navigation-top-fixed");
    else
        $(".page-container").removeClass("page-navigation-top-fixed");    
    /* END Header Fixed */
    
    /* Start Sidebar Fixed */
    if(theme_settings.st_sb_fixed == 1){        
        $(".page-sidebar").addClass("page-sidebar-fixed");
    }else
        $(".page-sidebar").removeClass("page-sidebar-fixed");
    /* END Sidebar Fixed */
    
    /* Start Sidebar Fixed */
    if(theme_settings.st_sb_scroll == 1){          
        $(".page-sidebar").addClass("scroll").mCustomScrollbar("update");        
    }else
        $(".page-sidebar").removeClass("scroll").css("height","").mCustomScrollbar("disable",true);
    
    /* END Sidebar Fixed */
    
    /* Start Right Sidebar */
    if(theme_settings.st_sb_right == 1)
        $(".page-container").addClass("page-mode-rtl");
    else
        $(".page-container").removeClass("page-mode-rtl");
    /* END Right Sidebar */
    
    /* Start Custom Sidebar */
    if(theme_settings.st_sb_custom == 1)
        $(".page-sidebar .x-navigation").addClass("x-navigation-custom");
    else
        $(".page-sidebar .x-navigation").removeClass("x-navigation-custom");
    /* END Custom Sidebar */
    
    /* Start Custom Sidebar */
    if(option && option === 'st_sb_toggled'){
        if(theme_settings.st_sb_toggled == 1){
            $(".page-container").addClass("page-navigation-toggled");
            $(".x-navigation-minimize").trigger("click");
        }else{          
            $(".page-container").removeClass("page-navigation-toggled");
            $(".x-navigation-minimize").trigger("click");
        }
    }
    /* END Custom Sidebar */
    
    /* Start Layout Boxed */
    if(theme_settings.st_layout_boxed == 1)
        $("body").addClass("page-container-boxed");
    else
        $("body").removeClass("page-container-boxed");
    /* END Layout Boxed */
    
    /* Set states for options */
    if(option === false || option === 'st_layout_boxed' || option === 'st_sb_fixed' || option === 'st_sb_scroll'){        
        for(option in theme_settings){
            set_settings_checkbox(option,theme_settings[option]);
        }
    }
    /* End states for options */
    
    /* Call resize window */
    $(window).resize();
    /* End call resize window */
}

function set_settings_checkbox(name,value){
    
    if(name == 'st_layout_boxed'){    
        
        $(".theme-settings").find("input[name="+name+"]").prop("checked",false).parent("div").removeClass("checked");
        
        var input = $(".theme-settings").find("input[name="+name+"][value="+value+"]");
                
        input.prop("checked",true);
        input.parent("div").addClass("checked");        
        
    }else{
        
        var input = $(".theme-settings").find("input[name="+name+"]");
        
        input.prop("disabled",false);            
        input.parent("div").removeClass("disabled").parent(".check").removeClass("disabled");        
        
        if(value === 1){
            input.prop("checked",true);
            input.parent("div").addClass("checked");
        }
        if(value === 0){
            input.prop("checked",false);            
            input.parent("div").removeClass("checked");            
        }
        if(value === -1){
            input.prop("checked",false);            
            input.parent("div").removeClass("checked");
            input.prop("disabled",true);            
            input.parent("div").addClass("disabled").parent(".check").addClass("disabled");
        }
    }
}

function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999;';  
}