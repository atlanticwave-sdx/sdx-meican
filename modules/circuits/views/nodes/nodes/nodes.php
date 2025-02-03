<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />

  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  

  <style type="text/css">
    #map {
      height: 800px;
      width: 1400px;
      max-width: 100%;
      max-height: 100%;
    }

    .required::after {
      content: " *";
      color: red;
    }

    .error-message {
      color: red;
      font-size: small;
    }

    .modal-dialog {
      width: 75vw;
      overflow-y: initial !important
    }

    .links-modal {
      width: 95vw;
    }

    .modal-body {
      overflow-y: auto;
    }

    .field-group {
      margin-bottom: 10px;
    }

    .input-container {
      margin-top: 10px;
    }

    .deleteButton {
      margin-top: 10px;
    }

    .notification-btn {
      margin-bottom: 10px;
      margin-right: 10px;
    }

    #portsTable th, #portsTable td, #linksTable th, #linksTable td {
      padding: 8px;
      text-align: left;
    }

    #tableSearchPorts, #tableSearchLinks {
      margin-bottom: 10px;
    }


  </style>

      <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .status {
            margin-top: 10px;
            font-size: 16px;
        }

        .advanced-options {
            display: none; /* Hide advanced fields initially */
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .advButton {
            margin-top: 10px;
            padding: 8px 12px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .advButton:hover {
            background-color: #0056b3;
        }
    </style>


</head>

<body>



  <div class="container-fluid">

    <label class="switch">
        <input type="checkbox" id="autoRefreshToggle">
        <span class="slider"></span>
    </label>
    <span id="statusText" class="status">Auto-Refresh is OFF</span>

    <div class="row">
      <div class="col-sm-9">
        <div id="map"></div>
      </div>
      <div class="col-sm-3">
        <form id="filtersform">
          <h4>Add connection</h4>

          <div class="form-group">
            <label for="exampleInputPassword1" class="required">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name" maxlength="50" required>
          </div>

          <div class="form-group">
            <label for="exampleInputPassword1" class="required">Endpoints</label>
            <br>Port ID:</br>
            <select class="form-control" id="endpoint_1_interface_uri" name="endpoint_1_interface_uri" placeholder="interface uri" required>
              <?php foreach ($nodes_array as $key => $value) {
                foreach ($value['sub_nodes'] as $key2 => $value2) {
                  foreach ($value2['ports'] as $key3 => $value3) {
                    echo "<option value='" . $value3['id'] . "'>" . str_replace("urn:sdx:port:", "",$value3['id']) . "</option>";
                  }
                }
              }
              ?>
            </select>


            <br>VLAN:</br>
            <select class="form-control" id="endpoint_1_vlan" name="endpoint_1_vlan" placeholder="vlan" required>
              <option value="any" title="Any available VLAN ID is chosen">any</option>
              <option value="number" title="Specific VLAN ID, e.g., '50'">VLAN ID</option>
              <option value="untagged" title="Transports Ethernet frames without IEEE 802.1Q Ethertype">untagged</option>
              <option value="VLAN range" title="Range of VLANs, e.g., '50:55'">VLAN range</option>
              <option value="all" title="Transport all Ethernet frames with and without IEEE 802.Q Ethertype">all</option>
            </select>

            <div id="endpoint_1_vlan-input-container" class="input-container"></div>

            <br>Port ID:</br>
            <select class="form-control" id="endpoint_2_interface_uri" name="endpoint_2_interface_uri" placeholder="interface uri" required>
              <?php foreach ($nodes_array as $key => $value) {
                foreach ($value['sub_nodes'] as $key2 => $value2) {
                  foreach ($value2['ports'] as $key3 => $value3) {
                    echo "<option value='" . $value3['id'] . "'>" . str_replace("urn:sdx:port:", "",$value3['id']) . "</option>";
                  }
                }
              }
              ?>
            </select>


            <br>VLAN:</br>
            <select class="form-control" id="endpoint_2_vlan" name="endpoint_2_vlan" placeholder="vlan" required>
            <option value="any" title="Any available VLAN ID is chosen">any</option>
              <option value="number" title="Specific VLAN ID, e.g., '50'">VLAN ID</option>
              <option value="untagged" title="Transports Ethernet frames without IEEE 802.1Q Ethertype">untagged</option>
              <option value="VLAN range" title="Range of VLANs, e.g., '50:55'">VLAN range</option>
              <option value="all" title="Transport all Ethernet frames with and without IEEE 802.Q Ethertype">all</option>
            </select>

            <div id="endpoint_2_vlan-input-container" class="input-container"></div>
          </div>

          <div id="field-container">

          </div>
          <button type="button" class="btn btn-primary" onclick="appendFields()">Add Endpoint</button>

          <div class="form-group">
            <label for="exampleInputPassword1">Description</label>
            <textarea class="form-control" id="description" name="description" placeholder="(optional)" maxlength="255"></textarea>
          </div>

          <div class="form-group">
            <label for="exampleInputPassword1">Start time (optional)</label>
            <!-- Time Zone needs to be verified -->
            <input type="date" class="form-control" id="start_time" name="start_time" placeholder="Start time" min="<?php echo date('Y-m-d'); ?>" oninput="validateDate(this)">
            <small id="start_time_error" class="error-message"></small>
          </div>


          <div class="form-group">
            <label for="exampleInputPassword1">End time (optional)</label>
            <!-- Time Zone needs to be verified -->
            <input type="date" class="form-control" id="end_time" name="end_time" placeholder="End time" min="<?php echo date('Y-m-d'); ?>" oninput="validateDate(this)">
            <small id="end_time_error" class="error-message"></small>
          </div>

                  <!-- Show Advanced Options Button -->
        <button type="button" class="advButton" onclick="toggleAdvancedOptions()">Show Advanced Options</button>

          <div id="advancedOptions" class="advanced-options">
          <div class="form-group">
            <label for="min_bw">Minimum Bandwidth (Gbps)</label>
            <input type="number" class="form-control" id="min_bw" name="min_bw" placeholder="(optional)" min="0" max="100" step="1" oninput="validateInput(this, 100)">
            <label><input type="checkbox" id="min_bw_strict" name="min_bw_strict"> Strict</label>
          </div>

          <div class="form-group">
            <label for="max_delay">Maximum Delay (ms)</label>
            <input type="number" class="form-control" id="max_delay" name="max_delay" placeholder="(optional)" min="0" max="1000" step="1" oninput="validateInput(this, 1000)">
            <label><input type="checkbox" id="max_delay_strict" name="max_delay_strict"> Strict</label>
          </div>

          <div class="form-group">
            <label for="max_number_oxps">Maximum Number of OXPs</label>
            <input type="number" class="form-control" id="max_number_oxps" name="max_number_oxps" placeholder="(optional)" min="0" max="100" step="1" oninput="validateInput(this, 100)">
            <label><input type="checkbox" id="max_number_oxps_strict" name="max_number_oxps_strict"> Strict</label>
          </div>

          <div id="notification-container">
            <div class="form-group">
              <label for="notifications">Notifications</label>
              <input type="email" class="form-control notification-field" id="notification_1" name="notification_1" placeholder="Notification Email 1 (optional)">
            </div>
          </div>
          <button type="button" class="btn btn-primary notification-btn" onclick="appendNotification()">Add Notification</button>
        </div>

          <div></div>
          <br>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>

    </div>
  </div>
  <div id="wrapper">
  </div>
</body>


<script type="text/javascript">

    var meican_url = "<?php echo $meican_url; ?>";

    function refreshMapData(){

      map.eachLayer((layer) => {
  if (layer instanceof L.Marker) {
     layer.remove();
  }
  if (layer instanceof L.Polyline) {
     layer.remove();
  }
  });
      //L.marker([50.5, 30.5]).addTo(map);
          $.ajax({
        url: "https://"+meican_url+"/circuits/nodes/refreshtopology",
        type: "GET",
        contentType: "application/json",
        success: function(data) {

          let jsonData = null;
          if (typeof data != null) {
              try {
                  jsonData = JSON.parse(data);

                  var nodes = jsonData.nodes
                  var latlngs = jsonData.latlngs
                  var links_array = jsonData.links

                  for (let [key, value] of Object.entries(nodes)) {
                    var marker = L.marker([value.latitude, value.longitude]);
                    var locations = "";
                    for (var j = 0; j < value.sub_nodes.length; j++) {
                      locations = locations + value.sub_nodes[j].sub_node_name + " ";
                    }
                    marker.myID = key;

                    var ports_down=0;
                    for (var j = 0; j < value.sub_nodes.length; j++) {
                      for (var k = 0; k < value.sub_nodes[j].ports.length; k++) {
                        var port = value.sub_nodes[j].ports[k];
                            if(port.status!='up' && port.state=='enabled'){
                                ports_down=ports_down+1;
                              }
                          }
                      }
                      
                    var tooltip = L.tooltip({permanent:true}).setContent(locations);
                    marker.bindTooltip(tooltip).on('click', function(e) {
                      var i = e.target.myID;
                      $('#wrapper').empty();
                      var modalstring = '<div id="myModal" class="modal fade" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">' + key + '</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body">';

                      modalstring += '<input id="tableSearchPorts" type="text" placeholder="Search..." class="form-control mb-3">';

                      modalstring += '<table id="portsTable" class="table table-bordered table-striped"><thead><tr><th>Location</th><th>ID</th><th>Port Name</th><th>Node</th><th>Type</th><th>Status</th><th>State</th></tr></thead><tbody>';
                      for (var j = 0; j < value.sub_nodes.length; j++) {
                          var portLocation = value.sub_nodes[j].sub_node_name;

                          for (var k = 0; k < value.sub_nodes[j].ports.length; k++) {
                              var port = value.sub_nodes[j].ports[k];
                              modalstring += '<tr>';
                              modalstring += '<td>' + portLocation + '</td>';
                              modalstring += '<td>' + port.id + '</td>';
                              modalstring += '<td>' + port.name + '</td>';
                              modalstring += '<td>' + port.node.replace("urn:sdx:node:",""); + '</td>';
                              modalstring += '<td>' + port.type + '</td>';
                              modalstring += '<td style="color:' + (port.status == 'up' ? 'green' : 'red') + '; font-weight: bold;">' + port.status + '</td>';  
                              modalstring += '<td>' + port.state + '</td>';
                              modalstring += '</tr>';
                          }
                      }

                      modalstring += '</tbody></table></div></div></div></div>';
                      $('#wrapper').append(modalstring);
                      $("#myModal").modal('show');

                      $("#tableSearchPorts").on("keyup", function() {
                        var value = $(this).val().toLowerCase();
                        $("#portsTable tbody tr").filter(function() {
                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                        });
                      });

                    });
                    
                    marker.addTo(map);
                    marker.openTooltip();
                    if(ports_down>0){
                      var popup = L.popup({autoClose:false,closeOnClick:false,keepInView:true,permanent:true,interactive:true})
                    .setContent('<p>ports down: <b style="color:red">'+ports_down+'</b></p>');
                        marker.bindPopup(popup);
                        marker.openPopup();
                      }
                    
                    
                  }
                  for (let [key, value] of Object.entries(latlngs)) {
                    var latlngs_final = [];
                    var latlngs2 = value.latlngs;
                    var linkname = value.link;
                    for (let [key2, value2] of Object.entries(latlngs2)) {
                      var link = [value2[0], value2[1]];
                      latlngs_final.push(link);

                      var polyline = L.polyline(latlngs_final, {
                        color: 'blue'
                      }).bindTooltip(linkname).addTo(map);

                      polyline.myID = linkname;


                        for (let [key3, value3] of Object.entries(links_array)) {
                          if (key3 == polyline.myID) {

                            for (var k = 0; k < value3.length; k++) {
                                var link = value3[k];
                                if(link.status!='up'){
                                  polyline.setStyle({
                                      color: 'yellow'
                                          });
                                }

                              }

                          }
                        }



                      polyline.bindTooltip(linkname).on('click', function(e) {
                        var i = e.target.myID;
                        for (let [key3, value3] of Object.entries(links_array)) {
                          if (key3 == i) {
                            $('#wrapper').empty();
                            var modalstring2 = '<div id="myModal2" class="modal fade" tabindex="-1"><div class="modal-dialog links-modal"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">' + i + '</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><p style="font-weight: bold">Links:</p>';

                            modalstring2 += '<input id="tableSearchLinks" type="text" placeholder="Search..." class="form-control mb-3">';

                            modalstring2 += '<table id="linksTable" class="table table-bordered table-striped"><thead><tr><th>ID</th><th>Name</th><th>Bandwidth</th><th>Residual <br /> Bandwidth</th><th>Type</th><th>Packet <br /> loss</th><th>Latency</th><th>Availability</th><th>Status</th><th>State</th></tr></thead><tbody>';

                            for (var k = 0; k < value3.length; k++) {
                                var link = value3[k];
                                modalstring2 += '<tr>';
                                modalstring2 += '<td>' + link.id + '</td>';
                                modalstring2 += '<td>' + link.name + '</td>';
                                modalstring2 += '<td>' + link.bandwidth + '</td>';
                                modalstring2 += '<td>' + link.residual_bandwidth + '</td>';
                                modalstring2 += '<td>' + link.type + '</td>';
                                modalstring2 += '<td>' + link.packet_loss + '</td>';
                                modalstring2 += '<td>' + link.latency + '</td>';
                                modalstring2 += '<td>' + link.availability + '</td>';
                                modalstring2 += '<td style="color:' + (link.status == 'up' ? 'green' : 'red') + '; font-weight: bold;">' + link.status + '</td>';
                                modalstring2 += '<td>' + link.state + '</td>';
                                modalstring2 += '</tr>';
                            }
                            modalstring2 += '</tbody></table></div></div></div></div>';
                            $('#wrapper').append(modalstring2);
                            $("#myModal2").modal('show');

                            $("#tableSearchLinks").on("keyup", function() {
                              var value = $(this).val().toLowerCase();
                              $("#linksTable tbody tr").filter(function() {
                                  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                              });
                            });
                          }
                        }
                      });
                    }
                  }
              } catch (e) {
                  console.error("Failed to parse JSON response:", e);
                  return;
              }
          } else {
              alert("Topology data is null");
          }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching topology data:", error);
        }
    });
    }
  

  var map = L.map('map',{closePopupOnClick : false}).setView(new L.LatLng(25.75, -80.37), 2);;

  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
  }).addTo(map);

  var nodes = <?php echo json_encode($nodes_array); ?>;
  for (let [key, value] of Object.entries(nodes)) {
    var marker = L.marker([value.latitude, value.longitude]);
    var locations = "";
    for (var j = 0; j < value.sub_nodes.length; j++) {
      locations = locations + value.sub_nodes[j].sub_node_name + " ";
    }
    marker.myID = key;

    var ports_down=0;
    for (var j = 0; j < value.sub_nodes.length; j++) {
      for (var k = 0; k < value.sub_nodes[j].ports.length; k++) {
        var port = value.sub_nodes[j].ports[k];
            if(port.status!='up' && port.state=='enabled'){
                ports_down=ports_down+1;
              }
          }
      }
      
    var tooltip = L.tooltip({permanent:true}).setContent(locations);
    marker.bindTooltip(tooltip).on('click', function(e) {
      var i = e.target.myID;
      $('#wrapper').empty();
      var modalstring = '<div id="myModal" class="modal fade" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">' + key + '</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body">';

      modalstring += '<input id="tableSearchPorts" type="text" placeholder="Search..." class="form-control mb-3">';

      modalstring += '<table id="portsTable" class="table table-bordered table-striped"><thead><tr><th>Location</th><th>ID</th><th>Port Name</th><th>Node</th><th>Type</th><th>Status</th><th>State</th></tr></thead><tbody>';
      for (var j = 0; j < value.sub_nodes.length; j++) {
          var portLocation = value.sub_nodes[j].sub_node_name;

          for (var k = 0; k < value.sub_nodes[j].ports.length; k++) {
              var port = value.sub_nodes[j].ports[k];
              modalstring += '<tr>';
              modalstring += '<td>' + portLocation + '</td>';
              modalstring += '<td>' + port.id + '</td>';
              modalstring += '<td>' + port.name + '</td>';
              modalstring += '<td>' + port.node.replace("urn:sdx:node:",""); + '</td>';
              modalstring += '<td>' + port.type + '</td>';
              modalstring += '<td style="color:' + (port.status == 'up' ? 'green' : 'red') + '; font-weight: bold;">' + port.status + '</td>';  
              modalstring += '<td>' + port.state + '</td>';
              modalstring += '</tr>';
          }
      }

      modalstring += '</tbody></table></div></div></div></div>';
      $('#wrapper').append(modalstring);
      $("#myModal").modal('show');

      $("#tableSearchPorts").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#portsTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });

    });
    
    marker.addTo(map);
    marker.openTooltip();
     if(ports_down>0){
      var popup = L.popup({autoClose:false,closeOnClick:false,keepInView:true,permanent:true,interactive:true})
    .setContent('<p>ports down: <b style="color:red">'+ports_down+'</b></p>');
        marker.bindPopup(popup);
        marker.openPopup();
      }
    
    
  }

  var latlngs = <?php echo json_encode($latlng_array); ?>;


  for (let [key, value] of Object.entries(latlngs)) {
    var latlngs_final = [];
    var latlngs2 = value.latlngs;
    var linkname = value.link;
    for (let [key2, value2] of Object.entries(latlngs2)) {
      var link = [value2[0], value2[1]];
      latlngs_final.push(link);

      var polyline = L.polyline(latlngs_final, {
        color: 'blue'
      }).bindTooltip(linkname).addTo(map);

      polyline.myID = linkname;


      var links_array = <?php echo json_encode($links_array); ?>;
        for (let [key3, value3] of Object.entries(links_array)) {
          if (key3 == polyline.myID) {

            for (var k = 0; k < value3.length; k++) {
                var link = value3[k];
                 if(link.status!='up'){
                  polyline.setStyle({
                      color: 'yellow'
                          });
                }

              }

          }
        }



      polyline.bindTooltip(linkname).on('click', function(e) {
        var i = e.target.myID;
        var links_array = <?php echo json_encode($links_array); ?>;
        for (let [key3, value3] of Object.entries(links_array)) {
          if (key3 == i) {
            $('#wrapper').empty();
            var modalstring2 = '<div id="myModal2" class="modal fade" tabindex="-1"><div class="modal-dialog links-modal"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">' + i + '</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><p style="font-weight: bold">Links:</p>';

            modalstring2 += '<input id="tableSearchLinks" type="text" placeholder="Search..." class="form-control mb-3">';

            modalstring2 += '<table id="linksTable" class="table table-bordered table-striped"><thead><tr><th>ID</th><th>Name</th><th>Bandwidth</th><th>Residual <br /> Bandwidth</th><th>Type</th><th>Packet <br /> loss</th><th>Latency</th><th>Availability</th><th>Status</th><th>State</th></tr></thead><tbody>';

            for (var k = 0; k < value3.length; k++) {
                var link = value3[k];
                modalstring2 += '<tr>';
                modalstring2 += '<td>' + link.id + '</td>';
                modalstring2 += '<td>' + link.name + '</td>';
                modalstring2 += '<td>' + link.bandwidth + '</td>';
                modalstring2 += '<td>' + link.residual_bandwidth + '</td>';
                modalstring2 += '<td>' + link.type + '</td>';
                modalstring2 += '<td>' + link.packet_loss + '</td>';
                modalstring2 += '<td>' + link.latency + '</td>';
                modalstring2 += '<td>' + link.availability + '</td>';
                modalstring2 += '<td style="color:' + (link.status == 'up' ? 'green' : 'red') + '; font-weight: bold;">' + link.status + '</td>';
                modalstring2 += '<td>' + link.state + '</td>';
                modalstring2 += '</tr>';
            }
            modalstring2 += '</tbody></table></div></div></div></div>';
            $('#wrapper').append(modalstring2);
            $("#myModal2").modal('show');

            $("#tableSearchLinks").on("keyup", function() {
              var value = $(this).val().toLowerCase();
              $("#linksTable tbody tr").filter(function() {
                  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
              });
            });
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

  $("#filtersform").submit(function(event) {
    event.preventDefault();
    var id = generate_uuidv4();
    var name = $('#name').val();
    var meican_url = "<?php echo $meican_url; ?>";
    var description = $('#description').val();
    var start_time = $('#start_time').val();
    var end_time = $('#end_time').val();
    var min_bw = $('#min_bw').val();
    var min_bw_strict = $('#min_bw_strict').is(":checked");
    var max_delay = $('#max_delay').val();
    var max_delay_strict = $('#max_delay_strict').is(":checked");
    var max_number_oxps = $('#max_number_oxps').val();
    var max_number_oxps_strict = $('#max_number_oxps_strict').is(":checked");

    var endpoints = [];
    var endpoint1 = [];
    var endpoint2 = [];

    endpoint1["interface_uri"] = $('#endpoint_1_interface_uri').val();
    endpoint1["vlan"] = $('#endpoint_1_vlan').val();
    endpoint2["interface_uri"] = $('#endpoint_2_interface_uri').val();
    endpoint2["vlan"] = $('#endpoint_2_vlan').val();

    if (endpoint1["vlan"] == 'number' || endpoint1["vlan"] == 'VLAN range') {
      endpoint1["vlan"] = $('#endpoint_1_vlan_value').val();
    }

    if (endpoint2["vlan"] == 'number' || endpoint2["vlan"] == 'VLAN range') {
      endpoint2["vlan"] = $('#endpoint_2_vlan_value').val();
    }

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

    var request = {
      "name": name,
      "endpoints": results
    };

    if (description) {
      request.description = description;
    }

    // =========================== Scheduling Assertion Starts =========================== //

    var scheduling = {};
    function convertToEST(dateStr) {
      if (!dateStr) return null;
      var date = new Date(dateStr);
      var estDate = new Date(date.toLocaleString('en-US', { timeZone: 'America/New_York' }));
      return estDate.toISOString(); //.split('.')[0];
    }

    if (start_time) {
      start_time = convertToEST(start_time);
      scheduling.start_time = start_time;
    }
    if (end_time) {
      end_time = convertToEST(end_time);
      scheduling.end_time = end_time;
    }
    if (end_time) {
      if (start_time > end_time) {
        alert("Enter valid Dates");
        return;
      }
    }
    if (Object.keys(scheduling).length > 0) {
      request.scheduling = scheduling;
    }

    // =========================== QOS_Metrix Assertion Starts =========================== //

    var qos_metrics = {};
    if (min_bw) {
      qos_metrics.min_bw = {
        "value": parseInt(min_bw),
        "strict": min_bw_strict
      };
    }
    if (max_delay) {
      qos_metrics.max_delay = {
        "value": parseInt(max_delay),
        "strict": max_delay_strict
      };
    }
    if (max_number_oxps) {
      qos_metrics.max_number_oxps = {
        "value": parseInt(max_number_oxps),
        "strict": max_number_oxps_strict
      };
    }
    if (Object.keys(qos_metrics).length > 0) {
      request.qos_metrics = qos_metrics;
    }

    // =========================== Notifications Assertion Starts =========================== //

    var notifications = [];
    const initialNotification = $('#notification_1').val();
    if (initialNotification) {
      notifications.push({
        "email": initialNotification
      });
    }

    const notificationFields = document.querySelectorAll('.notification-field input[type="email"]');
    notificationFields.forEach(field => {
      notifications.push({
        "email": field.value
      });
    });

    if (notifications.length > 0) {
      request.notifications = notifications;
    }

    // =========================== Assertion Ends =========================== //

    console.log(request);
    console.log(JSON.stringify(request));

        $.ajax({
        type: "POST",
        url: "https://"+meican_url+"/circuits/nodes/create",
        data: JSON.stringify(request),
        contentType: "application/json; charset=utf-8",
        success: function(data){alert(data);},
        error: function(errMsg) {
            alert(errMsg);
        }
    });

  });

  function validateVlanInput(input) {
    if (input.valueType.includes('range')) {
      input.value = input.value.replace(/[^0-9:]/g, '');
      const regex = /^(\d{1,4}:\d{1,4})?$/;
      if (!regex.test(input.value)) {
          input.setCustomValidity("Please enter a valid VLAN range (1-4095 : 1-4095)");
      } else {
          const parts = input.value.split(':');
          if (parts.length === 2) {
              const [start, end] = parts.map(Number);
              if (isNaN(start) || isNaN(end) || start < 1 || start > 4095 || end < 1 || end > 4095 || start > end || start == end) {
                  input.setCustomValidity("Please enter a valid VLAN range (1-4095 : 1-4095)");
              } else {
                  input.setCustomValidity("");
              }
          } else {
              input.setCustomValidity("Please enter a valid VLAN range (1-4095 : 1-4095)");
          }
      }
    } else {
      input.value = input.value.replace(/[^0-9]/g, '');
      const vlanNumber = parseInt(input.value, 10);
      if (isNaN(vlanNumber) || vlanNumber < 1 || vlanNumber > 4095) {
        input.setCustomValidity("Please enter a valid VLAN ID between 1 and 4095.");
      } else {
        input.setCustomValidity("");
      }
    }
  }


  // Validating qos metrix input fields
  function validateInput(input, max) {
    input.value = input.value.replace(/[^0-9]/g, '');
    if (input.value > max) {
      input.value = max;
    }
  }

  function validateDate(input) {
    const parts = input.value.split('-');
    if (parts.length === 3) {
      parts[0] = parts[0].substring(0, 4);
      input.value = parts.join('-');
    }
  }

  function appendFields() {
    const container = document.getElementById('field-container');

    const newDiv = document.createElement('div');
    newDiv.className = 'field-group';
    newDiv.innerHTML += 'Port ID:'

    const interfaceSelect = document.createElement('select');
    interfaceSelect.name = 'interface';
    interfaceSelect.className = 'form-control';

    <?php
    foreach ($nodes_array as $key => $value) {
      foreach ($value['sub_nodes'] as $key2 => $value2) {
        foreach ($value2['ports'] as $key3 => $value3) {
          echo "interfaceSelect.innerHTML += '<option value=\"" . $value3['id'] . "\">" . str_replace("urn:sdx:port:", "",$value3['id']) . "</option>';";
        }
      }
    }
    ?>


    const vlanSelect = document.createElement('select');
    vlanSelect.name = 'vlan';
    vlanSelect.className = 'form-control';
    vlanSelect.onchange = function() {
      handleVlanChange(newDiv, vlanSelect.value);
    };
    vlanSelect.innerHTML = `
      <option value="any" title="Any available VLAN ID chosen">any</option>
      <option value="number" title="Specific VLAN ID, e.g., '50'">VLAN ID</option>
      <option value="untagged" title="Transport Ethernet frames without IEEE 802.1Q Ethertype">untagged</option>
      <option value="VLAN range" title="Range of VLANs, e.g., '50:55'">VLAN range</option>
      <option value="all" title="Transport all Ethernet frames with and without IEEE 802.Q Ethertype">all</option>
    `;

    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.innerText = 'Delete';
    deleteButton.className = 'btn btn-primary deleteButton';
    deleteButton.id = 'deleteButton';
    deleteButton.style.backgroundColor = "red";
    deleteButton.onclick = function() {
      container.removeChild(newDiv);
    };

    newDiv.appendChild(interfaceSelect);
    newDiv.innerHTML += '<br>VLAN:</br>';
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
      vlanInput.placeholder = value === 'number' ? 'Enter VLAN ID (1-4095)' : 'Enter VLAN Range (e.g., 50:55)';
      vlanInput.className = 'form-control';
      vlanInput.valueType = value;
      vlanInput.oninput = function() {
        validateVlanInput(vlanInput);
      };
      temp = container.querySelector('button[id="deleteButton"]');
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
      inputField.placeholder = dropdown.value === 'number' ? 'Enter VLAN ID (1-4095)' : 'Enter VLAN Range (e.g., 50:55)';
      inputField.name = 'endpoint_1_vlan_value';
      inputField.id = 'endpoint_1_vlan_value';
      inputField.className = 'form-control';
      inputField.valueType = dropdown.value;
      inputField.oninput = function() {
        validateVlanInput(inputField);
      };
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
      inputField.placeholder = dropdown2.value === 'number' ? 'Enter VLAN ID (1-4095)' : 'Enter VLAN Range (e.g., 50:55)';
      inputField.name = 'endpoint_2_vlan_value';
      inputField.id = 'endpoint_2_vlan_value';
      inputField.className = 'form-control';
      inputField.valueType = dropdown2.value;
      inputField.oninput = function() {
        validateVlanInput(inputField);
      };
      inputContainer2.appendChild(inputField);
    }
  });

  function appendNotification() {
    const container = document.getElementById('notification-container');
    const notificationCount = container.getElementsByClassName('notification-field').length;

    if (notificationCount >= 10) {
      alert('You can only add up to 10 Emails.');
      return;
    }

    const newDiv = document.createElement('div');
    newDiv.className = 'form-group notification-field';

    const inputField = document.createElement('input');
    inputField.type = 'email';
    inputField.className = 'form-control';
    inputField.name = `notification_${notificationCount + 1}`;
    inputField.placeholder = `Notification Email ${notificationCount + 1} (optional)`;

    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.className = 'btn btn-danger deleteButton';
    deleteButton.innerText = 'Delete';
    deleteButton.style.backgroundColor = "red";
    deleteButton.onclick = function() {
      container.removeChild(newDiv);
    };

    newDiv.appendChild(inputField);
    newDiv.appendChild(deleteButton);
    container.appendChild(newDiv);
  }

  

  // setTimeout(function(){
  //   refreshMapData();
  //   console.log("timeout working");
    
  // }, 10000);

  // setInterval(refreshMapData, 10000);
</script>

    <script>
        const toggle = document.getElementById('autoRefreshToggle');
        const statusText = document.getElementById('statusText');

        // Example function to demonstrate auto-refresh processing
        function autoRefreshProcessing() {
            if (!toggle.checked) return; // Do nothing if auto-refresh is off
            console.log('Auto-Refresh is processing...');
            refreshMapData();
        }

        // Update status text and handle auto-refresh state
        toggle.addEventListener('change', () => {
            if (toggle.checked) {
                statusText.textContent = 'Auto-Refresh is ON';
                console.log('Auto-Refresh activated');
                // Call autoRefreshProcessing() at intervals when toggled on
                const interval = setInterval(() => {
                    if (!toggle.checked) {
                        clearInterval(interval); // Stop when toggled off
                        console.log('Auto-Refresh stopped');
                        return;
                    }
                    autoRefreshProcessing();
                }, 9000); // Example: Run every 3 seconds
            } else {
                statusText.textContent = 'Auto-Refresh is OFF';
                console.log('Auto-Refresh deactivated');
            }
        });
    </script>

        <script>
        function toggleAdvancedOptions() {
            var advancedOptions = document.getElementById("advancedOptions");
            var button = document.querySelector("button[onclick='toggleAdvancedOptions()']");

            if (advancedOptions.style.display === "none" || advancedOptions.style.display === "") {
                advancedOptions.style.display = "block";
                button.textContent = "Hide Advanced Options";
            } else {
                advancedOptions.style.display = "none";
                button.textContent = "Show Advanced Options";
            }
        }
    </script>

</html>