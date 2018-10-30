<?php

//insert.php

include('database_connection.php');

date_default_timezone_set("Europe/Athens");

$form_data = json_decode(file_get_contents("php://input"));


$checkArray =[]; 
$error = '';
$message = '';
$validation_error = '';
$datetime = '';
$name = '';
$grams = '';
$protein = '';
$fats = '';
$saturated = '';
$unsaturated = '';
$carbohydrates = '';
$calories = '';



if($form_data->action == 'fetch_single_data')
{	
	$query = "SELECT * FROM dietentries WHERE currententryid='".$form_data->currententryid."'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output['datetime'] = $row['datetime'];
		$output['name'] = $row['name'];
		$output['grams'] = $row['grams'];
		$output['protein'] = $row['protein'];
		$output['fats'] = $row['fats'];
		$output['saturated'] = $row['saturated'];
		$output['unsaturated'] = $row['unsaturated'];
		$output['carbohydrates'] = $row['carbohydrates'];
		$output['calories'] = $row['calories'];
	}
}
elseif($form_data->action == "Delete")
{
	$query = "
	DELETE FROM dietentries WHERE currententryid='".$form_data->currententryid."'
	";
	$statement = $connect->prepare($query);
	if($statement->execute())
	{
		$output['message'] = 'Food Deleted';
	}
}
//edit or insert
else{



	if(empty($form_data->name))
	{
		$checkArray[] = 'Name';
	}
	else
	{
		$name = $form_data->name;
	}

	if(empty($form_data->grams))
	{
		$checkArray[] = 'Grams';
	}
	else
	{
		$datetime =$form_data->dateIE;		//date("Y-m-d H:i:s");//"2018-05-23 21:57:46";
		$grams = $form_data->grams;

		// Fetch data based on the name and then multiply by grams to get result

		$query = "SELECT protein,fats,saturated,unsaturated,carbohydrates,calories FROM foods WHERE name='".$form_data->name."'";

		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row)
		{

			$protein = $row['protein']*$grams;
			$fats = $row['fats']*$grams;
			$saturated = $row['saturated']*$grams;
			$unsaturated = $row['unsaturated']*$grams;
			$carbohydrates = $row['carbohydrates']*$grams;
			$calories = $row['calories']*$grams;
		}
	}

	if(empty($checkArray))
	{
	
		if($form_data->action == 'Insert')
		{
	
			$data = array(
				':datetime'			=>	$datetime,
				':name'				=>	$name,
				':grams'			=>	$grams,
				':protein'			=>	$protein,
				':fats'				=>	$fats,
				':saturated'		=>	$saturated,
				':unsaturated'		=>	$unsaturated,
				':carbohydrates'	=>	$carbohydrates,
				':calories'			=>	$calories

			);
			$query = "
			INSERT INTO dietentries 
				(datetime, name, grams, protein, fats, saturated, unsaturated, carbohydrates,calories) VALUES 
				(:datetime, :name, :grams, :protein, :fats, :saturated, :unsaturated, :carbohydrates,:calories)
			";
			$statement = $connect->prepare($query);
			if($statement->execute($data))
			{
				$message = 'Food Inserted';
			}
		}
		if($form_data->action == 'Edit')
		{
			$data = array(
				':datetime'			=>	$datetime,
				':name'				=>	$name,
				':grams'			=>	$grams,
				':protein'			=>	$protein,
				':fats'				=>	$fats,
				':saturated'		=>	$saturated,
				':unsaturated'		=>	$unsaturated,
				':carbohydrates'	=>	$carbohydrates,
				':calories'			=>	$calories,
				':currententryid'	=>	$form_data->currententryid
			);
			$query = "
			UPDATE dietentries 
			SET datetime = :datetime, name = :name, grams = :grams, protein = :protein, fats = :fats, saturated = :saturated, unsaturated = :unsaturated, carbohydrates = :carbohydrates,  calories = :calories
			WHERE currententryid = :currententryid
			";

			$statement = $connect->prepare($query);
			if($statement->execute($data))
			{
				$message = 'Food Edited';
			}
		}
	} 
	
	else
	{
		$validation_error =  implode(", ", $checkArray)." fields are reguired";
	}

	$output = array(
		'error'		=>	$validation_error,
		'message'	=>	$message
	);

}

echo json_encode($output);

?>