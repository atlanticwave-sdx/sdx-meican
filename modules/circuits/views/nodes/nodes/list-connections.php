
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
                                                   <td><?php echo isset($connectionInfo['start_time']) ? $connectionInfo['start_time'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['end_time']) ? $connectionInfo['end_time'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['egress_port']['id']) ? $connectionInfo['egress_port']['id'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['ingress_port']['id']) ? $connectionInfo['ingress_port']['id'] : ''; ?></td>
                                                   <td><?php echo isset($connectionInfo['bandwidth_required']) ? $connectionInfo['bandwidth_required'] : ''; ?></td>
                                                   <td><button type="submit" class="btn btn-primary" style="background-color:red;">Delete</button></td>
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

</body>


<script type="text/javascript">
 
</script>

</html>



