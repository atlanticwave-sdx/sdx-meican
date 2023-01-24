
<!DOCTYPE html>
<html>
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
    <form id="filtersform">
    <h4>Add connection</h4>
  <div class="form-group">
    <label for="exampleInputEmail1">Source</label>
    <input type="text" class="form-control" id="Source" aria-describedby="emailHelp" placeholder="Enter Source">
    
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Destination</label>
    <input type="text" class="form-control" id="Destination" placeholder="Enter destination">
  </div>

<div class="form-group">
    <label for="exampleInputPassword1">Desired links</label>
    <textarea class="form-control" id="Desired" placeholder="Desired links"></textarea>
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Undesired links</label>
    <textarea class="form-control" id="Undesired" placeholder="Undesired links"></textarea>
  </div>




   <div class="form-group">
    <label for="exampleInputPassword1">Type</label>
    <input type="text" class="form-control" id="Type" placeholder="Type">
  </div>

   <div class="form-group">
    <label for="exampleInputPassword1">Bandwidth</label>
    <input type="text" class="form-control" id="Bandwidth" placeholder="Bandwidth">
  </div>
   <div class="form-group">
    <label for="exampleInputPassword1">Residual Bandwidth</label>
    <input type="text" class="form-control" id="Residual_Bandwidth" placeholder="Residual Bandwidth">
  </div>
   <div class="form-group">
    <label for="exampleInputPassword1">Latency</label>
    <input type="text" class="form-control" id="Latency" placeholder="Latency">
  </div>
   <div class="form-group">
    <label for="exampleInputPassword1">Packet loss</label>
    <input type="text" class="form-control" id="Packet_loss" placeholder="Packet loss">
  </div>
   <div class="form-group">
    <label for="exampleInputPassword1">Availability</label>
    <input type="text" class="form-control" id="Availability" placeholder="Availability">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Status</label>
    <input type="text" class="form-control" id="Status" placeholder="Status">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">State</label>
    <input type="text" class="form-control" id="State" placeholder="State">
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

    var nodes=<?php echo json_encode($nodes_array); ?>;
 for (let [key, value] of Object.entries(nodes)) {
    var marker = L.marker([value.latitude,value.longitude]);
    var locations="";
    for(var j=0; j<value.sub_nodes.length;j++){
    locations=locations+value.sub_nodes[j].sub_node_name+" ";
    }
     marker.myID = key;

    marker.bindTooltip(locations).on('click', function(e) {
      var i = e.target.myID;
      $('#wrapper').empty();
      var modalstring='<div id="myModal" class="modal fade" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">'+key+'</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body">';
      for(var j=0; j<value.sub_nodes.length;j++){
      modalstring=modalstring+'<p>Location: '+value.sub_nodes[j].sub_node_name+'</p><p>Ports:</p>';
      
      for(var k=0; k<value.sub_nodes[j].ports.length;k++){
      
      modalstring=modalstring+'<p>ID: '+value.sub_nodes[j].ports[k].id+'</p>';
      modalstring=modalstring+'<p>Name: '+value.sub_nodes[j].ports[k].name+'</p>';
      modalstring=modalstring+'<p>Node: '+value.sub_nodes[j].ports[k].node+'</p>';
      modalstring=modalstring+'<p>Type: '+value.sub_nodes[j].ports[k].type+'</p>';
      modalstring=modalstring+'<p>Status: '+value.sub_nodes[j].ports[k].status+'</p>';
      modalstring=modalstring+'<p>State: '+value.sub_nodes[j].ports[k].state+'</p>';
      modalstring=modalstring+'---------------------------------------------------';
      
      }
      
      
      
      }
      modalstring=modalstring+'</div></div></div></div>';
      $('#wrapper').append(modalstring);
      $("#myModal").modal('show');
     
    });
    
    
    
    marker.addTo(map);
    marker.openTooltip();
}
    
var latlngs=<?php echo json_encode($latlng_array); ?>;


