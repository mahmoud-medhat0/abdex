{{-- {{ dd($salaries) }} --}}
@extends('layouts.parent')
@section('title')
{{ __("track delegates") }}
@endsection
@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endsection
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __("track delegates") }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="height: 60%">
                        <div id="mapid" style="height: 400px;"></div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
@endsection
@section('js')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Create map object and set initial view
    var map = L.map('mapid').setView([30.0444, 31.2357], 7);
    function onLocationFound(e) {
      var radius = e.accuracy / 2;
      L.marker(e.latlng).addTo(map);
      L.circle(e.latlng, radius).addTo(map);
      map.setView(e.latlng, 13);
    }
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Zenonsoftware &copy; <a href="https://www.zenonsoftware.com/">zenon Software</a> Eng : Mahmoud Medhat',
        maxZoom: 20,
        tileSize: 512,
        zoomOffset: -1
    }).addTo(map);
</script>
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
    var firebaseConfig = {
    apiKey: "AIzaSyASfcY2Vo9VzgdUwzz5n3Anxkyy2RCCkC8",
    authDomain: "mar-express-3b0a1.firebaseapp.com",
    projectId: "mar-express-3b0a1",
    databaseURL: "https://mar-express-3b0a1-default-rtdb.firebaseio.com",
    storageBucket: "mar-express-3b0a1.appspot.com",
    messagingSenderId: "1011680860544",
    appId: "1:1011680860544:web:2cefbc3b02fcc118bcc965",
    measurementId: "G-ZE90HDF3GV"
};
firebase.initializeApp(firebaseConfig);
var database = firebase.database();
var myRef = database.ref("location");
myRef.on("value", function(snapshot) {
    map.eachLayer(function (layer) {
  if (layer instanceof L.Marker) {
    map.removeLayer(layer);
  }
});

    var data = snapshot.val();
  Object.keys(data).forEach(function(key) {
    var item = data[key];
    var location = item["location_coordinates"].split(", ");
    console.log(location[0]);
    L.marker([location[0],location[1]],{
  title: item["username"]
}).addTo(map);
  });
});
</script>
@endsection
