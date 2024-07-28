<?php
include("header.php");
session_start(); // Ensure the session is started
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">My Cart</h1>
        <div class="row">
            <div class="col-lg-8">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Serial No.</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">Item Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        if (isset($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $key => $value) {
                                $total += $value['Price'] * $value['Quantity'];
                                echo "
                                <tr>
                                    <td>" . ($key + 1) . "</td>
                                    <td>{$value['Item_Name']}</td>
                                    <td>{$value['Price']} <input type='hidden' class='iprice' value='{$value['Price']}'></td>
                                    <td><input class='form-control iquantity' onchange='subTotal()' type='number' value='{$value['Quantity']}' min='1' max='10'></td>
                                    <td class='itotal'>" . ($value['Price'] * $value['Quantity']) . "</td>
                                    <td>
                                    <form action='manage_cart.php' method='post'>
                                        <button class='btn btn-danger' name='Remove_Item'>REMOVE</button>
                                        <input type='hidden' name='Item_Name' value='{$value['Item_Name']}'>
                                    </form>
                                    </td>
                                </tr>
                                ";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Total:</h3>
                        <h5 class="card-text" id="grandTotal"><?php echo $total; ?></h5>
                        <br>
                        <form action="">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Cash On Delivery
                                </label>
                            </div>
                            <br>
                            <button class="btn btn-primary btn-block">Make Purchase</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function subTotal() {
            const iprices = document.getElementsByClassName('iprice');
            const iquantities = document.getElementsByClassName('iquantity');
            const itotals = document.getElementsByClassName('itotal');
            let grandTotal = 0;

            for (let i = 0; i < iprices.length; i++) {
                const itemTotal = iprices[i].value * iquantities[i].value;
                itotals[i].innerText = itemTotal;
                grandTotal += itemTotal;
            }

            document.getElementById('grandTotal').innerText = grandTotal;
        }

        // Add event listeners to each quantity input
        document.addEventListener('DOMContentLoaded', function() {
            const iquantities = document.getElementsByClassName('iquantity');
            for (let i = 0; i < iquantities.length; i++) {
                iquantities[i].addEventListener('change', subTotal);
            }
        });

        subTotal(); // Initial calculation
    </script>

</body>

</html>
