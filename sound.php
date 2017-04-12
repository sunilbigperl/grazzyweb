<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

    <head>
        <title>Audio test</title>

        <script type="text/javascript">
            
            function playSound(filename){   
				alert();
                document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' + filename + '.mp3" type="audio/mpeg" /><source src="' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3" /></audio>';
            }
			
        </script>
    </head>

    <body>

        <!-- Will try to play bing.mp3 or bing.ogg (depends on browser compatibility) -->
        <button onclick="playSound('smsalert5_7xL1bIAv');">Play</button>  
        <div id="sound"></div>

    </body>
</html>