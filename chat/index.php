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
                                        <input type="text" name="name" required class="form-control">

                                    </div>
                                    <div class="form-group col-2">
                                        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                                    </div>


                                </form>

                            <?php } else { ?>
                                <div class="row">
                                    <div class="form-group col-10">
                                        <input type="text" id="msg" class="form-control">

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
            .msg + "<br/></div></div>";
        jQuery('#msg_box').append(html);
    };

    jQuery('#btn').click(function() {
        var msg = jQuery('#msg').val();
        var name = "<?php echo $_SESSION['name'] ?>";
        var content = {
            msg: msg,
            name: name
        };
        conn.send(JSON.stringify(content));

        var html =
            "<div class='alert alert-primary' role='alert'><div class='text-left' style='text-align: right;'><b>" +
            name +
            "</b>: " + msg +
            "<br/></div></div>";
        jQuery('#msg_box').append(html);
        jQuery('#msg').val('');
    });
</script>

<?php } ?>