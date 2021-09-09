
class OfferFilter{
    _subCircle = null;
    _currency_id = null;
    _city_id = null;

    constructor() {
        this._subCircle = this._getSubCircleFilter();
        this._currency_id = this._getCurrencyFilter();
        this._city_id = this._getCityFilter();
    }

    _getSubCircleFilter(){
        return document.getElementById("subCircle").checked;
    }
    _getCurrencyFilter(){
        return $("#currencies").val();
    }
    _getCityFilter(){
        return $("#showCities").val();
    }

    getSubCircle(){
        return this._subCircle;
    }
    getCurrencyId(){
        return this._currency_id;
    }
    getcityId(){
        return this._city_id;
    }


}

class Circle{
    static circlesArray = [];
    static id = 0;

    constructor(centerPoint,radius, fillColor="#FF0000" ,fillOpacity=0.35,offer) {
        this.id = Circle.id++;
        this.centerPoint = centerPoint;
        this.radius = radius;
        this.fillColor = fillColor;
        this.fillOpacity = fillOpacity;
        this.offer = offer;

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

    //set marker for circle
    createMarkerOnMap(map){
        let marker = new google.maps.Marker({
            position: this.centerPoint,
            map,
        });

        const infowindow = new google.maps.InfoWindow({
            content: InfoWindow.createContentString(this.offer),
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
        let distance = this._dist(point);

        if(this.radius > distance ){
            inside = true;
        }
        return inside;
    }

    //calculate distance between given point to circle boundry
    distToBoundry(point){
        let distance = this._dist(point) - this.radius;

        if (distance < 0) {
            distance = 0;
        }
        return distance;
    }

    //calculate distance between given point to circle center
    distToCenter(point) {
        let distance = this._dist(point);
        return distance;
    }

    // private function calculate distance between given point to circle center
    _dist(point){
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
    static _mapping(price, price_min, price_max) {
        return (price - price_min) *100 / (price_max - price_min);
    }

    static priceToColor(min, max, price) {
        let mapped = ColorMap._mapping(price, min, max);
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
    static createContentString(offer) {
        var contentString = "<strong>" + 'Company : '+ "</strong>" + offer.company.name  + "<br>" +
            "<strong>" + 'Offer Price: ' + "</strong>" + offer.desired_price.toFixed(2) + ' ' +
            offer.desired_currency_symbol + " " +
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
function loadMap(subCircleFilterValue) {
    let hasMultiCircles = [];// save circle id which is inside in big circle

    //initilaize map
    let map = new google.maps.Map(document.getElementById("map"), {
        center: {lat: 47.5162,lng: 14.5501},
        zoom: 4.8,
    });

    //control circle has subcircles then write subcircle id to hasMultiCirle array
    Circle.circlesArray.forEach(circle1 => {
        Circle.circlesArray.forEach(circle2 => {
            let point2 =new Point(parseFloat(circle2.getLat()),parseFloat(circle2.getLng()));
            if (circle1.isInside(point2) == true && circle1.distToCenter(point2)+parseFloat(circle2.getRadius()) < parseFloat(circle1.getRadius()) && circle1.distToCenter(point2)!=0 ) { //control circle has sub circle
                hasMultiCircles.push(circle2.id);
            }
        });
    });

    //check subcircle filter if true put all circles else put circles without subcirles
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

function offersAjax(offerFilter) { // apis get offer related choosen cityId
    let cityId = offerFilter.getcityId();
    let currencyId = offerFilter.getCurrencyId();

    return  $.ajax({
        url: "/api/offerAjax",
        type: "GET",
        data: {
            cityId: cityId,
            currencyId:currencyId
        },
        success: function(response) {
            response['offers'].forEach(offer => {
                let point =new Point(parseFloat(offer.latitude),parseFloat(offer.longitude));
                let circle = new Circle(point,parseFloat(offer.radius),ColorMap.priceToColor(response['priceMin'],response['priceMax'],offer.price),0.30,offer);
            });
        },
        error: function() {
            swal("Oops", "Unexpected user action!", "error");
        }
    });
}

async function loadOffers(offerFilter){ // running after click search button
    let api_token = document.getElementById("api-token").getAttribute('content');
    $.ajaxSetup({
        headers: {"api-token":api_token}
    });

    Circle.circlesArray = [];
    await offersAjax(offerFilter);
    loadMap(offerFilter.getSubCircle());
}

async function main() { // first running function when google maps api initialize
    await getCurrencies();
    await getCountries();
    loadMap();
}

//ajax api's
function getCurrencies() { // get currency name and value
    return  $.ajax({
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
async function getCities(countryId) { //get selected country cities
    return await $.ajax({
        url: "/api/getCities",
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

//event Listener -> search button
let searchButton = document.querySelector("#searchButton");
searchButton.addEventListener('click', function() { //control subcircle filter checkbox
    let offerFilter = new OfferFilter();
    loadOffers(offerFilter);
});


//event Listener ->country input
let showCountries = document.getElementById("showCountries");
showCountries.addEventListener('change', (event) => { //control country filter
    let countryId = $("#showCountries").val();
    document.getElementById("showCities").disabled = false;
    getCities(countryId);
});
