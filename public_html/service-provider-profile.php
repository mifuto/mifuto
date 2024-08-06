<?php 

if (isset($_GET['key'])) {
    // Get the values of key and value parameters
    $key = $_GET['key'];

    date_default_timezone_set ("Asia/Calcutta");

    // Decode the base64 encoded values
    $providerId = base64_decode($key);

}else {
    header('Location: services.php');
    exit;
}

require_once('admin/config.php');
$DBC = mysqli_connect(HOST, DB_USER, DB_PASS,DB_NAME);

$providers = [];

$sql1 = "SELECT cmp.*,a.name,a.email,b.center_name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblproviderusercompany cmp left join tblprovideruserlogin a on a.id = cmp.user_id left join tblservicescenter b on cmp.servicescenter_id = b.id left join tblcountries c on c.country_id = cmp.county_id left join tblstate d on d.id=cmp.state_id left join tblcity e on e.id = cmp.city_id where a.active =1 and cmp.active =0 and cmp.is_company_add = 1 and cmp.id=$providerId order by cmp.id desc";
$result1 = $DBC->query($sql1);
$count1 = mysqli_num_rows($result1);
if($count1 > 0) {
    while ($row1 = mysqli_fetch_assoc($result1)) {
        array_push($providers,$row1);
    }
}

$services = [];

$where = "";
	   
$selProvider=$providerId;
if($selProvider !="") $where .= " and a.id=$selProvider ";

$sql2 = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,a.company_address,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,say.center_name as service_add,(SELECT file_path FROM tbeservice_folderfiles WHERE service_id = ins.id AND hide = 0 ORDER BY id DESC LIMIT 1) as file_path FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.active = 0 $where  order by ins.id desc LIMIT 6 ";
$result2 = $DBC->query($sql2);
$count2 = mysqli_num_rows($result2);
if($count2 > 0) {
    while ($row2 = mysqli_fetch_assoc($result2)) {
        array_push($services,$row2);
    }
}



include("templates/header.php");

