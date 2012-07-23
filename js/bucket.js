// Just a getCookies function for quirkmodes
function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) c_end = document.cookie.length;
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}

// Center div
jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - this.outerHeight()) / 2) + 
                                                $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - this.outerWidth()) / 2) + 
                                                $(window).scrollLeft()) + "px");
    return this;
}

// When + New Bucket is clicked
$('.add-bucket').on('click', function(){
	$('.overlay').fadeIn(100);
	$('.add-bucket-popup').center().css('display', 'block');
});

var fi = 2;

//When "Add Friend" is clicked
$('.add-friend').on('click', function(){
	if(fi > 3){
		$(this).remove();
	}else{

		var friends = "<div class=\"group\"><select name=\"friend-"+fi+"\">" + $('.fb_friends select').html() + "</select></div>";

		$('.fb_friends').after(friends);
		$('.add-bucket-popup').height("+=30").center();
		fi++;

	}
});