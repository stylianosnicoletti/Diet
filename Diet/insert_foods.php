<?php

//insert.php

include('database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));


$checkArray =[]; 
$error = '';
$message = '';
$validation_error = '';
$name = '';
$protein = '';
$fats = '';
$saturated = '';
$unsaturated = '';
$carbohydrates = '';
$calories = '';



if($form_data->action == 'fetch_single_data')
{	
	$query = "SELECT * FROM foods WHERE foodid='".$form_data->foodid."'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output['name'] = $row['name'];
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
	DELETE FROM foods WHERE foodid='".$form_data->foodid."'
	";
	$statement = $connect->prepare($query);
	if($statement->execute())
	{
		$output['message'] = 'Food Deleted';
	}
}
else
{
	if(empty($form_data->name))
	{
		$checkArray[] = 'Name';
	}
	else
	{
		$name = $form_data->name;
	}

	if(empty($form_data->protein))
	{
		$checkArray[] = 'Protein';
	}
	else
	{
		$protein = $form_data->protein;
	}
	if(empty($form_data->fats))
	{
		$checkArray[] = 'Fats';
	}
	else
	{
		$fats = $form_data->fats;
	}
	if(empty($form_data->saturated))
	{
		$checkArray[] = 'Saturated';
	}
	else
	{
		$saturated = $form_data->saturated;
	}
	if(empty($form_data->unsaturated))
	{
		$checkArray[] = 'Unsaturated';
	}
	else
	{
		$unsaturated = $form_data->unsaturated;
	}
	if(empty($form_data->carbohydrates))
	{
		$checkArray[] = 'Carbohydrates';
	}
	else
	{
		$carbohydrates = $form_data->carbohydrates;
	}
	if(empty($form_data->calories))
	{
		$checkArray[] = 'Calories';
	}
	else
	{
		$calories = $form_data->calories;
	}

	if(empty($checkArray))
	{
		if($form_data->action == 'Insert')
		{
			$data = array(
				':name'				=>	$name,
				':protein'			=>	$protein,
				':fats'				=>	$fats,
				':saturated'		=>	$saturated,
				':unsaturated'		=>	$unsaturated,
				':carbohydrates'	=>	$carbohydrates,
				':calories'			=>	$calories

			);
			$query = "
			INSERT INTO foods 
				(name, protein, fats, saturated, unsaturated, carbohydrates,calories) VALUES 
				(:name, :protein, :fats, :saturated, :unsaturated, :carbohydrates,:calories)
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
				':name'				=>	$name,
				':protein'			=>	$protein,
				':fats'				=>	$fats,
				':saturated'		=>	$saturated,
				':unsaturated'		=>	$unsaturated,
				':carbohydrates'	=>	$carbohydrates,
				':calories'			=>	$calories,
				':foodid'				=>	$form_data->foodid
			);
			$query = "
			UPDATE foods 
			SET name = :name, protein = :protein, fats = :fats, saturated = :saturated, unsaturated = :unsaturated, carbohydrates = :carbohydrates,  calories = :calories
			WHERE foodid = :foodid
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