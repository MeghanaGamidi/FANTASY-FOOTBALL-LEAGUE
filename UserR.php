<?php
$connection_error = false;
$connection_error_message = " ";

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
<h1>Prediction Report</h1>

<?php if( $connection_error ) {
    output_error( "Database Connection failure!",
         $connection_error_message );
   } else {
    function output_table_open()
    {
        echo "<table>\n";
        echo "<tr>";
        echo "   <td>PredictionID</td>\n";
        echo "   <td>User</td>\n";
        echo "   <td>Help Desk</td>";
        echo "</tr>\n";
    }
}
function output_table_close() {
    echo "</table>" ;
}

function output_player_row($PredictionID, $UserID, $HelpID ) {
    echo "<tr>" ;
    echo "   <td>". $PredictionID ."</td>\n" ;
    echo "   <td>". $UserID ."</td>\n" ;
    echo "   <td>". $HelpID ."</td>" ;
    echo "</tr>\n" ;
}


function output_person_details_row( $UserID, $HelpID){
    $UserID_str = implode(  ", ", $User ) ;
       $HelpID_str = implode( ", ", $Help ) ;

       echo "<tr>\n";
       echo "    <td colspan= '3'>\n" ;
       echo "      User: " . $UserID_str . "<br/>\n" ;
       echo "      Help: " . $HelpID_str . "<br/>\n" ;
       echo "    </td>\n" ;
       echo "</tr>\n" ;

}

$query = " SELECT t0.Win, t0.LeagueType, t0.Lose, t1.UserID, t2.HelpID"
    . " FROM Person t0"
    . " LEFT OUTER JOIN User t1 ON t0.WinID = t1.WinID"
    . " LEFT OUTER JOIN Help Desk t2 ON t0.UserID = t1.UserID"
    . " ORDER BY t0.WinID, t1.UserID, t2.HelpID" ;

$result = mysqli_query( $con, $query );

if( ! $result ) {
    if( mysqli_errorno( $con ) ) {
        output_error( "Data retrieval failure!",
            mysqli_error( $con ));
  } else {
        echo "No Prediction Data Found!" ;
    } else {
        output_table_open() ;

        $LeagueType = null ;
        $UserID = array() ;
        $HelpID = array() ;
        while($row = $result->fetch_array() ) {
            if( $LeagueType != $row[ "League"] ){
                if( LeagueType != null ) {
                    output_prediction_details_row( $UserID, $HelpID ) ;

                    $UserID = array() ;
                    $HelpID = array() ;
                }
                output_person_row( $row[ "PredictionID"], $row[ "UserID"], $row[ "HelpID"]);
            }
            $UserID[] = $row[ "User" ] ;
            $HelpID[] = $row[ "HelpDesk" ] ;
            $LeagueType = $row[ "League" ] ;
        }

        output_table_close() ;
    }
}
?>
</body>
</html>
