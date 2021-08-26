<x-app-layout>
    <x-slot name="header">Create Ships</x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach ($errors->all() as $error)
                            <strong>
                                <li>{{ $error }}</li>
                            </strong>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="" >
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label>Company</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" >
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" }>
                    </div>
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" >
                    </div>

                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude">
                    </div>

                    <div class="form-group">
                        <label>Radius</label>
                        <input type="text" class="form-control" id="radius" name="radius" >
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" class="form-control" id="price" name="price" >
                    </div>
                    <div class="form-group">
                        <label>Currency</label>
                        <input type="text" class="form-control" id="currency" name="currency" >
                    </div>

                    <p class="text-center"><button id="addButton" class="btn btn-primary mt-2">Update</button></p>
                </form>

            </div>
        </div>
    </div>

    <x-slot name="js"> </x-slot>

</x-app-layout>
