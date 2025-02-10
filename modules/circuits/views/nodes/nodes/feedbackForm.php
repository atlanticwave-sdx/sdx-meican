
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
     
      #map {
         height: 800px;
         width: 1400px;
         max-width: 100%;
         max-height: 100%;
      }

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
      }
      
      #tableSearchListConnections {
         margin-bottom: 10px;
      }

      
   </style>
   

</head>
<body>


  <div class="row">
        
   <section class="content">
      <div class="box box-default">
         <div class="box-header with-border">
            <h3 class="box-title">Feedback Form</h3>
            <form id="feedbackform">
               <br>
                     <!-- Name -->
                     <div class="form-group">
                           <label for="edit-name" class="required">Name</label>
                           <input type="text" class="form-control" id="name" name="name" placeholder="Name" maxlength="50" required>
                     </div>
                     <div class="form-group">
                           <label for="edit-name" class="required">Institue/Organization</label>
                           <input type="text" class="form-control" id="org" name="organization" placeholder="Institue/Organization" maxlength="50" required>
                     </div>
                     <div class="form-group">
                           <label for="edit-name" class="required">Message</label>
                           <textarea class="form-control" id="message" name="message" placeholder="Message" maxlength="500"></textarea>
                     </div>
                     <button type="submit" class="btn btn-primary">Submit</button>
            </form>
         </div>
         <div class="box-body">
            <div id="circuits-pjax" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">

            
             
            </div>
         </div>
      </div>
   </section>

   <script type="text/javascript">

    var meican_url = "<?php echo MEICAN_URL; ?>";

    $("#feedbackform").submit(function(event) {
    event.preventDefault();
    
    var name = $('#name').val();
    var organization = $('#org').val();
    var message = $('#message').val();

     var request = {
      "name": name,
      "organization": organization,
      "message": message
    };


        $.ajax({
        type: "POST",
        url: "https://"+meican_url+"/circuits/nodes/feedbackformsubmit",
        data: JSON.stringify(request),
        contentType: "application/json; charset=utf-8",
        success: function(data){alert(data);},
        error: function(errMsg) {
            alert(errMsg);
        }
    });
    

 });

     </script>


</body>


</html>

