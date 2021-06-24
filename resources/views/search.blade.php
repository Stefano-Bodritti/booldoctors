@extends('layouts.app')

@section('title')
    BoolDoctors - Ricerca avanzata
@endsection

@section('content')
<div class="container" id="search">
    <div class="search_input">
        {{-- <select name="spec" id="spec" v-model="spec" v-on:change="filterSpec">
            <option value="" disabled selected>Filtra per specializzazione</option>
            @foreach ($specializations as $specialization)
                <option :value="{{$specialization->name}}">{{$specialization->specialization}}</option>
            @endforeach
        </select> --}}
        <input type="text" placeholder="Cerca una specializzazione" v-model="spec" @keyup="filterText">
        <i class="fas fa-search"></i>
    </div>


      <div class="loading" v-if="loading">
          <div class="loader">
            <!-- here put a spinner or whatever you want to indicate that a request is in progress -->
          </div>
      </div>
    <div class="doctor_container">
        <h2 v-if="noFindTxt">Nessun dottore corrisponde a questa ricerca</h2>
        <div v-for="doctor in filterDoc" class="cardDoctor">
            <div class="docAvatar d-flex">
                <div class="image_box">
                    <img v-if="doctor.details.image != null"{{-- da modificare in caso di seed --}} :src="'storage/' + doctor.details.image" :alt="'Immagine di ' + doctor.name + ' ' + doctor.surname">
                    <img v-else src="https://i.ibb.co/wQBsxBd/standard-Doctor.png" alt="Immagine del dottore">
                </div>
            </div>
            <div class="docInfo">
                <h3>@{{doctor.name}} @{{doctor.surname}}</h3>
                <span v-for="doc in doctor.specializations" class="my_tag">@{{doc.field}}</span>
                <p>Indirizzo: @{{doctor.details.address}}</p>
                <a :href="'http://127.0.0.1:8000/doctor/' + doctor.id">Contatta questo professionista</a>
            </div>
        </div>  
    </div>
</div>
<script src="https://unpkg.com/vue@next"></script>
<script src="{{ asset('js/search.js') }}" defer></script>
@endsection
