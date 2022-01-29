// 用户界面与交互控制

function delOneChar() {
    if (isRequesting || isGuessed) return;
    var nodes = document.getElementById("guess_" + current_guess_cnt).getElementsByTagName("td");
    for (var i = nodes.length - 1; i >= 0; i--) {
        if (nodes[i].innerText != "") {
            nodes[i].innerText = "";
            break;
        }
    }
}

function putChar(c) {
    if (isRequesting || isGuessed) return;
    if (!isPrintableChar(c)) return;
    var nodes = document.getElementById("guess_" + current_guess_cnt).getElementsByTagName("td");
    for (var i = 0; i < nodes.length; i++) {
        if (nodes[i].innerText == "") {
            nodes[i].innerText = c;
            break;
        }
    }
}

function isPrintableChar(c) {
    return (c.charCodeAt() >= 32 && c.charCodeAt() <= 126);
}

function buttonPressListener(e) {
    var targ
    if (!e) var e = window.event
    if (e.target) targ = e.target
    else if (e.srcElement) targ = e.srcElement

    if (targ.className.toLowerCase() == "button") {
        switch (targ.innerText) {
            case "↵":
                guess();
                break;
            case "⇦":
                delOneChar();
                break;
            default:
                putChar(targ.innerText);
                break;
        }
    }
}

function keyDownListener(event) {
    if (event.keyCode == 8) {
        delOneChar();
    }
}

function keyPressListener(event) {
    switch (event.keyCode) {
        case 13:
            guess();
            break;
        case 32:
            break; // ignore space
        default:
            putChar(String.fromCharCode(event.keyCode).toUpperCase());
            break;
    }
}

window.onload = function() {
    var buttonTable = document.getElementById("buttons");
    buttonTable.addEventListener("click", buttonPressListener);
    document.onkeypress = keyPressListener;
    document.onkeydown = keyDownListener;
};