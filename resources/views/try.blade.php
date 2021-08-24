<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <title>Document</title>
</head>
<body>
    deneme
</body>
</html>

<script>
     function getApi() {
        var token = $.ajax({
            url: "/createApiToken",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                debugger
                alert('başarılı');

            }
        });
        return token;
    }
    getApi();

</script>
