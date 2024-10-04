<?php
if (isset($_POST['submit']) && $_POST['msg'] != '') {
    $host = "127.0.0.1";
    $port = 80811;
    $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die('Not Created');
    socket_connect($socket, $host, $port) or die('Not connect');
    $msg = $_POST['msg'];
    socket_write($socket, $msg, strlen($msg));
}
?>
<form method="post">
    <input type="text" name="msg">
    <input type="submit" name="submit">
</form>