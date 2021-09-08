<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta id="api-token" name="api-token" content="{{ api_token() }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <title>Create</title>
</head>

<body>
    @include('navbar')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div id="map"> </div>
                <div id="floating-panel"></div>
            </div>
            <div id="rightPanel" class="col-md-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Offer</h5>
                        <hr>
                        <button id="addCirleMode" class="btn btn-primary">Add Cirle</button>
                    </div>
                </div>
                <br>
            <div id="filterCard" class="card">
                <div class="card-body">
                    <h5 class="card-title">Filters</h5>
                    <hr>
                    <input class="form-check-input" type="checkbox" value="" id="subCircle">
                    <label class="form-check-label" for="flexCheckDefault">Show subcircle</label>
                    <br><br>
                    <label>Display Currency</label>
                    <select id="currencies" class="form-select form-select-sm"
                        aria-label=".form-select-sm example">
                    </select>
                    <br>
                    <label>Country</label>
                    <select id="showCountries" class="form-select form-select-sm"aria-label=".form-select-sm example">
                        <option value=''>Select Country</option>
                    </select>
                    <br>
                    <label>City</label>
                    <select id="showCities" class="form-select form-select-sm" aria-label=".form-select-sm example"
                        disabled>
                    </select>

                    <p class="text-center"><button id="searchButton" class="btn btn-primary mt-2">Search</button></p>

                </div>
            </div>
        </div>
    </div>
</body>

</html>


<script src="{{ asset('js/offersMap.js')}}"></script>
<script src="{{ asset('js/addCircleMode.js')}}"></script>

<script type="text/javascript"
src="http://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyBMU4r64e98czgUSW1_V6ESAend_wpYY6Q&callback=main">
</script>
