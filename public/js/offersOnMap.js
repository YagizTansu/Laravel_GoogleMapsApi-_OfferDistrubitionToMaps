let hasMultiPrice = [];

async function markersAndCircles(map, response, subCircleFilterValue, currencyFilterValue = 1, currencySymbol = '$') {

    map = Create.createMap(map); // create main map

    //get display symbol
    let symbol = await Filter.getCurrencySymbol(currencyFilterValue);
    currencySymbol = symbol[0].symbol;

    //get currency with todays Rate
    let exchangeCurrencySellingValue = await getCurrencySellingValue(currencyFilterValue);
    let exchangeSellingValue = exchangeCurrencySellingValue.selling;

    $.each(response['offers'], function(key, offer) {
        let contentString = InfoWindow.createContentString(offer, currencySymbol,exchangeSellingValue); // create String for offer info window
        let totalElement = 1;

        let offerPirce = changeCurrency(offer.price, offer.currency.currency_exchange_rates[9].selling,exchangeSellingValue);
        //let secondOfferownCurrencySellingValue = await getCurrencySellingValue(currencyFilterValue);

        let totalPrice = offerPirce;
        let priceArray = [offerPirce];

        $.each(response['offers'], function(key, secondOffer) {
            let distance = calcDistance(offer.latitude, offer.longitude, secondOffer.latitude,secondOffer.longitude); // take distance between 2 offers
            let sum = parseInt(distance) + parseInt(secondOffer.radius);

            if (sum < offer.radius && distance != 0 && distance < offer.radius) { // control block: if small circle included  big cicle

                if (secondOffer.price != null) {
                    totalElement++;
                }

                hasMultiPrice.push(offer.id);
                hasMultiPrice.push(secondOffer.id);
                priceArray.push(changeCurrency(secondOffer.price, secondOffer.currency.currency_exchange_rates[9].selling, exchangeSellingValue));

                var marker = Create.createMarker(map, offer,marker); // create Markers if coordinate has multi prices
                totalPrice += changeCurrency(secondOffer.price, secondOffer.currency.currency_exchange_rates[9].selling, exchangeSellingValue);
                contentString += InfoWindow.createContentString(secondOffer, currencySymbol,exchangeSellingValue);

                marker.addListener("click", () => {
                    infowindow.open(marker.get("map"), marker);
                });

                Create.createCirle(map, response, offer, 0.99, 3, 0.2);
            }
        });

        const infowindow = new google.maps.InfoWindow({ // max min average value for each multi cirle info window
            content: contentString + InfoWindow.createAveragePriceString(totalPrice,totalElement, priceArray, currencySymbol)
        });

        if (subCircleFilterValue == true) {
            Create.createCirle(map, response, offer, 0.99, 3, 0.2);
        }

    });

    $.each(response['offers'], function(key, offer) {
        if (hasMultiPrice.length == 0) {
            let marker = Create.createMarker(map, offer, marker);
            let contentString = contentString = InfoWindow.createContentString(offer, currencySymbol,exchangeSellingValue);

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
                        var contentString = contentString = InfoWindow.createContentString(offer,currencySymbol, exchangeSellingValue);

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
        var contentString = "<strong>" + 'Company : '+ "</strong>" + offer.company.name  + "<br>" +
            "<strong>" + 'Offer Price: ' + "</strong>" + changeCurrency(offer.price, offer.currency.currency_exchange_rates[9].selling, exchangeCurrencySellingValue).toFixed(2).toString() + ' ' +
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
        let subCircleFilterValue = Filter.getSubCircleFilterValue();
        let currencyFilterValue = Filter.getCurrencyFilterValue();
        loadMap(map, subCircleFilterValue, currencyFilterValue);
    }

    static getSubCircleFilterValue() {
        return document.getElementById("subCircle").checked;
    }

    static getCurrencyFilterValue() {
        return $("#showCurrency").val();

    }

    static getCurrencySymbol(currencyId) {
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

function calcDistance(fromLat, fromLng, toLat, toLng) {
    return google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(fromLat, fromLng),new google.maps.LatLng(toLat, toLng));
}

function changeCurrency(price, offerCurrency, exchangeCurrency) {
    return (price * offerCurrency) / exchangeCurrency;
}

async function getCurrencySellingValue(currencyId) {

    let selling = await $.ajax({
        url: "/api/getCurrencySellingValue",
        type: "GET",
        data: {
            currencyId: currencyId
        },
        success: function(response) {}
    });
    return selling;
}


async function loadMap(map, subCircleFilterValue, currencyFilterValue) { // Load all maps proporties

    let api_token = document.getElementById("api-token").getAttribute('content');
    let cityId = $("#showCities").val();

    $.ajaxSetup({
        headers: {"api-token":api_token}
    });

    await getOffers(map,cityId,subCircleFilterValue,currencyFilterValue);
    getCountries();
}
getDisplayExchangeRates();

async function getOffers(map,cityId,subCircleFilterValue,currencyFilterValue) {
    await $.ajax({
        url: "/api/getOffers",
        type: "GET",
        data: {
            cityId: cityId
        },
        success: function(response) {
            markersAndCircles(map, response, subCircleFilterValue, currencyFilterValue);
        },
        error: function() {
            swal("Oops", "Unexpected user action!", "error");
        }
    });
}

function getDisplayExchangeRates() {
    return $.ajax({
        url: "/getExchangeRate",
        type: "GET",
        success: function(response) {
            $('#showCurrency').empty();
            $.each(response['CurrencyExchangeRate'], function(key, exchange) {
                $('#showCurrency').append('<option value=' + exchange.currency.id + '> ' +exchange.currency.name + '</option>');
            });
        }
    });
}


function getCountries() {
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

function getCities(countryId) {
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

function getCurrency() {
    return $.ajax({
        url: "/api/getCurrency",
        type: "GET",
        success: function(response) {
            $('#currency_id').empty();
            $.each(response, function(key, currency) {
                $('#currency_id').append('<option id="currency_id" value=' + currency.id + '>' + currency.name +'</option>');
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
    loadMap();
});


