<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Chat UI</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            background-color: #f0f0f0;
        }

        .chat-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            overflow: hidden;
        }

        .chat-header {
            background-color: #075e54;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .chat-messages {
            padding: 20px;
            height: 400px;
            overflow-y: scroll;
            background-color: #e5ddd5;
        }

        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 8px;
            position: relative;
            max-width: 70%;
        }

        .message-sent {
            background-color: #dcf8c6;
            margin-left: auto;
        }

        .message-received {
            background-color: #ffffff;
            margin-right: auto;
        }

        .message .timestamp {
            position: absolute;
            bottom: -15px;
            right: 10px;
            font-size: 0.8rem;
            color: #999;
        }

        .chat-input {
            background-color: #f0f0f0;
            padding: 10px;
        }

        .chat-input textarea {
            width: 90%;
            height: 50px;
            resize: none;
        }

        .chat-input button {
            background-color: #075e54;
            color: white;
        }
    </style>
</head>

<body>

    <div class="chat-container">
        <div class="chat-header">
            <h5>Chat with Us</h5>
        </div>

        <div class="chat-messages" id="msg_box">

        </div>
        <form method="post">
            <div class="chat-input d-flex align-items-center">

                <?php
                session_start();
                if (isset($_POST['submit'])) {
                    $name = $_POST['name'];
                    $_SESSION['name'] = $name;
                }
                if (!isset($_SESSION['name'])) {
                ?>

                    <input type="text" name="name" required class="form-control" placeholder="Enter your name">

                    <button type="submit" name="submit" value="Submit" class="btn ml-2">Submit</button>



        </form>

    <?php } else { ?>

        <input type="text" id="msg" class="form-control" placeholder="Type Message">

        <button type="button" id="btn" value="Send" class="btn ml-2">Send</button>


    </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>


<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script>
    let now = new Date();
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
            "<div class='message message-received'><b>" +
            getData
            .name +
            "</b>: " + getData
            .msg + "<div class='timestamp'>" + getData.time + "</div></div>";
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
            "<div class='message message-sent'><b>" +
            name +
            "</b>: " + msg + "<div class='timestamp'>" + hours + ":" + minutes + " " +
            period +
            "</div></div>";
        jQuery('#msg_box').append(html);
        jQuery('#msg').val('');
    });
</script>

<?php } ?>