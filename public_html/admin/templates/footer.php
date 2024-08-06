</main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>MACHOOOS INTERNATIONAL</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
 
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <!-- <script src="assets/vendor/php-email-form/validate.js"></script> -->
  <script src="assets/js/sweetalert/sweetalert2.min.js"></script>
  <link href="assets/js/sweetalert/sweetalert2.dark.css" rel="stylesheet">
  <script src="assets/js/sweetalert/MySweetAlert.js"></script>
  <!-- DataTables -->
  <script src="assets/js/datatables/jquery.dataTables.min.js"></script>
  <script src="assets/js/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/js/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="assets/js/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="assets/js/datatables-custom/dataTables.buttons.min.js"></script>
  <script src="assets/js/datatables-custom/jszip.min.js"></script>
  <script src="assets/js/datatables-custom/pdfmake.min.js"></script>
  <!-- jquery-validation -->
  <script src="assets/js/jquery-validation/jquery.validate.min.js"></script>
  <script src="assets/js/jquery-validation/additional-methods.min.js"></script>
  <script src="assets/js/masonry.js"></script>
  <script src="assets/js/select2.js"></script>
  <!-- <script src="plugins/datatables-custom/pdfmake.min.js.map"></script> -->
  <!-- <script src="assets/js/datatables-custom/vfs_fonts.js"></script>
  <script src="assets/js/datatables-custom/buttons.html5.min.js"></script>
  <script src="assets/js/datatables-custom/buttons.print.min.js"></script> -->

  <!-- Template Main JS File -->

  <script src="assets/js/appbase.js"></script>
  <script src="assets/js/main.js"></script>
  <script src="assets/js/projectscript.js"></script>
  <script src="assets/js/imageuploadify.min.js"></script>
  
  
  <script>
      
       $(document).ready(function() {
            successFn = function(resp) {
              if(resp.status == 1){
                  $('#CSIC').html(resp.data);
              }
            }
            data = { "function": 'SignatureAlbum',"method": "getNoOfComEvents"};
            apiCall(data,successFn);
            
            
            getAllSelectedImageMessages();
         
      });
      
      function setSelectImageAsRead(){

           successFn = function(resp) {
              
            }
            data = { "function": 'SignatureAlbum',"method": "setSelectImageAsRead"};
            apiCall(data,successFn);
          
          
      }
      
      
      
      function getAllSelectedImageMessages(filter){

  

    successFn = function(resp)  {
      if(resp.status == 1){
        var Activitys = resp.data ;
        var len = Activitys.length ;
        
        var numberOfC = 0;
        
       
       
        var activityData = "";
            for(var i=0;i<len;i++){
    
              // Specify the target date and time
              var targetDate = new Date(Activitys[i]['created_in']);
    
              // Calculate the time difference in milliseconds
              var timeDifference = new Date(Activitys[i]['nowtime']) - targetDate.getTime();
    
              // Convert milliseconds to minutes
              var minutesAgo = Math.floor(timeDifference / (1000 * 60));
    
         
          
             if(minutesAgo < 1440){
                var hours = Math.floor(minutesAgo / 60);
                if(hours == 0) var activityTime = minutesAgo +" min" ;
                else var activityTime = hours +" hrs" ;
                
                
            
            }else{
            var daysAgo = Math.floor(minutesAgo / (60 * 24));
            var activityTime = daysAgo +" day" ;
            }
            
            
            if(Activitys[i]['is_read'] == 0 ) numberOfC = numberOfC + 1;
            
            
            
            activityData +='<li class="message-item">';
              activityData +='<a href="#">';
                
                activityData +='<div>';
                  activityData +='<h4>';
                  
                  activityData +=Activitys[i]['task'] ;
                  
                  activityData +='</h4>';
                  activityData +='<p>'+activityTime+'</p>';
                activityData +='</div>';
              activityData +='</a>';
            activityData +='</li>';
            activityData +='<li>';
              activityData +='<hr class="dropdown-divider">';
            activityData +='</li>';





        }

        $("#messagesDisplay").html(activityData);
        
         $("#messagesCount").html(numberOfC);
        $("#messagesCount1").html(numberOfC);
        

      }else{
          $("#messagesDisplay").html("");
          $("#messagesCount").html(0);
          $("#messagesCount1").html(0);
      } 
      
      
    }
    data = {"function": 'Dashboard', "method": "getRecentActivityForSelectImage"  };
    apiCall(data,successFn);
 }
      
      
      
  </script>
  

</body>
<style>
  .dataTables_filter {
    width: 50%;
    float: right;
  }
  .dataTables_paginate {
    width: 50%;
    float: right;
  }
    .select2-container .select2-selection--single{
      height: 40px !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #444;
      line-height: 36px !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow b {
      border-width: 8px 6px 0 7px !important;
  }
  .select2-container--default .select2-selection--single{
      border: 1px solid #ced4da !important; 
      border-radius: .375rem !important; 
  }
  select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 5px !important;
      right: 7px !important;
      width: 30px !important;
  }
</style>

</html>