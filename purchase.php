<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$con = mysqli_connect("localhost", "root", "root", "testing");

if (mysqli_connect_error()) {
    echo "<script>
                alert('Cannot connect to database');
                window.location.href='index.php';
                </script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['purchase'])) {
        if (!isset($_POST['full_name'], $_POST['phone_no'], $_POST['address'], $_POST['pay_mode'])) {
            echo "<script>
                alert('Please fill all fields');
                window.location.href='index.php';
                </script>";
            exit();
        }

        $query1 = "INSERT INTO `order_manager`(`Full_Name`, `Phone_No`, `Address`, `Pay_Mode`) VALUES (?, ?, ?, ?)";
        $stmt1 = mysqli_prepare($con, $query1);
        mysqli_stmt_bind_param($stmt1, "ssss", $_POST['full_name'], $_POST['phone_no'], $_POST['address'], $_POST['pay_mode']);
        
        if (mysqli_stmt_execute($stmt1)) {
            $Order_Id = mysqli_insert_id($con);
            $query2 = "INSERT INTO `user_orders`(`Order_Id`, `Item_Name`, `Price`, `Quantity`) VALUES (?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($con, $query2);

            if ($stmt2) {
                mysqli_stmt_bind_param($stmt2, "isii", $Order_Id, $Item_Name, $Price, $Quantity);

                foreach ($_SESSION['cart'] as $key => $values) {
                    $Item_Name = $values['Item_Name'];
                    $Price = $values['Price'];
                    $Quantity = $values['Quantity'];
                    mysqli_stmt_execute($stmt2);
                }

                unset($_SESSION['cart']);
                echo "<script>
                alert('Order Placed Successfully');
                window.location.href='index.php';
                </script>";
            } else {
                echo "<script>
                alert('SQL Error');
                window.location.href='index.php';
                </script>";
            }
        } else {
            echo "<script>
                alert('Cannot insert order');
                window.location.href='index.php';
                </script>";
        }
    }
}
?>
