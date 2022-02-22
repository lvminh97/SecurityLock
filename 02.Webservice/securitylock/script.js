function postRequest(link, data, func){
	var http = new XMLHttpRequest();
	http.open('POST', link, true);
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			func(http.responseText);
		}
	}
	http.send(data);
}

function getStatus(){
    var fd = new FormData();
    fd.append("cmd", "get_status");
    postRequest("./getstatus.php", fd, function(resp){
        var json = JSON.parse(resp);
        if(json.cmd == "get_status"){
            var x = document.getElementsByClassName("icon");
            var text = document.getElementById("stt-text");
            if(json.status == "open"){
                x[0].style.display = "block";
                x[1].style.display = x[2].style.display = "none";
                text.innerText = "ĐANG MỞ";
            }
            else if(json.status == "close"){
                x[1].style.display = "block";
                x[0].style.display = x[2].style.display = "none";
                text.innerText = "ĐANG ĐÓNG";
            }
            else if(json.status == "disconnect"){
                x[2].style.display = "block";
                x[1].style.display = x[0].style.display = "none";
                text.innerText = "MẤT KẾT NỐI";
            }
        }
    });
}

setInterval(function(){ getStatus(); }, 5000);