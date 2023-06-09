<?php
// Include employeeDAO file
require_once('./dao/employeeDAO.php');
$employeeDAO = new employeeDAO(); 

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Get URL parameter
    $id =  trim($_GET["id"]);
    $employee = $employeeDAO->getEmployee($id);
            
    if($employee){
        // Retrieve individual field value
        $name = $employee->getName();
        $address = $employee->getAddress();
        $salary = $employee->getSalary();
        $birthdate = $employee->getBirthdate();
        $filename = $employee->getFilename();
    } else{
        // URL doesn't contain valid id. Redirect to error page
        header("location: error.php");
        exit();
    }
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
} 

// Close connection
$employeeDAO->getMysqli()->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Record</h1>
                    <div class="form-group">
                        <label>Name:</label>
                        <b><?php echo $name; ?></b>
                    </div>
                    <div class="form-group">
                        <label>Address:</label>
                        <b><?php echo $address; ?></b>
                    </div>
                    <div class="form-group">
                        <label>Salary:</label>
                        <b><?php echo $salary; ?></b>
                    </div>
                    <div class="form-group">
                        <label>Birthdate:</label>
                        <b><?php echo $birthdate; ?></b>
                    </div>
                    <div class="form-group">
                        <label>Image:</label>
                        <b><?php echo $filename; ?></b>
                    </div>                    
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                    <img src="data/<?php echo $filename ?>" class="img-fluid img-thumbnail">                    
                </div>
            </div>        
        </div>
    </div>
</body>
</html>