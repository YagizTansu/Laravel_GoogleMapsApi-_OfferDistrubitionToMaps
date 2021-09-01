var hasMultiPrice = [];

async function markersAndCircles(map, response, subCircleFilterValue, currencyFilterValue, currencySymbol = '$') {

    map = Create.createMap(map); // create main map

    if (currencyFilterValue == null) {
        currencyFilterValue = 1;
    }

    //display symbol
    let symbol = await Filter.currencySymbols(currencyFilterValue);
    currencySymbol = symbol[0].symbol;

    //display currency today Rate
    let exchangeCurrencySellingValue = await getCurrencySellingValue(currencyFilterValue);
    var exchangeSellingValue = exchangeCurrencySellingValue.selling;

    $.each(response['offers'], function(key, offer) {
        var contentString = InfoWindow.createContentString(offer, currencySymbol,
        exchangeSellingValue); // create String for ship info window
        var totalElement = 1;

        var offerPirce = changeCurrency(offer.price, offer.currency.currency_exchange_rates[9].selling,
            exchangeSellingValue);
        //var ownCurrencySellingValue = await getCurrencySellingValue(currencyFilterValue);

        var totalPrice = offerPirce;
        var priceArray = [offerPirce];

        $.each(response['offers'], function(key, secondOffer) {
            var distance = calcDistance(offer.latitude, offer.longitude, secondOffer.latitude,
                secondOffer.longitude); // take distance between 2 offers
            var sum = parseInt(distance) + parseInt(secondOffer.radius);

            if (sum < offer.radius && distance != 0 && distance < offer
                .radius) { // control block: if small circle included  big cicle

                if (secondOffer.price != null) {
                    totalElement++;
                }

                hasMultiPrice.push(offer.id);
                hasMultiPrice.push(secondOffer.id);
                priceArray.push(changeCurrency(secondOffer.price, secondOffer.currency
                    .currency_exchange_rates[9].selling, exchangeSellingValue));

                var marker = Create.createMarker(map, offer,
                marker); // create Markers if coordinate has multi prices
                totalPrice += changeCurrency(secondOffer.price, secondOffer.currency
                    .currency_exchange_rates[9].selling, exchangeSellingValue);
                contentString += InfoWindow.createContentString(secondOffer, currencySymbol,
                    exchangeSellingValue);

                marker.addListener("click", () => {
                    infowindow.open(marker.get("map"), marker);
                });

                Create.createCirle(map, response, offer, 0.99, 3, 0.2);
            }
        });

        const infowindow = new google.maps
    .InfoWindow({ // max min average value for each multi cirle info window
            content: contentString + InfoWindow.createAveragePriceString(totalPrice,
                totalElement, priceArray, currencySymbol)
        });

        if (subCircleFilterValue == true) {
            Create.createCirle(map, response, offer, 0.99, 3, 0.2);
        }

    });

    $.each(response['offers'], function(key, offer) {
        if (hasMultiPrice.length == 0) {
            var marker = Create.createMarker(map, offer, marker);
            var contentString = contentString = InfoWindow.createContentString(offer, currencySymbol,
                exchangeSellingValue);

            const infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            marker.addListener("click", () => {
                infowindow.open(marker.get("map"), marker);
            });
            Create.createCirle(map, response, offer, 0.99, 3, 0.2);
        }
        else {
            let j = 0;
            for (let i = 0; i < hasMultiPrice.length; i++) {
                if (offer.id != parseInt(hasMultiPrice[i])) {
                    j = j + 1;
                    if (j == (hasMultiPrice.length)) {
                        var marker = Create.createMarker(map, offer, marker);
                        var contentString = contentString = InfoWindow.createContentString(offer,
                            currencySymbol, exchangeSellingValue);

                        const infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });

                        marker.addListener("click", () => {
                            infowindow.open(marker.get("map"), marker);
                        });

                        Create.createCirle(map, response, offer, 0.99, 3, 0.2);
                    }
                }
            }
        }
    });
}

