<?php
// Include employeeDAO file
require_once('./dao/employeeDAO.php');
 
// Define variables and initialize with empty values
$name = $address = $salary = $birthdate = $filename ="";
$name_err = $address_err = $salary_err = $birthdate_err = $filename_err ="";
$min_birthdate = "1950-01-01";
$max_birthdate = "2005-01-01";

$employeeDAO = new employeeDAO(); 

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address."; 
    } elseif (strlen($input_address) > 100) {
        $address_err = "Please enter an address less than 100 charaters.";           
    } else{
        $address = $input_address;
    }
    
    // Validate salary
    $input_salary = trim($_POST["salary"]);
    if(empty($input_salary)){
        $salary_err = "Please enter the salary amount.";
    } elseif(!ctype_digit($input_salary)){
        $salary_err = "Please enter a positive integer value.";
    } elseif (($input_salary < 500 ) || ($input_salary > 100000) ) {
        $salary_err = "Please enter an integer value between 500 and 100000.";        
    } else{
        $salary = $input_salary;
    }

    // Validate birthdate
    $input_birthdate = trim($_POST["birthdate"]);
    if(empty($input_birthdate)){
        $birthdate_err = "Please enter the birthdate.";     
    } elseif ((strcmp($input_birthdate, $min_birthdate) < 0) || (strcmp($input_birthdate, $max_birthdate) > 0) ) {
        $birthdate_err = "Please enter a validate date between $min_birthdate and $max_birthdate.";
    } else{
        $birthdate = $input_birthdate;
    }
    
    // Validate filename
    $input_filename = trim($_POST["filename"]);
    if(empty($input_filename)){
        $filename_err = "Please enter the filename."; 
    } else{
        $filename = $input_filename;
    }

    // validate and upload the image file
    if (isset($_FILES['image'])) {
        $file = $_FILES['image']['name'];
        $size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];

       if (!empty($file) && ($size > 0) )  {
            move_uploaded_file($file_tmp, "data/" . $_FILES['image']['name']);
            $filename = $file;
            echo '<br><h6 style="text-align:center">' . 'Image successfully uploaded: ' . $filename . '</h6>';
        } else {
            //echo "Using existing emage: $filename";
            echo '<br><h6 style="text-align:center">' . 'Using existing emage: ' . $filename . '</h6>';
        }
    } 

    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary_err) && empty($birthdate_err) && empty($filename_err)){        
        $employee = new Employee($id, $name, $address, $salary, $birthdate, $filename);
        $result = $employeeDAO->updateEmployee($employee);        
		header("refresh:2; url=index.php");
		echo '<br><h6 style="text-align:center">' . $result . '</h6>';
        // Close connection
        $employeeDAO->getMysqli()->close();
    }

} else{
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
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Salary</label>
                            <input type="text" name="salary" class="form-control <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary; ?>">
                            <span class="invalid-feedback"><?php echo $salary_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Birthdate</label>
                            <input type="text" name="birthdate" class="form-control <?php echo (!empty($birthdate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $birthdate; ?>">
                            <span class="invalid-feedback"><?php echo $birthdate_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Image:</label>
                            <?php echo $filename; ?>
                            <input type="hidden" name="filename" value="<?php echo $filename; ?>">
                        </div>                         
                        <div class="form-group">
                            <label>Upload New Image</label>
                            <input type="file" name="image">
                            <span class="invalid-feedback"><?php echo $filename_err;?></span>
                        </div>  
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>