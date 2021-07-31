<x-app-layout>
    <x-slot name="header">Ships</x-slot>

    <p class="text-center fs-3">Ship Distrubition of Firm Name</p>
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div id="map"> </div>
            </div>
            <div class="col-md-2">

                <form method="POST" action="{{ route('ships.store') }}">
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

                    <p class="text-center"><button id="addButton" class="btn btn-primary mt-2">Add</button></p>

                </form>

            </div>
        </div>
    </div>
    <div id="map"> </div>


<script>
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

            const cityCircle = new google.maps.Circle({
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#FF0000",
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
