<?php 
require_once('admin/config.php');

$DBC = mysqli_connect(HOST, DB_USER, DB_PASS,DB_NAME);
$selProvider = "";

if (isset($_GET['key'])) {
    // Get the values of key and value parameters
    $key = $_GET['key'];

    date_default_timezone_set ("Asia/Calcutta");

    // Decode the base64 encoded values
    $selProvider = base64_decode($key);

}

$providers = [];

$sql1 = "SELECT cmp.company_name,cmp.id FROM tblproviderusercompany cmp where cmp.active =0 and cmp.is_company_add = 1 order by cmp.company_name asc";
$result1 = $DBC->query($sql1);
$count1 = mysqli_num_rows($result1);
if($count1 > 0) {
    while ($row1 = mysqli_fetch_assoc($result1)) {
        array_push($providers,$row1);
    }
}

$serviceCenters = [];
$sql2 = "SELECT * FROM tblservicescenter where active =0 order by center_name asc";
$result2 = $DBC->query($sql2);
$count2 = mysqli_num_rows($result2);
if($count2 > 0) {
    while ($row2 = mysqli_fetch_assoc($result2)) {
        array_push($serviceCenters,$row2);
    }
}

$serviceType = [];
$sql3 = "SELECT * FROM tblservicesaddingtype where active =0 order by center_name asc";
$result3 = $DBC->query($sql3);
$count3 = mysqli_num_rows($result3);
if($count3 > 0) {
    while ($row3 = mysqli_fetch_assoc($result3)) {
        array_push($serviceType,$row3);
    }
}




include("templates/header.php");

