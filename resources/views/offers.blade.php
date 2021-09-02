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
    <script src="{{ mix('js/app.js') }}"  defer></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <title>Offers</title>
</head>

<body>
    @include('navbar')
    <br>
    <div class="container">
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        <strong>
                            <li>{{ $error }}</li>
                        </strong>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif

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
                        <select id="showCurrency" class="form-select form-select-sm"
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

                    </div>
                </div>
                <br>

                <div id="formCard" class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Offers Manually</h5>
                        <hr>
                        <form method="POST" action="/offerAddManully">
                            @csrf
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" class="form-control" id="company_id" name="company_id" value={{auth()->user()->company->name}} readonly>
                            </div>

                            <div class="form-group">
                                <label>City</label>
                                <input type="text" class="form-control" id="city_id" name="city_id" placeholder="Paris">
                            </div>

                            <div class="form-group">
                                <label>Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude"
                                    placeholder="34.45">
                            </div>

                            <div class="form-group">
                                <label>Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude"
                                    placeholder="54.64">
                            </div>

                            <div class="form-group">
                                <label>Radius</label>
                                <input type="text" class="form-control" id="radius" name="radius"
                                    placeholder="100000.28">
                            </div>

                            <div class="form-group">
                                <label>Price</label>
                                <input type="text" class="form-control" id="price" name="price" placeholder="1000">
                            </div>

                            <div class="form-group">
                                <label>Currency</label>
                                <select id="currency" class="form-select form-select-sm" name="currency"aria-label=".form-select-sm example">
                                </select>
                            </div>

                            <p class="text-center"><button id="addButton" class="btn btn-primary mt-2">Add</button></p>
                        </form>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</body>

</html>

<script src="{{ asset('js/map.js')}}"></script>
<script type="text/javascript"
src="http://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyBMU4r64e98czgUSW1_V6ESAend_wpYY6Q&callback=loadMap">
</script>
