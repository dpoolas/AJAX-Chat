$('#textinput').on('keypress', function (e) 
{	
	 if (e.which === 13)
	 {
		if (!Chat.Loaded)
		{
			alert("You must wait for the chat to be fully loaded before typing a message.");
			return;
		}
		
		var value = ($(this)).val()
		
		if (value.length < 2)
		{
			alert("Message must be more than 1 character.")
			return;
		}
		
		if (value.length > 100)
		{
			alert("Message > 100 characters.")
			return;
		}
		
		$.ajax({
            type: 'POST',
            cache: false,
            url: 'xml.php',
            data: 'message='+$(this).val(), 
            success: function(msg) {
               
            }
        });
		
		Chat.curUser = true;
		
		$(this).val("");
	 }
});

$(window).on('load', function () 
{
	Chat.chatContent = document.getElementById("mainchatContent");
	Chat.chatContent.innerHTML = "";
	
	setTimeout(Chat.onLoad, 400);
	
	window.setInterval(Chat.poll, 500);
 });
 
var Chat = {
	
	
	last: 0,
	Loaded: false,
	
	chatContent: true,
	
	chatIDs: [],
	
	audio: new Audio('notification.mp3'),
	
	flickeralert: function()
	{
		var alertm = "New Message!";
		var mainm = "Chatizzle - Main";
		var current = 1;
		var count = 0;
		
		var settitle = setInterval(function()
		{
			if (count >= 50 || document.hasFocus())
			{
				document.title = mainm;
				clearInterval(settitle);
				return;
			}
			
			current = (current % 2) + 1
			
			if (current == 1)
			{
				document.title = alertm;
			}
			else
			{
				document.title = mainm;
			}
		}, 1100)
		
	},
	
	getDate: function(timestamp)
	{
		var date = new Date(timestamp*1000);
		var month = date.getMonth() + 1;
		var day = date.getDate();
		var year = date.getFullYear();
		var hours = date.getHours();
		var minutes = "0" + date.getMinutes();
		var seconds = "0" + date.getSeconds();
		var formattedTime = Chat.format("{0}/{1}/{2} {3}:{4}:{5}", month, day, year, hours, minutes.substr(-2), seconds.substr(-2));
		
		return formattedTime;
	},
	
	format: function()
	{
		var s = arguments[0];
		for (var i = 0; i < arguments.length - 1; i++) {       
			var reg = new RegExp("\\{" + i + "\\}", "gm");             
			s = s.replace(reg, arguments[i + 1]);
		}
		
		return s;
	},
	
	addLine: function(object) 
	{
		if (!Chat.last || Chat.last == "undefined")
		{
			Chat.chatContent.innerHTML = "";
		}

		
		object.id = Number(object.id);
		
		if (Chat.last > object.id)
		{
			return;
		}
		
		if (object.id && Chat.chatIDs[object.id] != true)
		{
			Chat.last = object.id;
			Chat.chatIDs[Chat.last] = true;
			
			if (!object.avatar)
			{
				object.avatar = "images/default-avatar.png";
			}
			
			var id = Chat.format("chatcontent_{0}", object.id);
			
			
			
		Chat.chatContent.innerHTML += Chat.format("<div id='{2}' class='chatmessage'><div class='insidemessage'><div id='{2}_avatar' style='width: auto;height: auto;display:inline-block; margin-right: 3px;float: left;'><img src='{5}' alt=''/></div><div id='{2}_message'><span>[{4}] <a href='member.php?id={3}'>{0}</a>: {1}</span></div></div></div>", object.username, object.message, id, object.uid, Chat.getDate(object.timestamp), object.avatar)
			Chat.chatContent.scrollTop = Chat.chatContent.scrollHeight;
			
			setTimeout(function() {
				var rid = '#' + id;
				$(rid).attr('style', 'opacity: 1');
			}, 200)
			
			if (Chat.Loaded)
			{
				if (Chat.curUser != true)
				{
					Chat.audio.play();
				}
				
				Chat.curUser = false;

				
				if (!document.hasFocus())
				{
					Chat.flickeralert();
				}
			}
		}
	},
	
	
	poll: function()
	{
		if (Chat.Loaded != true)
		{
			return;
		}
		
		$.ajax({
            type: 'POST',
            cache: false,
            url: 'xml.php',
            data: Chat.format("update=1&id={1}", Chat.last), 
            success: function(msg) {
				if (msg.length < 5) { return; }
                var messages = jQuery.parseJSON( msg );
				$.each(messages, function(k,v) 
				{
					if(typeof(v) == "object")
					{
						Chat.addLine(v);
					}
				});
            }
        });
	},
	
	onLoad:	function() 
	{
		 $.ajax({
            type: 'POST',
            cache: false,
            url: 'xml.php',
            data: 'retrievelatest=true', 
            success: function(msg) {
				setTimeout(function(){ Chat.Loaded = true }, 2500);
				if (msg.length < 5) 
				{
					Chat.chatContent.innerHTML = "<b>No messages to display</b>";
					return; 
				}
                var messages = jQuery.parseJSON( msg );
				$.each(messages, function(k,v) 
				{
					if(typeof(v) == "object")
					{
						Chat.addLine(v);
					}
				});
            }
        });
	},
	
};