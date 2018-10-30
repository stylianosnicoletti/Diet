<?php


include('database_connection.php');

date_default_timezone_set("Europe/Athens");

$form_data = json_decode(file_get_contents("php://input"));

$outputcals = [];
$outputdates = [];

if($form_data->action == 'fetch_summary_calories')
{	
	$query = "SELECT SUM(calories) AS stcalories, datetime FROM dietentries GROUP BY datetime ORDER BY datetime";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$outputcals[] = $row['stcalories'];
		$outputdates[] = $row['datetime'];
	}
	$output['stcalories'] =$outputcals;
	$output['datetime'] = $outputdates;

}
elseif($form_data->action == 'fetch_average_calories')
{

	$query = "SELECT AVG(E.tcalories) AS avgcal FROM (SELECT SUM(calories) AS tcalories FROM dietentries GROUP BY datetime) AS E ";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output['avgcal'] = $row['avgcal'];

	}
}
echo json_encode($output);

?>