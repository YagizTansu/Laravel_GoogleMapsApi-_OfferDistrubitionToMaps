<x-app-layout>
    <x-slot name="header">Update Ships</x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('ships.update',$ship->id)}}" >
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label>Ship Name</label>
                        <input type="text" class="form-control" id="name" name="name" value={{$ship->name}}>
                    </div>

                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value={{$ship->latitude}}>
                    </div>

                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" value={{$ship->longitude}}>
                    </div>

                    <div class="form-group">
                        <label>Radius</label>
                        <input type="text" class="form-control" id="radius" name="radius" value={{$ship->radius}}>
                    </div>

                    <p class="text-center"><button id="addButton" class="btn btn-primary mt-2">Update</button></p>

                </form>

            </div>
        </div>
    </div>

    <x-slot name="js"> </x-slot>

</x-app-layout>
