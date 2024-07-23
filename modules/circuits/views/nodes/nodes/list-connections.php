
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
                                    
                                    <th style="width: 11%;">Name</th>
                                    <th style="width: 10%;">Quantity</th>
                                    <th style="width: 10%;"><a href="/circuits/reservation/status?ReservationSearch%5Bsrc_domain%5D=&amp;ReservationSearch%5Bdst_domain%5D=&amp;ReservationSearch%5Bdataplane_status%5D=ACTIVE&amp;_pjax=%23circuits-pjax&amp;sort=start" data-sort="start">Start Time <img style="width: 7px; height:11px;" src="/images/sort_image.png" alt="Order by:"></a></th>
                                    <th style="width: 10%;"><a href="/circuits/reservation/status?ReservationSearch%5Bsrc_domain%5D=&amp;ReservationSearch%5Bdst_domain%5D=&amp;ReservationSearch%5Bdataplane_status%5D=ACTIVE&amp;_pjax=%23circuits-pjax&amp;sort=finish" data-sort="finish">End Time <img style="width: 7px; height:11px;" src="/images/sort_image.png" alt="Order by:"></a></th>
                                    <th style="width: 14%;">Source Port</th>
                                    <th style="width: 14%;">Destination Port</th>
                                    <th style="width: 10%;"><a href="/circuits/reservation/status?ReservationSearch%5Bsrc_domain%5D=&amp;ReservationSearch%5Bdst_domain%5D=&amp;ReservationSearch%5Bdataplane_status%5D=ACTIVE&amp;_pjax=%23circuits-pjax&amp;sort=bandwidth" data-sort="bandwidth">Bandwidth <img style="width: 7px; height:11px;" src="/images/sort_image.png" alt="Order by:"></a></th>
                                    
                                 </tr>
                                 
                                 <?php
                                    if (!empty($str_response)) {
                                       $connectionsData = json_decode($str_response, true);

                                       if (is_array($connectionsData) && json_last_error() === JSON_ERROR_NONE) {
                                          foreach ($connectionsData as $connectionId => $connectionInfo) {
                                             ?>
                                                <tr id="circuits-gridcurrent-filters" class="filters">
                                                   <td><?php echo isset($connectionInfo['name']) ? $connectionInfo['name'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['quantity']) ? $connectionInfo['quantity'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['scheduling']['start_time']) ? $connectionInfo['scheduling']['start_time'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['scheduling']['end_time']) ? $connectionInfo['scheduling']['end_time'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['endpoints'][0]['port_id']) ? $connectionInfo['endpoints'][0]['port_id'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['endpoints'][count($connectionInfo['endpoints']) - 1]['port_id']) ? $connectionInfo['endpoints'][count($connectionInfo['endpoints']) - 1]['port_id'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['qos_metrics']['min_bw']['value']) ? $connectionInfo['qos_metrics']['min_bw']['value'] : ''; ?></td>
                                                   <td><button type="button" class="btn btn-primary view-connection" data-connection='<?php echo json_encode($connectionInfo); ?>'>View</button></td>
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
                                    
                                    <th style="width: 11%;">Name</th>
                                    <th style="width: 10%;">Quantity</th>
                                    <th style="width: 10%;"><a href="/circuits/reservation/status?ReservationSearch%5Bsrc_domain%5D=&amp;ReservationSearch%5Bdst_domain%5D=&amp;ReservationSearch%5Bdataplane_status%5D=ACTIVE&amp;_pjax=%23circuits-pjax&amp;sort=start" data-sort="start">Start Time <img style="width: 7px; height:11px;" src="/images/sort_image.png" alt="Order by:"></a></th>
                                    <th style="width: 10%;"><a href="/circuits/reservation/status?ReservationSearch%5Bsrc_domain%5D=&amp;ReservationSearch%5Bdst_domain%5D=&amp;ReservationSearch%5Bdataplane_status%5D=ACTIVE&amp;_pjax=%23circuits-pjax&amp;sort=finish" data-sort="finish">End Time <img style="width: 7px; height:11px;" src="/images/sort_image.png" alt="Order by:"></a></th>
                                    <th style="width: 14%;">Source Port</th>
                                    <th style="width: 14%;">Destination Port</th>
                                    <th style="width: 10%;"><a href="/circuits/reservation/status?ReservationSearch%5Bsrc_domain%5D=&amp;ReservationSearch%5Bdst_domain%5D=&amp;ReservationSearch%5Bdataplane_status%5D=ACTIVE&amp;_pjax=%23circuits-pjax&amp;sort=bandwidth" data-sort="bandwidth">Bandwidth <img style="width: 7px; height:11px;" src="/images/sort_image.png" alt="Order by:"></a></th>
                                    
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

    
  </div>
    <!-- Modal Structure -->
    <div id="jsonModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Connection Details</h4>
        </div>
        <div class="modal-body">
          <div id="jsonContent"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

   <script>
      function openModal(jsonData) {
        const modal = $('#jsonModal');
        const content = $('#jsonContent');
        content.html(formatJsonData(jsonData));
        modal.modal('show');
      }

      function formatJsonData(data) {
         let formattedData = '';

         formattedData += `<strong>Id:</strong> ${data.id || ''}<br>`;
         formattedData += `<strong>Name:</strong> ${data.name || ''}<br>`;
         formattedData += `<strong>Description:</strong> ${data.description || ''}<br>`;
         formattedData += `<strong>Start Time:</strong> ${data.scheduling.start_time || ''}<br>`;
         formattedData += `<strong>End Time:</strong> ${data.scheduling.end_time || ''}<br>`;

         formattedData += `<strong>QoS Metrics:</strong><br>`;
         formattedData += `<div style="padding-left: 20px;">${formatQosMetrics(data.qos_metrics)}</div>`;

         formattedData += `<strong>Notifications:</strong><br>`;
         data.notifications.forEach((notification, index) => {
            formattedData += `<div style="padding-left: 20px;"><strong>Email ${index + 1}: </strong>${formatNotificationData(notification)}</div></div>`;
         });

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
         formattedEndpointData += `<strong>Port ID:</strong> ${endpoint.port_id || ''}<br>`;
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
            formattedQosMetrics += `<strong>Minimum Bandwidth:</strong> ${qosMetrics.min_bw.value || ''} (Strict: ${qosMetrics.min_bw.strict ? 'Yes' : 'No'})<br>`;
            formattedQosMetrics += `<strong>Maximum Delay:</strong> ${qosMetrics.max_delay.value || ''} (Strict: ${qosMetrics.max_delay.strict ? 'Yes' : 'No'})<br>`;
            formattedQosMetrics += `<strong>Maximum OXPs:</strong> ${qosMetrics.max_number_oxps.value || ''} (Strict: ${qosMetrics.max_number_oxps.strict ? 'Yes' : 'No'})<br>`;
         }
         return formattedQosMetrics;
      }
      
      $(document).on('click', '.view-connection', function () {
         const connectionData = $(this).attr('data-connection');
         const parsedData = JSON.parse(connectionData);
         const connectionId = parsedData.id;
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
      });

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
            success: function(data){
               row.remove();
            }
         });
      });
   </script>



</body>




<script type="text/javascript">
 
</script>

</html>

