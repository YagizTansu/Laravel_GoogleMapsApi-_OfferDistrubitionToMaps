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
                        <select class="form-select form-select-sm" name="currency_id"
                            aria-label=".form-select-sm example">
                        </select>
                    </div>

                    <p class="text-center"><button id="addButton" class="btn btn-primary mt-2">Add</button></p>

                </form>

            </div>
        </div>
    </div>
    <div id="map"></div>

    <script>

    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMU4r64e98czgUSW1_V6ESAend_wpYY6Q&callback=initMap&libraries=&v=weekly"
        async>
    </script>

    <script>
        function distanceBetweenCenter(firstCordinate_lat, firstCordinate_lng, secondCordinate_lat, secondCordinate_lng) {
            return Math.sqrt(Math.pow((Math.abs(secondCordinate_lat) - Math.abs(firstCordinate_lat)), 2) + Math.pow((Math
                .abs(secondCordinate_lng) -
                Math.abs(firstCordinate_lng)), 2));
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

        let map;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 47.5162,
                    lng: 14.5501
                },
                zoom: 4.8,
            });
        }

        setInterval(function() {
            //document.getElementById('map').innerHTML = "";

            $.ajax({
                url: "{{ route('ajax-post') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    $.each(response['ships'], function(key, ship) {
                        $.each(response['ships'], function(key, secondShip) {
                            const marker = new google.maps.Marker({
                                position: new google.maps.LatLng(ship.latitude,ship.longitude),
                                map,
                            });
                            var distance = distanceBetweenCenter(ship.latitude, ship
                                .longitude, secondShip.latitude, secondShip
                                .longitude);

                            if ((ship.radius) > (distance * 1000000) && distance != 0) {
                                if (((distance * 1000000) + secondShip.radius) < ship.radius) {

                                    hasMultiPrice.push(ship.id);
                                    hasMultiPrice.push(secondShip.id);

                                    const marker = new google.maps.Marker({
                                        position: new google.maps.LatLng(ship
                                            .latitude, ship.longitude),
                                        map,
                                    });

                                    const infowindow = new google.maps.InfoWindow({
                                        content: "<strong>" + 'Ship Name: ' +
                                            "</strong>" + ship.name
                                            .toString() + "<br>" +
                                            "<strong>" + 'Ship Price: ' +
                                            "</strong>" + ship.price
                                            .toString() + "<br>" +
                                            "<a href=/ship-detail/" + ship
                                            .id +
                                            " class='btn btn-sm btn-primary'> " +
                                            'ship detail' + "</a>" +
                                            "<br> <br>" + "<strong>" +
                                            'Ship Name: ' + "</strong>" +
                                            secondShip.name
                                            .toString() + "<br>" +
                                            "<strong>" + 'Ship Price: ' +
                                            "</strong>" + secondShip.price
                                            .toString() + "<br>" +
                                            "<a href=/ship-detail/" +
                                            secondShip.id +
                                            " class='btn btn-sm btn-primary'> " +
                                            'ship detail' + "</a>" +
                                            "<br> <br>" + "<strong>" +
                                            "Average Price : " + "</strong>" + (
                                                ship.price +
                                                secondShip
                                                .price) / 2
                                    });

                                    marker.addListener("click", () => {
                                        infowindow.open(marker.get("map"),
                                            marker);
                                    });
                                }
                            }
                            const infowindow = new google.maps.InfoWindow({
                                content: "<strong>" + 'Ship Name: ' +
                                    "</strong>" + ship.name
                                    .toString() + "<br>" +
                                    "<strong>" + 'Ship Price: ' + "</strong>" +
                                    ship.price
                                    .toString() + "<br>" +
                                    "<a href=/ship-detail/" + ship.id +
                                    " class='btn btn-sm btn-primary'> " +
                                    'ship detail' + "</a>"
                            });

                            marker.addListener("click", () => {
                                infowindow.open(marker.get("map"), marker);
                            });
                        });

                        if (ship.id != parseInt(hasMultiPrice[0]) && ship.id != parseInt(
                                hasMultiPrice[2])) {
                            const marker = new google.maps.Marker({
                                position: new google.maps.LatLng(ship.latitude, ship
                                    .longitude),
                                map,
                            });

                            const infowindow = new google.maps.InfoWindow({
                                content: "<strong>" + 'Ship Name: ' + "</strong>" +
                                    ship.name
                                    .toString() + "<br>" +
                                    "<strong>" + 'Ship Price: ' + "</strong>" + ship
                                    .price
                                    .toString() + "<br>" +
                                    "<a href=/ship-detail/" + ship.id +
                                    " class='btn btn-sm btn-primary'> " +
                                    'ship detail' + "</a>"
                            });

                            marker.addListener("click", () => {
                                infowindow.open(marker.get("map"), marker);
                            });
                        }
                        const cityCircle = new google.maps.Circle({
                            strokeColor: priceToColor(1500, 6000, ship
                                .price),
                            strokeOpacity: 0.99,
                            strokeWeight: 3,
                            fillColor: priceToColor(1500, 6000, ship.price),
                            fillOpacity: 0.20,
                            map,
                            center: new google.maps.LatLng(ship.latitude,
                                ship
                                .longitude),
                            radius: ship.radius * 0.1,
                        });
                    });
                }
            });
        }, 10000);
    </script>
</x-app-layout>