?>

            
            
            
            
            <!--  wrapper  -->
            <div id="wrapper">
                <!-- content-->
                <div class="content">
                    <!--  section  -->
                    <section class="parallax-section single-par" data-scrollax-parent="true">
                        <div class="bg par-elem "  data-bg="images/bg/1.jpg" data-scrollax="properties: { translateY: '30%' }"></div>
                        <div class="overlay"></div>
                        <div class="container">
                            <div class="section-title center-align big-title">
                                <div class="section-title-separator"><span></span></div>
                                <h2><span>Popular Services</span></h2>
                                <span class="section-separator"></span>
                                <h4>Discover top-notch recommendations and services curated by our trusted partners and community.</h4>
                            </div>
                        </div>
                        <div class="header-sec-link">
                            <div class="container"><a href="#sec1" class="custom-scroll-link color-bg"><i class="fal fa-angle-double-down"></i></a></div>
                        </div>
                    </section>
                    <!--  section  end-->
                    <div class="breadcrumbs-fs fl-wrap">
                        <div class="container">
                            <div class="breadcrumbs fl-wrap"><a href="#">Home</a><a href="#">Services </a><span>Popular Services</span></div>
                        </div>
                    </div>
                    <!--  section-->
                    <section class="grey-blue-bg small-padding" id="sec1">
                        <div class="container">
                            <div class="row">
                                <!--filter sidebar -->
                                <div class="col-md-4">
                                    <div class="mobile-list-controls fl-wrap">
                                        <div class="mlc show-list-wrap-search fl-wrap"><i class="fal fa-filter"></i> Filter</div>
                                    </div>
                                    <div class="fl-wrap filter-sidebar_item fixed-bar">
                                        <div class="filter-sidebar fl-wrap lws_mobile">
                                            <!--col-list-search-input-item -->
                                            <div class="col-list-search-input-item in-loc-dec fl-wrap not-vis-arrow">
                                                <label>Service providers</label>
                                                <div class="listsearch-input-item">
                                                    <select data-placeholder="selProvider" id="selProvider" name="selProvider" class="chosen-select" onchange="getPopularServices();">
                                                        <option value="" selected>All providers</option>
                                                        
                                                        <?php if(count($providers) > 0) {
                                                        foreach ($providers as $key => $album) { 
                                                            if($selProvider == $album['id']) $sel = 'selected';
                                                            else $sel = '';
                                                        ?>
                                                        
                                                        <option value="<?=$album['id']?>" <?=$sel?>><?=$album['company_name']?></option>
                                                        
                                                        <?php } ?>
                                                        <?php } ?>
                                                       
                                                    </select>
                                                </div>
                                            </div>
                                            <!--col-list-search-input-item end-->                      
                                            <!--col-list-search-input-item -->
                                            <div class="col-list-search-input-item fl-wrap location autocomplete-container">
                                                <label>Destination</label>
                                                <span class="header-search-input-item-icon"><i class="fal fa-map-marker-alt"></i></span>
                                                <input type="text" placeholder="Destination or Hotel Name" class="autocomplete-input" id="autocompleteid3" value=""/>
                                                <a href="#"><i class="fal fa-dot-circle"></i></a>
                                            </div>
                                            <!--col-list-search-input-item end-->
                                           
                                                                               
                                            
                                            <!--col-list-search-input-item -->
                                            <div class="col-list-search-input-item fl-wrap">
                                                <label>Service Centers</label>
                                                <div class="search-opt-container fl-wrap">
                                                    <!-- Checkboxes -->
                                                    
                                                     <?php if(count($serviceCenters) > 0) {
                                                         $setOne = '';
                                                         $setTwo = '';
                                                         
                                                         $runOne = true;
                                                         
                                                    foreach ($serviceCenters as $key => $album) { 
                                                        

                                                        if($runOne){
                                                            $setOne .= '<li><input value="'.$album['id'].'"  type="checkbox" name="check-center" ><label>'.$album['center_name'].'</label></li>';
                                                            $runOne = false;
                                                            
                                                        }else{
                                                            $setTwo .= '<li><input value="'.$album['id'].'"  type="checkbox" name="check-center" ><label>'.$album['center_name'].'</label></li>';
                                                            $runOne = true;
                                                        }
                                                        
                                                   }  } ?>
                                                    
                                                    
                                                    <ul class="fl-wrap filter-tags half-tags">
                                                        <?=$setOne?>
                                                    </ul>
                                                    <!-- Checkboxes end -->
                                                    <!-- Checkboxes -->
                                                    <ul class="fl-wrap filter-tags half-tags">
                                                        <?=$setTwo?>
                                                    </ul>
                                                    <!-- Checkboxes end -->
                                                </div>
                                            </div>
                                            <!--col-list-search-input-item end-->  
                                            <!--col-list-search-input-item  -->                                         
                                            <div class="col-list-search-input-item fl-wrap">
                                                <button class="header-search-button" onclick="window.location.href='listing.html'">Search <i class="far fa-search"></i></button>
                                            </div>
                                            <!--col-list-search-input-item end--> 
                                        </div>
                                    </div>
                                </div>
                                <!--filter sidebar end-->
                                <!--listing -->
                                <div class="col-md-8">
                                    <!--col-list-wrap -->
                                    <div class="col-list-wrap fw-col-list-wrap post-container">
                                        <!-- list-main-wrap-->
                                        <div class="list-main-wrap fl-wrap card-listing">
                                            <!-- list-main-wrap-opt-->
                                            <div class="list-main-wrap-opt fl-wrap">
                                                <div class="list-main-wrap-title fl-wrap col-title">
                                                    <h2>Results For : <span id="filterResults">All Services </span></h2>
                                                </div>
                                                <!-- price-opt-->
                                                <div class="price-opt">
                                                    <span class="price-opt-title">Sort results by:</span>
                                                    <div class="listsearch-input-item">
                                                        <select data-placeholder="Popularity" class="chosen-select no-search-select" >
                                                            <option value="" selected>All</option>
                                                             <?php if(count($serviceType) > 0) {
                                                                foreach ($serviceType as $key => $album) { 
                                                                ?>
                                                                
                                                                <option value="<?=$album['id']?>"><?=$album['center_name']?></option>
                                                                
                                                                <?php } ?>
                                                                <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- price-opt end-->
                                                <!-- price-opt-->
                                                <div class="grid-opt">
                                                    <ul>
                                                        <li><span class="two-col-grid act-grid-opt"><i class="fas fa-th-large"></i></span></li>
                                                        <li><span class="one-col-grid"><i class="fas fa-bars"></i></span></li>
                                                    </ul>
                                                </div>
                                                <!-- price-opt end-->                               
                                            </div>
                                            <!-- list-main-wrap-opt end-->
                                            <!-- listing-item-container -->
                                            <div class="listing-item-container init-grid-items fl-wrap" id="listing-popular-services-item">
                                                
                                                
                                            </div>
                                            <!-- listing-item-container end-->
                                            <!--<a class="load-more-button" href="#">Load more </i> </a>-->
                                            <!--<a class="load-more-button" href="#">Load more <i class="fal fa-spinner"></i> </a>-->
                                        </div>
                                        <!-- list-main-wrap end-->
                                    </div>
                                    <!--col-list-wrap end -->
                                </div>
                                <!--listing  end-->
                            </div>
                            <!--row end-->
                        </div>
                        <div class="limit-box fl-wrap"></div>
                    </section>
                </div>
                <!-- content end-->
            </div>
            <!--wrapper end -->
            
            
  <?php 

include("templates/footer.php");

?>

<script>

    $(document).ready(function() {
        $('#service-menu').addClass('act-link');
        getPopularServices();
    });
    
    function getPopularServices(){
        
        $('#listing-popular-services-item').html('');
        
        var selProvider = $('#selProvider').val();
        if(selProvider != "") $('#filterResults').html($('#selProvider option:selected').text());

        
         var postData = {
            function: 'Services',
            method: "getPopularServices",
            'selProvider':selProvider,
           
          }
              
            $.ajax({
                url: '/admin/ajaxHandler.php',
                type: 'POST',
                data: postData,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    console.log(data.status);
                    //called when successful
                    if (data.status == 1) {
                        var services = data.data;
                        var tbl ="";
                        for(var i=0;i<services.length;i++){

                            
                                tbl +='<div class="listing-item" onclick="viewService('+services[i]['id']+');">';
                                tbl +='<article class="geodir-category-listing fl-wrap">';
                                tbl +='<div class="geodir-category-img">';
                                tbl +='<img src="'+services[i]['file_path']+'" alt="">';
                                // tbl +='<div class="listing-counter">'+services[i]['company_name']+'</div>';
                               
                                tbl +='<div class="geodir-category-opt">';
                                tbl +='<div class="listing-rating card-popup-rainingvis" data-starrating2="4"></div>';
                                tbl +='<div class="rate-class-name">';
                                tbl +='<div class="score"><strong> Good</strong>8 Reviews </div>';
                                tbl +='<span>4.1</span>';                                             
                                tbl +='</div>';
                                tbl +='</div>';
                                tbl +='</div>';
                                tbl +='<div class="geodir-category-content fl-wrap title-sin_item">';
                                tbl +='<div class="geodir-category-content-title fl-wrap">';
                                tbl +='<div class="geodir-category-content-title-item">';
                                tbl +='<h3 class="title-sin_map"><a href="service-view.php?">'+services[i]['name']+'</a></h3>';
                                tbl +='<div class="geodir-category-location fl-wrap"><a href="#" class="map-item"><i class="fas fa-map-marker-alt"></i>'+services[i]['company_address']+'</a></div>';
                                tbl +='</div>';
                                tbl +='</div>';
                                tbl +='<p> '+services[i]['description']+'</p>';

                                tbl +='<ul class="facilities-list fl-wrap">';
                                
                                
                                if(services[i]['provide_wifi'] == 1) tbl += '<li><i class="fal fa-wifi"></i><span>Free WiFi</span></li>';
                                if(services[i]['provide_parking'] == 1) tbl += '<li><i class="fal fa-parking"></i><span>Parking</span></li>';
                                if(services[i]['provide_ac'] == 1) tbl += '<li><i class="fas fa-cloud-meatball"></i><span>AC</span></li>';
                                if(services[i]['provide_rooftop'] == 1) tbl += '<li><i class="fal fa-hotel"></i><span>Rooftop</span></li>';
                                if(services[i]['provide_bathroom'] == 1) tbl += '<li><i class="fas fa-bath"></i><span>Bathroom</span></li>';
                                
                                 if(services[i]['provide_welcome_drink'] == 1) tbl += '<li><i class="fas fa-wine-glass-alt"></i><span>Welcome Drink</span></li>';
                                if(services[i]['provide_food'] == 1) tbl += '<li><i class="fas fa-hamburger"></i><span>Food</span></li>';
                                if(services[i]['provide_seperate_cabin'] == 1) tbl += '<li><i class="fas fa-car"></i></i><span>Seperate Cabin</span></li>';
                                if(services[i]['provide_common_restaurant'] == 1) tbl += '<li><i class="fas fa-utensils-alt"></i><span>Common Restaurant</span></li>';
                                                                
                                                                
                             
                                tbl +='</ul>';
                                                           
                                tbl +='</div>';
                                tbl +='</article>';
                                tbl +='</div>';

                           

                        }
                        
                        
                        
                        
                        $('#listing-popular-services-item').html(tbl);
                      
                    }
                   
                },
                error: function (x,h,r) {
                //called when there is an error
                    console.log(x);
                    console.log(h);
                    console.log(r);
                    $('#btnLogIn').removeClass('d-none');
                   
                }
            });
                
        
    }


    
    
    
</script>



