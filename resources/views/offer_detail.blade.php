<x-app-layout>
    <x-slot name="header">Detail</x-slot>

    <p class="text-center fs-3">Offer Detail</p>
    <div class="container">
        <div class="row">
            <div class="col">
                <form method="POST" action="{{ route('ship-add') }}">
                    @csrf
                    <div class="form-group">
                        <label>Offer Id</label>
                        <input type="text" class="form-control" id="name" name="name"  value="{{ $offer->company_id }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Destination</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value="{{ $offer->city->name }}" readonly>
                    </div>


                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" class="form-control" id="price" name="price" value="{{ $offer->price }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Currency</label>
                        <input type="text" class="form-control" id="price" name="price" value="{{ $offer->currency->name }}" readonly>
                    </div>
                </form>
              </div>
              <div class="col">
                <form method="POST" action="{{ route('ship-add') }}">
                    @csrf
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" class="form-control" id="name" name="name"  value="{{ $offer->company->name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Adrress</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value="{{  $offer->company->address }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Mail</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" value="{{ $offer->company->mail }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Tel</label>
                        <input type="text" class="form-control" id="radius" name="radius" value="{{  $offer->company->tel }}" readonly>
                    </div>
                </form>
              </div>

            </div>
        </div>
    </div>
    <x-slot name="js"></x-slot>

</x-app-layout>
