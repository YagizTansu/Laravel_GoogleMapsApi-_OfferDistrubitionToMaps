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

                <form method="POST" action="{{ route('ship-add') }}">
                    @csrf
                    <div class="form-group">
                        <label>Ship Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="ship name">
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
                        <input type="text" class="form-control" id="radius" name="radius" placeholder="3.67">
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" class="form-control" id="price" name="price" placeholder="1000">
                    </div>

                    <div class="form-group">
                        <label>Currency</label>
                        <select class="form-select form-select-sm" name="currency_id"
                            aria-label=".form-select-sm example">

                            <option value="1">Dollar</option>
                            <option value="2">Euro</option>
                            <option value="3">Pound</option>
                            <option value="4">TL</option>
                        </select>
                    </div>

                    <p class="text-center"><button id="addButton" class="btn btn-primary mt-2">Add</button></p>

                </form>

            </div>
        </div>
    </div>
    <div id="map"></div>

    <script>
        function priceToColor(min, max, price) {

            var r, g, b = 0;
            if (price < ((min + max) / 2)) {
                r = 255;
                g = Math.round(5.1 * price);
            } else {
                g = 255;
                r = Math.round(510 - 5.10 * price);
            }
            var h = r * 0x10000 + g * 0x100 + b * 0x1;
            return '#' + ('000000' + h.toString(16)).slice(-6);
        }

        function initMap() {
            // Create the map.
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 3,
                center: {
                    lat: 40,
                    lng: 40
                },
                mapTypeId: "terrain",
            });
            var data = {!! json_encode($ships, JSON_HEX_TAG) !!};
            var priceMax = {!! json_encode($priceMax, JSON_HEX_TAG) !!};
            var priceMin = {!! json_encode($priceMin, JSON_HEX_TAG) !!};


            data.forEach(element => {
                const marker = new google.maps.Marker({
                    position: new google.maps.LatLng(element.latitude, element.longitude),
                    map,
                });

                const infowindow = new google.maps.InfoWindow({
                    content: element.price.toString() + ' ',
                });
                marker.addListener("click", () => {
                    infowindow.open(marker.get("map"), marker);
                });

                const cityCircle = new google.maps.Circle({
                    strokeColor: priceToColor(priceMax,priceMin,element.price),
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: priceToColor(priceMax,priceMin,element.price),
                    fillOpacity: 0.50,
                    map,
                    center: new google.maps.LatLng(element.latitude, element.longitude),
                    radius: element.radius * 0.05,
                });
            });

            var latitude;
            var longitude;
            var radius;

        }
    </script>



    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMU4r64e98czgUSW1_V6ESAend_wpYY6Q&callback=initMap&libraries=&v=weekly"
        async>
    </script>

    <x-slot name="js"></x-slot>

</x-app-layout>