for (let [key, value] of Object.entries(latlngs)){
   var latlngs_final=[];
   var latlngs2=value.latlngs;
   var linkname=value.link;
   for (let [key2, value2] of Object.entries(latlngs2)){
    var link=[value2[0],value2[1]];
    latlngs_final.push(link);

    var polyline = L.polyline(latlngs_final, {color: 'blue'}).bindTooltip(linkname).addTo(map);

    polyline.myID=linkname;
    polyline.bindTooltip(linkname).on('click', function(e) {
      var i = e.target.myID;
      var links_array=<?php echo json_encode($links_array); ?>;
      for (let [key3, value3] of Object.entries(links_array)){
        if(key3==i){
          $('#wrapper').empty();

          var modalstring2='<div id="myModal2" class="modal fade" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">'+i+'</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><p>Links:</p>';
      
      for(var k=0; k<value3.length;k++){
      
      modalstring2=modalstring2+'<p>ID: '+value3[k].id+'</p>';
      modalstring2=modalstring2+'<p>Name: '+value3[k].name+'</p>';
      modalstring2=modalstring2+'<p>Bandwidth: '+value3[k].bandwidth+'</p>';
      modalstring2=modalstring2+'<p>Residual Bandwidth: '+value3[k].residual_bandwidth+'</p>';
      modalstring2=modalstring2+'<p>Type: '+value3[k].type+'</p>';
      modalstring2=modalstring2+'<p>Packet loss: '+value3[k].packet_loss+'</p>';
      modalstring2=modalstring2+'<p>Latency: '+value3[k].latency+'</p>';
      modalstring2=modalstring2+'<p>Availability: '+value3[k].availability+'</p>';
      modalstring2=modalstring2+'<p>Status: '+value3[k].status+'</p>';
      modalstring2=modalstring2+'<p>State: '+value3[k].state+'</p>';
      modalstring2=modalstring2+'---------------------------------------------------';
      
      $
      }
      modalstring2=modalstring2+'</div></div></div></div>';
      $('#wrapper').append(modalstring2);
      $("#myModal2").modal('show');


        }

      }

    });


   }
  


}

$( "#filtersform" ).submit(function( event ) {
  
  event.preventDefault();
    var Source=$('#Source').val();
    var Destination=$('#Destination').val();
    var Type=$('#Type').val();
    var Bandwidth=$('#Bandwidth').val();
    var Residual_Bandwidth=$('#Residual_Bandwidth').val();
    var Latency=$('#Latency').val();
    var Packet_loss=$('#Packet_loss').val();
    var Availability=$('#Availability').val();
    var Status=$('#Status').val();
    var State=$('#State').val();
    var temp_key=Source+'-'+Destination;
    var links_array=<?php echo json_encode($links_array); ?>;
    var desired_links=[];
    var undesired_links=[];
    for (let [key3, value3] of Object.entries(links_array)){
        
        if(key3==temp_key){
            var filters_flag=0;
            for(var k=0; k<value3.length;k++){
                var match_flag=1;
                if(Type){
                  filters_flag=1;
                  if(Type==value3[k].type){
                      
                  }
                  else{
                    match_flag=0;
                  }  
                }

                if(Bandwidth){
                  filters_flag=1;
                  if(Bandwidth==value3[k].bandwidth){
                      
                  }
                  else{
                    match_flag=0;
                  }  
                }

                if(Residual_Bandwidth){
                  filters_flag=1;
                  if(Residual_Bandwidth==value3[k].residual_bandwidth){
                      
                  }
                  else{
                    match_flag=0;
                  }  
                }

                if(Latency){
                  filters_flag=1;
                  if(Latency==value3[k].latency){
                      
                  }
                  else{
                    match_flag=0;
                  }  
                }

                if(Packet_loss){
                  filters_flag=1;
                  if(Packet_loss==value3[k].packet_loss){
                      
                  }
                  else{
                    match_flag=0;
                  }  
                }

                if(Availability){
                  filters_flag=1;
                  if(Availability==value3[k].availability){
                      
                  }
                  else{
                    match_flag=0;
                  }  
                }

                if(Status){
                  filters_flag=1;
                  if(Status==value3[k].status){
                      
                  }
                  else{
                    match_flag=0;
                  }  
                }

                if(State){
                  filters_flag=1;
                  if(State==value3[k].state){
                      
                  }
                  else{
                    match_flag=0;
                  }  
                }

                if(filters_flag==0)
                {
                    match_flag=0;
                    }
                
                if(match_flag==1){
                    var link=[value3[k]];
                    desired_links.push(link);
                    
                }
                    
                // }
                else{
                    var link=[value3[k]];
                    undesired_links.push(link);
                    
                }
            }
        }
    }
    $('#Desired').val(JSON.stringify(desired_links));
    $('#Undesired').val(JSON.stringify(undesired_links));
    
});




</script>

</html>



