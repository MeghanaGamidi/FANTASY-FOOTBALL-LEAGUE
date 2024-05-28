<?php
$connection_error = "false";
$connection_error_message = "";

$con=mysqli_connect("localhost","s_mgamidi","u5gLaEVD","s_mgamidi");


if( mysqli_connect_error() ) {
    $connection_error = true ;
    $connection_error_message = "Failed to connect to MySQL"
        . mysqli_connect_error() ;
}


function output_error( $title, $error) {
    echo "<span style='color: red;'>\n" ;
    echo "<h2>" . $title . "</h2>\n" ;
    echo "<h4>" . $error . "</h4>\n" ;
    echo "</span>\n" ;
}
?>

<html>
<head>
    <title>League Data Report</title>
</head>
<body>
<h1>League Data Report</h1>

<?php if( $connection_error ) {
    output_error( "Database Connection failure!",
         $connection_error_message );
   } else {
    function output_table_open()
    {
        echo "<table>\n";
        echo "<tr>";
        echo "   <td>TeamID</td>\n";
        echo "   <td>League Type</td>\n";
        echo "   <td>Country</td>";
        echo "</tr>\n";
    }
}

function output_table_close() {
    echo "</table>" ;
}

function output_player_row($TeamID, $CatID, $PriceID ) {
    echo "<tr>" ;
    echo "   <td>". $TeamID ."</td>\n" ;
    echo "   <td>". $CatID ."</td>\n" ;
    echo "   <td>". $PriceID ."</td>" ;
    echo "</tr>\n" ;
}


function output_person_details_row( $CatID, $PriceID){
    $CatID_str = implode(  ", ", $Category ) ;
       $PriceID_str = implode(  ", ", $Price ) ;

       echo "<tr>\n";
       echo "    <td colspan= '3'>\n" ;
       echo "      Category: " . $CatID_str . "<br/>\n" ;
       echo "      Price: " . $PriceID_str . "<br/>\n" ;
       echo "    </td>\n" ;
       echo "</tr>\n" ;

}

$query = " SELECT t0.TeamID, t0.LeagueType, t0.Country, t1.CatID, t2.PriceID"
    . " FROM Person t0"
    . " LEFT OUTER JOIN Category t1 ON t0.TeamID = t1.TeamID"
    . " LEFT OUTER JOIN Price t2 ON t0.CatID = t1.CatID"
    . " ORDER BY t0.TeamID, t1.CatID, t2.PriceID" ;

$result = mysqli_query( $con, $query );

if( ! $result ) {
    if( mysqli_errorno( $con ) ) {
        output_error(  "Data retrieval failure!",
            mysqli_error( $con ));
  } else {
        echo "No League Data Found!" ;
    } else {
        output_table_open() ;

        $LeagueType = null ;
        $CatID = array() ;
        $PriceID = array() ;
        while($row = $result->fetch_array() ) {
            if( $LeagueType != $row[ "League"] ){
                if( LeagueType != null ) {
                    output_player_details_row( $CatID, $PriceID ) ;

                    $CatID = array() ;
                    $PriceID = array() ;
                }
                output_person_row( $row[ "TeamID"], $row[ "CatID"], $row[ "PriceID"]);
            }
            $CatID[] = $row[ "Category" ] ;
            $PriceID[] = $row[ "Price" ] ;
            $LeagueType = $row[ "League" ] ;
        }

        output_table_close() ;
    }
}
?>
</body>
</html>
