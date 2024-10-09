<!DOCTYPE html> 
<html> 
<head> 
<title> 
	How to reload page after 
	specific seconds? 
</title> 
</head> 
<body> 
<h1 style="color: green"> 
	Pilar Processing data, dengan refresh halaman
</h1> 
<b> 
	How to reload page after 
	specific seconds? 
</b> 
<p> 
	GeeksforGeeks is a computer science 
	portal with a huge variety of well 
	written and explained computer science 
	and programming articles, quizzes and 
	interview questions. 
</p> 
<p> 
	The page will be reloaded in 5 seconds. 
</p> 
<script src="https://code.jquery.com/jquery-3.3.1.min.js"> 
</script> 

<script type="text/javascript"> 
	$(document).ready(function () { 
	setTimeout(function () { 
		alert('Reloading Page'); 
		location.reload(true); 
	}, 5000); 
	}); 
</script> 
</body> 
</html> 
