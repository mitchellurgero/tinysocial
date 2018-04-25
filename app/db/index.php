<?php
//die("Test disabled");
include('db.php');
$data = new JSONDatabase('db1');
file_put_contents("db1.json", $data->dump_tables());
//Generate content NOW - 1000 rows.
/*
for($i = 0; $i <= 10000; $i++){
	$t = mt_rand(1, 10);
	$id = mt_rand(0, 1000);
	if(!$data->check_table("table$t")){
		if($data->create_table("table$t")){
			$data->insert("table$t", '{"id":'.$id.',"data":"'.generateRandomString(100).'"}');
		}
	} else {
		$data->insert("table$t", '{"id":'.$id.',"data":"'.generateRandomString(100).'"}');
	}
}
*/
echo '
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
';
if(isset($_GET['table']) && isset($_GET['db'])){
	$data = new JSONDatabase($_GET['db']);
	echo '<a href="./">Go back</a>';
	echo '<h2>'.$_GET['table'].'</h2>';
	echo 'Search:<br>
		<form action="index.php" method="GET">
			<input type="hidden" name="db" value="'.$_GET['db'].'">
			<input type="hidden" name="table" value="'.$_GET['table'].'">
			Where: <input type="text" name="search"><br/>
			Equals: <input type="text" name="query"><br/>
			<button type="submit">Search</button>
		</form>
		';
	echo '<table>';
	echo "<thead><th>row_id</th><th>data</th><th>id</th></thead>";
	$rows = $data->select($_GET['table'], $_GET['search'], $_GET['query']);
	$i = 0;
	foreach($rows as $row){
		echo '<tr><td>'.$row['row_id'].'</td><td>'.$row['data'].'</td><td>'.$row['id'].'</td></tr>';
		$i++;
	}
	echo '</table>';
	
	
} else if(isset($_GET['db']) && !isset($_GET['table'])) {
	if($_GET['db'] != "db1" || $_GET['db2']){
		die("NO DB");
	}
	$data = new JSONDatabase($_GET['db']);
	echo "<h2>Select a table to dump:</h2>";
	echo '<table>';
	$tables = $data->list_tables();
	foreach($tables as $table){
		echo '<tr><td><a href="?db='.$_GET['db'].'&table='.$table.'">'.$table.'</a></td></tr>';
	}
	echo '</table>';
	echo '<pre>';
	// /echo $data->dump_tables();
	echo '</pre>';
	
} else {
	echo "<h2>Select a Database:</h2><br/>";
	echo '<a href="?db=db1">db1</a><br/>';
	echo '<a href="?db=db2">db2(This one was imported)</a><br/>';
	echo '<a href="?db=db3">db3</a>';
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