class InfoWindow {
    static createContentString(offer, currencySymbols, exchangeCurrencySellingValue) {
        var contentString = "<strong>" + 'Company id: ' + offer.company_id + "</strong>" + "<br>" +
            "<strong>" + 'Offer Price: ' + "</strong>" + changeCurrency(offer.price, offer.currency
                .currency_exchange_rates[9].selling, exchangeCurrencySellingValue).toFixed(2).toString() + ' ' +
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

class Filter {
    static updateFilter() {
        var subCircleFilterValue = Filter.getSubCircleFilterValue();
        var currencyFilterValue = Filter.getCurrencyFilterValue();
        loadMap(map, subCircleFilterValue, currencyFilterValue);
    }

    static getSubCircleFilterValue() {
        return document.getElementById("subCircle").checked;
    }

    static getCurrencyFilterValue() {
        return $("#showCurrency").val();

    }

    static currencySymbols(currencyId) {
        return $.ajax({
            url: "/api/getCurrencySymbol",
            type: "GET",
            data: {
                currencyId: currencyId
            },
            success: function(response) {
            }
        });
    }
}

class Create {
    static createMap() { //create only map

        var map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: 47.5162,
                lng: 14.5501
            },
            zoom: 4.8,
        });
        return map;
    }

    static createCirle(map, response, offer, strokeOpacity, strokeWeight, fillOpacity) {
        const cityCircle = new google.maps.Circle({
            strokeColor: ColorMap.priceToColor(response['priceMin'], response['priceMax'], offer.price),
            strokeOpacity: strokeOpacity,
            strokeWeight: strokeWeight,
            fillColor: ColorMap.priceToColor(response['priceMin'], response['priceMax'], offer.price),
            fillOpacity: fillOpacity,
            map,
            center: new google.maps.LatLng(offer.latitude, offer.longitude),
            radius: offer.radius * 1,
        });
    }

    static createMarker(map, offer, marker) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(offer.latitude, offer.longitude),
            map,
        });
        return marker;
    }
}

class ColorMap {
    static mapping(price, price_min, price_max) {
        return (price - price_min) * (100 - 0) / (price_max - price_min);
    }

    static priceToColor(min, max, price) {
        var mapped = ColorMap.mapping(price, min, max);
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
}

function calcDistance(fromLat, fromLng, toLat, toLng) {
    return google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(fromLat, fromLng),
        new google.maps.LatLng(toLat, toLng));
}

function changeCurrency(price, currency, exchangeCurrency) {
    return (price * currency) / exchangeCurrency;
}

async function getCurrencySellingValue(currencyId) {

    var selling = await $.ajax({
        url: "/api/getCurrencySellingValue",
        type: "GET",
        data: {
            currencyId: currencyId
        },
        success: function(response) {}
    });
    return selling;
}

function loadMap(map, subCircleFilterValue, currencyFilterValue) { // Load all maps proporties

    var api_token = document.getElementById("api-token").getAttribute('content');

    $.ajaxSetup({
        headers: {"api-token":api_token}
    });

    var cityId = $("#showCities").val();
    $.ajax({
        url: "/api/getOffers",
        type: "GET",
        data: {
            cityId: cityId
        },
        success: function(response) {
            printCurrency(response);
            var subCircleFilterValue = Filter.getSubCircleFilterValue();
            markersAndCircles(map, response, subCircleFilterValue, currencyFilterValue);
        },
        error: function() {
            swal("Oops", "Unexpected user action!", "error");
        }
    });
    getCountries();
}
getDisplayExchangeRates();

function printCurrency(response) {
    $('#currency').empty();
    $.each(response['currency'], function(key, currency) {
        $('#currency').append('<option value=' + currency.id + '>' + currency.name + '</option>');
    });
}

function getDisplayExchangeRates() {
    $.ajax({
        url: "/getExchangeRate",
        type: "GET",
        success: function(response) {
            $('#showCurrency').empty();
            $.each(response['CurrencyExchangeRate'], function(key, exchange) {
                $('#showCurrency').append('<option value=' + exchange.currency.id + '> ' +
                    exchange.currency.name + '</option>');
            });
        }
    });
}


