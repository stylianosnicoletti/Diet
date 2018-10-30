<?php


include('database_connection.php');

date_default_timezone_set("Europe/Athens");

$form_data = json_decode(file_get_contents("php://input"));


if($form_data->action == 'fetch_daily_stats')
{	
	$query = "SELECT SUM(protein)*4 AS tprotein, SUM(fats)*9 AS tfats, SUM(saturated)*9 AS tsaturated, SUM(unsaturated)*9 AS tunsaturated, SUM(carbohydrates)*4 AS tcarbohydrates, SUM(calories) AS tcalories FROM dietentries WHERE datetime='".$form_data->date."'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output['tprotein'] = $row['tprotein'];
		$output['tfats'] = $row['tfats'];
		$output['tsaturated'] = $row['tsaturated'];
		$output['tunsaturated'] = $row['tunsaturated'];
		$output['tcarbohydrates'] = $row['tcarbohydrates'];
		$output['tcalories'] = $row['tcalories'];
	}
}


elseif($form_data->action == 'fetch_overall_stats')
{	
	$query = "SELECT SUM(protein)*4 AS tprotein, SUM(fats)*9 AS tfats, SUM(saturated)*9 AS tsaturated, SUM(unsaturated)*9 AS tunsaturated, SUM(carbohydrates)*4 AS tcarbohydrates, SUM(calories) AS tcalories FROM dietentries";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output['tprotein'] = $row['tprotein'];
		$output['tfats'] = $row['tfats'];
		$output['tsaturated'] = $row['tsaturated'];
		$output['tunsaturated'] = $row['tunsaturated'];
		$output['tcarbohydrates'] = $row['tcarbohydrates'];
		$output['tcalories'] = $row['tcalories'];
	}
}


echo json_encode($output);

?>