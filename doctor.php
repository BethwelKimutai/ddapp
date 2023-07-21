<?php
session_start();

if (!isset($_SESSION["name"])) {
  header("Location: doctor_login.php");
  exit();
}

$hostname = "localhost";
$username = "root";
$password = ""; 
$database = "ddapp"; 

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
    $name = $_SESSION["name"];
    $file_name = $_FILES["profile_picture"]["name"];
    $file_tmp = $_FILES["profile_picture"]["tmp_name"];
    $file_size = $_FILES["profile_picture"]["size"];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if ($file_size > 5242880) { 
      $error_message = "File size exceeds the allowed limit (5MB).";
    } else {
      $new_file_name = uniqid("profile_", true) . "." . $file_ext;
      $upload_path = "profile_pictures/" . $new_file_name;
      
      if (move_uploaded_file($file_tmp, $upload_path)) {
        $query = "INSERT INTO profile_pictures (doctor_name, file_name, file_path) 
                  VALUES ('$name', '$file_name', '$upload_path')";
        
        if (mysqli_query($conn, $query)) {
          $success_message = "Profile picture uploaded successfully!";
        } else {
          $error_message = "Error: " . mysqli_error($conn);
        }
      } else {
        $error_message = "Failed to upload the profile picture.";
      }
    }
  } else {
    $error_message = "Please choose a file to upload.";
  }
}

$name = $_SESSION["name"];
$query = "SELECT file_path FROM profile_pictures WHERE doctor_name = '$name' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$profile_picture = mysqli_fetch_assoc($result);
?>








<!DOCTYPE html>
<html>
<head>
  <title>Doctor Landing Page</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="copyright" content="MACode ID, https://macodeid.com/">
  <link rel="stylesheet" href="../ddapp/assets/css/maicons.css">
  <link rel="stylesheet" href="../ddapp/assets/css/bootstrap.css">
  <link rel="stylesheet" href="../ddapp/assets/vendor/owl-carousel/css/owl.carousel.css">
  <link rel="stylesheet" href="../ddapp/assets/vendor/animate/animate.css">
  <link rel="stylesheet" href="vidstyle.css">
  <link rel="stylesheet" href="../ddapp/assets/css/theme.css">
  <style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
    }

    .container-1 {
      max-width: 800px;
      margin: 0 auto;
      padding: 40px;
      background-color: #ffffff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .logout-button {
      background-color: green;
      color: #ffffff;
      border: none;
      padding: 8px 16px;
      font-size: 14px;
      border-radius: 4px;
      cursor: pointer;
    }

    .profile-section {
      display: flex;
      align-items: center;
    }

    .profile-picture {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 20px;
    }

    .profile-info {
      font-size: 16px;
      font-weight: bold;
    }

    .upload-form {
      margin-top: 20px;
    }

    .success-message {
      color: green;
      margin-top: 10px;
    }

    .error-message {
      color: red;
      margin-top: 10px;
    }

  table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

  </style>
</head>
<body>

<header>
    <div class="topbar">
      <div class="container">
        <div class="row">
          <div class="col-sm-8 text-sm">
            <div class="site-info">
              
              <a href="tel:+254740860629"><span class="mai-call text-primary"></span> +254740860629</a>
              <span class="divider">|</span>
              <a href="mailto:admin@ondoc.com"><span class="mai-mail text-primary"></span> admin@ondoc.com</a>
            </div>
          </div>
          <div class="col-sm-4 text-right text-sm">
            <div class="social-mini-button">
              
             
          </div>
        </div> 
      </div> 
    </div> 

    <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="home.php"><span class="text-primary">On</span>Doc</a>

       

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupport" aria-controls="navbarSupport" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupport">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="doctor.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="patients.php">Patients</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="abouts.php">About Us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contacts.php">Contact</a>
            
            <li class="nav-item">
              <li  ><a class="btn btn-primary" href="logout.php">Logout</a></li>
          </ul>
        </div> 
      </div> 
    </nav>
  </header>

  <section >
      <div>
    
    </div><div class="hero-section">
    
      
    </div>

  <div class="container text-center wow ">
        <?php if ($profile_picture && file_exists($profile_picture["file_path"])) : ?>
          <img class="profile-picture" src="<?php echo $profile_picture["file_path"]; ?>" alt="Profile Picture">
        <?php else : ?>
          <img class="profile-picture" src="default_profile_picture.png" alt="Profile Picture">
        <?php endif; ?>
        <div class="profile-info">
         
    <div class="upload-form">
      <h2>Upload Profile Picture</h2>
      <form method="post" enctype="multipart/form-data">
        <input class="btn btn-primary" type="file" name="profile_picture" required><br><br>
        <input class="btn btn-primary" type="submit" value="Upload">
      </form>
      <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo $success_message; ?></p>
      <?php endif; ?>
      <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
      <?php endif; ?>
    </div>
  </div>
  <br>
         <h3>WELCOME, <?php  echo $_SESSION['name']; ?></h3>
        <span class="subhead">SAY HELLO TO BETTER</span>
        <h1 class="display-4">Healthy Living</h1>
        <a href="contact.html" class="btn btn-primary">Let's Consult</a>
      </div>
    </div>
  </div>
</section>

 <section class="new-sect">

    <div class="page-section pb-0">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 py-3 wow ">
            <h1> <br> </h1>
            <p class="text-grey mb-4"></p>
            
          </div>
          
            <div class="img-place custom-img-1">
              <img src="../ddapp/assets/img/slider-hero-img.png" alt="">
            </div>
          </div>
        </div>
      </div>
    </div> 
  </div> 

  
  <div class="page-section">
    <div class="container">
      <h1 class="text-center mb-5 wow ">Our Patients</h1>


<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ddapp";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT ssn, name, primary_physician_ssn FROM patients";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table>';
    echo '<tr><th>SSN</th><th>Name</th><th>Primary physician</th></tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['ssn'] . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['primary_physician_ssn'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo '<p>No Patients found.</p>';
}
?> 

</body>
</html>
