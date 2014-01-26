<?php
// this is where PHP code starts
// the following PHP code is not visible to visitors of this page
// compare this source code to the source code in your browser and you will see

// please change this line to match your WFS (replace group05ws with the name of your workspace):
$wfs_server_url = "http://giv-siidemo.uni-muenster.de:8080/geoserver/group05ws/wfs";

// this is the function that will send your XML query to the (web-, wfs-, ...) server
// you don't need to understand how this works
function do_post_request($url, $data, $optional_headers = null)
{
  $params = array('http' => array(
              'method' => 'POST',
              'content' => $data
            ));
  if ($optional_headers !== null) {
    $params['http']['header'] = $optional_headers;
  }
  $ctx = stream_context_create($params);
  $fp = @fopen($url, 'rb', false, $ctx);
  if (!$fp) {
    throw new Exception("Problem with $url, $php_errormsg");
  }
  $response = @stream_get_contents($fp);
  if ($response === false) {
    throw new Exception("Problem reading data from $url, $php_errormsg");
  }
  return $response;
}
// end function

// end PHP code
?>
<html>
	<head>
		<title>Watch Scenario: Submission Page</title>
  </head>
  
  <body>
    <b>You submitted:</b><br><br>
    <?php 
      // $_POST[] allows you to find out what the user submitted via parameters to this page, like submit.php?lat=123&...
      // 
      // we have defined names for our input text boxes in the index.php file like this: <input type="text" name="lat">
      // the names are the same here:
      $latitude = $_POST["lat"];
      $longitude = $_POST["lon"];
      $accuracy = $_POST["acc"];
      $name = $_POST["name"];
      $adresse= $_POST["adresse"];
      $tel= $_POST["tel"];
      $email= $_POST["email"];
      $open= $_POST["open"];

      echo "Latitude: ".$latitude."<br>";   // this tells the user what he sent, we defined the $latitude variable above
      echo "Longitude: ".$longitude."<br>"; // notice: we are not validating the data for correctness, so be careful what you submit!
      
      //  This is the XML part that will be sent to the server. Please change where needed, according to the 
      //  GetCapabilities response of your WFS, your database schema, etc.
	  //  
	  //  Make sure that $longitude and $latitude variables are not switched!
	  //  replace wobs2 with the name of your PostGIS layer and group05ws with the name of your workspace
      $query_string = '<wfs:Transaction xmlns:wfs="http://www.opengis.net/wfs" service="WFS" version="1.0.0" xsi:schemaLocation="http://www.opengis.net/wfs http://schemas.opengis.net/wfs/1.0.0/WFS-transaction.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<wfs:Insert>
<feature:workshops xmlns:feature="group05ws">
<feature:name>'.$name.'</feature:name>
<feature:telephone>'.$tel.'</feature:telephone>
<feature:email>'.$email.'</feature:email>
<feature:openingtimes>'.$open.'</feature:openingtimes>
<feature:the_geom>
    <gml:Point xmlns:gml="http://www.opengis.net/gml" srsName="EPSG:4326">
          <gml:coordinates>'.$longitude.','.$latitude.'</gml:coordinates>
      </gml:Point>
</feature:the_geom>
</feature:workshops>
</wfs:Insert>
</wfs:Transaction>';

      echo "<br><br>";
      echo "Request sent:<br>";
      echo "<textarea style=\"width:550px;height:250px;\">";
      echo htmlspecialchars($query_string);
      echo "</textarea><br><br>";
      echo "The server responded: <br>";
      echo "<textarea style=\"width:550px;height:250px;\">";
      echo htmlspecialchars(do_post_request($wfs_server_url, $query_string, "Content-Type: text/xml; charset=utf-8"));
      echo "</textarea>";
    ?>
	</body>
</html>
