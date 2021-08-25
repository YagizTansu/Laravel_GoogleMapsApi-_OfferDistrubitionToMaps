<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta id="api-token" name="api-token" content="{{ api_token() }}">

    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <title>Document</title>
</head>
<body>

</body>
</html>

<script>

    var api_token = document.getElementById("api-token").getAttribute('content');
    //alert(api_token);

    $.ajaxSetup({
        headers: {"api-token":api_token }
    });

     function getApi() {
        var token = $.ajax({
            url: "/api/createApiToken",
            type: "GET",
            success: function(response) {
                swal("Good job!", "You clicked the button!", "success");
            },
            error: function () {
                swal ( "Oops" ,  "Something went wrong!" ,  "error" )
            }
        });
        return token;
    }
 getApi();
debugger
</script>
