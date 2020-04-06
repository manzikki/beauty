# beauty
Demo application for beauty parlors, Webster University Thailand,
DBApps 2020.

"connect.php" is not in this repository, but it should look like this (inside php tags):



       $dbhost = 'localhost';
       $dbuser = 'myuser';
       $dbpass = 'mypass';
       $dbname = 'mydb';
       $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
       if ($conn->connect_error) {
           echo "Connection failed<br/>";
           die("Connection failed: " . $conn->connect_error);
       }



