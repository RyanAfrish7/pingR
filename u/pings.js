function listPings()
{
	var query = document.getElementById("name").value.trim();
	if(query == "")
	{ 
		$("#result").text("");
		document.getElementById("pinglist").innerHTML = "";
		return;
	}
	$.post("/apis/u.php",{
		'querydata': query,
		callfunction: 'listPings',
	}, function (data, status){
		if(status == 'success')
		{
			if(data!='0')
			{
				document.getElementById("pinglist").innerHTML = data;
				$("#result").text("");
			}
			else
			{
				$("#result").text("Sorry, Someone is missing! Invite them.");
				document.getElementById("pinglist").innerHTML = "";
			}
		}	
	});
}