<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';
use Twilio\Rest\Client;

$client = new Client($sid, $token);
//$lookup = new Lookups_Services_Twilio($sid, $token);
$error = [];
$number = "";
$message = "";
$success = "";

if (isset($_REQUEST['s'])) {
    if (isset($_REQUEST['number']) && !empty($_REQUEST['number'])) {
        $number = $_REQUEST['number'];

        /*        try {
            $number = $lookup->phone_numbers->get($_REQUEST['number']);
        } catch(Exception $e) {
            $error['number'] = "Please enter a valid number!";
        }*/
    } else {
        $error['number'] = "Please enter a number!";
    }

    if (isset($_REQUEST['message']) && !empty($_REQUEST['message'])) {
        $message = $_REQUEST['message'];
    } else {
        $error['message'] = "Please enter a message!";
    }

    if (empty($error)) {
        try {
            $client->messages->create(
                $number,
                array(
                    'from' => $tpn,
                    'body' => $message,
                )
            );

            $success = "SMS has been sent!";
        } catch(Exception $e) {

            switch($e->getCode()) {
                case '21211':
                    $error['number'] = "Please enter a valid phone number!";
                    break;
                default:
                    //echo $e->getMessage();
                    $error['number'] = "An error occurred.";
                    break;
            }
        }
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta name="robots" content="noodp, noarchive"/>

    <title>Twilio Example</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>

<section class="container" style="margin-top:5%;">

    <div class="col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">

            <div class="panel-heading">
                Twilio Example
            </div>

            <div class="panel-body">

                <h3 style="margin-top:0px;paddding:0px;margin-bottom:15px;">Sent an SMS!</h3>

                <div style="color:green;padding-bottom:15px;"><?php echo $success;?></div>

                <form method="post" id="txt">
                    <div style="color:red;"><?php echo (isset($error['number'])) ? $error['number'] : "";?></div>
                    <input type="text" name="number" placeholder="Enter a phone number" value="<?php echo $number;?>" class="form-control" />
                    <br />
                    <div style="color:red;"><?php echo (isset($error['message'])) ? $error['message'] : "";?></div>
                    <textarea name="message" placeholder="Enter the message" class="form-control"><?php echo $message;?></textarea>
                    <br />
                    <input type="submit" name="s" class="btn btn-success" value="Send!" />
                    <input type="reset" class="btn btn-default" value="Reset" />
                </form>

            </div>

        </div>
    </div>

</section>

<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>


