<!DOCTYPE html>
<html lang="en">
  <head>
    
    <title>WorkshopFinder - a Bike-Workshop-Search</title>
    
    <!--======= Basic Metas =======-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <meta name="A bike workshop searchengine" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <!--======= Mobile Metas =======-->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0;">
    
    <!--======= Favicons =======-->
    
    <!-- website favicon -->
    <link rel="shortcut icon" href="img/favicon.png">
    <!-- iPhone, iPod Touch -->
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
    <!-- iPhone with Retina display -->
    <link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">
    <!-- iPad first and second generation -->
    <link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
    <!-- iPad with Retina display -->
    <link rel="apple-touch-icon" sizes="144x144" href="img/apple-touch-icon-144x144.png">    
    
    <!--======= CSS =======-->
    
    <!-- CSSHórus Library -->
    <link rel="stylesheet" type="text/css" href="styles/horus.css">
    <!-- Leflet Library -->
    <link rel="stylesheet" type="text/css" href="styles/leaflet.css">
    <!-- Skin Styles -->
    <link rel="stylesheet" type="text/css" href="styles/skin/default/style.css">
    <!-- Tab Tab Styles -->
    <link rel="stylesheet" type="text/css" href="styles/tab-tab.css">

    <!-- jQuery DataTables Styles -->
    <link rel="stylesheet" type="text/css" href="styles/jquery.dataTables.css">

    <!--======= JavaScript =======-->

    <!-- Leaflet.js Library -->
    <script src="scripts/leaflet.js"></script>

    <!-- jquery.js Library -->
    <script src="scripts/jquery.js"></script>

    <!-- flexie.js Library -->
    <script src="scripts/flexie.js"></script>

    <!-- jquery.tab-tab.js Library -->
    <script src="scripts/jquery.tab-tab.js"></script>

    <!-- jquery.dataTables.js Library -->
    <script src="scripts/jquery.dataTables.js"></script>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <script src="http://css3-mediaqueries-js.googlecode.com/files/css3-mediaqueries.js"></script>
    <![endif]-->

  </head>

  <body>

    <div class="wrapper">
    
      <header class="row">
        <h1 class="m20-top"> <img src="img/logo.png" alt="WorkshopFinder-Logo" height="74" width="200"> 
            WorkshopFinder - a bike workshop search
        </h1>
        <p>Version 0.8</p>
        <hr>
      </header>
 
      <section class="row" id="intro">
        <h2>Find your way to the next bike workshop!</h2>   
        <hr>    
      </section>    
           
      <div class="row">      
        <div class="col-8">
          <form id="searchform" class="search row">
            <input type="text" name="s" id="s"> <button type="submit">Search</button>
            <br>
            <hr>
          </form>

          <article class="tabtab bananayellow flex">
            <nav>
              <ul>
                <li><a href="#tab1">Map</a></li>
                <li><a href="#tab2">Table</a></li>
              </ul>
            </nav>

            <section id="tab1">
              <div class="wrapper">
                <div id="map" class="col-12" style="height: 300px;"></div> 
              </div>
            </section>

            <section id="tab2">
              <table id="tb_workshops" class="pretty">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Housenumber</th>
                    <th>Street</th>
                    <th>Zip</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>Telephone</th>
                    <th>Email</th>
                    <th>Website</th>
                    <th>Opening Times</th>
                    <th>Rating</th>
                    <th>Coordinates</th>
                  </tr>
                </thead>   
                <tbody>
                  <?php
                    $host = "host=127.0.0.1";
                    $port = "port=5432";
                    $dbname = "dbname=group05";
                    $credentials = "user=group05 password=Ez0SfiWTYB41zL7";

                    $db = pg_connect( "$host $port $dbname $credentials" );
                    if(!$db){
                      echo "Error : Unable to open database\n";
                    }

                    $sql =<<<EOF
                      SELECT * from workshops;
EOF;

                    $ret = pg_query($db, $sql);
                    if(!$ret){
                      echo pg_last_error($db);
                      exit;
                    }
 
                    while($row = pg_fetch_row($ret)){
                      echo " <tr>\n";
                      echo " <td> ". $row[0] . "</td>\n";
                      echo " <td> ". $row[1] . "</td>\n";
                      echo " <td> ". $row[2] . "</td>\n";
                      echo " <td> ". $row[3] . "</td>\n";
                      echo " <td> ". $row[4] . "</td>\n";
                      echo " <td> ". $row[5] . "</td>\n";
                      echo " <td> ". $row[6] . "</td>\n";
                      echo " <td> ". $row[7] . "</td>\n";
                      echo " <td> ". $row[8] . "</td>\n";
                      echo " <td> ". $row[9] . "</td>\n";
                      echo " <td> ". $row[10] . "</td>\n";
                      echo " <td> ". $row[11] . "</td>\n";
                      echo " <td> ". $row[12] . "</td>\n";
                      echo " </tr>\n";
                    }
                    pg_close($db);
                  ?>                           
                </tbody>   
              </table>                    
            </section>
          </article>
        </div>

        <div class="col-4">
                  <form id="new-workshop" action="submit.php" method="post" target="_blank">
                    <fieldset>
                      <legend>Add Workshop</legend>
                   		<label for="name">Name
                        <input type="text" name="name" id="name">
                      </label>
                      <label for="lat">Latitude 
                        <input type="text" name="lat" id="lat" readonly>
                      </label>
                      <label for="lon">Longitude 
                        <input type="text" name="lon" id="lon" readonly>
                      </label>
                      <label for="acc">Accuracy 
                        <input type="text" name="acc" id="acc" readonly>
                      </label>
                      <label for="adresse">Adresse 
                        <input type="text" name="adresse" id="adresse">
                      </label>
                      <label for="tel">Telefonnummer 
                        <input type="text" name="tel" id="tel">
                      </label>
                      <label for="web">Website 
                        <input type="text" name="web" id="web">
                      </label>
                      <label for="email">eMail 
                        <input type="text" name="email" id="email">
                      </label>
                      <label for="open">Öffnungszeiten 
                      <textarea name="open" id="open" rows="3"></textarea>
                      </label>

                      <button type="submit">Submit</button> <button type="reset">Clear</button>
 
                  </fieldset>

                 </form> 
                 
             </div>
             
           </div>
           
        </section>   
        
        <footer class="row">
            <hr class="m20-bottom">
            <h6>Erstellt 2014 im Rahmen der Übungen zur Lehrveranstaltung SII- Gruppe 05</a></h6>
        </footer>

	</div>
    
  <!--======= JS =======-->
  <!-- <script type="text/javascript" src="script.js"></script> -->
  <script src="scripts/map.js"></script>
  <script src="scripts/tab.js"></script>
  <script src="scripts/table.js"></script>
</body>
</html>
