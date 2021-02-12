<!-- Beauty parlor demo application, Marko Niinimaki niinimakim@webster.ac.th, DBApps 2020          -->
<!-- This file represents the entire application as functions. The default page shows the bookings, -->
<!-- others: artists, customers. -->
<html>
<head>
        <title>Beauty Parlor</title>
	<style>
		table,td,tr { border:1px solid #021ff9;
			background-color:#9ec2d3;
			padding:4px;
			}
		td { color:#002156;}
		body { background-color:#cee4ef;}
	</style>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
      <a class="navbar-brand">Beauty Parlor</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="index.php">Bookings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=artists">Artists</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=customers">Customers</a>
          </li>
        </ul>
       <ul class="nav justify-content-end">
       <li>
       <a class="nav-link" href="https://docs.google.com/your-online-docs">Help</a>
       </li>
       </ul>
      </div>
    </nav>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


function bookings_page() {
    require('connect.php');
    $result = $conn->query("select ArtistName, CustomerName, Date, Timeofday from Artist, Booking, Customer where Booking.ArtistID = Artist.ArtistID and Booking.CustomerID = Customer.CustomerID");
    //get customer list by SQL here
    //make table prettier by CSS
    echo "<h3>Bookings</h3> <div class='container'> <table class='table table-bordered'>";
    echo "<tr><th>Customer</th> <th>Artist</th> <th>Date</th> <th>Time</th></tr>";
    while ($row = $result->fetch_assoc()) {
       echo "<tr>";
       echo "<td>".$row["CustomerName"]."</td>";
       echo "<td>".$row["ArtistName"]."</td>";
       echo "<td>".$row["Date"]."</td>";
       echo "<td>".$row["Timeofday"]."</td>";
       echo "</tr>";
    }
    echo '</table></div>';

    //get information about artists and customers to populate drop-downs

    $cust_sel = "<select name='custselect'>";
    $result = $conn->query("select * from Customer");
    while ($row = $result->fetch_assoc()) {
        $cust_sel = $cust_sel."<option value=".$row["CustomerID"].">".$row["CustomerName"]."</option>";
    }
    $cust_sel = $cust_sel."</select>";

    $artist_sel = "<select name='artistselect'>";
    $result = $conn->query("select * from Artist");
    while ($row = $result->fetch_assoc()) {
        $artist_sel = $artist_sel."<option value=".$row["ArtistID"].">".$row["ArtistName"]." : ".$row["SpecialityName"]."</option>";
    }
    $artist_sel = $artist_sel."</select>";

    $conn->close();

    $today = date("Y-m-j");

    echo "<h3>Add new</h3>";
    echo
     "<form action='index.php'  method='post'>
        <input type='hidden' name='page' value='addbooking'/>
        <div class='container'> <table class='table table-bordered'>
        <tr><th>Customer</th> <th>Artist</th> <th>Date</th> <th>Time</th></tr>
        <tr><td>".$cust_sel."</td><td>".$artist_sel."</td><td><input name='bookingdate' type='date' value=".$today."></input></td>
            <td><input type='number' name='hour' min='9' max='18' value='9'></input></td></tr>
   	  <td colspan='4'><button type='submit'> Add new </button></td>
            </tr>
            </table></div>
   </form>";
}

//Demonstration of how to use objects
class Artist {
    public $a_id;
    public $name;
    public $speciality;
    public $rate;
    function __construct($a_id, $name, $spec, $rate) {
        $this->a_id = $a_id;
        $this->name = $name;
        $this->speciality = $spec;
        $this->rate = $rate;
    }
    function store($connection) {
        //stores the current artist in the database using the connection.
        //If the artist id is negative, gets an unused artist id from the database.
        //If it is nonnegative, replaces the existing artist in the database.
        $myid = $this->a_id;
        if ($myid < 0) {
           $result = $connection->query("select max(ArtistID) from Artist");
           while ($row = $result->fetch_array()) { $myid = $row[0] + 1; }                    
           //die("XXX".$myid);
           $stmt = $connection->prepare("insert into Artist values(?,?,?,?)");
           $bind = $stmt->bind_param("issi", $myid, $this->name, $this->speciality, $this->rate);
           if (!$bind) { die($stmt->error); }
           if (!$stmt->execute()) { die($stmt->error); }
        } else { //untested
           $stmt = $connection->prepare("update Artist set name=?, speciality=?, rate=? where ArtistID=?");
           $bind = $stmt->bind_param("ssii", $this->name, $this->speciality, $this->rate, $myid);
           if (!$bind) { die($stmt->error); }
           if (!$stmt->execute()) { die($stmt->error); }
        }
        $connection->close();
    }
}

function artists_page()
{
    require('connect.php');
    $result = $conn->query("select ArtistName, SpecialityName, HourlyRate from Artist");
    echo "<h3>Artists</h3> <div class='container'> <table class='table table-bordered'>";
    echo "<tr><th>Artist</th> <th>Speciality</th> <th>Rate</th> </tr>";
    while ($row = $result->fetch_assoc()) {
       echo "<tr>";
       echo "<td>".$row["ArtistName"]."</td>";
       echo "<td>".$row["SpecialityName"]."</td>";
       echo "<td>".$row["HourlyRate"]."</td>";
       echo "</tr>";
    }
    echo '</table></div>';
    $conn->close();

    echo "<h3>Add new</h3>";
    echo
     "<form action='index.php'  method='post'>
          <input type='hidden' name='page' value='addartist'/>
          <div class='container'> <table class='table table-bordered'>
          <tr>
      <td>Name: </td><td> <input type='text' name='ArtistName' required='true' maxlength='30'></td>
          </tr>

          <tr>
      <td>Speciality: </td><td> <input type='text' name='SpecialityName' required='true'></td>
          </tr>

          <tr>
      <td>Rate: </td><td> <input type='number' name='HourlyRate' required='true'></td>
          </tr>

    <td colspan='2'><button type='submit'> Add new </button></td>
          </tr>
          </table></div>
    </form>";
}

function add_artist()
{
    require('connect.php');
    //get the submitted input
    $name = $_POST["ArtistName"];
    $spec = $_POST["SpecialityName"];
    $rate = $_POST["HourlyRate"];
    //we cannot trust the input. check.
    if ( ($name == "") or ($spec == "") or (!is_numeric($rate)) ) { die("Invalid input"); }
    $artist = new Artist(-1, $name, $spec, $rate);
    $artist->store($conn);
    artists_page(); //go to artists page
}

function add_booking()
{
    echo "Sorry, this functionality not yet implemented, but the POST values are listed below.<br/>";
    var_dump($_POST);
}

function customers_page()
{
    //list existing
    require('connect.php');

    //make table prettier by CSS
    echo "<h3>Customers</h3> <div class='container'> <table class='table table-bordered'>";
    echo "<tr><th>Cust. no</th> <th>Name</th> </tr>";

    echo '</table></div>';
    $conn->close();

    echo "<h3>Add new</h3>";
}


// main

$page = "";
if (isset($_GET['page'])) { $page = $_GET['page']; }
if (isset($_POST['page'])) { $page = $_POST['page']; }

if ($page == "") { bookings_page(); }
//if ($page == "addcustomer") { addcustomer(); }

if ($page == "artists") { artists_page(); }
if ($page == "customers") { customers_page(); }
if ($page == "addartist") { add_artist(); }
if ($page == "addbooking") { add_booking(); }

?>

</html>

