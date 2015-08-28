
function sendmessage(formX){

	$.post("/apis/u.php", {
		text: formX.text.value,
		callfunction: 'sendmessage',
		uid: <?php
			session_start();
			echo $_GET['u'];
			?>,
	}, function(data, status){
		if(status=='success')
			if(data!='success')
				alert("Message sent failed! "+data);
	});

	formX.text.value = "";
	formX.text.focus();
	return false;

}

var x="";

window.onload = function update(){
	$.post("/apis/u.php", {
		callfunction: 'getmessage',
		uid: <?php
				echo $_GET['u'];
			?>,
	},function(data, status){

		switch(data)
		{
		case 'QUERY FAILED':
			$('.wide .messagespace').html("<div class='err'>Sorry, something had gone wrong !</div>");
			$('.long .messagespace').html("<div class='err'>Sorry, something had gone wrong !</div>");
			break;
		default:
			if(data=="")
			{
				$('.wide .messagespace').html("<div class='err'>Say a hi !</div>");
				$('.long .messagespace').html("<div class='err'>Say a hi !</div>");
			}
			if(x!=data) 
			{
				$('.wide .messagespace').html(data);
				$('.long .messagespace').html(data);
				$(".long .messagespace").animate({ scrollTop: $(".long .messagespace")[0].scrollHeight }, 1000);
				$(".wide .messagespace").animate({ scrollTop: $(".wide .messagespace")[0].scrollHeight }, 1000);
				x = data;
			}
			break;
		}
		setTimeout(function(){
			update();
		},1500);
	});
};