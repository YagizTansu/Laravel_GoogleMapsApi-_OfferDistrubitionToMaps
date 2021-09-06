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

// ADD CIRCLE PART
document.getElementById("addCirleMode").addEventListener("click", function() {

    //clean unexpected forms
    $("#formCard").remove();
    $("#filterCard").remove();
    $('#floating-panel').empty();

    $('#floating-panel').append('<h5 class="text-success">Add Circle Mode</h5>');
    $('#floating-panel').append('<select id="showCountries" class="form-select form-select-sm"aria-label=".form-select-sm example"><option>Select Country</option></select> <br>');
    $('#floating-panel').append('<select id="showCities" class="form-select form-select-sm" aria-label=".form-select-sm example" disabled></select> <br>');
    $('#floating-panel').append('<input type="number" class="form-control" id="offerPrice" name="offerPrice" placeholder="Enter Price ($)"></input> <br>');
    $('#floating-panel').append('<select name="currency_id" id="currency_id" class="form-select form-select-sm"aria-label=".form-select-sm example"></select> <br>');

    //buttons
    $('#floating-panel').append('<a id="saveCircleButton" class="btn btn-primary mr-2">Save</a>');
    $('#floating-panel').append('<a id="delete-markers" class="btn btn-danger  mr-2"> Delete Markers</a>');
    $('#floating-panel').append('<a href="/offers" class="btn btn-warning mr-2">Exit</a>');

    document.getElementById("delete-markers").addEventListener("click", function() {
        AddCircleMode();
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
                    cityCircle.set('radius', (firstX * 100) + ((event.domEvent.clientX) * 1000) - ((event.domEvent.clientY - firstY) * 1000));
                }
            });
        }

        if (clicked != 1) {

            getCurrency();
            getCountries();

            let showCountriesForAdd = document.getElementById("showCountries");
            showCountriesForAdd.addEventListener('change', (event) => { //control country filter
                let  country_id = $("#showCountries").val();
                document.getElementById("showCities").disabled = false;
                getCities(country_id);
            });
            document.getElementById("saveCircleButton").addEventListener("click", function() {

                let city_id = $("#showCities").val();
                let currencyId = $("#currency_id").val();
                let offerPrice = $("#offerPrice").val();

                $.ajax({
                    url: "/addOffer",
                    type: "GET",
                    data: {
                        currencyId :currencyId,
                        offerPrice: offerPrice,
                        cityId: city_id,
                        radius: cityCircle.getRadius(),
                        latitude: cityCircle.getCenter().lat(),
                        longitude: cityCircle.getCenter().lng()
                    },
                    success: function(response) {
                        swal("Offer added succesfully!","","success",
                        {
                           buttons: {
                             stay: "Stay",
                             turn: "Turn to Offer Page",
                           },
                         })
                         .then((value) => {
                           switch (value) {

                             case "stay":
                               window.location.href = "#";
                               break;

                             case "turn":
                               window.location.href = "http://localhost:8000/offers";
                               break;
                           }
                       });
                    },
                    error: function () {
                        swal("Something wrong", "please check offer info!", "error")
                    }
                });
                clicked = 0;
            });
        }
    });
}
