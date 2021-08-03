<x-app-layout>
    <x-slot name="header">Ships</x-slot>

    <div class="card">
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif


            <a href="{{ route('ships.create') }}" class="btn btn-primary mb-2">Create new Ships location </a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">name</th>
                        <th scope="col">latitude</th>
                        <th scope="col">longitude</th>
                        <th scope="col">radius</th>
                        <th scope="col">Price</th>
                        <th scope="col">Currency</th>
                        <th scope="col">operation</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($ships as $ship)
                        <tr>
                            <td>{{ $ship->name }}</td>
                            <td>{{ $ship->latitude }}</td>
                            <td>{{ $ship->longitude }}</td>
                            <td>{{ $ship->radius }}</td>
                            <td>{{ $ship->price }}</td>
                            <td>{{ $ship->currency_id }}</td>
                            <td>
                                <a href="{{ route('ships.edit', $ship->id) }}" class="btn btn-primary">edit</a>
                                <a href="{{ route('ships.destroy', $ship->id) }}" class="btn btn-danger">delete</a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <x-slot name="js"></x-slot>
</x-app-layout>
