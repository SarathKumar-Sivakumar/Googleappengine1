<?php
   
   session_start();
   error_reporting(0);

    ob_start();

   require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
   use google\appengine\api\cloud_storage\CloudStorageTools;
   use \google\appengine\api\mail\Message;

   $user = $_SESSION['sess_user'];

   $results = CloudStorageTools::createUploadUrl('/results', $options);

// Connect to server and select databse.

$conn = mysql_connect(':/cloudsql/cloudwebstorage:sarathweb',
  'root', // username
  'root'      // password
  )or die("cannot connect");
  mysql_select_db("guestbook")or die("cannot select DB");

$sql = "SELECT * FROM filedetails";


echo "<table border='1'>";
echo "<tr> <th>FileName</th> <th>File Size</th> <th> Upload Time </th> <th> Query Time </th> <th> UserName </th> </tr>";
$contents = "";
$result = mysql_query($sql);
while($row = mysql_fetch_array( $result )) {
       
       echo "<tr><td>"; 
       echo $row['filename'];
       echo "</td><td>"; 
       echo $row['filesize'];
       echo "</td><td>";
       echo $row['uploadtime'];
       echo "</td><td>";
       echo $row['querytime'];
       echo "</td><td>";
       echo $row['username'];
       echo "</td></tr>";

    

      $contents = $content."Filename: " . $row['filename']. " - File Size: " . $row['filesize']. "Kb - Upload Time " . $row['uploadtime']. "Query Time" . $row['querytime'] . "User Name: ". $row['username']. "- - - - - - - - - - - -" ;	
      

    }

    $sqlmail = "SELECT email from login where username='".$user."'";
    $resultmail = mysql_query($sqlmail);
    $rowmail = mysql_fetch_array( $resultmail );
    $email = $rowmail['email'];


  $message = new Message();
  $message->setSender("sarath5791@gmail.com");
  $message->addTo($email);
  $message->setSubject("Results Table");
  $message->setTextBody($contents);
  $message->send();

echo "</table>";

?>
<html>
<body>
<button onclick="location.href='upload.php'">
     Back</button>

   </body>
   </html>