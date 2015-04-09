"use strict";

$(function() {
	var select2_placeholder_text = (select2_placeholder_text)? select2_placeholder_text : '選擇...';

	$(".select2").select2({
		placeholder: select2_placeholder_text,
		tags: true,
		width: '100%'
	});

	$(".select2NoTags").select2({
		placeholder: select2_placeholder_text,
		width: '100%'
	});

});