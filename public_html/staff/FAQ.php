<?php 

include("header.php");


// session_start();
// print_r($_SESSION['MachooseAdminUser']['user_id']);
if($_SESSION['MachooseAdminUser']['id'] == ""){
  header("Location: login.php");
  // print_r("sasaa");
}
// include("templates/provider-header.php");

$isProvider = $_SESSION['isProviderStaff'];

if(!$isProvider){
    echo '<script>';
    echo 'window.location.href = "login.php";';
    echo '</script>';
    
}

$selAlbums = [];
$sql = "SELECT * FROM tblFAQ WHERE active=0 ORDER BY id DESC";
$Wresult = $DBC->query($sql);
$Wcount = mysqli_num_rows($Wresult);
 if($Wcount > 0) {
       while ($row = mysqli_fetch_assoc($Wresult)) {
           array_push($selAlbums,$row);
           
       }
       
   }

// include("header.php");

?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">FAQ</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">FAQ</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          
          <div class="col-12" id="accordion">
              
                    <?php 
                    
                        if(sizeof($selAlbums) > 0){
                            
                            $count = 0;
                            
                            foreach($selAlbums as $row){ 
                                $count++;
                                if($count == 1) $iss = 'show';
                                else $iss = '';
                    ?>
                    
                    
                    
                        <div class="card card-primary card-outline">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapse_<?=$row['id']?>">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <?=$count?>. <?=$row['role']?>
                                    </h4>
                                </div>
                            </a>
                            <div id="collapse_<?=$row['id']?>" class="collapse <?=$iss?>" data-parent="#accordion">
                                <div class="card-body">
                                    <?=$row['description']?>
                                </div>
                            </div>
                        </div>
                               
                                
                                
                                
                                
                    <?php  }
                         
                        }
                    ?>
                   
                   
                     
              
            </div>
          
        
     
       
       
      </div><!-- /.container-fluid -->
      
      
       <div class="row">
            <div class="col-12 mt-3 text-center">
                <p class="lead">
                    <a href="contact-us.html">Contact us</a>,
                    if you found not the right anwser or you have a other question?<br />
                </p>
            </div>
        </div>
      
      
      
      
      
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
<?php 

include("footer.php");



?>

<script>
    $('#navDashboard').removeClass('active');
    $('#navOurCompanies').removeClass('active');
    $('#navOurServices').removeClass('active');
    $('#navProfile').removeClass('active');
    $('#navFAQ').addClass('active');
    $('#navTermsAndConditions').removeClass('active');
    
   
    
    $( document ).ready(function() {
    

  });
  
 
    
</script>





