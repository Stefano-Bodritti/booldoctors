@extends('layouts.app')
@section('title')
    Modifica profilo
@endsection
@section('content')
  <div class="container">
    <h2>Vuoi ottenere una sponsorizzazione?</h2>
    <a href="#"><button type="button" class="btn btn-info">Clicca qui!</button></a>
      <form action="{{route('admin.profile.update', $doctor->id)}}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <img src="{{ asset('storage/' . $doctor->details->image) }}" alt="Immagine" style="height: 150px">
          <div class="form-group">
            <label for="image">Immagine</label>
            <input type="file" class="form-control" id='image' name='image'>
          </div>
          <div class="form-group mt-5">
              <label for="address">Indirizzo</label>
              <input type="text" class="form-control" name="address" id="address" placeholder="Inserisci il tuo indirizzo" value="{{$doctor->details->address}}">
          </div>
          <div class="form-group mt-5">
              <label for="phone">Numero di telefono</label>
              <input type="text" class="form-control" name="phone" id="phone" placeholder="Inserisci il tuo numero di telefono" value="{{$doctor->details->phone}}">
          </div>
          <div class="form-group mt-5">
            <label for="bio">Bio</label>
            <textarea name="bio" id="bio" cols="30" rows="10">{{$doctor->details->bio}}</textarea>
          </div>

          <div class="form-group mt-5">
            <h3>Aggiungi nuova prestazione</h3>
              <label for="service_name">Nome prestazione</label>
              <input type="text" class="form-control" name="service_name" id="service_name" placeholder="Inserisci il nome della prestazione" value="">
              <label for="service_price">Prezzo prestazione</label>
              <input type="number" class="form-control" name="service_price" id="service_price" min="0" max="9999.99" step="0.01" placeholder="Inserisci il prezzo della prestazione" value="">
          </div>

          <div class="mt-3">
              <h3>Specializzazioni</h3>
              @foreach ($specializations as $spec)
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="{{$spec->id}}" id="{{$spec->specialization}}" name="field[]" {{ $doctor->specializations->contains($spec) ? 'checked' : '' }}>
                  <label class="form-check-label" for="{{$spec->specialization}}">
                    {{$spec->specialization}}
                  </label>
                </div>
              @endforeach
            </div>

          <button type="submit" class="btn btn-primary">Inserisci</button>
        </form>

        <h3>Cancella prestazione</h3>
          <ul>
          @foreach ($services as $service)
            <li class='mt-5'>
              <p>{{$service['service']}}</p>
              <p>{{$service['price']}}</p>
              <form action="{{route('admin.service.destroy', $service)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Cancella</button>
              </form>
            </li>
          {{-- <div class="form-group mt-5">
              <label for="service_name">Nome prestazione</label>
              <input type="text" class="form-control" name="service_name" id="service_name" placeholder="Inserisci il nome della prestazione" value="{{$service['service']}}">
              <label for="service_price">Prezzo prestazione</label>
              <input type="number" class="form-control" name="service_price" id="service_price" min="0" max="9999.99" step="0.01" placeholder="Inserisci il prezzo della prestazione" value="{{$service['price']}}">
          </div> --}}
          @endforeach
          </ul>
          
        <p> <a href="{{ route('admin.profile.index') }}">Back to Homepage?</a> </p>
    </div>
@endsection