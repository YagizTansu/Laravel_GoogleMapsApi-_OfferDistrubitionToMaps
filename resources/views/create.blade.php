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

    class Cirle{

        constructor(centerPoint,radius, fillColor="#FF0000" ,fillOpacity=0.35,array) {
            this.centerPoint = centerPoint;
            this.radius = radius;
            this.fillColor = fillColor;
            this.fillOpacity = fillOpacity;

        }

        createCirleOnMap(map){
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

    //check given point inside the cirle or not
    function isInside(cirle,point) {
        let inside = false;
        let distance = dist(cirle,point);

        if(cirle.getRadius() > distance ){
            inside = true;
        }
        return inside;
    }

    // calculate distance between circle center  point to given point
    function dist(cirle,point)
    {
      let R = 6378000; // meter
      let dLat = toRad(point.getLat()-cirle.getLat());
      let dLon = toRad(point.getLng()-cirle.getLng());

      let lat1 = toRad(cirle.getLat());
      let lat2 = toRad(point.getLat());

      let a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2);
      let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
      let distance = R * c;

      return distance;
    }

    // Converts numeric degrees to radians
    function toRad(Value)
    {
        return Value * Math.PI / 180;
    }

    //calculate distance between given point to circle boundry if point is not inside circle
    function calcDistance(cirle,point) {
        let distance = dist(cirle,point);
        if(isInside(cirle,point) == false){
            distance = distance - cirle.getRadius();
        }
        return distance;
    }





    function loadMap() {
        let  map = new google.maps.Map(document.getElementById("map"), {
            center: {lat: 47.5162,lng: 14.5501},
            zoom: 4.8,
        });

        let point = new Point(49.75,14);
        let cirle = new Cirle(new Point(47,14),300000);

        new google.maps.Marker({
            position: point,
            map,
        });

        cirle.createCirleOnMap(map);

        alert(calcDistance(cirle,point));
        alert(isInside(cirle,point));



    }

</script>



<script type="text/javascript"
src="http://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyBMU4r64e98czgUSW1_V6ESAend_wpYY6Q&callback=loadMap">
</script>
