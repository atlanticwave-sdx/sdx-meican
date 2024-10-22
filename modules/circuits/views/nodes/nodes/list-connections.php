
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
   height: 560px;
   overflow-y: auto;
   font-family: "Helvetica Neue";
   font-size: 16px;
}

.modal-title {
  font-weight: bold;
  font-size: 24px;
  font-family: Arial, sans-serif;
}
.modal-header {
   display: flex;
   /* justify-content: space-between; */
   /* align-items: center; */
}

      
   </style>
   

</head>
<body>


  <div class="row">
        
   <section class="content-header">
      <h1>
         List
         <small>Home &gt; SDX Circuits &gt; View</small>
      </h1>
   </section>
   <section class="content">
      <div class="box box-default">
         <div class="box-header with-border">
            <h3 class="box-title">Connections</h3>
            
         </div>
         <div class="box-body">
            <div id="circuits-pjax" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
               <ul id="w0" class="nav nav-tabs">
                  <li class="active"><a href="#tabCurrent" data-toggle="tab">Current</a></li>
                  <li><a href="#tabPast" data-toggle="tab">Past</a></li>
               </ul>
               <div class="tab-content">
                  <div id="tabCurrent" class="tab-pane active">
                     <div id="circuits-gridcurrent" class="grid-view">
                        <div class="table-responsive">
                           <table class="table table-striped">
                              <thead>
                                 <tr>
                                    <th style="width: 20%;">Name</th>
                                    <th style="width: 25%;">Connection ID</th>
                                    <th style="width: 25%;">Description</th>
                                    <th style="width: 22%;">EndPoints</th>
                                 </tr>
                                 
                                 <?php
                                    if (!empty($str_response)) {
                                       $connectionsData = json_decode($str_response, true);
                                       if (is_array($connectionsData) && json_last_error() === JSON_ERROR_NONE) {
                                          foreach ($connectionsData as $connectionId => $connectionInfo) {
                                             ?>
                                                <tr id="circuits-gridcurrent-filters" class="filters">
                                                   <td><?php echo isset($connectionInfo['name']) ? $connectionInfo['name'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['service_id']) ? $connectionInfo['service_id'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['description']) ? $connectionInfo['description'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['endpoints']) ? implode(', ', array_column($connectionInfo['endpoints'], 'port_id')) : ''; ?></td>
                                                   <td><button type="button" class="btn btn-primary view-connection" data-connection='<?php echo json_encode($connectionInfo); ?>'>View | Edit</button></td>
                                                   <td><button type="submit" class="btn btn-primary delete-connection" delete-connection='<?php echo json_encode($connectionInfo); ?>' style="background-color:red;">Delete</button></td>
                                                </tr>
                                             <?php
                                          }
                                          
                                       }
                                    }
                                 ?>
                                 
                              </thead>
                              <tbody>
                                 <!-- <tr>
                                    <td colspan="10">
                                       <div class="empty">No results found.</div>
                                    </td>
                                 </tr> -->
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                 
                  <div id="tabPast" class="tab-pane">
                     <div id="circuits-gridpast" class="grid-view">
                        <div class="table-responsive">
                            <table class="table table-striped">
                              <thead>
                                 <tr>
                                    <th style="width: 20%;">Name</th>
                                    <th style="width: 25%;">Connection ID</th>
                                    <th style="width: 25%;">Description</th>
                                    <th style="width: 22%;">EndPoints</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <!-- <tr>
                                    <td colspan="10">
                                       <div class="empty">No results found.</div>
                                    </td>
                                 </tr> -->
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
             
            </div>
         </div>
      </div>
   </section>

   <!-- View Modal -->
   <div id="jsonModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header d-flex">
               <h4 class="modal-title">Connection Details</h4>
               <div style="margin-left: 270px;">
                  <button type="button" class="btn btn-primary" id="edit-btn" style="padding-left: 20px; padding-right: 20px; font-size: 16px;">Edit</button>
                  <!-- <button type="button" class="close" data-dismiss="modal" >&times;</button> -->
               </div>
            </div>
            <div class="modal-body">
               <div id="jsonContent"></div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal" style="font-size: 16px;">Close</button>
            </div>
         </div>
      </div>
   </div>

   <!-- Edit Modal -->
   <div id="editModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <div class="modal-content">
               <div class="modal-header d-flex justify-content-between align-items-center">
                  <h4 class="modal-title">Edit Connection</h4>
                  <button type="button" class="close d-flex justify-content-end" data-dismiss="modal">&times;</button>
               </div>
               <div class="modal-body">
                  <form id="editForm">
                     <!-- Name -->
                     <div class="form-group">
                           <label for="edit-name" class="required">Name</label>
                           <input type="text" class="form-control" id="edit-name" name="name" placeholder="Name" maxlength="50" required>
                     </div>

                     <!-- Endpoints -->
                     <div class="form-group">
                        <label class="required">Endpoints</label>

                        <!-- Endpoint 1 -->
                        <div class="endpoint-field">
                           <br>Interface:</br>
                           <select class="form-control" id="edit-endpoint_1_interface_uri" name="endpoint_1_interface_uri" required>
                           </select>

                           <br>VLAN:</br>
                           <select class="form-control vlan-dropdown" id="edit-endpoint_1_vlan" name="endpoint_1_vlan" required>
                                 <option value="any" title="Any available VLAN ID is chosen">any</option>
                                 <option value="number" title="Specific VLAN ID, e.g., '50'">VLAN ID</option>
                                 <option value="untagged" title="Transports Ethernet frames without IEEE 802.1Q Ethertype">untagged</option>
                                 <option value="VLAN range" title="Range of VLANs, e.g., '50:55'">VLAN range</option>
                                 <option value="all" title="Transport all Ethernet frames with and without IEEE 802.Q Ethertype">all</option>
                           </select>
                           <div id="edit-endpoint_1_vlan-input-container" class="input-container"></div>
                        </div>

                        <!-- Endpoint 2 -->
                        <div class="endpoint-field">
                           <br>Interface:</br>
                           <select class="form-control" id="edit-endpoint_2_interface_uri" name="endpoint_2_interface_uri" required>
                           </select>

                           <br>VLAN:</br>
                           <select class="form-control vlan-dropdown" id="edit-endpoint_2_vlan" name="endpoint_2_vlan" required>
                                 <option value="any">any</option>
                                 <option value="number">VLAN ID</option>
                                 <option value="untagged">untagged</option>
                                 <option value="VLAN range">VLAN range</option>
                                 <option value="all">all</option>
                           </select>
                           <div id="edit-endpoint_2_vlan-input-container" class="input-container"></div>
                        </div>
                     </div>

                     
                     <div id="edit-field-container">

                     </div>
                     <button type="button" class="btn btn-primary" onclick="appendFields()">Add Endpoint</button>

                     <!-- Description -->
                     <div class="form-group">
                           <label for="edit-description">Description</label>
                           <textarea class="form-control" id="edit-description" name="description" maxlength="255"></textarea>
                     </div>

                     <!-- Scheduling -->
                     <div class="form-group">
                           <label>Start time (optional)</label>
                           <input type="date" class="form-control" id="edit-start_time" name="start_time">
                     </div>

                     <div class="form-group">
                           <label>End time (optional)</label>
                           <input type="date" class="form-control" id="edit-end_time" name="end_time">
                     </div>

                     <!-- QoS Metrics -->
                     <div class="form-group">
                           <label for="edit-min_bw">Minimum Bandwidth (Gbps)</label>
                           <input type="number" class="form-control" id="edit-min_bw" name="min_bw" min="0" max="100" step="1" oninput="validateInput(this, 100)">
                           <label><input type="checkbox" id="edit-min_bw_strict" name="min_bw_strict"> Strict</label>
                     </div>

                     <div class="form-group">
                           <label for="edit-max_delay">Maximum Delay (ms)</label>
                           <input type="number" class="form-control" id="edit-max_delay" name="max_delay" min="0" max="1000" step="1" oninput="validateInput(this, 1000)">
                           <label><input type="checkbox" id="edit-max_delay_strict" name="max_delay_strict"> Strict</label>
                     </div>

                     <div class="form-group">
                           <label for="edit-max_number_oxps">Maximum Number of OXPs</label>
                           <input type="number" class="form-control" id="edit-max_number_oxps" name="max_number_oxps" min="0" max="100" step="1" oninput="validateInput(this, 100)">
                           <label><input type="checkbox" id="edit-max_number_oxps_strict" name="max_number_oxps_strict"> Strict</label>
                     </div>

                     <!-- Notifications -->
                     <div id="edit-notification-container">
                     </div>
                     <button type="button" class="btn btn-primary" onclick="appendNotification()">Add Notification</button>

                  </form>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-primary save-changes-button">Save Changes</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
         </div>
      </div>
   </div>


   <script type="text/javascript">
      var meican_url = "<?php echo MEICAN_URL; ?>";
      let nodesArray = [];
      let startDateChanged = false;
      let endDateChanged = false;
      let originalStartDate = '';
      let originalEndDate = '';
      let editconnectionId = null;
      

      //////////////////////// View Modal related functions start ////////////////////////
      function openModal(jsonData) {
         const modal = $('#jsonModal');
         const content = $('#jsonContent');
         content.html(formatJsonData(jsonData));
         modal.modal('show');
      }

      function formatJsonData(data) {

         const key = Object.keys(data)[0];
         data = data[key];

         let formattedData = '';

         formattedData += `<strong>Id:</strong> ${data.service_id || ''}<br>`;
         editconnectionId = data.service_id;

         formattedData += `<strong>Name:</strong> ${data.name || ''}<br>`;
         if(data.description!==undefined){
         formattedData += `<strong>Description:</strong> ${data.description || ''}<br>`;
         }

         if(data.scheduling!==undefined){
            if(data.scheduling.start_time!==undefined){
         formattedData += `<strong>Start Time:</strong> ${data.scheduling.start_time || ''}<br>`;
         }
         }
         
         if(data.scheduling!==undefined){
            if(data.scheduling.end_time!==undefined){
         formattedData += `<strong>End Time:</strong> ${data.scheduling.end_time || ''}<br>`;
         }
         }

         if(data.qos_metrics!==undefined){
         formattedData += `<strong>QoS Metrics:</strong><br>`;
         formattedData += `<div style="padding-left: 20px;">${formatQosMetrics(data.qos_metrics)}</div>`;
         }

         if(data.notifications!==undefined){
         formattedData += `<strong>Notifications:</strong><br>`;
         data.notifications.forEach((notification, index) => {
            formattedData += `<div style="padding-left: 20px;"><strong>Email ${index + 1}: </strong>${formatNotificationData(notification)}</div></div>`;
         });
         }

         formattedData += `<strong>Endpoints:</strong><br>`;
         data.endpoints.forEach((endpoint, index) => {
            formattedData += `<div style="padding-left: 20px;"><strong>Interface ${index + 1}:</strong><br>`;
            formattedData += `<div style="padding-left: 20px;">${formatEndpointData(endpoint)}</div></div>`;
         });

         return formattedData;
      }

      // Function to format each endpoint field
      function formatEndpointData(endpoint) {
         let formattedEndpointData = '';
         formattedEndpointData += `<strong>ID:</strong> ${endpoint.port_id || ''}<br>`;
         // Needs to be chnaged to vlan_range => vlan
         formattedEndpointData += `<strong>VLAN:</strong> ${endpoint.vlan || ''}<br>`;
         return formattedEndpointData;
      }

      // Function to format each notification field
      function formatNotificationData(notification) {
         let formattedNotificationData = '';
         formattedNotificationData += `${notification.email || ''}<br>`;
         return formattedNotificationData;
      }

      // Function to format QoS metrics
      function formatQosMetrics(qosMetrics) {
         let formattedQosMetrics = '';
         if (qosMetrics) {
            if(qosMetrics.min_bw!==undefined){
               formattedQosMetrics += `<strong>Minimum Bandwidth:</strong> ${qosMetrics.min_bw.value || ''} (Strict: ${qosMetrics.min_bw.strict ? 'Yes' : 'No'})<br>`;
            }
            if(qosMetrics.max_delay!==undefined){
               formattedQosMetrics += `<strong>Maximum Delay:</strong> ${qosMetrics.max_delay.value || ''} (Strict: ${qosMetrics.max_delay.strict ? 'Yes' : 'No'})<br>`;
            }
            if(qosMetrics.max_number_oxps!==undefined){
               formattedQosMetrics += `<strong>Maximum OXPs:</strong> ${qosMetrics.max_number_oxps.value || ''} (Strict: ${qosMetrics.max_number_oxps.strict ? 'Yes' : 'No'})<br>`;
            }
         }
         return formattedQosMetrics;
      }
      
      $(document).on('click', '.view-connection', function () {
         const connectionData = $(this).attr('data-connection');
         const parsedData = JSON.parse(connectionData);
         const connectionId = parsedData.service_id;
         const meican_url="<?php echo MEICAN_URL;?>";

         $.ajax({
            url: "https://"+meican_url+"/circuits/nodes/connection",
            type: "GET",
            data: { connectionId: connectionId },
            contentType: "application/json; charset=utf-8",
            success: function(data){
               openModal(JSON.parse(data));
            }
         });

         $.ajax({
            url: "https://"+meican_url+"/circuits/nodes/refreshtopology",
            type: "GET",
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                  let jsonData = null;
                  if (typeof data != null) {
                     try {
                        jsonData = JSON.parse(data);
                        
                        nodesArray = [];
                        Object.values(jsonData.nodes).forEach(region => 
                              region.sub_nodes.forEach(subNode => 
                                 subNode.ports.forEach(port => nodesArray.push(port))
                              )
                        );
                     } catch (e) {
                        console.error("Error parsing JSON data:", e);
                     }
                  } else {
                     console.warn("Empty data returned from the server.");
                  }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                  console.error("Error:", textStatus, errorThrown);
                  alert("Error occurred: " + textStatus);
            }
         });

      });
      //////////////////////// View Modal related functions end ////////////////////////


      $(document).on('click', '.delete-connection', function () {
         const connectionData = $(this).attr('delete-connection');
         const parsedData = JSON.parse(connectionData);
         const connectionId = parsedData.id;
         const meican_url="<?php echo MEICAN_URL;?>";
         const row = $(this).closest('tr');

         $.ajax({
            url: "https://"+meican_url+"/circuits/nodes/delete",
            type: "GET",
            data: { connectionId: connectionId },
            contentType: "application/json; charset=utf-8",
            success: function(data, textStatus, jqXHR) {
               row.remove();
               alert("Status: " + jqXHR.status + " - Response: " + data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                  alert("Status: " + jqXHR.status + " - Error: " + errorThrown);
            }
         });
      });


      //////////////////////// Endpoints related functions start ////////////////////////
      function populateEndpointSelectOptions(selectElement, selectedValue = '') {
         selectElement.empty();

         if (nodesArray && nodesArray.length > 0) {
            nodesArray.forEach(node => {
                  const option = new Option(node.id, node.id, false, node.id === selectedValue);
                  selectElement.append(option);
            });
         } else {
            selectElement.append(new Option('No interfaces available', ''));
         }
      }

      function getEndpoints() {
         const endpoints = [];

         $('.endpoint-field').each(function(index) {
            const interfaceUri = $(this).find('select[name*="interface_uri"]').val();
            const vlanDropdown = $(this).find('select[name*="vlan"]');
            let vlanValue = vlanDropdown.val();

            if (vlanValue === 'number' || vlanValue === 'VLAN range') {
                  vlanValue = $(this).find('input[id*="-input"]').val();
            }

            if (interfaceUri && vlanValue) {
                  endpoints.push({
                     port_id: interfaceUri,
                     vlan: vlanValue
                  });
            } else {
                  console.warn(`Skipping endpoint ${index + 1} due to missing interface URI or VLAN value.`);
            }
         });

         return endpoints;
      }

      function appendFields(endpoint = { id: '' }) {
         const container = document.getElementById('edit-field-container');

         const newDiv = document.createElement('div');
         newDiv.className = 'field-group endpoint-field';
         newDiv.innerHTML += 'Interface:';

         const interfaceSelect = document.createElement('select');
         interfaceSelect.name = `endpoint_${container.children.length + 1}_interface_uri`;
         interfaceSelect.className = 'form-control';

         const newIndex = container.children.length + 3;
         $(interfaceSelect).attr('id', `edit-endpoint_${newIndex}_interface_uri`);

         populateEndpointSelectOptions($(interfaceSelect), endpoint.id || '');

         newDiv.appendChild(interfaceSelect);

         const vlanSelect = document.createElement('select');
         vlanSelect.name = `endpoint_${container.children.length + 1}_vlan`;
         vlanSelect.className = 'form-control vlan-dropdown';
         vlanSelect.style.marginTop = "10px";
         $(vlanSelect).attr('id', `edit-endpoint_${newIndex}_vlan`);
         vlanSelect.onchange = function () {
            handleVlanChange(vlanSelect, newDiv.querySelector('.input-container'));
         };
         vlanSelect.innerHTML = `
            <option value="any" title="Any available VLAN ID chosen">any</option>
            <option value="number" title="Specific VLAN ID, e.g., '50'">VLAN ID</option>
            <option value="untagged" title="Transport Ethernet frames without IEEE 802.1Q Ethertype">untagged</option>
            <option value="VLAN range" title="Range of VLANs, e.g., '50:55'">VLAN range</option>
            <option value="all" title="Transport all Ethernet frames with and without IEEE 802.Q Ethertype">all</option>
         `;

         const inputContainer = document.createElement('div');
         inputContainer.className = 'input-container';

         const deleteButton = document.createElement('button');
         deleteButton.type = 'button';
         deleteButton.innerText = 'Delete';
         deleteButton.className = 'btn btn-primary deleteButton';
         deleteButton.style.backgroundColor = "red";
         deleteButton.style.marginTop = "10px";
         deleteButton.style.marginBottom = "10px";
         deleteButton.onclick = function () {
            container.removeChild(newDiv);
            reIndexEndpoints();
         };

         newDiv.appendChild(vlanSelect);
         newDiv.appendChild(inputContainer);
         newDiv.appendChild(deleteButton);
         container.appendChild(newDiv);

         populateVlanField($(vlanSelect), $(inputContainer), endpoint.vlan || 'any');
      }


      function reIndexEndpoints() {
         $('#edit-field-container .endpoint-field').each(function(index) {
            $(this).find('select[name*="interface_uri"]').attr('name', `endpoint_${index + 3}_interface_uri`);
            $(this).find('select[name*="interface_uri"]').attr('id', `edit-endpoint_${index + 3}_interface_uri`);
            
            $(this).find('select[name*="vlan"]').attr('name', `endpoint_${index + 3}_vlan`);
            $(this).find('select[name*="vlan"]').attr('id', `edit-endpoint_${index + 3}_vlan`);
            
            $(this).find('.input-container').attr('id', `edit-endpoint_${index + 3}_vlan-input-container`);
         });
      }

      function validateVlanInput(input, valueType) {
         let errorMessage = '';

         if (valueType === 'range') {
            input.value = input.value.replace(/[^0-9:]/g, '');
            const regex = /^(\d{1,4}:\d{1,4})?$/;
            if (!regex.test(input.value)) {
                  errorMessage = "Please enter a valid VLAN range (1-4095:1-4095)";
            } else {
                  const parts = input.value.split(':');
                  if (parts.length === 2) {
                     const [start, end] = parts.map(Number);
                     if (isNaN(start) || isNaN(end) || start < 1 || start > 4095 || end < 1 || end > 4095 || start >= end) {
                        errorMessage = "Please enter a valid VLAN range (1-4095:1-4095)";
                     }
                  } else {
                     errorMessage = "Please enter a valid VLAN range (1-4095:1-4095)";
                  }
            }
         } else if (valueType === 'number') {
            input.value = input.value.replace(/[^0-9]/g, '');
            const vlanNumber = parseInt(input.value, 10);
            if (isNaN(vlanNumber) || vlanNumber < 1 || vlanNumber > 4095) {
                  errorMessage = "Please enter a valid VLAN ID between 1 and 4095.";
            }
         }

         let errorContainer = input.nextElementSibling;
         if (!errorContainer || !errorContainer.classList.contains('error-message')) {
            errorContainer = document.createElement('small');
            errorContainer.classList.add('error-message');
            errorContainer.style.color = 'red';
            input.parentNode.appendChild(errorContainer);
         }

         if (errorMessage) {
            errorContainer.textContent = errorMessage;
            input.setCustomValidity(errorMessage);
         } else {
            errorContainer.textContent = '';
            input.setCustomValidity('');
         }
      }

      function populateVlanField(vlanDropdown, inputContainer, vlanValue) {
         vlanDropdown.data('original-vlan', vlanValue);

         if (vlanValue === 'any' || vlanValue === 'untagged' || vlanValue === 'all' || vlanValue === undefined) {
            vlanDropdown.val(vlanValue).trigger('change');
            inputContainer.empty();
         } else if (vlanValue && vlanValue.includes(':')) {
            vlanDropdown.val('VLAN range').trigger('change');
            inputContainer.html(`
                  <label for="${vlanDropdown.attr('id')}-input">VLAN Range:</label>
                  <input type="text" id="${vlanDropdown.attr('id')}-input" class="form-control" value="${vlanValue}" oninput="validateVlanInput(this, 'range')" placeholder="Enter VLAN Range (1-4095:1-4095)">
                  <small class="error-message" style="color:red"></small>
            `);
         } else if (!isNaN(vlanValue)) {
            vlanDropdown.val('number').trigger('change');
            inputContainer.html(`
                  <label for="${vlanDropdown.attr('id')}-input">VLAN ID:</label>
                  <input type="text" id="${vlanDropdown.attr('id')}-input" class="form-control" value="${vlanValue}" oninput="validateVlanInput(this, 'number')" placeholder="Enter VLAN ID (1-4095)">
                  <small class="error-message" style="color:red"></small>
            `);
         } else {
            vlanDropdown.val('any').trigger('change');
            inputContainer.empty();
         }
      }


      function handleVlanChange(vlanDropdownElement, inputContainerElement) {
         const vlanDropdown = $(vlanDropdownElement);
         const selectedValue = vlanDropdown.val();

         $(inputContainerElement).empty();

         if (selectedValue === 'number') {
            $(inputContainerElement).html(`
                  <label for="${vlanDropdown.attr('id')}-input">VLAN ID:</label>
                  <input type="text" id="${vlanDropdown.attr('id')}-input" class="form-control" value="" 
                        oninput="validateVlanInput(this, 'number')" placeholder="Enter VLAN ID (1-4095)">
                  <small class="error-message" style="color:red"></small>
            `);
         } else if (selectedValue === 'VLAN range') {
            $(inputContainerElement).html(`
                  <label for="${vlanDropdown.attr('id')}-input">VLAN Range:</label>
                  <input type="text" id="${vlanDropdown.attr('id')}-input" class="form-control" value="" 
                        oninput="validateVlanInput(this, 'range')" placeholder="Enter VLAN Range (1-4095:1-4095)">
                  <small class="error-message" style="color:red"></small>
            `);
         }
      }
      //////////////////////// Endpoints related functions end ////////////////////////


      //////////////////////// Scheduling related functions start ////////////////////////
      function setMinDate() {
         const currentDate = new Date();
         const minDate = new Date(currentDate);
         minDate.setDate(minDate.getDate());
         
         const formattedMinDate = minDate.toISOString().split('T')[0];

         document.getElementById('edit-start_time').setAttribute('min', formattedMinDate);
         document.getElementById('edit-end_time').setAttribute('min', formattedMinDate);
      }

      document.addEventListener('DOMContentLoaded', setMinDate);

      function validateDateFields() {
         const startDateInput = document.getElementById('edit-start_time');
         const endDateInput = document.getElementById('edit-end_time');
         const currentDate = new Date().toISOString().split('T')[0];
         let isValid = true;

         const startDate = startDateInput.value;
         const endDate = endDateInput.value;

         clearDateErrors();

         if (startDate !== originalStartDate && startDate < currentDate) {
            displayDateError(startDateInput, 'Start date cannot be in the past.');
            isValid = false;
         }

         if (endDate !== originalEndDate && endDate < currentDate) {
            displayDateError(endDateInput, 'End date cannot be in the past.');
            isValid = false;
         }

         if (startDate && endDate && startDate > endDate) {
            displayDateError(startDateInput, 'Start date cannot be greater than end date.');
            displayDateError(endDateInput, 'End date cannot be earlier than start date.');
            isValid = false;
         }

         // if (startDate == endDate) {
         //    displayDateError(startDateInput, 'Start date and End date cannot be the same.');
         //    isValid = false;
         // }

         return isValid;
      }

      document.getElementById('edit-start_time').addEventListener('input', function() {
         validateDateFields();
      });

      document.getElementById('edit-end_time').addEventListener('input', function() {
         validateDateFields();
      });

      function clearDateErrors() {
         const dateInputs = document.querySelectorAll('#edit-start_time, #edit-end_time');
         dateInputs.forEach(input => {
            const errorContainer = input.nextElementSibling;
            if (errorContainer && errorContainer.classList.contains('error-message')) {
               errorContainer.textContent = '';
            }
            input.setCustomValidity('');
         });
      }

      function displayDateError(input, message) {
         let errorContainer = input.nextElementSibling;
         if (!errorContainer || !errorContainer.classList.contains('error-message')) {
            errorContainer = document.createElement('small');
            errorContainer.classList.add('error-message');
            errorContainer.style.color = 'red';
            input.parentNode.appendChild(errorContainer);
         }
         errorContainer.textContent = message;
         input.setCustomValidity(message);
      }
      //////////////////////// Scheduling related functions end ////////////////////////


      function getNotifications() {
         const notifications = [];
         $('#edit-notification-container .notification-field').each(function() {
            const email = $(this).find('input').val();
            if (email) {
                  notifications.push({ email });
            }
         });
         return notifications;
      }

      function appendNotification(email = '') {
         const container = document.getElementById('edit-notification-container');
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
         inputField.value = email;

         const deleteButton = document.createElement('button');
         deleteButton.type = 'button';
         deleteButton.className = 'btn btn-danger deleteButton';
         deleteButton.innerText = 'Delete';
         deleteButton.style.backgroundColor = "red";
         deleteButton.style.marginTop = "10px";
         deleteButton.onclick = function() {
            container.removeChild(newDiv);
         };

         newDiv.appendChild(inputField);
         newDiv.appendChild(deleteButton);
         container.appendChild(newDiv);
      }

      function validateInput(input, max) {
         input.value = input.value.replace(/[^0-9]/g, '');
         if (input.value > max) {
            input.value = max;
         }
      }

      function openEditModal(connectionData) {
         
         $('#edit-endpoint_1_interface_uri').empty();
         $('#edit-endpoint_2_interface_uri').empty();
         $('#edit-endpoint_1_vlan-input-container').empty();
         $('#edit-endpoint_2_vlan-input-container').empty();
         $('#edit-field-container').empty();

         const key = Object.keys(connectionData)[0];
         connectionData = connectionData[key];
         console.log(connectionData);

         connectionData.endpoints.forEach((endpoint, index) => {
            if (index === 0) {
                  populateEndpointSelectOptions($('#edit-endpoint_1_interface_uri'), endpoint.port_id || '');
                  populateVlanField($('#edit-endpoint_1_vlan'), $('#edit-endpoint_1_vlan-input-container'), endpoint.vlan);
            } else if (index === 1) {
                  populateEndpointSelectOptions($('#edit-endpoint_2_interface_uri'), endpoint.port_id || '');
                  populateVlanField($('#edit-endpoint_2_vlan'), $('#edit-endpoint_2_vlan-input-container'), endpoint.vlan);
            } else {
                  appendFields(endpoint);
            }
         });

         $('#edit-name').val(connectionData.name || '');
         $('#edit-description').val(connectionData.description || '');

         if (connectionData.scheduling && connectionData.scheduling.start_time) {
            originalStartDate = connectionData.scheduling.start_time.substring(0, 10);
            $('#edit-start_time').val(originalStartDate);
         } else {
            $('#edit-start_time').val('');
         }

         if (connectionData.scheduling && connectionData.scheduling.end_time) {
            originalEndDate = connectionData.scheduling.end_time.substring(0, 10);
            $('#edit-end_time').val(originalEndDate);
         } else {
            $('#edit-end_time').val('');
         }

         if (connectionData.qos_metrics) {
            if (connectionData.qos_metrics.min_bw) {
                  $('#edit-min_bw').val(connectionData.qos_metrics.min_bw.value);
                  $('#edit-min_bw').attr('placeholder', '');
                  $('#edit-min_bw_strict').prop('checked', connectionData.qos_metrics.min_bw.strict);
            } else {
                  $('#edit-min_bw').val('').attr('placeholder', '(optional)');
                  $('#edit-min_bw_strict').prop('checked', false);
            }

            if (connectionData.qos_metrics.max_delay) {
                  $('#edit-max_delay').val(connectionData.qos_metrics.max_delay.value);
                  $('#edit-max_delay').attr('placeholder', '');
                  $('#edit-max_delay_strict').prop('checked', connectionData.qos_metrics.max_delay.strict);
            } else {
                  $('#edit-max_delay').val('').attr('placeholder', '(optional)');
                  $('#edit-max_delay_strict').prop('checked', false);
            }

            if (connectionData.qos_metrics.max_number_oxps) {
                  $('#edit-max_number_oxps').val(connectionData.qos_metrics.max_number_oxps.value);
                  $('#edit-max_number_oxps').attr('placeholder', '');
                  $('#edit-max_number_oxps_strict').prop('checked', connectionData.qos_metrics.max_number_oxps.strict);
            } else {
                  $('#edit-max_number_oxps').val('').attr('placeholder', '(optional)');
                  $('#edit-max_number_oxps_strict').prop('checked', false);
            }
         } else {
            $('#edit-min_bw').val('').attr('placeholder', '(optional)');
            $('#edit-min_bw_strict').prop('checked', false);

            $('#edit-max_delay').val('').attr('placeholder', '(optional)');
            $('#edit-max_delay_strict').prop('checked', false);

            $('#edit-max_number_oxps').val('').attr('placeholder', '(optional)');
            $('#edit-max_number_oxps_strict').prop('checked', false);
         }

         $('#edit-notification-container').empty();
         if (connectionData.notifications && connectionData.notifications.length > 0) {
            connectionData.notifications.forEach((notification, index) => {
                  appendNotification(notification.email);
            });
         } else {
            appendNotification();
         }

         $('#editModal').modal('show');
      }
      $(document).on('click', '#edit-btn', function () {

         $.ajax({
            url: "https://"+meican_url+"/circuits/nodes/connection",
            type: "GET",
            data: { connectionId: editconnectionId },
            contentType: "application/json; charset=utf-8",
            success: function(data){
               openEditModal(JSON.parse(data));
            }
         });



         $('.vlan-dropdown').on('change', function() {
            const vlanDropdown = $(this);
            const inputContainer = $('#' + vlanDropdown.attr('id') + '-input-container');
            handleVlanChange(vlanDropdown, inputContainer);
         });

         setMinDate();
      });

      $(document).on('click', '.save-changes-button', function() {
         let isValid = true;

         const vlanInputs = $('input[id$="-input"]');
         vlanInputs.each(function() {
            if (this.checkValidity() === false) {
                  isValid = false;
            }
         });
         if (!isValid) {
            return false;
         }

         const isDateValid = validateDateFields();
         if (!isDateValid) {
            return false;
         }

         const connectionData = {
            name: $('#edit-name').val(),
            endpoints: getEndpoints(),
         };

         const description = $('#edit-description').val();
         if (description) {
            connectionData.description = description;
         }

         const start_time = $('#edit-start_time').val();
         const end_time = $('#edit-end_time').val();
         if (start_time || end_time) {
            connectionData.scheduling = {};
            if (start_time) connectionData.scheduling.start_time = start_time;
            if (end_time) connectionData.scheduling.end_time = end_time;
         }

         const min_bw = $('#edit-min_bw').val();
         const max_delay = $('#edit-max_delay').val();
         const max_number_oxps = $('#edit-max_number_oxps').val();
         if (min_bw || max_delay || max_number_oxps) {
            connectionData.qos_metrics = {};
            if (min_bw) {
                  connectionData.qos_metrics.min_bw = {
                     value: parseInt(min_bw, 10),
                     strict: $('#edit-min_bw_strict').is(':checked'),
                  };
            }
            if (max_delay) {
                  connectionData.qos_metrics.max_delay = {
                     value: parseInt(max_delay, 10),
                     strict: $('#edit-max_delay_strict').is(':checked'),
                  };
            }
            if (max_number_oxps) {
                  connectionData.qos_metrics.max_number_oxps = {
                     value: parseInt(max_number_oxps, 10),
                     strict: $('#edit-max_number_oxps_strict').is(':checked'),
                  };
            }
         }

         const notifications = getNotifications();
         if (notifications.length > 0) {
            connectionData.notifications = notifications;
         }

         console.log(JSON.stringify(connectionData));

         $.ajax({
            url: "https://" + meican_url + "/circuits/nodes/editconnection",
            type: "PATCH",
            contentType: "application/json",
            accept: "application/json",
            data: JSON.stringify({
               connectionId: editconnectionId,
               request: connectionData
            }),
            success: function (response) {
               console.log(response);
               alert(response);
               $('#editModal').modal('hide');
               location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
               console.error("Error updating connection:", textStatus, errorThrown);
            }
         });

      });
      
   </script>


</body>




<script type="text/javascript">
 
</script>

</html>

