<x-app-layout>
    <x-slot name="header">Detail</x-slot>

    <p class="text-center fs-3">Ship Detail</p>
    <div class="container">
        <div class="row">
                <form method="POST" action="{{ route('ship-add') }}">
                    @csrf
                    <div class="form-group">
                        <label>Ship Name</label>
                        <input type="text" class="form-control" id="name" name="name"  value="{{ $ship->name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value="{{ $ship->latitude }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" value="{{ $ship->longitude }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Radius</label>
                        <input type="text" class="form-control" id="radius" name="radius" value="{{ $ship->radius }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" class="form-control" id="price" name="price" value="{{ $ship->price }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Currency</label>
                        <input type="text" class="form-control" id="price" name="price" value="{{ $ship->currency->name }}" readonly>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-slot name="js"></x-slot>

</x-app-layout>
