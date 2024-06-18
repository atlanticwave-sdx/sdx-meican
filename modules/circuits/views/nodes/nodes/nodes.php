
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
.field-group {
            margin-bottom: 10px;
        }

        .input-container {
            margin-top: 10px;
        }

        .deleteButton{
          margin-top: 10px;
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
    <label for="exampleInputPassword1">Name</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
  </div>

<div class="form-group">
    <label for="exampleInputPassword1">Endpoints</label>
      <br>Interface:</br>
      <select class="form-control" id="endpoint_1_interface_uri" name="endpoint_1_interface_uri" placeholder="interface uri" required>
      <?php foreach ($nodes_array as $key => $value) {
              foreach ($value['sub_nodes'] as $key2 => $value2) {
                foreach ($value2['ports'] as $key3 => $value3) {
                  echo "<option value='".$value3['id']."'>".$value3['id']."</option>";
                }
              }
      }
        ?>
    </select>


    <br>VLAN:</br>
    <select class="form-control" id="endpoint_1_vlan" name="endpoint_1_vlan" placeholder="vlan" required>
      <option value="any">any</option>
      <option value="number">number</option>
      <option value="untagged">untagged</option>
      <option value="VLAN range">VLAN range</option>
      <option value="all">all</option>
    </select>

    <div id="endpoint_1_vlan-input-container" class="input-container"></div>

    <br>Interface:</br>
    <select class="form-control" id="endpoint_2_interface_uri" name="endpoint_2_interface_uri" placeholder="interface uri" required>
      <?php foreach ($nodes_array as $key => $value) {
              foreach ($value['sub_nodes'] as $key2 => $value2) {
                foreach ($value2['ports'] as $key3 => $value3) {
                  echo "<option value='".$value3['id']."'>".$value3['id']."</option>";
                }
              }
      }
        ?>
    </select>

    
    <br>VLAN:</br>
    <select class="form-control" id="endpoint_2_vlan" name="endpoint_2_vlan" placeholder="vlan" required>
      <option value="any">any</option>
      <option value="number">number</option>
      <option value="untagged">untagged</option>
      <option value="VLAN range">VLAN range</option>
      <option value="all">all</option>
    </select>

    <div id="endpoint_2_vlan-input-container" class="input-container"></div>
  </div>

  <div id="field-container">
            
  </div>
  <button type="button" class="btn btn-primary" onclick="appendFields()">Add Endpoint</button>

  <div class="form-group">
    <label for="exampleInputPassword1">Start time</label>
    <input type="date" class="form-control" id="start_time" name="start_time" placeholder="Start time" >
  </div>




   <div class="form-group">
    <label for="exampleInputPassword1">End time</label>
    <input type="date" class="form-control" id="end_time" name="end_time" placeholder="End time" >
  </div>



  <div class="form-group">
    <label for="inputLatencyRequired">Maximum Latency</label>
    <input type="number" maxlength="4" class="form-control" id="latency_required" name="latency_required" >
  </div>

  <div class="form-group">
    <label for="inputBandwidthRequired">Minimum Bandwidth</label>
    <input type="number" maxlength="4" class="form-control" id="bandwidth_required" name="bandwidth_required" >
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

function generate_uuidv4() {
   return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g,
   function(c) {
      var uuid = Math.random() * 16 | 0, v = c == 'x' ? uuid : (uuid & 0x3 | 0x8);
      return uuid.toString(16);
   });
}

