<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <?php if (!$_GET) {
    	echo '<meta http-equiv="refresh" content="3;URL=../../index.php">';
    }?>
    <title><?php if (!$_GET) {
    echo 'Map Error!';
    }
    else {
     echo 'Aardvertiser Directions from ' . $_GET['userpost'] . ' to ' . $_GET['adpost'];
    }?></title>
    <script src="http://maps.google.co.uk/maps?file=api&amp;v=2&amp;sensor=false&amp;key=<?php echo $_GET['key']; ?>"></script>
    <style type="text/css">
    body {
      font-family: Verdana, Arial, sans serif;
      font-size: 11px;
      margin: 2px;
    }
    table.directions th {
      background-color:#EEEEEE;
      width:100%;
    }

    img {
      color: #000000;
    }
    </style>
    <script type="text/javascript">

    var map;
    var gdir;
    var geocoder = null;
    var addressMarker;
    
    function initialize() {
      if (GBrowserIsCompatible()) {      
        map = new GMap2(document.getElementById("map_canvas"));
        gdir = new GDirections(map, document.getElementById("directions"));
        GEvent.addListener(gdir, "load", onGDirectionsLoad);
        GEvent.addListener(gdir, "error", handleErrors);
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
    
        setDirections("<?php echo $_GET['userpost']; ?>", "<?php echo $_GET['adpost']; ?>", "en_GB");
      }
    }
    
    function setDirections(fromAddress, toAddress, locale) {
      gdir.load("from: " + fromAddress + " to: " + toAddress,
                { "locale": locale });
    }
    
    function handleErrors(){
     if (gdir.getStatus().code == G_GEO_UNKNOWN_ADDRESS)
       alert("No corresponding geographic location could be found for one of the specified addresses. This may be due to the fact that the address is relatively new, or it may be incorrect.\nError code: " + gdir.getStatus().code);
     else if (gdir.getStatus().code == G_GEO_SERVER_ERROR)
       alert("A geocoding or directions request could not be successfully processed, yet the exact reason for the failure is not known.\n Error code: " + gdir.getStatus().code);
     
     else if (gdir.getStatus().code == G_GEO_MISSING_QUERY)
       alert("The HTTP q parameter was either missing or had no value. For geocoder requests, this means that an empty address was specified as input. For directions requests, this means that no query was specified in the input.\n Error code: " + gdir.getStatus().code);
    
    //   else if (gdir.getStatus().code == G_UNAVAILABLE_ADDRESS)  <--- Doc bug... this is either not defined, or Doc is wrong
    //     alert("The geocode for the given address or the route for the given directions query cannot be returned due to legal or contractual reasons.\n Error code: " + gdir.getStatus().code);
       
     else if (gdir.getStatus().code == G_GEO_BAD_KEY)
       alert("The given key is either invalid or does not match the domain for which it was given. \n Error code: " + gdir.getStatus().code);
    
     else if (gdir.getStatus().code == G_GEO_BAD_REQUEST)
       alert("A directions request could not be successfully parsed.\n Error code: " + gdir.getStatus().code);
      
     else alert("An unknown error occurred.");
    }
    
    function onGDirectionsLoad(){
        // Use this function to access information about the latest load()
        // results.
    
        // e.g.
        // document.getElementById("getStatus").innerHTML = gdir.getStatus().code;
      // and yada yada yada...
    }

    </script>
  </head>
  <?php if (!$_GET) {
    echo '<center><h1>Map Error!</h1>
    <br><br><h3>You will be returned in 3 seconds</h3></center>';
    }
    else {
    ?>
  <body onload="initialize()" onunload="GUnload()" style="font-family: Arial;border: 0 none;">
    <center><h2>Aardvertiser Directions from <?php echo $_GET['userpost']; ?> to <?php echo $_GET['adpost']; ?></h2></center>
    <br/>
    <table class="directions">
      <tr>
        <th>Directions</th><th>Map</th>
      </tr>
      <tr>
        <td width="50%" valign="top">
          <div id="directions" style="width: 90%"></div>
        </td>
        <td width="50%" valign="top">
          <div id="map_canvas" style="width: 400px; height:550px;"></div>
        </td>
      </tr>
    </table>
  </body>
  <?php }
  ?>
</html>