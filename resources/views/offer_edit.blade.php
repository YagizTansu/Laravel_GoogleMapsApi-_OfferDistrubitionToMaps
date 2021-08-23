<x-app-layout>
    <x-slot name="header">Update Offer</x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('offer-update',$offer->id)}}" >
                    @method('PUT')
                    @csrf
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

                    <p class="text-center"><button id="addButton" class="btn btn-primary mt-2">Update</button></p>
                </form>

            </div>
        </div>
    </div>

    <x-slot name="js"> </x-slot>

</x-app-layout>