function getCountries() {
    $.ajax({
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

function getCities(countryId) {
    return $.ajax({
        url: "/getCities",
        type: "GET",
        data: {
            countryId: countryId
        },
        success: function(response) {
            $('#showCities').empty();
            $('#showCities').append('<option >Select City</option>');
            $.each(response, function(key, city) {
                $('#showCities').append('<option value=' + city.id + '>' + city.name +'</option>');
            });
        }
    });
  }

const subCircle = document.querySelector("#subCircle");
subCircle.addEventListener('change', function() { //control subcircle filter checkbox
    Filter.updateFilter();
});

const currencyValue = document.querySelector('#showCurrency');
currencyValue.addEventListener('change', (event) => { //control display currency filter
    Filter.updateFilter();
});


var showCountries = document.getElementById("showCountries");
showCountries.addEventListener('change', (event) => { //control country filter
    var countryId = $("#showCountries").val();
    document.getElementById("showCities").disabled = false;
    getCities(countryId);
});

var showCities = document.getElementById("showCities");
showCities.addEventListener('change', (event) => { //control cities filter
    document.getElementById("showCities").disabled = false;
    loadMap();
});


// ADD CIRCLE PART
$("#addCirleMode").click(function() {

    //clean unexpected forms
    $("#formCard").remove();
    $("#filterCard").remove();
    $('#floating-panel').empty();

    $('#floating-panel').append('<h5 class="text-success">Add Circle Mode</h5>');
    $('#floating-panel').append('<select id="showCountries" class="form-select form-select-sm"aria-label=".form-select-sm example"><option>Select Country</option></select> <br>');
    $('#floating-panel').append('<select id="showCities" class="form-select form-select-sm" aria-label=".form-select-sm example"></select> <br>');
    $('#floating-panel').append('<input type="number" class="form-control" id="offerPrice" name="offerPrice" placeholder="Enter Price"></input> <br>');

    //buttons
    $('#floating-panel').append('<a href="/offers" id="saveCircleButton" class="btn btn-primary mr-2">Save</a>');
    $('#floating-panel').append('<a id="delete-markers" class="btn btn-danger  mr-2"> Delete Markers</a>');
    $('#floating-panel').append('<a href="/offers" class="btn btn-warning mr-2">Exit</a>');

    $("#delete-markers").click(function() {
        AddCircleMode(map);
    });

    AddCircleMode();
});

function AddCircleMode() {
    var map = Create.createMap(map);

    const myLatlng = {
        lat: 47.5162,
        lng: 14.5501
    };

    // Create the initial InfoWindow.
    let infoWindow = new google.maps.InfoWindow({
        content: "Click the map to add Circle",
        position: myLatlng,
    });
    infoWindow.open(map);


    var clicked = 0;
    var cityCircle;
    // Configure the click listener.
    map.addListener("click", (event) => {
        clicked++;

        if (clicked == 1) {
            //create cirle on map with click event
            cityCircle = new google.maps.Circle({
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#FF0000",
                fillOpacity: 0.35,
                map,
                draggable: true,
                center: event.latLng,
                radius: 0,
            });

            //control circle radius with mouse event
            var firstX = 0;
            var firstY = 0;
            google.maps.event.addListener(map, "mousemove", function(event) {
                if (clicked == 1) {
                    if (firstX == 0) {
                        firstX = event.domEvent.clientX;
                        firstY = event.domEvent.clientY;
                    }
                    cityCircle.set('radius', (firstX * 100) + ((event.domEvent.clientX) * 1000) - ((
                        event.domEvent.clientY - firstY) * 1000));
                }
            });
        }

        if (clicked != 1) {

            getCountries();
            let showCountriesForAdd = document.getElementById("showCountries");
            showCountriesForAdd.addEventListener('change', (event) => { //control country filter
                let  country_id = $("#showCountries").val();
                //document.getElementById("showCities").disabled = false;
                getCities(country_id);
            });

            document.getElementById("saveCircleButton").addEventListener("click", function() {
                let city_id = $("#showCities").val();
                let offerPrice = $("#offerPrice").val();
                debugger

                $.ajax({
                    url: "/addOffer",
                    type: "GET",
                    data: {
                        offerPrice: offerPrice,
                        cityId: city_id,
                        radius: cityCircle.getRadius(),
                        latitude: cityCircle.getCenter().lat(),
                        longitude: cityCircle.getCenter().lng()
                    },
                    success: function(response) {
                    }
                });
                clicked = 0;
            });
        }
    });
}
