<?php 
include("header.php");

// require_once("../admin/config.php");
// $DBC = mysqli_connect(HOST, DB_USER, DB_PASS,DB_NAME);

// session_start();
// print_r($_SESSION['MachooseAdminUser']['user_id']);
if($_SESSION['MachooseAdminUser']['id'] == ""){
  header("Location: login.php");
  // print_r("sasaa");
}
// include("templates/provider-header.php");

$isProvider = $_SESSION['isProvider'];

if(!$isProvider){
    echo '<script>';
    echo 'window.location.href = "login.php";';
    echo '</script>';
    
}

$sql3 = "SELECT * FROM tbl_tac  ";
$result3 = $DBC->query($sql3);
$rowU = mysqli_fetch_assoc($result3);





?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">MI Terms and Conditions </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">MI Terms and Conditions </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          
          
              <div class="card">
                    <div class="card-body">
                    <h4 class="card-title text-primary">
                    <strong>Terms and Conditions</strong>
                    </h4><br>
                    
                    <?=$rowU['description']?>
                    
                          
                    </div>
                    </div>
                                   
          
          
          
        
       
       
      </div><!-- /.container-fluid -->
      
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
    $('#navFAQ').removeClass('active');
    $('#navTermsAndConditions').addClass('active');
    
   
    
    $( document ).ready(function() {
   

  });
  
 
    
</script>