$( "#filtersform" ).submit(function( event ) {
  
    event.preventDefault();
    var id=generate_uuidv4();
    var name=$('#name').val();
    var quantity=$('#quantity').val();
    var start_time=$('#start_time').val();
    var end_time=$('#end_time').val();
    var egress_port=$('#egress_port').val();
    var ingress_port=$('#ingress_port').val();
    let time_stamp = new Date().toJSON();
    var meican_url="<?php echo $meican_url;?>";
    //var source_vlan=$('#source_vlan').val();
    //var destination_vlan=$('#destination_vlan').val();
    var latency_required=$('#latency_required').val();
    var bandwidth_required=$('#bandwidth_required').val();

    start_time=new Date(start_time).toISOString();
    end_time=new Date(end_time).toISOString();

    var endpoints=[];
    var endpoint1=[];
    var endpoint2=[];

    endpoint1["interface_uri"]=$('#endpoint_1_interface_uri').val();
    endpoint1["vlan"]=$('#endpoint_1_vlan').val();
    endpoint2["interface_uri"]=$('#endpoint_2_interface_uri').val();
    endpoint2["vlan"]=$('#endpoint_2_vlan').val();

    if(endpoint1["vlan"]=='number'||endpoint1["vlan"]=='VLAN range'){
      endpoint1["vlan"]=$('#endpoint_1_vlan_value').val();
    }

    if(endpoint2["vlan"]=='number'||endpoint2["vlan"]=='VLAN range'){
      endpoint2["vlan"]=$('#endpoint_2_vlan_value').val();
    }

    console.log(endpoint1);
    endpoints.push(endpoint1);
    console.log(endpoint2);
    endpoints.push(endpoint2);

    

     const fieldGroups = document.getElementsByClassName('field-group');
            const results = [];

            results.push({
                    port_id: endpoint1["interface_uri"],
                    vlan: endpoint1["vlan"]
                });

             results.push({
                    port_id: endpoint2["interface_uri"],
                    vlan: endpoint2["vlan"]
                });

            for (let i = 0; i < fieldGroups.length; i++) {
                const interfaceInput = fieldGroups[i].querySelector('select[name="interface"]').value;
                const vlanSelect = fieldGroups[i].querySelector('select[name="vlan"]').value;
                const vlanValueInput = fieldGroups[i].querySelector('input[name="vlan_value"]');

                const vlanValue = vlanSelect === 'number' || vlanSelect === 'VLAN range' ? vlanValueInput.value : vlanSelect;

                results.push({
                    port_id: interfaceInput,
                    vlan: vlanValue
                });

                
            }

            console.log("all endpoints:"+JSON.stringify(results));
            

            var request={"name":name,"endpoints":results};

    console.log(request);
    console.log(JSON.stringify(request));

//     $.ajax({
//     type: "POST",
//     url: "https://"+meican_url+"/circuits/nodes/create",
//     data: JSON.stringify(request),
//     contentType: "application/json; charset=utf-8",
//     success: function(data){alert(data);},
//     error: function(errMsg) {
//         alert(errMsg);
//     }
// });
     
    
    
});


        function appendFields() {
            const container = document.getElementById('field-container');

            const newDiv = document.createElement('div');
            newDiv.className = 'field-group';
            newDiv.innerHTML+='Interface:'

            const interfaceSelect = document.createElement('select');
            interfaceSelect.name = 'interface';
            interfaceSelect.className='form-control';

        <?php
            foreach ($nodes_array as $key => $value) {
                foreach ($value['sub_nodes'] as $key2 => $value2) {
                    foreach ($value2['ports'] as $key3 => $value3) {
                        echo "interfaceSelect.innerHTML += '<option value=\"" . $value3['id'] . "\">" . $value3['id'] . "</option>';";
                    }
                }
            }
            ?>


            const vlanSelect = document.createElement('select');
            vlanSelect.name = 'vlan';
            vlanSelect.className='form-control';
             vlanSelect.onchange = function() {
                handleVlanChange(newDiv, vlanSelect.value);
            };
            vlanSelect.innerHTML='<option value="any">any</option><option value="number">number</option><option value="untagged">untagged</option><option value="VLAN range">VLAN range</option><option value="all">all</option>';

            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.innerText = 'Delete';
            deleteButton.className='btn btn-primary deleteButton';
            deleteButton.id='deleteButton';
            deleteButton.style.backgroundColor = "red";
            deleteButton.onclick = function() {
                container.removeChild(newDiv);
            };

            newDiv.appendChild(interfaceSelect);
            newDiv.innerHTML+='<br>VLAN:</br>';
            newDiv.appendChild(vlanSelect);
            newDiv.appendChild(deleteButton);

            container.appendChild(newDiv);
        }

        function handleVlanChange(container, value) {
            let existingInput = container.querySelector('input[name="vlan_value"]');
            if (existingInput) {
                container.removeChild(existingInput);
            }

            if (value === 'number' || value === 'VLAN range') {
                const vlanInput = document.createElement('input');
                vlanInput.type = 'text';
                vlanInput.name = 'vlan_value';
                vlanInput.placeholder = value === 'number' ? 'Enter VLAN Number' : 'Enter VLAN Range';
                vlanInput.className='form-control';
                temp=container.querySelector('button[id="deleteButton"]');
                container.removeChild(temp);
                container.appendChild(vlanInput);
                container.appendChild(temp);
            }
        }

        const dropdown = document.getElementById('endpoint_1_vlan');
        const inputContainer = document.getElementById('endpoint_1_vlan-input-container');
        const dropdown2 = document.getElementById('endpoint_2_vlan');
        const inputContainer2 = document.getElementById('endpoint_2_vlan-input-container');

        dropdown.addEventListener('change', function() {
            // Clear any existing input fields
            inputContainer.innerHTML = '';

            // Options that require an additional input field
            const optionsRequiringInput = ['number', 'VLAN range'];

            if (optionsRequiringInput.includes(dropdown.value)) {
                const inputField = document.createElement('input');
                inputField.type = 'text';
                inputField.placeholder = 'Please provide value';
                inputField.name = 'endpoint_1_vlan_value';
                inputField.id='endpoint_1_vlan_value';
                inputField.className='form-control';
                inputContainer.appendChild(inputField);
            }
        });

        dropdown2.addEventListener('change', function() {
            // Clear any existing input fields
            inputContainer2.innerHTML = '';

            // Options that require an additional input field
            const optionsRequiringInput = ['number', 'VLAN range'];

            if (optionsRequiringInput.includes(dropdown2.value)) {
                const inputField = document.createElement('input');
                inputField.type = 'text';
                inputField.placeholder = 'Please provide value';
                inputField.name = 'endpoint_2_vlan_value';
                inputField.id = 'endpoint_2_vlan_value';
                inputField.className='form-control';
                inputContainer2.appendChild(inputField);
            }
        });


</script>

</html>



