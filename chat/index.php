<?php
session_start();
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $_SESSION['name'] = $name;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            padding: 10px 0;
        }
    </style>
</head>

<body>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="card mt-5">
                        <div class="card-body">
                            <div id="msg_box"></div>
                            <?php
                            if (!isset($_SESSION['name'])) {
                            ?>
                                <form method="post" class="row">
                                    <div class="form-group col-10">
                                        <input type="text" name="name" required class="form-control"
                                            placeholder="Enter your name">
                                    </div>
                                    <div class="form-group col-2">
                                        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                                    </div>


                                </form>

                            <?php } else { ?>
                                <div class="row">
                                    <div class="form-group col-10">
                                        <input type="text" id="msg" class="form-control" placeholder="Type Message">
                                    </div>
                                    <div class="form-group col-2">

                                        <input type="button" id="btn" value="Send" class="btn btn-success">
                                    </div>
                                </div>



                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>


<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script>
    let now = new Date();
    let mumbaiTime = now.toLocaleString('en-IN', {
        timeZone: 'Asia/Kolkata'
    });

    console.log(`Mumbai Time: ${mumbaiTime}`);


    let hours = now.getHours();
    let minutes = now.getMinutes();
    let seconds = now.getSeconds();
    // Determine AM or PM
    let period = hours >= 12 ? 'PM' : 'AM';

    // Convert hours from 24-hour format to 12-hour format
    hours = hours % 12 || 12; // Adjust hours so that 0 becomes 12

    console.log(`Current Time: ${hours}:${minutes}:${seconds} ${period}`);
    var conn = new WebSocket('ws://localhost:8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        var getData = jQuery.parseJSON(e.data);
        var html =
            "<div class='alert alert-success float-right' role='alert'><div class='text-right' style='text-align: left;'><b>" +
            getData
            .name +
            "</b>: " + getData
            .msg + "<br/><span style='font-size:13px;text-align: right'>" + getData.time + "</span> </div></div>";
        jQuery('#msg_box').append(html);
    };

    jQuery('#btn').click(function() {
        var msg = jQuery('#msg').val();
        var name = "<?php echo $_SESSION['name'] ?>";
        var content = {
            msg: msg,
            name: name,
            time: `${hours}:${minutes} ${period}`

        };
        conn.send(JSON.stringify(content));

        var html =
            "<div class='alert alert-primary' role='alert'><div class='text-left' style='text-align: right;'><b>" +
            name +
            "</b>: " + msg + "<br /><span style='font-size:13px;text-align: right'>" + hours + ":" + minutes + " " +
            period +
            "</span>" +
            "<br/></div></div>";
        jQuery('#msg_box').append(html);
        jQuery('#msg').val('');
    });
</script>

<?php } ?>