<x-app-layout>
    <x-slot name="header">Create Ships</x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('ships.store') }}" >
                    @csrf
                    <div class="form-group">
                        <label>Ship Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="ship name">
                    </div>

                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" placeholder="34.45">
                    </div>

                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" placeholder="54.64">
                    </div>

                    <div class="form-group">
                        <label>Radius</label>
                        <input type="text" class="form-control" id="radius" name="radius" placeholder="3.67">
                    </div>

                    <p class="text-center"><button id="addButton" class="btn btn-primary mt-2">Add</button></p>

                </form>

            </div>
        </div>
    </div>

    <x-slot name="js">
        <script>
            $("#latitude").change(function() {
                alert("asdasd");
            });
        </script>

    </x-slot>

</x-app-layout>
