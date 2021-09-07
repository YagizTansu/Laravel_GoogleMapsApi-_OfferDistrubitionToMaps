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
        <div id="map"> </div>
</body>

</html>
<script>

    class Circle{
        static circlesArray = [];

        constructor(centerPoint,radius, fillColor="#FF0000" ,fillOpacity=0.35) {
            this.centerPoint = centerPoint;
            this.radius = radius;
            this.fillColor = fillColor;
            this.fillOpacity = fillOpacity;

            Circle.circlesArray.push(this);
        }

        // set cirle on given map
        createCircleOnMap(map){
            let cityCircle = new google.maps.Circle({
                strokeColor: this.fillColor,
                strokeOpacity: 0.99,
                strokeWeight: 3,
                fillColor: this.fillColor,
                fillOpacity: this.fillOpacity,
                map,
                center: this.centerPoint,
                radius: this.radius,
            });
        }

        //check given point inside the circle or not
        isInside(point) {
            let inside = false;
            let distance = this.#dist(point);

            if(this.radius > distance ){
                inside = true;
            }
            return inside;
        }

        //calculate distance between given point to circle boundry
        distToBoundry(point){
            let distance = this.#dist(point) - this.radius;

            if (distance < 0) {
                distance = 0;
            }
            return distance;
        }

        //calculate distance between given point to circle center
        distToCenter(point) {
            let distance = this.#dist(point);
            return distance;
        }

        // private function calculate distance between given point to circle center
        #dist(point){
            let R = 6378000; // meter
            let dLat = (point.getLat()-this.getLat())* Math.PI / 180;
            let dLon = (point.getLng()-this.getLng())* Math.PI / 180;

            let lat1 = (this.getLat())* Math.PI / 180;
            let lat2 = (point.getLat())* Math.PI / 180;

            let a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2);
            let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            let distance = R * c;

            return distance;
        }

        //getters
        getRadius(){
            return this.radius;
        }
        getLat(){
            return this.centerPoint.getLat();
        }
        getLng(){
            return this.centerPoint.getLng();
        }
    }

    // lat lng object
    class Point{
        constructor(lat,lng) {
            this.lat = lat;
            this.lng = lng;
        }

        getLat(){
            return this.lat;
        }
        getLng(){
            return this.lng;
        }

    }


    // main function
    async function loadMap() {

        //initilaize map
        let  map = new google.maps.Map(document.getElementById("map"), {
            center: {lat: 47.5162,lng: 14.5501},
            zoom: 4.8,
        });

        let circles = await getOffers(); // get offers from db


        /*circles.forEach(circle => {
            //debugger
            let point =new Point(parseFloat(circle.latitude),parseFloat(circle.longitude));
            let cir = new Circle(point,parseFloat(circle.radius));
            cir.createCircleOnMap(map);
        });*/


        circle = new Circle(new Point(47.30498,14),300000);
        circle.createCircleOnMap(map);


        let point = new Point(50,14);
        new google.maps.Marker({position: point,map});

        alert(circle.distToBoundry(point));
        alert(circle.isInside(point));

    }



    async function getOffers() {
        return await $.ajax({
            url: "/api/getOffers",
            type: "GET",
            data: {
                cityId: 1001
            },
            success: function(response) {
            }
        });
      }

</script>



<script type="text/javascript"
src="http://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyBMU4r64e98czgUSW1_V6ESAend_wpYY6Q&callback=loadMap">
</script>
