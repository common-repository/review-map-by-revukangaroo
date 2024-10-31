<?php get_header(); ?>


<!-- =====================================================Get Page Title starts Here-=======================================================================-->

<div class="title_reviews_api"><h1><?php echo get_the_title(); ?></h1></div>

<!-- =============================================================Get Page Title Ends Here-================================================================-->


<!-- ============================ Post Thumbnail starts here ===================================== -->
<?php
$reviewmapby_revkang_url_thumb_mapsch = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
if($reviewmapby_revkang_url_thumb_mapsch){
?>
<div class="post-thumbnail">

    <img src="<?php echo $reviewmapby_revkang_url_thumb_mapsch; ?>" longdesc="URL_2" alt="Text_2" />

</div>
<?php } ?>
<!-- ============================ Post Thumbnail ends here ===================================== -->


<?php //loop to get content of page
if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

        <?php the_content(); ?>

    <?php endwhile;
    endif;
	?>

<!--===================================== API Section Starts here============================================ -->


<?php
//================================ Get all dynamic options from settings starts here====================================
 $reviewmapby_revkang_custom_map_key_map= get_option('reviewmapby_revkang_custom_map_key_map');
 //$reviewmapby_revkang_city_api_mapsch= get_option('reviewmapby_revkang_city_api');
 $reviewmapby_revkang_city_api_mapsch= '';
 $reviewmapby_revkang_cities_api_mapsch= get_option('reviewmapby_revkang_cities_api');
 $reviewmapby_revkang_state_api_mapsch= get_option('reviewmapby_revkang_state_api');
 $reviewmapby_revkang_show_map_api= get_option('reviewmapby_revkang_show_map_api');
 $reviewmapby_revkang_show_posts_api= get_option('reviewmapby_revkang_show_posts_api');
 $reviewmapby_revkang_count_api_mapsch= get_option('reviewmapby_revkang_count_api');
 $reviewmapby_revkang_pincount_api_mapsch= get_option('reviewmapby_revkang_pincount_api');
 $reviewmapby_revkang_map_zoom_level= get_option('reviewmapby_revkang_map_zoom_level');




//================================ Get all dynamic options from settings ends here======================================



?>


<?php if($reviewmapby_revkang_custom_map_key_map){ ?>

<?php
//Get City name from url
    $permalink_map_schapi = get_permalink($post->post_parent);

    $cityKey_map_schvar = explode('//',$permalink_map_schapi);

    $cityKey2_map_sch = explode('/',$cityKey_map_schvar[1]);

    $cityKey2_map_sch = array_filter($cityKey2_map_sch);

    $cityKey_ur_mapsch = explode('/',$_SERVER['REQUEST_URI']);

       $count_url_mapsch =  count($cityKey2_map_sch);

    if (!empty($cityKey_ur_mapsch[$count_url_mapsch])) {
        $city = urlencode($cityKey_ur_mapsch[$count_url_mapsch]);

    } else {


        if ($reviewmapby_revkang_city_api_mapsch) {
            $city = urlencode("$reviewmapby_revkang_city_api_mapsch");
        } else {

            $city = urlencode("");
        }




    }
    if ($reviewmapby_revkang_cities_api_mapsch) {
        $cities = urlencode("$reviewmapby_revkang_cities_api_mapsch");
    } else {
        $cities = urlencode("");
    }


    $endpoint = "https://api.revukangaroo.com/plugins/reviews";
    $key = "$reviewmapby_revkang_custom_map_key_map";

    /*optional API Settings*/
    if ($reviewmapby_revkang_state_api_mapsch) {
        $state = "$reviewmapby_revkang_state_api_mapsch";
    } else{

        $state = "";
    }


    if ($reviewmapby_revkang_show_map_api) {
        $showMap = "$reviewmapby_revkang_show_map_api";

    } else {
        $showMap = "Yes";
    }


    if ($reviewmapby_revkang_show_posts_api) {
        $showPosts = "$reviewmapby_revkang_show_posts_api";
    } else {
        $showPosts = "Yes";
    }

    $showTitle = "Yes";

    if ($reviewmapby_revkang_count_api_mapsch) {
        $count = "$reviewmapby_revkang_count_api_mapsch";

    } else {
        $count = "15";

    }

    if($reviewmapby_revkang_pincount_api_mapsch) {
        $pincount = "$reviewmapby_revkang_pincount_api_mapsch";

    } else{

        $pincount = "100";
    }

    if($reviewmapby_revkang_map_zoom_level) {
        $zoomlevel = "$reviewmapby_revkang_map_zoom_level";

    } else{

        $zoomlevel = "7";
    }




$reviewCityUrl = "//$cityKey_map_schvar[1]{city}";

$reviewmapby_revkang_reviewcount = (isset($_POST['reviewcount'])) ? sanitize_text_field($_POST['reviewcount']): $count;
$reviewmapby_revkang_reviewstart = (isset($_POST['reviewstart'])) ? sanitize_text_field($_POST['reviewstart']): 0;
$reviewmapby_revkang_sorting = (isset($_POST['sorting'])) ? sanitize_text_field($_POST['sorting']): '';
$reviewmapby_revkang_receiver = (isset($_POST['receiver'])) ? sanitize_text_field($_POST['receiver']): '';
$reviewmapby_revkang_sender = (isset($_POST['sender'])) ? sanitize_text_field($_POST['sender']): '';
$reviewmapby_revkang_emp = (isset($_POST['emp'])) ? sanitize_text_field($_POST['emp']): '';


/*do nothing below*/
$reviewcount = (isset($reviewmapby_revkang_reviewcount)) ? $reviewmapby_revkang_reviewcount : $count;
$reviewstart = (isset($reviewmapby_revkang_reviewstart)) ? $reviewmapby_revkang_reviewstart : "0";
$hostUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$sorting = (isset($reviewmapby_revkang_sorting)) ? $reviewmapby_revkang_sorting : '';
$receiver = (isset($reviewmapby_revkang_receiver)) ? $reviewmapby_revkang_receiver : '';
$sender = (isset($reviewmapby_revkang_sender)) ? $reviewmapby_revkang_sender : '';
$emp = (isset($reviewmapby_revkang_emp)) ? urlencode($reviewmapby_revkang_emp) : '';
$query = "?clientkey=$key&state=$state&city=$city&cities=$cities&zoomlevel=$zoomlevel&start=$reviewstart&count=$reviewcount&pincount=$pincount&showmap=$showMap&showPosts=$showPosts&showTitle=$showTitle&sorting=$sorting&receiver=$receiver&sender=$sender&emp=$emp&reviewCityUrl=$reviewCityUrl&hostUrl=$hostUrl";
$url = "$endpoint$query";

if( ini_get('allow_url_fopen') ) {

  $response = file_get_contents($url);
 
}else{ 
	
$curl_handle_review=curl_init();
curl_setopt($curl_handle_review, CURLOPT_URL,$url);
curl_setopt($curl_handle_review, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle_review, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle_review, CURLOPT_USERAGENT, 'Review Map by RevuKangaroo');
$response = curl_exec($curl_handle_review);
curl_close($curl_handle_review);
	
} 


echo $response;


?>
  <?php } else{

    echo '<p style="text-align: center;" class="title_reviews_api">Nothing found, Please submit your API keys plugin settings!</p>';


}

?>

<!--============================================================================ API Section Ends here=========================================================== -->
<style type="text/css">.input-text, input[type="text"] {
        width: auto !important;
    }
</style>
<?php get_footer();