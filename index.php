<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Postal PIN Code API</title>
<style>
    *,
    *:before,
    *:after {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    body {
        font-family: 'Nunito', sans-serif;
        color: #384047;
    }

    form {
        max-width: 300px;
        margin: 10px auto;
        padding: 10px 20px;
        background: #f4f7f8;
        border-radius: 8px;
    }

    h1 {
        margin: 0 0 30px 0;
        text-align: center;
    }

    input[type="text"],
    input[type="password"],
    input[type="date"],
    input[type="datetime"],
    input[type="email"],
    input[type="number"],
    input[type="search"],
    input[type="tel"],
    input[type="time"],
    input[type="url"],
    textarea,
    select {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        font-size: 16px;
        height: auto;
        margin: 0;
        outline: 0;
        padding: 15px;
        width: 100%;
        background-color: #e8eeef;
        color: #8a97a0;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.03) inset;
        margin-bottom: 30px;
    }

    input[type="radio"],
    input[type="checkbox"] {
        margin: 0 4px 8px 0;
    }

    select {
        padding: 6px;
        height: 32px;
        border-radius: 2px;
    }

    input[type="submit"]  {
        padding: 19px 39px 18px 39px;
        color: #FFF;
        background-color: #4bc970;
        font-size: 18px;
        text-align: center;
        font-style: normal;
        border-radius: 5px;
        width: 100%;
        border: 1px solid #3ac162;
        border-width: 1px 1px 3px;
        box-shadow: 0 -1px 0 rgba(255, 255, 255, 0.1) inset;
        margin-bottom: 10px;
    }

    fieldset {
        margin-bottom: 30px;
        border: none;
    }

    legend {
        font-size: 1.4em;
        margin-bottom: 10px;
    }

    label {
        display: block;
        margin-bottom: 8px;
    }

    label.light {
        font-weight: 300;
        display: inline;
    }

    .number {
        background-color: #5fcf80;
        color: #fff;
        height: 30px;
        width: 30px;
        display: inline-block;
        font-size: 0.8em;
        margin-right: 4px;
        line-height: 30px;
        text-align: center;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.2);
        border-radius: 100%;
    }

    .container {
        text-align: center;
        margin-top: 30px;
    }

    .response-container {
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .response {
        text-align: left;
        margin-bottom: 10px;
        margin-right: 10px;
        flex: 0 0 auto;
    }
</style>
</head>
<body>

<div class="container">
    <form method="post">
        <input type="text" name="pincode" placeholder="Enter PIN code">
        <input type="submit" value="Get Post Office">
    </form>
    <form method="post">
        <input type="text" name="postOffice" placeholder="Enter Post Office name">
        <input type="submit" value="Get PIN code">
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["pincode"])) {
        $pincode = $_POST["pincode"];
        $url = "https://api.postalpincode.in/pincode/" . $pincode;
    } elseif (isset($_POST["postOffice"])) {
        $postOffice = $_POST["postOffice"];
        $postOffice = urlencode($postOffice); // URL encode if spaces present
        $url = "https://api.postalpincode.in/postoffice/" . $postOffice;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data[0]['Status']) && $data[0]['Status'] == "Success") {
        echo '<div class="response-container">';
        echo '<div class="response"><input type="text" value="' . $data[0]['Message'] . '" readonly></div>';
        foreach ($data[0]['PostOffice'] as $office) {
            echo '<div class="response">';
            foreach ($office as $key => $value) {
                if (in_array($key, ['Pincode', 'Block', 'Region', 'Division', 'DeliveryStatus', 'Name'])) {
                    echo '<input type="text" value="' . $key . ': ' . $value . '" readonly><br>';
                }
            }
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<div class="response"><input type="text" value="Error: No records found" readonly></div>';
    }
}
?>

</body>
</html>
