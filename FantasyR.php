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
    <title>Player Data Report</title>
</head>
<body>
<h1>PlayerData Report</h1>

<?php if( $connection_error ) {
    output_error( "Database Connection failure!",
         $connection_error_message );
   } else {
    function output_table_open()
    {
        echo "<table>\n";
        echo "<tr>";
        echo "   <td>Name</td>\n";
        echo "   <td>Age</td>\n";
        echo "   <td>Gender</td>";
        echo "</tr>\n";
    }
}

function output_table_close() {
    echo "</table>" ;
}

function output_player_row($PlayerID, $StatisticsID, $FantasyID ) {
    echo "<tr>" ;
    echo "   <td>". $PlayerID ."</td>\n" ;
    echo "   <td>". $StatisticsID ."</td>\n" ;
    echo "   <td>". $FantasyID ."</td>" ;
    echo "</tr>\n" ;
}


function output_person_details_row( $StatisticsID, $FantasyID){
    $StatisticsID_str = implode(  ", ", $Statistics ) ;
       $FantasyID_str = implode(  ", ", $FantasyLeague ) ;

       echo "<tr>\n";
       echo "    <td colspan= '3'>\n" ;
       echo "      Statistics: " . $StatisticsID_str . "<br/>\n" ;
       echo "      Fatnasy League: " . $FantasyID_str . "<br/>\n" ;
       echo "    </td>\n" ;
       echo "</tr>\n" ;

}

$query = " SELECT to.name, t0.age, t0.gender, t1.StatisticsID, t2.FantasyID"
    . " FROM Person t0"
    . " LEFT OUTER JOIN Statistics t1 ON t0.name = t1.name"
    . " LEFT OUTER JOIN Fantasy League t2 ON t0.name = t1.name"
    . " ORDER BY t0.name, t1.StatisticsID, t2.FantasyID" ;

$result = mysqli_query( $con, $query );

if( ! $result ) {
    if( mysqli_errorno( $con ) ) {
        output_error(  "Data retrieval failure!",
            mysqli_error( $con ));
  } else {
        echo "No Player Data Found!" ;
    } else {
        output_table_open() ;

        $last_name = null ;
        $StatisticsID = array() ;
        $FantasyID = array() ;
        while($row = $result->fetch_array() ) {
            if( $last_name != $row[ "name"] ){
                if( last_name != null ) {
                    output_player_details_row( $StatisticsID, $FantasyID ) ;

                    $StatisticsID = array() ;
                    $FantasyID = array() ;
                }
                output_person_row( $row[ "PlayerID"], $row[ "StatisticsID"], $row[ "FantasyID"]);
            }
            $StatisticsID[] = $row[ "Statistics" ] ;
            $FantasyID[] = $row[ "Fantasy League" ] ;
            $last_name = $row[ "name" ] ;
        }

        output_table_close() ;
    }
}
?>
</body>
</html>