        <!DOCTYPE html>
        <html>

        <head>
          <style>
          <link rel="stylesheet" type="text/css" href="styles.css">
        </style>

        <script src="placeMethods.js"></script>

      </head>
      <body onload="myLocation()">


        <form id="myForm" action="" method="post">
          <fieldset style="padding:10px;padding-bottom:20px">
            <h1 style="font-style:italic; text-align:center; margin-top:0px; margin-bottom:0px;"> Travel and Entertainment Search </h1>
            <hr> 
            <label  style="font-weight:bold">Keyword</label> <input type="text" name="keyword" id="keyword" required style="margin-bottom:10px"><br>
            <label  style="font-weight:bold">Category</label> <select name="category" id="category" style="margin-bottom:10px">
              <option value="default">default</option>   
              <option value="cafe">Cafe</option>
              <option value="bakery">Bakery</option>
              <option value="restaurant">Restaurant</option>
              <option value="beauty salon">Beauty Salon</option>
              <option value="casino">Casino</option>
              <option value="movie theater">Movie Theater</option>
              <option value="lodging">Lodging</option>
              <option value="airport">Airport</option>
              <option value="train station">Train Station</option>
              <option value="subway station">Subway Station</option>
              <option value="bus station">Bus Station</option>
            </select><br>
            <label style="font-weight:bold">Distance (miles)</label>  <input type="text" id="distance" name="distance" placeholder="10"> <label style="font-weight:bold">   from</label> 
            <input type="radio" name="currentloc" id="here" value="here" onclick="checkRadio()" checked> Here<br>
            <input type="radio" name="currentloc" id="currentloc" onclick="checkRadio()" value="other" style="margin-left:288px;">  Other <input type="text" name="location" placeholder="location" id="otherloc"  required disabled> </input> </input>
            <input type="hidden" name="geoloc" id="geoloc" value="">
            <br>
            <input type="submit" value="Search" name="submit" id="Search" disabled style="margin-left:60px;"> <input type="button" value="Clear" onclick="resetForm()">

          </fieldset>
        </form>

        <p id="loc">

          <script src="formMethods.js"> </script>

          <?php

          $latitude= $longitude = NULL;


          if(isset($_POST['submit'])){




            if (isset($_POST['keyword']) && isset($_POST['currentloc']))
            {
              if($_POST['currentloc'] == "other") {
                $keyword = $_POST['keyword'];
                $category = $_POST['category'];
                $distance = $_POST['distance'];
                $location = $_POST['location'];
                $geourl = urlencode($location);

                $getUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $geourl . "&key=AIzaSyACm9DTlAS-_minsGnn114414d_-Uv4eoc";
                $content = file_get_contents($getUrl);
                $jsonresult = json_decode($content,true);
                if(!empty($jsonresult['results'])){

                  $latitude = $jsonresult['results'][0]['geometry']['location']['lat'];
                  $longitude = $jsonresult['results'][0]['geometry']['location']['lng'];

                }


              }

              else if($_POST['currentloc'] == "here"){
                $keyword = $_POST['keyword'];
                $category = $_POST['category'];
                $distance = $_POST['distance'];
                $json = $_POST['geoloc'];

                $obj = json_decode($json, true);

                $latitude = $obj['lat'];
                $longitude = $obj['lon'];


              }

              $placesUrl = "";

              if($latitude != null && $longitude !=null){

                $placesUrl = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=" . urlencode($latitude) . "," . urlencode($longitude) . "&radius=" .  urlencode($distance*1609) . "&type=" . urlencode($category) . "&keyword=" . urlencode($keyword) . "&key=AIzaSyDva_yEzSEvRZ4f76u85DfFSaEazuLs89s" ;

                $contentPlaces = file_get_contents($placesUrl);

                if(empty($contentPlaces)){

                  echo 'No records have been found';

                }

                else {

                  $jsonresultPlaces = json_decode($contentPlaces,true);
                  $columns = array("Category", "Name", "Address");
                  echo "<table border=2><thead><tr>";
                  foreach($columns as $column){
                    echo('<th>' . $column . '</th>');
                  }

                  echo "</tr></thead>";
                  $m=1;
                  foreach ($jsonresultPlaces['results'] as $place) {
                    echo "<tbody><tr><td><img src='" . $place['icon'] . "'></td>";
                    $placeId = (string)$place["place_id"];
                    
                    echo "<td><a href='place.php?parameter1=$placeId'  style= 'text-decoration:none; color:black;'>".$place["name"]."</a></td>";
                    
                    $cellId = (string)"maps".$m;
                    echo '<td id="maps'.$m.'"><a style="cursor:pointer;" onclick="mapOn('.$place['geometry']['location']['lat'].','.$place['geometry']['location']['lng'].',\''.$cellId.'\');" style="text-decoration:none; color:black;">' . $place['vicinity'] . '</a></td></tr>';
                    $m = $m + 1;
                  }


                  echo "</tbody> </table>" ;

                }

              }

            }

          }


          if(isset($_GET['parameter1'])){
            $placeID = $_GET["parameter1"];

            $detailUrl = urlencode($placeID);

            $newUrl = "https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $detailUrl . "&key=AIzaSyBjgpo-qYI5xySvIsiLC18hSRm4wWZFF7o";

            $details = file_get_contents($newUrl);
            $detailresult = json_decode($details,true);
                                   //   $photoDetail = array();

            $x=0;
            $min_size = sizeof($detailresult['result']['photos']) < 5 ? sizeof($detailresult['result']['photos']) : 5;

            while($x < $min_size)
            {

              $photoRef = urlencode($detailresult['result']['photos'][$x]['photo_reference']);
              $maxWidth = urlencode($detailresult['result']['photos'][$x]['width']);
              $photoUrl = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=" . $maxWidth . "&photoreference=" . $photoRef . "&key=AIzaSyD6GiD0GAZPMFJAhzZ2sDZiZkwMIMp9TB0";
              $photoDetail = file_get_contents($photoUrl);
              $photo = $photoDetail."png";

              file_put_contents("picture".$x, $photoDetail);

              $x = $x + 1;


            }


            echo "<h3 style='text-align:center'>" . $detailresult['result']['name'] . "</h3>";
            echo "<br><br>";
            echo "<p id='showReview' style='text-align:center'>click to show reviews</p>";
            echo "<br><input type='button' id='reviewButton' onclick='reviewOn()' value='show' style='margin: auto;'><br>";
            echo "<div id='reviewtable' style='display:none;'>" ;  

            $y=1;
            $min_size = count($detailresult['result']['reviews']) < 5 ? count($detailresult['result']['reviews']) : 5;
            while( $y < $min_size)
            {


              echo "<table border=2><tbody><tr><td> <img src='" . $detailresult['result']['reviews'][$y]['profile_photo_url'] . "'><h5>". $detailresult['result']['reviews'][$y]['author_name']. "</h5></td></tr>";
              echo "<tr><td>". $detailresult['result']['reviews'][$y]['text'] . "</td></tr></tbody></table>"; 


              $y = $y + 1;

            }



            echo "</div>";
            echo "<p id='showPhoto' style='text-align:center'>click to show photos</p>";
            echo "<br><input type='button' id='photoButton' onclick='picOn()' value='show' style='margin: auto;'>";
            echo "<div id='phototable' style='display:none;'>" ;  



            $z=1;
            while($z <= 5 && $z <= sizeof($detailresult['result']['photos'])){

             echo "<table border=2><tbody><tr><td><a href='/picture".$z."' target=_blank> <img src='/picture".$z."'></a></td></tr></tbody></table>";


             $z = $z + 1;


           }

           echo "</div>";

         }

         ?>
         <div id="content">
          <a id="walk">Walk There</a>
          <a id="bike">Bike There</a>
          <a id="drive">Drive There</a>
        </div>

        <div id='mapDiv' style='z-index:100; width:400px;height:400px;display:none;position:absolute;'>

        </div>

        <script src="mapMethods.js"></script>

        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfZgp_gYbp--m-rN-bQ5bb9XdY7i3Rbmw&callback=initMap">
      </script>


    </body>
    </html>

