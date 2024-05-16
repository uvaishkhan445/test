<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        const xhr = new XMLHttpRequest();
        xhr.withCredentials = true;

        xhr.addEventListener('readystatechange', function() {
            if (this.readyState === this.DONE) {
                console.log(this.responseText);
            }
        });

        xhr.open('get',
            'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/{M228D6L38IAFB}/{113}');
        xhr.setRequestHeader('accept', 'text/plain');

        xhr.setRequestHeader('Content-Type', 'application/json')
        xhr.send(data);
    </script>
</body>

</html>