?>
            
            
            <!--  wrapper  -->
            <div id="wrapper">
                <!-- content-->
                <div class="content">
                    <!--  section  -->
                    <section class="color-bg middle-padding ">
                        <div class="wave-bg wave-bg2" ></div>
                        <!--style="background: url('<?=$providers[0]['company_logo_url']?>') repeat !important;opacity: 1 !important;"-->
                        <div class="container">
                            <div class="flat-title-wrap">
                                <h2><span>Provider  : <strong><?=$providers[0]['company_name']?></strong></span></h2>
                                <span class="section-separator"></span>
                                <h4><?=$providers[0]['company_address']?></h4>
                            </div>
                        </div>
                    </section>
                    <!--  section  end-->
                    <div class="breadcrumbs-fs fl-wrap">
                        <div class="container">
                            <div class="breadcrumbs fl-wrap"><a href="#">Home</a><a href="#">Service Provider</a><span><?=$providers[0]['company_name']?></span></div>
                        </div>
                    </div>
                    <!-- section-->
                    <section  id="sec1" class="middle-padding grey-blue-bg">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- box-widget-item-->
                                    <div class="box-widget-item fl-wrap">
                                        <div class="box-widget">
                                            <div class="box-widget-content">
                                                <div class="box-widget-item-header">
                                                    <h3>Service Provider</h3>
                                                </div>
                                                <div class="box-widget-author fl-wrap">
                                                    <div class="box-widget-author-title fl-wrap">
                                                        <div class="box-widget-author-title-img">
                                                            <img src="<?=$providers[0]['company_logo_url']?>" alt=""> 
                                                        </div>
                                                        <a ><?=$providers[0]['company_name']?></a>
                                                        <span><?=$providers[0]['city_id']?>, <?=$providers[0]['state_id']?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- box-widget-item end-->
                                    <!-- box-widget-item-->
                                    <div class="box-widget-item fl-wrap">
                                        <div class="box-widget">
                                            <div class="box-widget-content">
                                                <div class="box-widget-item-header">
                                                    <h3>SP Contact</h3>
                                                </div>
                                                <div class="box-widget-list">
                                                    <ul>
                                                        <li><span><i class="fal fa-map-marker"></i> Adress :</span> <a href="#"><?=$providers[0]['company_address']?></a></li>
                                                        <li><span><i class="fal fa-phone"></i> Phone :</span> <a href="#"><?=$providers[0]['company_phone']?></a></li>
                                                        <li><span><i class="fal fa-envelope"></i> Mail :</span> <a href="#"><?=$providers[0]['company_mail']?></a></li>
                                                        <li><span><i class="fal fa-browser"></i> Website :</span> <a href="<?=$providers[0]['company_link']?>"><?=$providers[0]['company_link']?></a></li>
                                                    </ul>
                                                </div>
                                                <div class="list-widget-social">
                                                    <ul>
                                                        
                                                         <?php 
                                                            
                                                                if($providers[0]['facebook_link'] != "") echo '<li><a href="'.$providers[0]['facebook_link'].'" target="_blank" ><i class="fab fa-facebook-f"></i></a></li>';
                                                                if($providers[0]['instagram_link'] != "") echo '<li><a href="'.$providers[0]['instagram_link'].'" target="_blank" ><i class="fab fa-instagram"></i></a></li>';
                                                                if($providers[0]['twitter_link'] != "") echo '<li><a href="'.$providers[0]['twitter_link'].'" target="_blank"><i class="fab fa-twitter"></i></a></li>';
                                                                if($providers[0]['linkedin_link'] != "") echo '<li><a href="'.$providers[0]['linkedin_link'].'" target="_blank"><i class="fab fa-linkedin"></i></a></li>';
                                                                
                                                                if($providers[0]['pinterest_link'] != "") echo '<li><a href="'.$providers[0]['pinterest_link'].'" target="_blank"><i class="fab fa-pinterest"></i></a></li>';
                                                                if($providers[0]['youtube_link'] != "") echo '<li><a href="'.$providers[0]['youtube_link'].'" target="_blank"><i class="fab fa-youtube"></i></a></li>';
                                                                if($providers[0]['reddit_link'] != "") echo '<li><a href="'.$providers[0]['reddit_link'].'" target="_blank"><i class="fab fa-reddit"></i></a></li>';
                                                              
                                                            
                                                            ?>
                                                        
                                                  
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- box-widget-item end-->
                                    <!-- box-widget-item-->
                                    <div class="box-widget-item fl-wrap">
                                        <div class="box-widget">
                                            <div class="box-widget-content">
                                                <div class="box-widget-item-header">
                                                    <h3>Get In Touuch</h3>
                                                </div>
                                                <div id="contact-form">
                                                    <div id="message"></div>
                                                    <form  class="custom-form" action="php/contact.php" name="contactform" id="contactform">
                                                        <fieldset>
                                                            <label><i class="fal fa-user"></i></label>
                                                            <input type="text" name="name" id="name" placeholder="Your Name *" value=""/>
                                                            <div class="clearfix"></div>
                                                            <label><i class="fal fa-envelope"></i>  </label>
                                                            <input type="text"  name="email" id="email" placeholder="Email Address*" value=""/>
                                                            <textarea name="comments"  id="comments" cols="40" rows="3" placeholder="Your Message:"></textarea>
                                                        </fieldset>
                                                        <button class="btn float-btn color2-bg no-shdow-btn" style="margin-top:15px;" id="submit">Send Message<i class="fal fa-angle-right"></i></button>
                                                    </form>
                                                </div>
                                                <!-- contact form  end--> 
                                            </div>
                                        </div>
                                    </div>
                                    <!-- box-widget-item end-->                                   
                                </div>
                                <div class="col-md-8">
                                    <div class="list-single-main-item fl-wrap no-mar-bottom ">
                                        <div class="list-single-main-item-title fl-wrap">
                                            <h3>About SERVICE PROVIDER .</h3>
                                        </div>
                                        <p>
                                            Vestibulum orci felis, ullamcorper non condimentum non, ultrices ac nunc. Mauris non ligula suscipit, vulputate mi accumsan, dapibus felis. Nullam sed sapien dui. Nulla auctor sit amet sem non porta. Integer iaculis tellus nulla, quis imperdiet magna venenatis vitae..
                                        </p>
                                        <p>Ut nec hinc dolor possim. An eros argumentum vel, elit diceret duo eu, quo et aliquid ornatus delicatissimi. Cu nam tale ferri utroque, eu habemus albucius mel, cu vidit possit ornatus eum. Eu ius postulant salutatus definitionem,  explicari. Graeci viderer qui ut, at habeo facer solet usu. Pri choro pertinax indoctum ne, ad partiendo persecuti forensibus est.</p>
                                        <blockquote>
                                            <p>Vestibulum id ligula porta felis euismod semper. Sed posuere consectetur est at lobortis. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper.</p>
                                        </blockquote>
                                        <p>Ut nec hinc dolor possim. An eros argumentum vel, elit diceret duo eu, quo et aliquid ornatus delicatissimi. Cu nam tale ferri utroque, eu habemus albucius mel, cu vidit possit ornatus eum. Eu ius postulant salutatus definitionem, an e trud erroribus explicari. Graeci viderer qui ut, at habeo facer solet usu. Pri choro pertinax indoctum ne, ad partiendo persecuti forensibus est.</p>
                                    </div>
                                    <div class="list-main-wrap-opt fl-wrap">
                                        <div class="list-main-wrap-title fl-wrap">
                                            <h2>Services</span></h2>
                                        </div>
                                    </div>
                                    <!-- listing-item-container -->
                                    <div class="listing-item-container init-grid-items fl-wrap">
                                        
                                        
                                        <?php if(count($services) > 0) {
                                        foreach ($services as $key => $album) { 
                                        ?>
                                        
                                        
                                        <!-- listing-item  -->
                                        <div class="listing-item" onclick="viewService(<?=$album['id']?>);">
                                            <article class="geodir-category-listing fl-wrap">
                                                <div class="geodir-category-img">
                                                    <a ><img src="<?=$album['file_path']?>" alt=""></a>
                                                   
                                                    <div class="sale-window"><?=$album['service_add']?></div>
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
                                                            <h3 class="title-sin_map"><a href="listing-single.html"><?=$album['name']?></a></h3>
                                                            <div class="geodir-category-location fl-wrap"><a href="#" class="map-item"><i class="fas fa-map-marker-alt"></i> <?=$album['city_id']?>, <?=$album['state_id']?>, <?=$album['county_id']?></a></div>
                                                        </div>
                                                    </div>
                                                    <p><?=$album['description']?></p>
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
                                        
                                        
                                    
                                    <?php } ?>
                                <?php } ?>
                                        
                                        
                                  
                                    </div>
                                    <!-- listing-item-container end-->                                 
                                </div>
                            </div>
                        </div>
                        <div class="section-decor"></div>
                    </section>
                    <!-- section end -->
                </div>
                <!-- content end-->
            </div>
            <!--wrapper end -->
            
            
<?php 

include("templates/footer.php");

?>

