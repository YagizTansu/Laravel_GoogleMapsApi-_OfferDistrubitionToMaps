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
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div id="map"> </div>
            </div>

            <div id="rightPanel" class="col-md-2">
            <div id="filterCard" class="card">
                <div class="card-body">
                    <h5 class="card-title">Filters</h5>
                    <hr>
                    <input class="form-check-input" type="checkbox" value="" id="subCircle">
                    <label class="form-check-label" for="flexCheckDefault">Show subcircle</label>
                    <br><br>
                    <label>Display Currency</label>
                    <select id="currencies" class="form-select form-select-sm"
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
        </div>
    </div>
</body>

</html>
<script>
    class OfferFilter{
        #subCirle;
        #currency_id;
        #city_id;

        constructor() {
            this.subCirle = getSubCircleFilter();
            this.currency_id = getCurrencyFilter();
            this.city_id = getCityFilter();
        }

        getSubCircleFilter(){
            return document.getElementById("subCircle").checked;
        }
        getCurrencyFilter(){
            return $("#currencies").val();
        }
        getCityFilter(){
            let cityId = $("#showCities").val();
            return cityId;
        }
    }

    class Circle{
        static circlesArray = [];
        static id = 0;

        constructor(centerPoint,radius, fillColor="#FF0000" ,fillOpacity=0.35) {
            this.id = Circle.id++;
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

        createMarkerOnMap(map,contentString){
            let marker = new google.maps.Marker({
                position: this.centerPoint,
                map,
            });

            const infowindow = new google.maps.InfoWindow({
                content: contentString,
            })

            marker.addListener("click", () => {
                infowindow.open({
                    anchor: marker,
                    map,
                    shouldFocus: false,
                });
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
        getCenterPoints(){
            return this.centerPoint;
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

    class Marker{
        constructor(centerPoint) {
            this.centerPoint = centerPoint;
        }

        // set cirle on given map
        createMarkerOnMap(map){
            let marker = new google.maps.Marker({
                position: this.centerPoint,
                map,
                title: "#",
            });
        }

    }

    class Filter {
        static updateFilter() {
            let subCircleFilterValue = Filter.getSubCircleFilterValue();
            let currencyFilterValue = Filter.getCurrencyFilterValue();
            loadMap(subCircleFilterValue, currencyFilterValue);
        }

        static getSubCircleFilterValue() {
            return document.getElementById("subCircle").checked;
        }

        static getCurrencyFilterValue() {
            return $("#currencies").val();
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

    class ColorMap {
        static mapping(price, price_min, price_max) {
            return (price - price_min) *100 / (price_max - price_min);
        }

        static priceToColor(min, max, price) {
            let mapped = ColorMap.mapping(price, min, max);
            let r, g, b = 0;
            if (mapped < 50) {
                g = 255;
                r = Math.round(5.1 * mapped);
            } else {
                r = 255;
                g = Math.round(510 - 5.10 * mapped);
            }
            let h = r * 0x10000 + g * 0x100 + b * 0x1;

            return '#' + ('000000' + h.toString(16)).slice(-6);
        }
    }

    class InfoWindow {
        static createContentString(offer, currencySymbols, exchangeCurrencySellingValue) {
            var contentString = "<strong>" + 'Company : '+ "</strong>" + offer.company.name  + "<br>" +
                "<strong>" + 'Offer Price: ' + "</strong>" + changeCurrency(offer.price, offer.currency.currency_exchange_rates[17].selling, exchangeCurrencySellingValue).toFixed(2).toString() + ' ' +
                currencySymbols + " " +
                "<br>" + "<a href=/offer-detail/" + offer.id + " class='btn btn-sm btn-primary'> " +
                'offer detail' + "</a>" +
                "<br> <br>";

            return contentString;
        }

        static createAveragePriceString(totalPrice, totalElement, priceArray, currencySymbols) {
            var contentString = " <strong class='text-warning' >" + " Average Price :" +
                " </strong>" + (totalPrice / totalElement).toFixed(2) + ' ' + currencySymbols + "<br>" +
                " <strong class='text-success' >" +
                " Min Price : " + " </strong>" + Math.min.apply(null, priceArray).toFixed(2) + ' ' +
                currencySymbols +
                "<br> " + " <strong class='text-danger' >" + " Max Price : " + " </strong>" + Math.max.apply(null,
                    priceArray).toFixed(2) + ' ' + currencySymbols;

            return contentString;
        }
    }

    // main function
    function loadMap(subCircleFilterValue,currencyFilterValue) {
        let hasMultiCircles = [];// save circle id which is inside in big circle

        //initilaize map
        let map = new google.maps.Map(document.getElementById("map"), {
            center: {lat: 47.5162,lng: 14.5501},
            zoom: 4.8,
        });

        Circle.circlesArray.forEach(circle1 => {
            Circle.circlesArray.forEach(circle2 => {
                let point2 =new Point(parseFloat(circle2.getLat()),parseFloat(circle2.getLng()));
                if (circle1.isInside(point2) == true && circle1.distToCenter(point2)+parseFloat(circle2.getRadius()) < parseFloat(circle1.getRadius()) && circle1.distToCenter(point2)!=0 ) { //control circle has sub circle
                    hasMultiCircles.push(circle2.id);
                }
            });
        });

        if (subCircleFilterValue) {
            Circle.circlesArray.forEach(circle => {
                circle.createCircleOnMap(map);
                circle.createMarkerOnMap(map,"offer");
            });
        }else{
            Circle.circlesArray.forEach(circle => {
                if (!hasMultiCircles.includes(circle.id)) {
                    circle.createCircleOnMap(map);
                    circle.createMarkerOnMap(map,"offer");
                }
            });
        }
    }

    //ajax api's
    async function getOffers(cityId) { // get offers from city Id
        return await $.ajax({
            url: "/api/getOffers",
            type: "GET",
            data: {
                cityId:cityId
            },
            success: function(response) {

                response['offers'].forEach(circle => {
                    let point =new Point(parseFloat(circle.latitude),parseFloat(circle.longitude));
                    let cir = new Circle(point,parseFloat(circle.radius));
                });
            }
        });
    }
    async function getCurrencies() { // get currency name and value
        return await $.ajax({
            url: "/api/getCurrency",
            type: "GET",
            success: function(response) {
                $('#currencies').empty();
                $.each(response, function(key, currency) {
                    $('#currencies').append('<option id="currency_id" value=' + currency.id + '>' + currency.name +'</option>');
                });
            }
        });
    }
    function getCountries() { // get countries
            return $.ajax({
                url: "/api/getCountries",
                type: "GET",
                success: function(response) {
                    $.each(response, function(key, country) {
                        $('#showCountries').append('<option value=' + country.id + '>' + country.name +'</option>');
                    });
                },
                error: function() {
                    swal("Oops", "Unexpected user action!", "error");
                }
            });
    }
    function getCities(countryId) { //get selected country cities
        return $.ajax({
            url: "/getCities",
            type: "GET",
            data: {
                countryId: countryId
            },
            success: function(response) {
                $('#showCities').empty();
                $('#showCities').append('<option>Select City</option>');
                $.each(response, function(key, city) {
                    $('#showCities').append('<option value=' + city.id + '>' + city.name +'</option>');
                });
            }
        });
    }
    //ajax api's end

    function changeCurrency(price, offerCurrency, exchangeCurrency) {
        return (price * offerCurrency) / exchangeCurrency;
    }

    let subCircle = document.querySelector("#subCircle");
    subCircle.addEventListener('change', function() { //control subcircle filter checkbox
        Filter.updateFilter();
    });

    let currencyValue = document.querySelector('#currencies');
    currencyValue.addEventListener('change', (event) => { //control display currency filter
        Filter.updateFilter();
    });

    let showCountries = document.getElementById("showCountries");
    showCountries.addEventListener('change', (event) => { //control country filter
        let countryId = $("#showCountries").val();
        document.getElementById("showCities").disabled = false;
        getCities(countryId);
    });

    let showCities = document.getElementById("showCities");
    showCities.addEventListener('change', (event) => { //control cities filter
        main();
    });

    async function main(){
        Circle.circlesArray = [];
        let cityId = $("#showCities").val();
        await getCurrencies();
        await getCountries();
        await getOffers(cityId);

        let subCircleFilterValue = Filter.getSubCircleFilterValue();
        let currencyFilterValue = Filter.getCurrencyFilterValue();
        loadMap(subCircleFilterValue, currencyFilterValue);
    }

</script>



<script type="text/javascript"
src="http://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyBMU4r64e98czgUSW1_V6ESAend_wpYY6Q&callback=main">
</script>