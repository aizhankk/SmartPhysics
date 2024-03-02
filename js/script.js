function openMenu(id, id2) {
	menu = document.getElementById(id)
	menu2 = document.getElementById(id2)
	background = document.getElementById('back')
	if (menu.style.opacity == "1") {
		menu.style.zIndex = "-500";
		background.style.zIndex = "-500";
		menu.style.opacity = "0%";
		menu.style.pointerEvents = "none";
		background.style.opacity = "0%";
	} else {
		background.style.zIndex = "500";
		menu.style.zIndex = "501";
		menu.style.opacity = "1";
		menu.style.pointerEvents = "auto";
		menu2.style.opacity = "0%";
		menu2.style.pointerEvents = "none";
		background.style.opacity = "100%";
	}
}
function closeAllMenu() {
	menu = document.getElementById('menu_left')
	menu2 = document.getElementById('menu_right')
	background = document.getElementById('back')
	background.style.zIndex = "-500";
	menu.style.opacity = "0%";
	menu.style.pointerEvents = "none";
	menu2.style.opacity = "0%";
	menu2.style.pointerEvents = "none";
	background.style.opacity = "0%";
}
function toggleAnswer(button) {
	answer = document.getElementById('answer')
	if (answer.style.display == "none") {
		answer.style.display = "block";
	} else {
		answer.style.display = "none";
	}
	button.innerHTML = button.innerHTML == "Show Answer" ? "Hide Answer" : "Show Answer";
}

function toggleSolution(button) {
	solution = document.getElementById('solution')
	if (solution.style.display == "none") {
		solution.style.display = "block";
	} else {
		solution.style.display = "none";
	}
	button.innerHTML = button.innerHTML == "Show Solution" ? "Hide Solution" : "Show Solution";
}


var fileInputs = document.querySelectorAll('.input-file input[type="file"]');

fileInputs.forEach(function (fileInput) {
    fileInput.addEventListener('change', function () {
        var file = this.files[0];
        var nextElement = this.nextElementSibling;
        if (file) {
            nextElement.innerHTML = file.name;
        }
    });
});




function showALert(text, error) {
	if (error == 1) {
		$(`#alert`).addClass("error")
	} else {
		$(`#alert`).addClass("succ")
	}
	$(`#alert`).css("display", "block")
	$(`#alert`).html(`<p>${text}</p>`)
}

function setCookie(name, value, days) {
	var d = new Date;
	d.setTime(d.getTime() + 24*60*60*1000*days);
	document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
}
function geetCookie(name) {
	var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
	return v ? v[2] : null;
}