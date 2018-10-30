<?php

//fetch_data.php

include('database_connection.php');

$form_data_fetch = json_decode(file_get_contents("php://input"));

	// Fetching data from foods table
if($form_data_fetch->action == 'fetch_data_foods'){	
	$query = "SELECT * FROM foods ORDER BY name";
	}

	// Fetching data from dietentries table
elseif($form_data_fetch->action == 'fetch_data_dietentries'){

	$date = $form_data_fetch->fetchdate;

	$query = "SELECT * FROM dietentries WHERE DATE(datetime)='".$date."' ORDER BY currententryid DESC";
}

$statement = $connect->prepare($query);
if($statement->execute())
{
	while($row = $statement->fetch(PDO::FETCH_ASSOC))
	{
		$data[] = $row;
	}

	echo json_encode($data);
}

?>