<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
 
    <title>
   <?php 
     echo 'Aardvertiser Directions';
    ?></title>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
  var directionDisplay;
  var directionsService = new google.maps.DirectionsService();
  var map;

  function initialize() {
    directionsDisplay = new google.maps.DirectionsRenderer();
    var myOptions = {
      zoom:7,
      mapTypeId: google.maps.MapTypeId.ROADMAP     
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById("directionsPanel"));
  }
  
  function calcRoute() {
    var start = document.getElementById("start").value;
    var end = document.getElementById("end").value;
    var request = {
        origin:start, 
        destination:end,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
      }
    });
  }
</script>
    
    </head>
    <body style="margin:0px; padding:0px;" onload="initialize(); calcRoute();">
    <div>
    Start: <input type="text" id="start" onchange="calcRoute();" />
    End: <input type="text" id="end" onchange="calcRoute();" value="<?php echo $_GET['adpost'];?>" />
    <input type="submit" value="Get Directions!" onclick="calcRoute();" />
     Remember to include extra details like UK in the location name e.g. CO15 6FG, UK</div>
<div id="map_canvas" style="float:left;width:70%; height:100%"></div>
<div id="directionsPanel" style="float:right;width:30%;height 100%"></div>
</body>
    

</html>