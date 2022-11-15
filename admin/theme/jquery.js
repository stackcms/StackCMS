$(document).ready(function() {
	// hides the menu as soon as the DOM is ready
	$('#l1').hide();
	$('#l2').hide();
	$('#l3').hide();
	$('#l4').hide();
	$('#l5').hide();
	$('#l6').hide();
	// toggles the menu on clicking the noted link  

	$('#s1').click(function() {
		$(".slideable:not(#l1)").slideUp();
	$('#l1').slideToggle(100);
	return false;
	});

	$('#s2').click(function() {
		$(".slideable:not(#l2)").slideUp();
	$('#l2').slideToggle (100);
	return false;
	});

	$('#s3').click(function() {
		$(".slideable:not(#l3)").slideUp();
	$('#l3').slideToggle (100);
	return false;
	});

	$('#s4').click(function() {
		$(".slideable:not(#l4)").slideUp();
	$('#l4').slideToggle (100);
	return false;
    });
    
    $('#s5').click(function() {
		$(".slideable:not(#l5)").slideUp();
	$('#l5').slideToggle (100);
	return false;
    });
    
    $('#s6').click(function() {
		$(".slideable:not(#l6)").slideUp();
	$('#l6').slideToggle (100);
	return false;
    });
});

$(function () {
	$(document).scroll(function () {
		var $nav = $(".navbar-fixed-top");
		$nav.toggleClass('scrolled', $(this).scrollTop() > 150);
	});
});

function changeFunc(elementID, value) {
    var textArea = document.getElementById(elementID);

    if (typeof(textArea.selectionStart) != "undefined") {
        var begin = textArea.value.substr(0, textArea.selectionStart);
        var selection = textArea.value.substr(textArea.selectionStart, textArea.selectionEnd - textArea.selectionStart);
        var end = textArea.value.substr(textArea.selectionEnd);
        if(typeof(value) !== null) {
            textArea.value = begin + "<" + value + ">" + selection + "</" + value + ">" + end;
        } else {
            // no data
        }
    }
}

function wrapText(elementID, tag, sub) {
    var textArea = document.getElementById(elementID);
    var textSub = document.getElementById(sub);

    if (typeof(textArea.selectionStart) != "undefined") {
        var begin = textArea.value.substr(0, textArea.selectionStart);
        var selection = textArea.value.substr(textArea.selectionStart, textArea.selectionEnd - textArea.selectionStart);
        var end = textArea.value.substr(textArea.selectionEnd);
        if (typeof(sub) === "undefined") {
            textArea.value = begin + "<" + tag + ">" + selection + "</" + tag + ">" + end;
        } else {
            textArea.value = begin + "<" + tag + ">" + "<" + sub + ">" + selection + "</" + sub + ">" + "</" + tag + ">" + end;
        }
    }
}

function wrapTextTwo(elementID, openTag, closeTag) {
    var textArea = document.getElementById(elementID);

    if (typeof(textArea.selectionStart) != "undefined") {
        var begin = textArea.value.substr(0, textArea.selectionStart);
        var selection = textArea.value.substr(textArea.selectionStart, textArea.selectionEnd - textArea.selectionStart);
        var end = textArea.value.substr(textArea.selectionEnd);
        if (typeof(closeTag) === "undefined") {
            textArea.value = begin + "<" + openTag + ">" + selection + end;
        } else {
            textArea.value = begin + "<" + openTag + ">" + selection + "</" + closeTag + ">" + end;
        }
    }
}