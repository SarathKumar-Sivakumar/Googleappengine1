<?php

   ob_start();
   error_reporting(0);
   session_start();
   echo "Welcome  ".$_SESSION['sess_user'];
   $user = $_SESSION['sess_user'];
   

   require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
   use google\appengine\api\cloud_storage\CloudStorageTools;

   $options = [ 'gs_bucket_name' => 'sarathwebstorage' ];
   $upload_url = CloudStorageTools::createUploadUrl('/upload', $options);
  

ob_start();

// Connect to server and select databse.

$conn = mysql_connect(':/cloudsql/cloudwebstorage:sarathweb',
  'root', // username
  'root'      // password
  )or die("cannot connect");
  mysql_select_db("guestbook")or die("cannot select DB");

?>

<!DOCTYPE html>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="/inc/topcoat-0.8.0/css/topcoat-mobile-dark.css">
<link rel="stylesheet" type="text/css" href="/inc/css/main.css">
</head>
<body>
  <a href="logout.php">Logout</a></h2>
<div class="contentArea">
  
<?php

if(isset($_POST['do-upload']) AND $_POST['do-upload'] === "yes"){

   
   $yesupload = $_POST['do-upload'];
   preg_match("/yes/", "".$yesupload."");

   $filename = $_FILES['testupload']['name'];
   $ext = pathinfo($filename, PATHINFO_EXTENSION);
     
    $first = microtime(true);
  
    $file = $_FILES['testupload']['tmp_name'];
    $handle = fopen($file,"r");

    $conn1 = mysql_connect(':/cloudsql/cloudwebstorage:sarathweb',
  'root', // username
  'root'      // password
  )or die("cannot connect");
  mysql_select_db("guestbook")or die("cannot select DB");
   
    //loop through the csv file and insert into database
    while ($data = fgetcsv($handle,1000,",","'")) {
        
            mysql_query("INSERT INTO new (first, last, email) VALUES
                (
                    '".addslashes($data[0])."',
                    '".addslashes($data[1])."',
                    '".addslashes($data[2])."'
                )
            ");
        
    }

$timetaken = microtime(true) - $first;
$timetaken = $timetaken/60;
$_SESSION['csvtime'] = $timetaken;

   $firstfiletime = microtime(true);
   $gs_name = $_FILES['testupload']['tmp_name'];
   move_uploaded_file($gs_name, 'gs://sarathwebstorage/'.$filename.'');
   $filetimetaken = microtime(true) - $firstfiletime;
   $filetimetaken = $filetimetaken/60;
   $_SESSION['filetime'] = $filetimetaken;

$file_size = $_FILES['testupload']['size'];

$query_start = microtime(true);
mysql_query("SELECT * from new");
$querytime = microtime(true) - $query_start;

 mysql_query("INSERT INTO filedetails (filename, filesize, uploadtime, querytime, username) VALUES
                (
                    '".$filename."',
                    '".$file_size."',
                    '".$timetaken."',
                    '".$querytime."',
                    '".$user."'
                )
            ");
?>

<?php
   echo "<p>File Uploaded</p>";
   echo "<p>Name of the file you uploaded: ".$filename."</p>";
   echo "<h4>".$filename. " - Time taken to extract csv contents to database : ".$_SESSION['csvtime']." Minutes</h4>";
   echo "<h4>".$filename. " file uploaded to Google Bucket in : ".$_SESSION['filetime']." Minutes</h4>";
   
   }
 ?>

 <form class="SomeSpaceDude" action="<?php echo $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data" method="post">
   <p>File to upload: </p>
   <input type="hidden" name="do-upload" value="yes">
   <input class="topcoat-button" type="file" name="testupload" id="csv">
   <input class="topcoat-button" type="submit" value="Upload">
</form>
<button onclick="location.href='results.php'">
    Results Table</button>   
</div>
</body>
</html>