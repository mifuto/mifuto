<?php 
require_once('admin/config.php');

$DBC = mysqli_connect(HOST, DB_USER, DB_PASS,DB_NAME);

$services = [];
$providers = [];

$sql = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,say.center_name as service_add FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.active = 0  order by ins.id desc LIMIT 9";
$result = $DBC->query($sql);
$count = mysqli_num_rows($result);
if($count > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($services,$row);
    }
}

$sql1 = "SELECT cmp.*,a.name,a.email,b.center_name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblproviderusercompany cmp left join tblprovideruserlogin a on a.id = cmp.user_id left join tblservicescenter b on cmp.servicescenter_id = b.id left join tblcountries c on c.country_id = cmp.county_id left join tblstate d on d.id=cmp.state_id left join tblcity e on e.id = cmp.city_id where a.active =1 and cmp.active =0 and cmp.is_company_add = 1 order by cmp.id desc";
$result1 = $DBC->query($sql1);
$count1 = mysqli_num_rows($result1);
if($count1 > 0) {
    while ($row1 = mysqli_fetch_assoc($result1)) {
        array_push($providers,$row1);
    }
}


include("templates/header.php");

?>

            
            
            
            
            <!--  wrapper  -->
            <div id="wrapper">
                <!-- content-->
                <div class="content">
                    <!--section -->
                    <section class="hero-section" data-scrollax-parent="true" id="sec1">
                        <div class="hero-parallax">
                            <div class="media-container video-parallax" data-scrollax="properties: { translateY: '200px' }">
                                <div class="bg mob-bg" style="background-image: url(images/bg/1.jpg)"></div>
                                <div class="video-container">
                                    <video autoplay  loop muted  class="bgvid">
                                        <source src="video/1.mp4" type="video/mp4">
                                    </video>
                                </div>
                                        <!--  
                                            Vimeo code
                                            
                                             <div  class="background-vimeo" data-vim="97871257"> </div> --> 
                                        <!--  
                                            Youtube code 
                                            
                                             <div  class="background-youtube-wrapper" data-vid="Hg5iNVSp2z8" data-mv="1"> </div> -->
                                
                            </div>
                            <div class="overlay op7"></div>
                        </div>
                        <div class="hero-section-wrap fl-wrap">
                            <div class="container">
                                <div class="home-intro">
                                    <div class="section-title-separator"><span></span></div>
                                    <h2>MIfuto-online photographer booking </h2>
                                    <span class="section-separator"></span>                                    
                                    <h3>Let's start exploring the world together with MIfuto</h3>
                                </div>
                                <div class="main-search-input-wrap">
                                    <div class="main-search-input fl-wrap">
                                        <div class="main-search-input-item location" id="autocomplete-container">
                                            <span class="inpt_dec"><i class="fal fa-map-marker"></i></span>
                                            <input type="text" placeholder="Hotel , City..." class="autocomplete-input" id="autocompleteid2"  value=""/>
                                            <a href="#"><i class="fal fa-dot-circle"></i></a>
                                        </div>
                                        <div class="main-search-input-item main-date-parent main-search-input-item_small">
                                            <span class="inpt_dec"><i class="fal fa-calendar-check"></i></span> <input type="text" placeholder="When" name="main-input-search"   value=""/>
                                        </div>
                                        <div class="main-search-input-item">
                                            <div class="qty-dropdown fl-wrap">
                                                <div class="qty-dropdown-header fl-wrap"><i class="fal fa-users"></i> Persons</div>
                                                <div class="qty-dropdown-content fl-wrap">
                                                    <div class="quantity-item">
                                                        <label><i class="fas fa-male"></i> Adults</label>
                                                        <div class="quantity">
                                                            <input type="number" min="1" max="3" step="1" value="1">
                                                        </div>
                                                    </div>
                                                    <div class="quantity-item">
                                                        <label><i class="fas fa-child"></i> Children</label>
                                                        <div class="quantity">
                                                            <input type="number" min="0" max="3" step="1" value="0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="main-search-button color2-bg" onclick="window.location.href='service-view.php?key=OA=='">Search <i class="fal fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="header-sec-link">
                            <div class="container"><a href="#sec2" class="custom-scroll-link color-bg"><i class="fal fa-angle-double-down"></i></a></div>
                        </div>
                    </section>
                    <!-- section end -->
                    <!--section -->
                    <section id="sec2">
                        <div class="container">
                            <div class="section-title">
                                <div class="section-title-separator"><span></span></div>
                                <h2>Popular Services</h2>
                                <span class="section-separator"></span>
                                <p>Discover top-notch recommendations and services curated by our trusted partners and community.</p>
                            </div>
						 </div>
                            <!-- portfolio start -->
                            <div class="gallery-items fl-wrap mr-bot spad home-grid">
                                
                                
                                <?php if(count($services) > 0) {
                                    foreach ($services as $key => $album) { 
                                        $id = $album['id'];
                                        $getimgsql = "SELECT a.* FROM tbeservice_folderfiles a where a.service_id='$id' and a.hide=0 order by a.id desc";
                                        $imgresult = $DBC->query($getimgsql);
                                        $row = mysqli_fetch_assoc($imgresult);

                                    
                                    ?>
                                    
                                    
                                        <!-- gallery-item-->
                                            <div class="gallery-item" onclick="viewService(<?=$id?>);">
                                                <div class="grid-item-holder">
                                                    <div class="listing-item-grid">
                                                        <!--<div class="listing-counter"><?=$album['company_name']?></div>-->
                                                        <img  src="<?=$row['file_path']?>"   alt="" >
                                                        <div class="listing-item-cat">
                                                            <h3><a ><?=$album['name']?></a></h3>
                                                            <div class="weather-grid"   data-grcity="Rome"></div>
                                                            <div class="clearfix"></div>
                                                            <p><?=$album['description']?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- gallery-item end-->
                                    
                                    
                                    <?php } ?>
                                <?php } ?>
                                
                        
                              
                            </div>
                            <!-- portfolio end -->
                            <a href="services.php" class="btn    color-bg">Browse All Services<i class="fas fa-caret-right"></i></a>
                    </section>
                    <!-- section end -->
                    <!-- section-->
                    <section class="grey-blue-bg">
                        <!-- container-->
                        <div class="container">
                            <div class="section-title">
                                <div class="section-title-separator"><span></span></div>
                                <h2>Recently Added Service providers </h2>
                                <span class="section-separator"></span>
                                <p>Our Trusted Partners</p>
                            </div>
                        </div>
                        <!-- container end-->
                        <!-- carousel -->
                        <div class="list-carousel fl-wrap card-listing ">
                            <!--listing-carousel-->
                            <div class="listing-carousel  fl-wrap ">
                                
                                
                                <?php if(count($providers) > 0) {
                                    foreach ($providers as $key => $album) { 
                                    ?>
                                    
                                    
                                         <!--slick-slide-item-->
                                            <div class="slick-slide-item" onclick="viewProviderProfile(<?=$album['id']?>);">
                                                <!-- listing-item  -->
                                                <div class="listing-item">
                                                    <article class="geodir-category-listing fl-wrap">
                                                        <div class="geodir-category-img">
                                                            <img src="<?=$album['company_logo_url']?>" alt="">
                                                            <!--<div class="listing-counter"><?=$album['center_name']?></div>-->
                                                            <!--<div class="sale-window"><?=$album['center_name']?></div>-->
                                                            <div class="geodir-category-opt">
                                                                <div class="listing-rating card-popup-rainingvis" data-starrating2="5"></div>
                                                                <div class="rate-class-name">
                                                                    <div class="score"><strong>Very Good</strong>27 Reviews </div>
                                                                    <span>5.0</span>                                             
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="geodir-category-content fl-wrap title-sin_item">
                                                            <div class="geodir-category-content-title fl-wrap">
                                                                <div class="geodir-category-content-title-item">
                                                                    <h3 class="title-sin_map"><a href="listing-single.html"><?=$album['company_name']?></a></h3>
                                                                    <div class="geodir-category-location fl-wrap"><a href="#" class="map-item"><i class="fas fa-map-marker-alt"></i> <?=$album['city_id']?>, <?=$album['state_id']?>, <?=$album['county_id']?></a></div>
                                                                </div>
                                                            </div>
                                                            <p><?=$album['company_address']?></p>
                                                            <ul class="facilities-list fl-wrap">
                                                                
                                                                <?php 
                                                                
                                                                if($album['provide_wifi'] == 1) echo '<li><i class="fal fa-wifi"></i><span>Free WiFi</span></li>';
                                                                if($album['provide_parking'] == 1) echo '<li><i class="fal fa-parking"></i><span>Parking</span></li>';
                                                                if($album['provide_ac'] == 1) echo '<li><i class="fas fa-cloud-meatball"></i><span>AC</span></li>';
                                                                if($album['provide_rooftop'] == 1) echo '<li><i class="fal fa-hotel"></i><span>Rooftop</span></li>';
                                                                if($album['provide_bathroom'] == 1) echo '<li><i class="fas fa-bath"></i><span>Bathroom</span></li>';
                                                                
                                                                if($album['provide_welcome_drink'] == 1) echo '<li><i class="fas fa-wine-glass-alt"></i><span>Welcome Drink</span></li>';
                                                                if($album['provide_food'] == 1) echo '<li><i class="fas fa-hamburger"></i><span>Food</span></li>';
                                                                if($album['provide_seperate_cabin'] == 1) echo '<li><i class="fas fa-car"></i></i><span>Seperate Cabin</span></li>';
                                                                if($album['provide_common_restaurant'] == 1) echo '<li><i class="fas fa-utensils-alt"></i><span>Common Restaurant</span></li>';
                                                                
                                                                
                                                                ?>
                                                                

                                                            </ul>
                                                           
                                                        </div>
                                                    </article>
                                                </div>
                                                <!-- listing-item end -->
                                            </div>
                                            <!--slick-slide-item end-->
                                    
                                    
                                    <?php } ?>
                                <?php } ?>
                                            
                                
                        
                                
                            </div>
                            <!--listing-carousel end-->
                            <div class="swiper-button-prev sw-btn"><i class="fa fa-long-arrow-left"></i></div>
                            <div class="swiper-button-next sw-btn"><i class="fa fa-long-arrow-right"></i></div>
                        </div>
                        <!--  carousel end-->
                    </section>
                    <!-- section end -->
                    <!--section -->
                    <section class="parallax-section" data-scrollax-parent="true">
                        <div class="bg"  data-bg="images/bg/1.jpg" data-scrollax="properties: { translateY: '100px' }"></div>
                        <div class="overlay op7"></div>
                        <!--container-->
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="colomn-text fl-wrap pad-top-column-text_small">
                                        <div class="colomn-text-title">
                                            <h3>Most Popular Resorts & Hotels</h3>
                                            <p>These are our selected best service partners to serve you</p>
                                            <a onclick="viewProviderProfile(<?=$providers[0]['id']?>);" class="btn  color2-bg float-btn">View All Service Providers<i class="fas fa-caret-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <!--light-carousel-wrap-->
                                    <div class="light-carousel-wrap fl-wrap">
                                        <!--light-carousel-->
                                        <div class="light-carousel">
                                            
                                            
                                            <?php if(count($providers) > 0) {
                                            foreach ($providers as $key => $album) { 
                                            ?>
                                            
                                            <!--slick-slide-item-->
                                            <div class="slick-slide-item">
                                                <div class="hotel-card fl-wrap title-sin_item">
                                                    <div class="geodir-category-img card-post">
                                                        <a href="listing-single.html"><img src="<?=$album['company_logo_url']?>" alt=""></a>
                                                        <!--<div class="listing-counter"><?=$album['center_name']?></div>-->
                                                        <div class="geodir-category-opt">
                                                            <div class="listing-rating card-popup-rainingvis" data-starrating2="5"></div>
                                                            <h4 class="title-sin_map"><a href="listing-single.html"><?=$album['company_name']?></a></h4>
                                                            <div class="geodir-category-location"><a href="#" class="single-map-item" data-newlatitude="40.90261483" data-newlongitude="-74.15737152"><i class="fas fa-map-marker-alt"></i> <?=$album['city_id']?></a></div>
                                                            <div class="rate-class-name">
                                                                <div class="score"><strong> Good</strong>8 Reviews </div>
                                                                <span>4.8</span>                                             
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--slick-slide-item end-->
                                            
                                            
                                            
                                            
                                            <?php } ?>
                                            <?php } ?>
                                            
                                                                       
                                        </div>
                                        <!--light-carousel end-->
                                        <div class="fc-cont  lc-prev"><i class="fal fa-angle-left"></i></div>
                                        <div class="fc-cont  lc-next"><i class="fal fa-angle-right"></i></div>
                                    </div>
                                    <!--light-carousel-wrap end-->
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- section end -->
                    <!--section -->
                   
                    <!-- section end -->
                   
                </div>
                <!-- content end-->
            </div>
            <!--wrapper end -->
            
            
            
            
<?php 

include("templates/footer.php");

?>

<script>

    $(document).ready(function() {
        $('#home-menu').addClass('act-link');
    });


    
    
    
</script>


