<?php
require_once('Timer.Class.php') ;

$timer = new Timer(1);

$handle = @fopen("/path/to/settings.php", "r");
$db=$user=$pass=$host="";

if ($handle)
{
  while (!feof($handle))
  {
    $buffer = fgets($handle);
    if(strpos($buffer,"'database' =>") !== FALSE)
    {
      preg_match('/\=\> \'(.*)\',/',$buffer,$dbm);
      $db = $dbm[1];
    } else if(strpos($buffer,"'username' =>") !== FALSE) {
      preg_match('/\=\> \'(.*)\',/',$buffer,$um);
      $user = $um[1];
    } else if(strpos($buffer,"'password' =>") !== FALSE) {
      preg_match('/\=\> \'(.*)\',/',$buffer,$pm);
      $pass = $pm[1];
    } else if(strpos($buffer,"'host' =>") !== FALSE) {
      preg_match('/\=\> \'(.*)\',/',$buffer,$hm);
      $host = $hm[1];
    }
  }
  fclose($handle);
}

if($db == NULL || $user == NULL || $pass == NULL || $host == NULL) 
{
  echo "Something went wrong trying to grab the connection data\n";
  echo "db=$db; user=$user; pass=$pass; host=$host";
  exit();
}

$db = new mysqli($host,$user,$pass,$db);

if($db->connect_errno > 0) {
  die("Unable to connect to the database [ " . $db->connect_error . " ]");
}

echo "\n ";
echo  "DB connection established at \t\t" . $timer->get() . " secs\n " ;

$db->query("DROP TABLE IF EXISTS TEST_TBL");

$tableCreate = "CREATE TABLE TEST_TBL ( id int(11) NOT NULL auto_increment , txt TEXT , PRIMARY KEY (id))";

if($db->query($tableCreate) === FALSE ){
  die("Error creating the table [ " . $db->error . " ]");

} else {

  echo  "Table created at \t\t\t" . $timer->get() . " secs\n " ;

  echo "Generating random data\t\t\t"; 

  for ($i = 1; $i <= 1000; $i++) 
  {
    if($i % 66 == 0) echo ".";
    if($i == 1000) echo "\n ";

    if(!$insert = $db->query("INSERT INTO TEST_TBL (txt) VALUES ('" . $timer->randString(25) ."')")) {
      die("Error inserting data [ " . $db->error . " ]\n ");
    }

    $insertText = NULL;
  }
  echo  "Data inserted into the table at \t" . $timer->get() . " secs\n " ;

} 


if($result = $db->query("SELECT txt FROM TEST_TBL")) {

  echo "Data queried at \t\t\t" . $timer->get() . " secs\n ";

  $arrayResults = array() ;

  while ($row = $result->fetch_assoc()) 
  {
    array_push($arrayResults , $row['txt']);     
  }

  echo "Data inserted into an array at \t" . $timer->get() . " secs\n ";

  $result->close();
} else {
  echo "Something went wrong with the select..";

}

$db->query("DROP TABLE IF EXISTS TEST_TBL");
$db->close();
