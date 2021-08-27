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

    <div id="table">

    </div>

</body>
</html>

<script>


class matrixDisplayer{
    static createTable(tableData) {
        var table = document.createElement('table');
        var tableBody = document.createElement('tbody');

        tableData.forEach(function (rowData) {
            var row = document.createElement('tr',);

            rowData.forEach(function (cellData) {
                var cell = document.createElement('td');
                cell.appendChild(document.createTextNode(cellData));
                row.appendChild(cell);
                row.setAttribute("style","font-size:0.5rem;")
            });

            tableBody.appendChild(row);
        });

        table.appendChild(tableBody);
        return table;
    }
}

$('#table').append( matrixDisplayer.createTable(activities));
































  /*  var api_token = document.getElementById("api-token").getAttribute('content');
    alert(api_token);

    $.ajaxSetup({
        headers: {"api-token":api_token }
    });

    function getApi() {
         $.ajax({
            url: "/api/createApiToken",
            type: "GET",
            success: function(response) {
                swal("Good job!", "You clicked the button!", "success");
            },
            error: function () {
                swal( "Oops" ,  "Something went wrong!" ,  "error" )
            }
        });
    }
 getApi();*/
</script>
