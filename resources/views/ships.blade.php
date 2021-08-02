<x-app-layout>
    <x-slot name="header">Ships</x-slot>

    <p class="text-center fs-3">Truck Distrubition of Firm </p>
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

            <div class="col-md-10">
                <div id="map"> </div>
            </div>
            <div class="col-md-2">

                <form method="POST" action="{{ route('ships.store') }}">
                    @csrf
                    <div class="form-group">
                        <label>Truck Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Titanic"
                            value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" placeholder="34.45"
                            value="{{ old('latitude') }}">
                    </div>

                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" placeholder="54.64"
                            value="{{ old('longitude') }}">
                    </div>

                    <div class="form-group">
                        <label>Radius</label>
                        <input type="text" class="form-control" id="radius" name="radius" placeholder="3.67"
                            value="{{ old('radius') }}">
                    </div>

                    <p class="text-center"><button id="addButton" class="btn btn-primary mt-2">Add</button></p>

                </form>

            </div>
        </div>
    </div>
    <div id="map"></div>

    <script>
        function priceToColor(price) {

            var r, g, b = 0;
            if (price < 3575) {
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

            data.forEach(element => {
                const marker = new google.maps.Marker({
                    position: new google.maps.LatLng(element.latitude, element.longitude),
                    map,
                });

                const infowindow = new google.maps.InfoWindow({
                    content: element.price.toString() + ' ' ,
                });
                marker.addListener("click", () => {
                    infowindow.open(marker.get("map"), marker);
                });

                const cityCircle = new google.maps.Circle({
                    strokeColor: priceToColor(element.price),
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: priceToColor(element.price),
                    fillOpacity: 0.30,
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
