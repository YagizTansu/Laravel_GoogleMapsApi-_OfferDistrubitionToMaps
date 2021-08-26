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
                        <input type="text" class="form-control" id="latitude" name="latitude" value={{$offer->company->name}}>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value={{$offer->city->name}}>
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
                        <input type="text" class="form-control" id="currency" name="currency" value={{$offer->currency->name}}>
                    </div>

                    <p class="text-center"><button id="addButton" class="btn btn-primary mt-2">Update</button></p>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
