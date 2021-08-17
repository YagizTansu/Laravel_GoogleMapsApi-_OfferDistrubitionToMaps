<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <title>Distrubition</title>
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
                        <h5 class="card-title">Add</h5>
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
                        <select id="showCurrency" class="form-select form-select-sm"aria-label=".form-select-sm example">
                        </select>

                    </div>
                </div>
                <br>

                <div id="formCard" class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Ship</h5>
                        <hr>
                        <form method="POST" action="{{ route('ship-add') }}">
                            @csrf
                            <div class="form-group">
                                <label>Ship Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Titanic">
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
                                <select id="currency" class="form-select form-select-sm" name="currency_id"
                                    aria-label=".form-select-sm example">
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

<script>
    const hasMultiPrice = [];
    var map;
    var subCircleController = null;
    var subCircleFilterValue = null;
    var currencyFilterValue = null;
    var exchangeCurrencySymbol = null;
    var currencyText= "Dollar";
    var currencySymbol = null;
    var excahngeCurrencyLength = null;

    function markersAndCircles(map, response) {
        map = Create.createMap(map); // create main map


        if (currencyFilterValue == null) { // control display currency value
            currencyFilterValue = response['defaultCurrency'];
        }

        $.each(response['ships'], function(key, ship) {
            var contentString = InfoWindow.createContentString(ship); // create String for ship info window
            var totalElement = 1;
            var totalPrice = changeCurrency(ship.price, ship.currency.currency_exchange_rates[0].selling,currencyFilterValue);
            var priceArray = [changeCurrency(ship.price, ship.currency.currency_exchange_rates[0].selling,currencyFilterValue)];

            $.each(response['ships'], function(key, secondShip) {
                var distance = calcDistance(ship.latitude, ship.longitude, secondShip.latitude,secondShip.longitude); // take distance between 2 ships
                var sum = parseInt(distance)+parseInt(secondShip.radius);
                if (sum < ship.radius  && distance !=0  && distance < ship.radius) {   // control block: if small circle included  big cicle

                if(secondShip.price != null ){
                    totalElement++;
                }

                 hasMultiPrice.push(ship.id);
                 hasMultiPrice.push(secondShip.id);
                 priceArray.push(changeCurrency(secondShip.price, secondShip.currency.currency_exchange_rates[0].selling, currencyFilterValue));

                 var marker = Create.createMarker(map, ship,marker); // create Markers if coordinate has multi prices
                 totalPrice += changeCurrency(secondShip.price,secondShip.currency.currency_exchange_rates[0].selling, currencyFilterValue);
                 contentString += InfoWindow.createContentString(secondShip);

                 marker.addListener("click", () => {
                     infowindow.open(marker.get("map"), marker);
                 });

                 Create.createCirle(map, response, ship, 0.99, 3, 0.2);
                }
            });

            const infowindow = new google.maps.InfoWindow({ // max min average value for each multi cirle info window
                content: contentString + InfoWindow.createAveragePriceString(totalPrice,totalElement,currencyText,priceArray)
            });

            if (subCircleController == true) {
                Create.createCirle(map, response, ship, 0.99, 3, 0.2);
            }

        });

        $.each(response['ships'], function(key, ship) {
            let j = 0;
            for (let i = 0; i < hasMultiPrice.length; i++) {
                if (ship.id != parseInt(hasMultiPrice[i])) {
                    j = j + 1;
                    if (j == (hasMultiPrice.length)) {
                        var marker = Create.createMarker(map, ship, marker);
                        var contentString = contentString = InfoWindow.createContentString(ship);

                        const infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });

                        marker.addListener("click", () => {
                            infowindow.open(marker.get("map"), marker);
                        });

                        Create.createCirle(map, response, ship, 0.99, 3, 0.2);
                    }
                }
            }
        });
    }

    class InfoWindow{
        static createContentString(ship) {
            var contentString = "<strong>" + 'Ship Name: ' + "</strong>" + ship.name.toString() + "<br>" +
                "<strong>" + 'Ship Price: ' + "</strong>" + changeCurrency(ship.price, ship.currency.currency_exchange_rates[0].selling,currencyFilterValue).toFixed(2).toString() +' '+Filter.currencySymbols(currencyText) +  " " +
                "<br>" + "<a href=/ship-detail/" + ship.id + " class='btn btn-sm btn-primary'> " + 'ship detail' + "</a>" +
                "<br> <br>";

        return contentString;
        }

        static createAveragePriceString(totalPrice,totalElement,currencyText,priceArray) {
            var contentString = " <strong class='text-warning' >" + " Average Price :" +
                " </strong>" + (totalPrice /totalElement).toFixed(2)  + ' ' + Filter.currencySymbols(currencyText)+  "<br>" + " <strong class='text-success' >" +
                " Min Price : " + " </strong>" + Math.min.apply(null, priceArray).toFixed(2) +' '+ Filter.currencySymbols(currencyText) +
                "<br> " + " <strong class='text-danger' >" + " Max Price : " + " </strong>" + Math.max.apply(null, priceArray).toFixed(2) + ' ' + Filter.currencySymbols(currencyText);

        return contentString;
        }
    }

    class Filter{
        static updateFilter() {
            subCircleFilterValue = Filter.getSubCircleFilterValue();
            currencyFilterValue = Filter.getCurrencyFilterValue();
            loadMap(map, subCircleFilterValue, currencyFilterValue);
        }

        static getSubCircleFilterValue() {
            subCircle.addEventListener('change', function() {
                subCircleController = this.checked;
            });
            return subCircleController;
        }

        static getCurrencyFilterValue() {
            currencyValue.addEventListener('change', (event) => {
                currencyFilterValue = event.target.value;
            });
            Filter.currencySymbols(currencyText);
            return currencyFilterValue;
        }

        static currencySymbols(currencyText) {
            currencyText = $( "#showCurrency option:selected" ).text();
            switch (currencyText.trim()) {
                case "Dollar":
                    currencySymbol = "$";
                    break;
                case "Euro":
                    currencySymbol = "€";
                    break;
                case "Liras":
                    currencySymbol = "₺";
                    break;
                default:
                    currencySymbol = "$";
                    break;
            }
            return currencySymbol ;
        }
    }

    class Create{
        static createMap(map) { //create only map
            map = null;
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 47.5162,
                    lng: 14.5501
                },
                zoom: 4.8,
            });
            return map;
        }

        static createCirle(map, response, ship, strokeOpacity, strokeWeight, fillOpacity) {
            const cityCircle = new google.maps.Circle({
                strokeColor: ColorMap.priceToColor(response['priceMin'],response['priceMax'],ship.price),
                strokeOpacity: strokeOpacity,
                strokeWeight: strokeWeight,
                fillColor: ColorMap.priceToColor(response['priceMin'],response['priceMax'],ship.price),
                fillOpacity: fillOpacity,
                map,
                center: new google.maps.LatLng(ship.latitude,ship.longitude),
                radius: ship.radius*1 ,
            });
        }

        static createMarker(map, ship, marker) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(ship.latitude, ship.longitude),map,
            });
            return marker;
        }
    }

    class ColorMap{
        static mapping(price, price_min, price_max) {
            return (price - price_min) * (100 - 0) / (price_max - price_min) + 0;
        }

        static priceToColor(min, max, price) {
            var mapped = ColorMap.mapping(price, min, max);
            var r, g, b = 0;
            if (mapped < 50) {
                g = 255;
                r = Math.round(5.1 * mapped);
            } else {
                r = 255;
                g = Math.round(510 - 5.10 * mapped);
            }
            var h = r * 0x10000 + g * 0x100 + b * 0x1;

            return '#' + ('000000' + h.toString(16)).slice(-6);
        }
    }

    function calcDistance(fromLat, fromLng, toLat, toLng) {
        return google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(fromLat, fromLng),new google.maps.LatLng(toLat, toLng));
    }

    function changeCurrency(price, currency, exchangeCurrency) {
        var excahngeCurrencyLength = exchangeCurrency.length;
        return (price * currency) / exchangeCurrency;
    }

    function loadMap(map) { // Load all maps proporties
        masterAjax(map);
    }

    function masterAjax() {
        $.ajax({
            url: "{{ route('ajax-post') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                printCurrency(response);
                markersAndCircles(map, response);
            }
        });
    }

    function printCurrency(response) {
        $('#currency').empty();
        $.each(response['currency'], function(key, currency) {
            $('#currency').append('<option value=' + currency.id + '>' + currency.name + '</option>');
        });
    }

    function getDisplayExchangeRates() {
        $.ajax({
            url: "{{ route('get-exchange-rate') }}",
            type: "GET",
            success: function(response) {
                $('#showCurrency').empty();
                $.each(response['CurrencyExchangeRate'], function(key, exchange) {
                    $('#showCurrency').append('<option value=' + exchange.selling + '> ' + exchange.currency.name + '</option>');
                });
            }
        });
    }
    getDisplayExchangeRates();

    var subCircle = document.querySelector("#subCircle");
    subCircle.addEventListener('change', function() { //control subcircle filter checkbox
        subCircleController = this.checked;
        Filter.updateFilter();
    });

    const currencyValue = document.querySelector('#showCurrency');
    currencyValue.addEventListener('change', (event) => { //control display currency filter
        currencyFilterValue = event.target.value;
        Filter.updateFilter();
    });

    $("#addCirleMode").click(function() {
        $("#formCard").empty();
        $("#filterCard").empty();
        $('#floating-panel').empty();

        $('#floating-panel').append('<h5 class="text-success">Add Circle Mode</h5>');
        $('#floating-panel').append('<a href="/distribution" id="saveCircleButton" class="btn btn-primary mr-2 ">Save</a>');
        $('#floating-panel').append('<a id="delete-markers" class="btn btn-danger  mr-2"> Delete Markers</a>');
        $('#floating-panel').append('<a href="/distribution" class="btn btn-warning mr-2 ">Exit</a>');

        $("#delete-markers").click(function() {
            AddCircleMode(map);
        });

        AddCircleMode(map);
    });

    function AddCircleMode(map) {
        map = null;
        map = Create.createMap(map);

        const myLatlng = {
            lat: 47.5162,
            lng: 14.5501
        };

        // Create the initial InfoWindow.
        let infoWindow = new google.maps.InfoWindow({
            content: "Click the map to add Circle",
            position: myLatlng,
        });
        infoWindow.open(map);

        var clicked = 0;
        var cityCircle;
        // Configure the click listener.
        map.addListener("click", (mapsMouseEvent) => {

            clicked++;
            if (clicked != 1) {
                $("#saveCircleButton").click(function() {
                    //save cityCirle to db
                    $.ajax({
                        url: "{{ route('addCircle') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            radius: parseInt(cityCircle.getRadius()) ,
                            latitude: cityCircle.getCenter().lat,
                            longitude: cityCircle.getCenter().lng
                        },
                        success: function(response) {}
                    });
                    clicked = 0;
                });
            }

            if (clicked == 1) {
                cityCircle = new google.maps.Circle({
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35,
                    map,
                    draggable: true,
                    center: mapsMouseEvent.latLng,
                    radius: 0,
                });

                var firstX = 0;
                var firstY = 0;
                google.maps.event.addListener(map, "mousemove", function(event) {
                    if (clicked == 1) {
                        if (firstX == 0) {
                            firstX = event.domEvent.clientX;
                            firstY = event.domEvent.clientY;
                        }
                        cityCircle.set('radius', (firstX * 100) + ((event.domEvent.clientX) *
                            1000) - ((event.domEvent.clientY - firstY) * 1000));
                    }
                });
            }
        });
    }

</script>

<script src="http://maps.google.com/maps/api/js?sensor=false&libraries=geometry" type="text/javascript"></script>
<script
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMU4r64e98czgUSW1_V6ESAend_wpYY6Q&callback=loadMap&libraries=&v=weekly"
async>
</script>
