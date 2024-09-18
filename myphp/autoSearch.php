<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jQuery Autocomplete Example</title>

    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- jQuery and jQuery UI JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function() {
            // Autocomplete feature using jQuery UI
            $("#student_name").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "db.php",
                        type: "POST",
                        data: {
                            search: request.term
                        },
                        success: function(data) {
                            response($.map(JSON.parse(data), function(item) {
                                return {
                                    label: item.name, // This is the value displayed in the suggestions
                                    value: item.name  // This is the value inserted in the input field
                                };
                            }));
                        }
                    });
                },
                minLength: 2 // Minimum length of characters before starting the search
            });
        });
    </script>
</head>
<body>
    <h2>Search for Student</h2>
    <form method="POST" action="submit.php">
        <label for="student_name">Student Name:</label>
        <input type="text" id="student_name" name="student_name" placeholder="Start typing...">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
