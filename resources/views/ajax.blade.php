<x-app-layout>
    <x-slot name="header">Ships</x-slot>

    <p class="text-center fs-3">Ship Distrubition of Firm </p>
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
            </div>
            <div class="col-md-2">
                <h3>Add Ship</h3>
                <form method="POST" action="{{ route('ship-add') }}">
                    @csrf
                    <div class="form-group">
                        <label>Ship Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Titanic">
                    </div>

                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" placeholder="34.45">
                    </div>

                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" placeholder="54.64">
                    </div>

                    <div class="form-group">
                        <label>Radius</label>
                        <input type="text" class="form-control" id="radius" name="radius" placeholder="100000.28">
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

                    <input class="form-check-input" type="checkbox" value="" name="subCircle">
                    <label class="form-check-label" for="flexCheckDefault">Show Sub Circle</label>

                </form>

            </div>
        </div>
    </div>
    <div id="map"></div>

    <script>
        function calcDistance(fromLat, fromLng, toLat, toLng) {
            return google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(fromLat, fromLng),
                new google.maps.LatLng(toLat, toLng));
        }

        function mapping(price, price_min, price_max) {
            return (price - price_min) * (100 - 0) / (price_max - price_min) + 0;
        }

        function priceToColor(min, max, price) {
            var mapped = mapping(price, min, max);
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


        const hasMultiPrice = [];
        function initMap(subCirleController) {}

        $(function initMap(subCirleController) {
            var map;
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 47.5162,
                    lng: 14.5501
                },
                zoom: 4.8,
            });

            $.ajax({
                url: "{{ route('ajax-post') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    //alert('girdi');
                    $.each(response['currency'], function(key, currency) {
                        $('#currency').append('<option value=' + currency.id + '> ' + currency
                            .name + '</option>');
                    });

                    var subCircleController = subCirleController;

                    $.each(response['ships'], function(key, ship) {

                        var contentString = "<strong>" + 'Ship Name: ' + "</strong>" + ship
                            .name
                            .toString() + "<br>" +
                            "<strong>" + 'Ship Price: ' + "</strong>" + ship.price .toString() + " " + ship.currency.symbol + "<br>" +
                            "<a href=/ship-detail/" + ship.id +
                            " class='btn btn-sm btn-primary'> " +
                            'ship detail' + "</a>" + "<br> <br>";

                        var totalElement = 1;
                        var totalPrice = ship.price;
                        var priceArray = [ship.price];
                        $.each(response['ships'], function(key, secondShip) {
                            var distance = calcDistance(ship.latitude, ship
                                .longitude, secondShip.latitude,
                                secondShip.longitude);

                            if ((ship.radius / 10) > (distance) && distance != 0) {
                                if (((distance) + secondShip.radius / 10) < ship
                                    .radius / 10) {
                                    totalElement++;
                                    hasMultiPrice.push(ship.id);
                                    hasMultiPrice.push(secondShip.id);
                                    priceArray.push(secondShip.price);

                                    const marker = new google.maps.Marker({
                                        position: new google.maps.LatLng(ship
                                            .latitude, ship
                                            .longitude),
                                        map,
                                    });

                                    totalPrice += secondShip.price;
                                    contentString += " <strong>" +
                                        'Ship Name: ' + "</strong>" + secondShip.name
                                        .toString() + "<br>" +
                                        "<strong>" + 'Ship Price: ' + "</strong>" +
                                        secondShip.price
                                        .toString() + " "+ secondShip.currency.symbol +"<br>" +
                                        "<a href=/ship-detail/" + secondShip.id +
                                        " class='btn btn-sm btn-primary'> " +
                                        'ship detail' + "</a>" + "<br> <br>";



                                    marker.addListener("click", () => {
                                        infowindow.open(marker.get("map"),
                                            marker);
                                    });

                                    const cityCircle = new google.maps.Circle({
                                        strokeColor: priceToColor(response[
                                                'priceMin'],
                                            response['priceMax'], (
                                                totalPrice) /
                                            totalElement),
                                        strokeOpacity: 0.99,
                                        strokeWeight: 3,
                                        fillColor: priceToColor(response[
                                                'priceMin'],
                                            response['priceMax'], (
                                                totalPrice) /
                                            totalElement),
                                        fillOpacity: 0.20,
                                        map,
                                        center: new google.maps.LatLng(ship
                                            .latitude, ship
                                            .longitude),
                                        radius: ship.radius * 0.1,
                                    });
                                }
                            }
                        });

                        const infowindow = new google.maps.InfoWindow({
                                        content: contentString +" <strong>"+  " Average Price :" +" </strong>"+ totalPrice/totalElement +  "<br>" + " Min Price : "+Math.min.apply(null, priceArray)+  "<br> "+  " Max Price : " + +Math.max.apply(null, priceArray),
                                    });

                        if (subCircleController == true) {
                            const cityCircle = new google.maps.Circle({
                                strokeColor: priceToColor(response[
                                        'priceMin'], response[
                                        'priceMax'], ship
                                    .price),
                                strokeOpacity: 0.99,
                                strokeWeight: 3,
                                fillColor: priceToColor(response[
                                        'priceMin'], response[
                                        'priceMax'], ship
                                    .price),
                                fillOpacity: 0.20,
                                map,
                                center: new google.maps.LatLng(ship.latitude, ship
                                    .longitude),
                                radius: ship.radius * 0.1,
                            });
                        }


                    });
                    $.each(response['ships'], function(key, ship) {
                        let j = 0;
                        for (let i = 0; i < hasMultiPrice.length; i++) {
                            //alert(hasMultiPrice[i]);
                            if (ship.id != parseInt(hasMultiPrice[i])) {
                                j = j + 1;
                                if (j == (hasMultiPrice.length)) {
                                    const marker = new google.maps.Marker({
                                        position: new google.maps.LatLng(ship
                                            .latitude, ship.longitude),
                                        map,
                                    });

                                    const infowindow = new google.maps.InfoWindow({
                                        content: "<strong>" + 'Ship Name: ' +
                                            "</strong>" + ship.name
                                            .toString() + "<br>" +
                                            "<strong>" + 'Ship Price: ' + "</strong>" +
                                            ship.price
                                            .toString() + " " + ship.currency.symbol + "<br>" +
                                            "<a href=/ship-detail/" + ship.id +
                                            " class='btn btn-sm btn-primary'> " +
                                            'ship detail' + "</a>"
                                    });

                                    marker.addListener("click", () => {
                                        infowindow.open(marker.get("map"), marker);
                                    });
                                    const cityCircle = new google.maps.Circle({
                                        strokeColor: priceToColor(response['priceMin'],
                                            response['priceMax'],
                                            ship.price),
                                        strokeOpacity: 0.99,
                                        strokeWeight: 3,
                                        fillColor: priceToColor(response['priceMin'],
                                            response['priceMax'],
                                            ship.price),
                                        fillOpacity: 0.20,
                                        map,
                                        center: new google.maps.LatLng(ship.latitude,
                                            ship.longitude),
                                        radius: ship.radius * 0.1,
                                    });
                                }
                            }
                        }
                    });

                    var subCircle = document.querySelector("input[name=subCircle]");

                    subCircle.addEventListener('change', function() {
                        if (this.checked) {
                            $("#map").empty();
                            initMap(true);
                        } else {
                            $("#map").empty();
                            initMap(false);
                        }

                    });
                }
            });
        });
    </script>


    <script src="http://maps.google.com/maps/api/js?sensor=false&libraries=geometry" type="text/javascript"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMU4r64e98czgUSW1_V6ESAend_wpYY6Q&callback=initMap&libraries=&v=weekly"
        async>
    </script>
</x-app-layout>
