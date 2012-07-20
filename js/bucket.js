yepnope({
	test : (!Modernizr.input.list || (parseInt($.browser.version) > 400)),
	yep : [
	  '../js/datalist-plugin.js',
	  '../js/datalist-fallback.js'
	]
});