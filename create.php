<?php
// Include employeeDAO file
require_once('./dao/employeeDAO.php');

 
// Define variables and initialize with empty values
$name = $address = $salary = $birthdate = $filename ="";
$name_err = $address_err = $salary_err = $birthdate_err = $filename_err ="";
$image_err = "";
$min_birthdate = "1950-01-01";
$max_birthdate = "2005-01-01";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
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
        $salary_err = "Please enter an integer value between 500-100000.";
    } else{
        $salary = $input_salary;
    }

    // Validate birthdate
    $input_birthdate = trim($_POST["birthdate"]);
    if(empty($input_birthdate)){
        $birthdate_err = "Please enter the birthdate."; 
    } elseif ((strcmp($input_birthdate, $min_birthdate) < 0) || (strcmp($input_birthdate, $max_birthdate) > 0) ) {
        $birthdate_err = "Please enter a validate date between $min_birthdate and $max_birthdate.";
    }else {
        $birthdate = $input_birthdate;
    }
    
    // upload image
    if (isset($_FILES['image'])) {
        $filename = $_FILES['image']['name'];
        $size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];

        if (!empty($filename) && ($size > 0)) {
            move_uploaded_file($file_tmp, "data/" . $_FILES['image']['name']);
            echo '<br><h6 style="text-align:center">' . 'Image successfully uploaded: ' . $filename . '</h6>';
        } else {
            $filename_err = "Please choose an image to upload";
        }
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary_err) && empty($birthdate_err) && empty($filename_err)){
        $employeeDAO = new employeeDAO();    
        $employee = new Employee(0, $name, $address, $salary, $birthdate, $filename);
        $addResult = $employeeDAO->addEmployee($employee);        
        header( "refresh:2; url=index.php" ); 
		echo '<br><h6 style="text-align:center">' . $addResult . '</h6>';   
        // Close connection
        $employeeDAO->getMysqli()->close();
        }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
					
					<!--the following form action, will send the submitted form data to the page itself ($_SERVER["PHP_SELF"]), instead of jumping to a different page.-->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
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
                            <label>Upload Image</label>
                            <input type="file" name="image" >
                            <input type="hidden" name="filename" class="form-control <?php echo (!empty($filename_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $filename; ?>">
                            <span class="invalid-feedback"><?php echo $filename_err;?></span>
                        </div>                                                                                              
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        <?include 'footer.php';?>
    </div>
</body>
</html>