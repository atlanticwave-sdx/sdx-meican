<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
   integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
   crossorigin=""/>

    <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
   integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
   crossorigin=""></script>
   
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

   <style type="text/css">

     #map { height: 800px;
      width: 1400px;
      max-width: 100%;
      max-height: 100%; }


.modal-dialog{
      overflow-y: initial !important
}
.modal-body{
  height: 250px;
  overflow-y: auto;
}

   </style>
   

</head>
<body>

<div class="container-fluid">

  <div class="row">
    <div class="col-sm-9"><div id="map"></div></div>
    <div class="col-sm-3">
    <form>
    <h4>Add connection</h4>
  <div class="form-group">
    <label for="exampleInputEmail1">Source</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Source">

  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Destination</label>
    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Enter destination">
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>

  </div>
</div>



   <div id="wrapper">

</div>






</body>

<script type="text/javascript">
  var map = L.map('map').setView(new L.LatLng(25.75, -80.37), 2);;

  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);

    var nodes=<?php echo json_encode($nodes); ?>;
   
 for (let [key, value] of Object.entries(nodes)) {
    
    var marker = L.marker([value.latitude,value.longitude]);
    
    var locations="";
    for(var j=0; j<value.sub_nodes.length;j++){
   
    locations=locations+value.sub_nodes[j].sub_node_name+" ";
    }
    
     marker.myID = key;

    marker.bindTooltip(locations).on('click', function(e) {
      var i = e.target.myID;
      console.log(value);
      $('#wrapper').empty();
      
      for(var j=0; j<value.sub_nodes.length;j++){
      var modalstring='<div id="myModal" class="modal fade" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">'+key+'</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><p>Location: '+value.sub_nodes[j].sub_node_name+'</p><p>Ports:</p>';
      
      for(var k=0; k<value.sub_nodes[j].ports.length;k++){
      
      modalstring=modalstring+'<p>ID: '+value.sub_nodes[j].ports[k].id+'</p>';
      modalstring=modalstring+'<p>Name: '+value.sub_nodes[j].ports[k].name+'</p>';
      modalstring=modalstring+'<p>Node: '+value.sub_nodes[j].ports[k].node+'</p>';
      modalstring=modalstring+'<p>Type: '+value.sub_nodes[j].ports[k].type+'</p>';
      modalstring=modalstring+'<p>Status: '+value.sub_nodes[j].ports[k].status+'</p>';
      modalstring=modalstring+'<p>State: '+value.sub_nodes[j].ports[k].state+'</p>';
      modalstring=modalstring+'---------------------------------------------------';

      $
      }
      modalstring=modalstring+'</div></div></div></div>';
      $('#wrapper').append(modalstring);
      $("#myModal").modal('show');

      }

    });
    

    marker.addTo(map);
    marker.openTooltip();
}
</script>

</html>

