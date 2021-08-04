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

                            <?php
                            foreach ($currency as $value) {
                            ?>
                            <option value="<?php echo $value['id']; ?>"> <?php echo $value['name']; ?> </option>
                            <?php
                            }
                            ?>

                        </select>
                    </div>

                    <p class="text-center"><button id="addButton" class="btn btn-primary mt-2">Add</button></p>

                </form>

            </div>
        </div>
    </div>
    <div id="map"></div>

    <script>
        function map(price, price_min, price_max) {
            return (price - price_min) * (100 - 0) / (price_max - price_min) + 0;
        }

        function priceToColor(min, max, price) {
            var mapped = map(price, min, max);
            var r, g, b = 0;
            if (mapped < 50) {
                g = 255;
                r = Math.round(5.1 * mapped);
            } else {
                r = 255;
                g = Math.round(510 - 5.10 * mapped);
            }
            var h = r * 0x10000 + g * 0x100 + b * 0x1;

            //alert(mapped);
            return '#' + ('000000' + h.toString(16)).slice(-6);

        }

        function initMap() {
            // Create the map.
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 4.8,
                center: {
                    lat: 47.5162,
                    lng: 14.5501
                },
                mapTypeId: "terrain",
            });
            var data = {!! json_encode($ships) !!};
            var priceMax = {!! json_encode($priceMax) !!};
            var priceMin = {!! json_encode($priceMin) !!};


            data.forEach(element => {
                const marker = new google.maps.Marker({
                    position: new google.maps.LatLng(element.latitude, element.longitude),
                    map,
                });

                const infowindow = new google.maps.InfoWindow({

                    content: "<strong>" + 'Ship Name: ' + "</strong>" + element.name.toString() + "<br>" +
                             "<strong>" + 'Ship Price: ' + "</strong>" + element.price.toString() + "<br>" +
                             "<a href=/ship-detail/"+element.id+" class='btn btn-sm btn-primary'> "+'ship detail' + "</a>",
                });
                marker.addListener("click", () => {
                    infowindow.open(marker.get("map"), marker);
                });

                const cityCircle = new google.maps.Circle({
                    strokeColor: priceToColor(priceMin, priceMax, element.price),
                    strokeOpacity: 0.99,
                    strokeWeight: 3,
                    fillColor: priceToColor(priceMin, priceMax, element.price),
                    fillOpacity: 0.20,
                    map,
                    center: new google.maps.LatLng(element.latitude, element.longitude),
                    radius: element.radius * 0.1,
                });
            });
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMU4r64e98czgUSW1_V6ESAend_wpYY6Q&callback=initMap&libraries=&v=weekly"
        async>
    </script>

    <x-slot name="js"></x-slot>
</x-app-layout>
