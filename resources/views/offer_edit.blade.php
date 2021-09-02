<x-app-layout>
    <x-slot name="header">Update Offer</x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('offers.update',$offer->id)}}" >
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label>Company</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value={{$offer->company->name}} readonly>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value={{$offer->city->name}} readonly >
                    </div>
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value={{$offer->latitude}}>
                    </div>

                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" value={{$offer->longitude}}>
                    </div>

                    <div class="form-group">
                        <label>Radius</label>
                        <input type="text" class="form-control" id="radius" name="radius" value={{$offer->radius}}>
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" class="form-control" id="price" name="price" value={{$offer->price}}>
                    </div>

                    <div class="form-group">
                        <label>Currency</label>
                        <select name="currency_id" id="currency_id" class="form-select form-select-sm"aria-label=".form-select-sm example">
                        </select>
                    </div>

                    <p class="text-center"><button id="addButton" class="btn btn-primary mt-2">Update</button></p>
                </form>

            </div>
        </div>
    </div>

    <script>
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

        getCurrency();


    </script>
</x-app-layout>
