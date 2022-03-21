<?php
/**
* Plugin Name: PlanetConectUs Switch API
* Plugin URI: https://planetconectus.com
* Description: API for Switch Website
* Version: 1.0
* Author: Kassis Bassem
* Author URI: https://planetconectus.com
**/



use Thunder\Shortcode\Serializer\JsonSerializer;
use Thunder\Shortcode\Serializer\TextSerializer;
use Thunder\Shortcode\Serializer\XmlSerializer;
use Thunder\Shortcode\Serializer\YamlSerializer;
use Thunder\Shortcode\Shortcode\Shortcode;
use Thunder\Shortcode\HandlerContainer\HandlerContainer;
use Thunder\Shortcode\Parser\RegularParser;
use Thunder\Shortcode\Processor\Processor;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;



use Automattic\WooCommerce\Client;


class PlanetConectUsAPI {
 public $error_permission = array('code' => 400, 'message' => 'invalid permission ');
   public $error = array('code' => 400, 'message' => 'an error occurred');
      public $success = array('code' => 200, 'message' => 'success');
     public $cache ;
    public $category_banner_images = array();
     public $page_banner_images = array();
    public $sliders = array();
    public $home_categories = array();
    public $flash_deals = array();
    public $health_deals = array();
    public $shop_carousel = array();
    public $extra_mega_sales = array();
    public $brands = array();
    public $mega_sales = array();
    public $exclusive_deals = array();
    public $category_brand_image = array();
    public $special_deals = array();
    public $second_carousel = array();
    public $third_carousel = array();
    public $recent_deals = array();
    public $topoffers = array();
     public $todayselection = array();
      public $section2 = array();
     public $recommendedforyou = array();
    public $extraMegaSale_banner = array();
     public $extraMegaSale = array();
    public $top_banner = array();
    public $slider_home_kitchen = array();
    public $new_pics = array();
     public $header_titles = array();
        public $pageProducts = "";
     public $secondee_banners = array();
      public $recomm_banners = array();
       public $final_pics = array();
      public $second_pics = array();

      public $page_mobile_banners = array();
    public $url,$woocommerce;
    public function __construct()
    {
     if ( !defined('ABSPATH') )
         define('ABSPATH', dirname(__FILE__) . '/');
     require ABSPATH.'/vendor/autoload.php';

      $this->cache = new \DivineOmega\DOFileCache\DOFileCache();

     $this->cache->changeConfig(["cacheDirectory" => "/tmp/apicache/"]);


 $this->woocommerce = new Client(
                     "https://staging.switch.com.kw",
                   "ck_74b03edb010faebfacc9a0675f719372531ef5aa",
                   "cs_de0250f6f388289e29d0eab0e0b08520cd4709d6",
                   [
                     'wp_api' => true,
                                 'version' => 'wc/v2',
                                 'verify_ssl' => false,
                                 'query_string_auth' => true,
                   ]
               );




add_filter('woocommerce_rest_check_permissions', 'my_woocommerce_rest_check_permissions', 90, 4);




add_filter( 'woocommerce_product_data_store_cpt_get_products_query', array( $this, 'handle_custom_query_var'), 10, 2 );



    add_action('rest_api_init', function () {

     if ( empty( WC()->cart ) ) {
            WC()->frontend_includes();
            wc_load_cart();
        }
      register_rest_route( 'api/v1', 'homepage',array(
                    'methods'  => 'GET',
                    'callback' => array( $this, 'get_home_page')
          ));
      register_rest_route( 'api/v1', 'product(?:/(?P<id>\d+))?',array(
                           'methods'  => 'GET',
                           'callback' => array( $this, 'get_product_detail'),
                           'args' => [
                                   'id'
                               ]
       ));

        register_rest_route( 'api/v1', 'page',array(
                                  'methods'  => 'GET',
                                  'callback' => array( $this, 'get_page_detail'),
                                  'args' => [
                                          'slug'
                                      ]
              ));

 register_rest_route( 'api/v1', 'switchplus',array(
                                  'methods'  => 'GET',
                                  'callback' => array( $this, 'switchplus')

              ));

       register_rest_route( 'api/v1', 'collection',array(
                                        'methods'  => 'GET',
                                        'callback' => array( $this, 'get_collection_detail')

                    ));




           register_rest_route( 'api/v1', 'menu',array(
                                           'methods'  => 'GET',
                                           'callback' => array( $this, 'get_menu')

                       ));

           register_rest_route( 'api/v1', 'getuser',array(
                                           'methods'  => 'GET',
                                           'callback' => array( $this, 'getuser')

                       ));

  register_rest_route( 'api/v1', 'forgotpassword',array(
                                           'methods'  => 'POST',
                                           'callback' => array( $this, 'forgotpassword')

                       ));


 register_rest_route( 'api/v1', 'updateproduct',array(
       'methods'  => 'POST',
        'callback' => array( $this, 'updateproduct')
                         ));

  register_rest_route( 'api/v1', 'addreview',array(
                                             'methods'  => 'POST',
                                             'callback' => array( $this, 'addreview')

                         ));






      register_rest_route( 'api/v1', 'login',array(
                    'methods'  => 'POST',
                    'callback' => array( $this, 'login')
          ));




             register_rest_route( 'api/v1', 'updateuser',array(
                                                                  'methods'  => 'POST',
                                                                  'callback' => array( $this, 'updateuser')

                                              ));


          register_rest_route( 'api/v1', 'category',array(
                                          'methods'  => 'GET',
                                          'callback' => array( $this, 'get_category_detail'),
                                          'args' => [
                                                  'slug'
                                              ]



                      ));

           register_rest_route( 'api/v1', 'register',array(
                                                    'methods'  => 'POST',
                                                    'callback' => array( $this, 'register')

                                ));


            register_rest_route( 'api/v1', 'getaddresses',array(
                                                               'methods'  => 'GET',
                                                               'callback' => array( $this, 'getaddresses')

                                           ));


            register_rest_route( 'api/v1', 'setaddresses',array(
                                                                           'methods'  => 'POST',
                                                                           'callback' => array( $this, 'setaddresses')

                                                       ));


           register_rest_route( 'api/v1', 'orders',array(
                                                               'methods'  => 'GET',
                                                               'callback' => array( $this, 'getorders')

                                           ));

           register_rest_route( 'api/v1', 'order',array(
                                                                          'methods'  => 'GET',
                                                                          'callback' => array( $this, 'getorder')

                                                      ));

             register_rest_route( 'api/v1', 'addwishlist',array(
                                                                                      'methods'  => 'POST',
                                                                                      'callback' => array( $this, 'addwishlist')

                                                                  ));


   register_rest_route( 'api/v1', 'compare',array(
                                                                                      'methods'  => 'GET',
                                                                                      'callback' => array( $this, 'compare')

                                                                  ));

 register_rest_route( 'api/v1', 'getCompare',array(
                                                                                      'methods'  => 'GET',
                                                                                      'callback' => array( $this, 'getCompare')

                                                                  ));





        register_rest_route( 'api/v1', 'getwishlist',array(
                                                                     'methods'  => 'GET',
                                                                     'callback' => array( $this, 'getwishlist')

                                                 ));

  register_rest_route( 'api/v1', 'removewishlist',array(
                                                                                      'methods'  => 'POST',
                                                                                      'callback' => array( $this, 'removewishlist')

                                                                  ));

    register_rest_route( 'api/v1', 'addtocart',array(
                                                                                        'methods'  => 'POST',
                                                                                        'callback' => array( $this, 'addtocart')

                                                                    ));

 register_rest_route( 'api/v1', 'getcard',array(
                                                                                        'methods'  => 'POST',
                                                                                        'callback' => array( $this, 'getcard')

                                                                    ));


  register_rest_route( 'api/v1', 'getcardios',array(
                                                                                         'methods'  => 'POST',
                                                                                         'callback' => array( $this, 'getcardios')

                                                                     ));


register_rest_route( 'api/v1', 'getcheckoutdata',array(
                                                                                        'methods'  => 'POST',
                                                                                        'callback' => array( $this, 'getcheckoutdata')

                                                                    ));


register_rest_route( 'api/v1', 'search',array(
                                                                                        'methods'  => 'GET',
                                                                                        'callback' => array( $this, 'search')

                                                                    ));

register_rest_route( 'api/v1', 'getextramegasales',array(
                                                                                        'methods'  => 'GET',
                                                                                        'callback' => array( $this, 'getextramegasales')

                                                                    ));

register_rest_route( 'api/v1', 'createorder',array('methods'  => 'POST', 'callback' => array( $this, 'createorder')

                                                                    ));

    });





    }

    function my_woocommerce_rest_check_permissions($permission, $context, $object_id, $post_type) {

        if($_SERVER['PHP_AUTH_USER'] == 'ck_6b3e80e56bc8a33ec9f19cd175f478b81f1d2a57' && $_SERVER['PHP_AUTH_PW'] == 'cs_478730dfd19e5b1f35ccf5d1bd961f36a6b5ce4f') {
    		return true;
        }

        return $permission;
    }


    function switchplus(){
        global $wpdb;
        $result = array();
        $table_name = $wpdb->base_prefix."mobile_management";
        $row = $wpdb->get_row("select * from $table_name where id=1");
        if($_GET['language']=='ar'){
          $result['sp_latest_slider'] = explode(",",$row->sp_latest_slider_ar);
          $result['sp_s_slider'] = explode(",",$row->sp_s_slider_ar);
          $result['sp_a_slider'] = explode(",",$row->sp_a_slider_ar);
          $result['sp_j_slider'] = explode(",",$row->sp_j_slider_ar);
          $result['sp_tablets_slider'] = explode(",",$row->sp_tablets_slider_ar);
        }
        else{
          $result['sp_latest_slider'] = explode(",",$row->sp_latest_slider);
          $result['sp_s_slider'] = explode(",",$row->sp_s_slider);
          $result['sp_a_slider'] = explode(",",$row->sp_a_slider);
          $result['sp_j_slider'] = explode(",",$row->sp_j_slider);
          $result['sp_tablets_slider'] = explode(",",$row->sp_tablets_slider);
        }
        foreach($result['sp_latest_slider'] as $key => $pid){
          $product = wc_get_product($pid);
          $p['id'] = $product->id;
        //  $p['wishlist'] = $this->isInWishlist($_GET['fingerprint'],$product->id);
          $p['name'] = $product->name;
          $p['price_html'] = $product->get_price_html();
          $p['image'] = get_the_post_thumbnail_url($product->id,array(600,600));
          $meta_data = $product->get_meta_data();
          $p['label_text'] = "";
          $p['label_style'] = "";
          if(sizeof($meta_data)!=0){
            foreach($meta_data as $data){
              if($data->key=="_product_label"){
                $p['label_text'] = $data->value;
              }
              else if($data->key=="_label_style"){
                $p['label_style'] = $data->value;
              }
            }
          }
          //Dynamic Labels
          $product_style = get_post_meta($product->id, '_label_style', true);
          if($product_style == 'discount'){
            if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){
              // Get product prices
              $regular_price = (float) $product->get_regular_price(); // Regular price
              $sale_price = (float) $product->get_price(); // Active price (the "Sale price" when on-sale)

              // "Saving Percentage" calculation and formatting
              $precision = 1; // Max number of decimals
              $saving_percentage = (int) round( 100 - ( $sale_price / $regular_price * 100 ), 1 );

              // Append to the formated html price
              $price .= sprintf( __('<p class="saved-sale">Save: %s</p>', 'woocommerce' ), $saving_percentage );
              $p['label_text'] = $saving_percentage." % ".__("Off");
              $p['label_style'] = "style2";
            }

          }
          else if($product_style == 'new_arrival'){
            $p['label_text'] = __("New Arrival");
            $p['label_style'] = "style3";
          }
          //End Dynamic Labels
          $result['sp_latest_slider'][$key] = $p;
        }
        foreach($result['sp_s_slider'] as $key => $pid){
          $product = wc_get_product($pid);
          $p['id'] = $product->id;
         // $p['wishlist'] = $this->isInWishlist($_GET['fingerprint'],$product->id);
          $p['name'] = $product->name;
          $p['price_html'] = $product->get_price_html();
          $p['image'] = get_the_post_thumbnail_url($product->id,array(600,600));
          $meta_data = $product->get_meta_data();
          $p['label_text'] = "";
          $p['label_style'] = "";
          if(sizeof($meta_data)!=0){
            foreach($meta_data as $data){
              if($data->key=="_product_label"){
                $p['label_text'] = $data->value;
              }
              else if($data->key=="_label_style"){
                $p['label_style'] = $data->value;
              }
            }
          }
          //Dynamic Labels
          $product_style = get_post_meta($product->id, '_label_style', true);
          if($product_style == 'discount'){
            if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){
              // Get product prices
              $regular_price = (float) $product->get_regular_price(); // Regular price
              $sale_price = (float) $product->get_price(); // Active price (the "Sale price" when on-sale)

              // "Saving Percentage" calculation and formatting
              $precision = 1; // Max number of decimals
              $saving_percentage = (int) round( 100 - ( $sale_price / $regular_price * 100 ), 1 );

              // Append to the formated html price
              $price .= sprintf( __('<p class="saved-sale">Save: %s</p>', 'woocommerce' ), $saving_percentage );
              $p['label_text'] = $saving_percentage." % ".__("Off");
              $p['label_style'] = "style2";
            }

          }
          else if($product_style == 'new_arrival'){
            $p['label_text'] = __("New Arrival");
            $p['label_style'] = "style3";
          }
          //End Dynamic Labels
          $result['sp_s_slider'][$key] = $p;
        }
        foreach($result['sp_a_slider'] as $key => $pid){
          $product = wc_get_product($pid);
          $p['id'] = $product->id;
       //   $p['wishlist'] = $this->isInWishlist($_GET['fingerprint'],$product->id);
          $p['name'] = $product->name;
          $p['price_html'] = $product->get_price_html();
          $p['image'] = get_the_post_thumbnail_url($product->id,array(600,600));
          $meta_data = $product->get_meta_data();
          $p['label_text'] = "";
          $p['label_style'] = "";
          if(sizeof($meta_data)!=0){
            foreach($meta_data as $data){
              if($data->key=="_product_label"){
                $p['label_text'] = $data->value;
              }
              else if($data->key=="_label_style"){
                $p['label_style'] = $data->value;
              }
            }
          }
          //Dynamic Labels
          $product_style = get_post_meta($product->id, '_label_style', true);
          if($product_style == 'discount'){
            if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){
              // Get product prices
              $regular_price = (float) $product->get_regular_price(); // Regular price
              $sale_price = (float) $product->get_price(); // Active price (the "Sale price" when on-sale)

              // "Saving Percentage" calculation and formatting
              $precision = 1; // Max number of decimals
              $saving_percentage = (int) round( 100 - ( $sale_price / $regular_price * 100 ), 1 );

              // Append to the formated html price
              $price .= sprintf( __('<p class="saved-sale">Save: %s</p>', 'woocommerce' ), $saving_percentage );
              $p['label_text'] = $saving_percentage." % ".__("Off");
              $p['label_style'] = "style2";
            }

          }
          else if($product_style == 'new_arrival'){
            $p['label_text'] = __("New Arrival");
            $p['label_style'] = "style3";
          }
          //End Dynamic Labels
          $result['sp_a_slider'][$key] = $p;
        }
        foreach($result['sp_j_slider'] as $key => $pid){
          $product = wc_get_product($pid);
          $p['id'] = $product->id;
      //    $p['wishlist'] = $this->isInWishlist($_GET['fingerprint'],$product->id);
          $p['name'] = $product->name;
          $p['price_html'] = $product->get_price_html();
          $p['image'] = get_the_post_thumbnail_url($product->id,array(600,600));
          $meta_data = $product->get_meta_data();
          $p['label_text'] = "";
          $p['label_style'] = "";
          if(sizeof($meta_data)!=0){
            foreach($meta_data as $data){
              if($data->key=="_product_label"){
                $p['label_text'] = $data->value;
              }
              else if($data->key=="_label_style"){
                $p['label_style'] = $data->value;
              }
            }
          }
          //Dynamic Labels
          $product_style = get_post_meta($product->id, '_label_style', true);
          if($product_style == 'discount'){
            if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){
              // Get product prices
              $regular_price = (float) $product->get_regular_price(); // Regular price
              $sale_price = (float) $product->get_price(); // Active price (the "Sale price" when on-sale)

              // "Saving Percentage" calculation and formatting
              $precision = 1; // Max number of decimals
              $saving_percentage = (int) round( 100 - ( $sale_price / $regular_price * 100 ), 1 );

              // Append to the formated html price
              $price .= sprintf( __('<p class="saved-sale">Save: %s</p>', 'woocommerce' ), $saving_percentage );
              $p['label_text'] = $saving_percentage." % ".__("Off");
              $p['label_style'] = "style2";
            }

          }
          else if($product_style == 'new_arrival'){
            $p['label_text'] = __("New Arrival");
            $p['label_style'] = "style3";
          }
          //End Dynamic Labels
          $result['sp_j_slider'][$key] = $p;
        }
        foreach($result['sp_tablets_slider'] as $key => $pid){
          $product = wc_get_product($pid);
          $p['id'] = $product->id;
     //     $p['wishlist'] = $this->isInWishlist($_GET['fingerprint'],$product->id);
          $p['name'] = $product->name;
          $p['price_html'] = $product->get_price_html();
          $p['image'] = get_the_post_thumbnail_url($product->id,array(600,600));
          $meta_data = $product->get_meta_data();
          $p['label_text'] = "";
          $p['label_style'] = "";
          if(sizeof($meta_data)!=0){
            foreach($meta_data as $data){
              if($data->key=="_product_label"){
                $p['label_text'] = $data->value;
              }
              else if($data->key=="_label_style"){
                $p['label_style'] = $data->value;
              }
            }
          }
          //Dynamic Labels
          $product_style = get_post_meta($product->id, '_label_style', true);
          if($product_style == 'discount'){
            if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){
              // Get product prices
              $regular_price = (float) $product->get_regular_price(); // Regular price
              $sale_price = (float) $product->get_price(); // Active price (the "Sale price" when on-sale)

              // "Saving Percentage" calculation and formatting
              $precision = 1; // Max number of decimals
              $saving_percentage = (int) round( 100 - ( $sale_price / $regular_price * 100 ), 1 );

              // Append to the formated html price
              $price .= sprintf( __('<p class="saved-sale">Save: %s</p>', 'woocommerce' ), $saving_percentage );
              $p['label_text'] = $saving_percentage." % ".__("Off");
              $p['label_style'] = "style2";
            }

          }
          else if($product_style == 'new_arrival'){
            $p['label_text'] = __("New Arrival");
            $p['label_style'] = "style3";
          }
          //End Dynamic Labels
          $result['sp_tablets_slider'][$key] = $p;
        }

        echo json_encode($result);
        exit;
      }


function handle_custom_query_var( $query, $query_vars ) {
    if ( isset( $query_vars['like_name'] ) && ! empty( $query_vars['like_name'] ) ) {
        $query['s'] = esc_attr( $query_vars['like_name'] );
    }

    return $query;
}

     public function search($args){
       $language_param = $args['language'];
  $language_param = $args['language'];
        global $wpdb;


           if($args["query"] == ""){
             $data['products'] = [];
             $response = new WP_REST_Response($data);
             $response->set_status(200);
              return $response;
             }




       $search_query = "SELECT ID FROM {$wpdb->prefix}posts
                                WHERE post_type = 'product' and post_status = 'publish'
                            AND post_title LIKE %s";

       $like = '%'.$args["query"].'%';
       $results = $wpdb->get_results($wpdb->prepare($search_query, $like), ARRAY_N);

       $ids = array_merge(...$results);





          $ids = array_slice($ids, 0 , 200);


             $products = array();

           for ($i = 0; $i < count($ids); $i++) {


               $product_id = apply_filters( 'wpml_object_id', $ids[$i], 'product', false, $language_param);
               $product   = wc_get_product($product_id );
               if ($product){
             $permalink = $product->get_permalink();
               $item = array();

               // $item["permalink"] = $permalink;
               $item["id"] = $product->id;
               $item["name"] = htmlspecialchars_decode($product->name);;
               $item["date_modified"] = $product->date_modified;
               $item["date_created"] = $product->date_created;
               $item["short_description"] = $product->short_description;
                $item["price"] = number_format( $product->price, 3, '.', '');
               $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
               $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
               $item["stock_status"] = $product->stock_status;
               $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
               $item["express"]  = 0;
               $item["best_seller"]  = 0;
                $item["flash_deal"]  =0;

                            $collections =  wp_get_post_terms($product->id,"collection");
                            for ($j = 0; $j < count($collections); $j++) {
                              if  ($collections[$j]->name == "Express"){
                               $item["express"] = 1;
                              }
                                if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                             $item["best_seller"] = 1;
                               }
                              if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                               $item["flash_deal"] = 1;
                              }
                            }

               	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

                 	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

                 	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





                        $item["warranty"] = $warranty;
                        $item["flash_deal_date"] = $date;

               $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
               if(  $item["id"] ){
                 $products[] = $item;
               }
           }
           }
               $data['products'] = $products;

              $response = new WP_REST_Response($data);
              $response->set_status(200);

              return $response;
 }
function getextramegasales($args){


        $post= get_post( 100545);


        $handlers = new HandlerContainer();
        $self = $this;
        $handlers->add('vc_gallery', function(ShortcodeInterface $s)  use($self) {
         if ($s->getParent()->getParent()->getParameter('el_id') == "banner_extraMegaSale"){
             $item = array();
              $item['url'] = $s->getParameter('images');
             $item['link'] = $s->getParameter('onclick');
             $self->top_banner[] =  $item;
             }
           if ($s->getParent()->getParameter('el_id') == "extra_mega_sales_gallery"){
                       $item = array();
                        $item['url'] = $s->getParameter('images');
                       $item['link'] = $s->getParameter('onclick');
                       $self->extraMegaSale_banner[] =  $item;
                       }




    return "";
    });

     $handlers->add('product_slider_widget', function(ShortcodeInterface $s)  use($self) {
      if ($s->getParent()->getParent()->getParameter('el_id') == "top_offres"){
                  $self->topoffers =  $s->getParameter('mobile_products');;
                  }
 if ($s->getParent()->getParent()->getParameter('el_id') == "slider_home_kitchen"){
                  $self->slider_home_kitchen =  $s->getParameter('mobile_products');;
                  }

 if ($s->getParent()->getParent()->getParameter('el_id') == "extraMegaSale"){
                  $self->extraMegaSale = $s->getParameter('mobile_products');;
                  }


        return "";
        });

 $processor = new Processor(new RegularParser(), $handlers);
    $processor->process($post->post_content);
    $data = array();


$data = array();

 foreach ( $this->extraMegaSale_banner as $item  ) {
           $url =     wp_get_attachment_image_src($item["url"], $size )[0];
             if (!is_null ($url)){
               $item["url"] =  $url ;
               $data['extraMegaSale_banner'] [] = $item ;
             }
        }

        foreach ( $this->top_banner as $item  ) {
                   $url =     wp_get_attachment_image_src($item["url"], $size )[0];
                     if (!is_null ($url)){
                       $item["url"] =  $url ;
                       $data['top_banner'] [] = $item ;
                     }
        }


        $topoffers = explode(",", $this->topoffers);




        $topoffers_products = array();
        for ($i = 0; $i < count($topoffers); $i++) {
               $product_id = apply_filters( 'wpml_object_id', $topoffers[$i], 'product', false, $args["language"] );
               $product   = wc_get_product(  $product_id );
             $item = array();
             $item["id"] = $product->id;
              $item["permalink"] = $product->get_permalink();
             $item["name"] = htmlspecialchars_decode($product->name);;
             $item["date_modified"] = $product->date_modified;
             $item["date_created"] = $product->date_created;
             $item["short_description"] = $product->short_description;
              $item["price"] = number_format( $product->price, 3, '.', '');
             $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
        $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                          $item["stock_status"] = $product->stock_status;
          $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
                $item["status"] = $product->status;
              $item["express"]  = 0;
             $item["best_seller"]  = 0;
             $item["flash_deal"]  =0;

             $collections =  wp_get_post_terms($product->id,"collection");
             for ($j = 0; $j < count($collections); $j++) {
               if  ($collections[$j]->name == "Express"){
                $item["express"] = 1;
               }
                 if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                              $item["best_seller"] = 1;
                }
               if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                $item["flash_deal"] = 1;
               }
             }

	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

  	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

  	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





         $item["warranty"] = $warranty;
         $item["flash_deal_date"] = $date;
             $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
             if(  $item["id"] ){
              $topoffers_products[] = $item;
             }
         }

         $data['topoffers'] = $topoffers_products;




        $extraMegaSale = explode(",", $this->extraMegaSale);




        $extraMegaSale_products = array();
        for ($i = 0; $i < count($extraMegaSale); $i++) {
           $product_id = apply_filters( 'wpml_object_id', $extraMegaSale[$i], 'product', false, $args["language"] );
               $product   = wc_get_product(  $product_id );
             $item = array();
             $item["id"] = $product->id;
               $item["permalink"] = $product->get_permalink();
             $item["name"] = htmlspecialchars_decode($product->name);;
             $item["date_modified"] = $product->date_modified;
             $item["date_created"] = $product->date_created;
             $item["short_description"] = $product->short_description;
               $item["price"] = number_format( $product->price, 3, '.', '');
             $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
           $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                          $item["stock_status"] = $product->stock_status;
          $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
                  $item["express"]  = 0;
                         $item["best_seller"]  = 0;
                         $item["flash_deal"]  =0;

                         $collections =  wp_get_post_terms($product->id,"collection");
                         for ($j = 0; $j < count($collections); $j++) {
                           if  ($collections[$j]->name == "Express"){
                            $item["express"] = 1;
                           }
                             if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                          $item["best_seller"] = 1;
                            }
                           if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                            $item["flash_deal"] = 1;
                           }
                         }

            	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

              	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

              	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





                     $item["warranty"] = $warranty;
                     $item["flash_deal_date"] = $date;

             $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
             if(  $item["id"] ){
              $extraMegaSale_products[] = $item;
             }
         }

         $data['extraMegaSale'] = $extraMegaSale_products;



$slider_home_kitchen = explode(",", $this->slider_home_kitchen);




        $slider_home_kitchen_products = array();
        for ($i = 0; $i < count($slider_home_kitchen); $i++) {
               $product_id = apply_filters( 'wpml_object_id', $extraMegaSale[$i], 'product', false, $args["language"] );
                       $product   = wc_get_product(  $product_id );
             $item = array();
             $item["id"] = $product->id;
               $item["permalink"] = $product->get_permalink();
             $item["name"] = htmlspecialchars_decode($product->name);;
             $item["date_modified"] = $product->date_modified;
             $item["date_created"] = $product->date_created;
             $item["short_description"] = $product->short_description;
               $item["price"] = number_format( $product->price, 3, '.', '');
              $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
     $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                          $item["stock_status"] = $product->stock_status;
          $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
                   $item["express"]  = 0;
                          $item["best_seller"]  = 0;
                          $item["flash_deal"]  =0;

                          $collections =  wp_get_post_terms($product->id,"collection");
                          for ($j = 0; $j < count($collections); $j++) {
                            if  ($collections[$j]->name == "Express"){
                             $item["express"] = 1;
                            }
                              if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                           $item["best_seller"] = 1;
                             }
                            if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                             $item["flash_deal"] = 1;
                            }
                          }

             	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

               	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

               	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





                      $item["warranty"] = $warranty;
                      $item["flash_deal_date"] = $date;

             $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
             if(  $item["id"] ){
              $slider_home_kitchen_products[] = $item;
             }
         }

         $data['slider_home_kitchen'] = $slider_home_kitchen_products;

        $response = new WP_REST_Response(  $data);
                   $response->set_status(200);
                   return $response;

}


function getorderbyid($id){



        try{

          $results = $this->woocommerce->get('orders/'.$id);



           $order = wc_get_order($results->id);




           $results->date_formatted = date('Y-M-d',strtotime($results->date_created));



           $results->order_status_label = wc_get_order_status_name($results->status);
           $results->order_totals =  $order->get_order_item_totals();

           $order_data = $order->get_data();

$total = 0;
$sub_total = 0;

    foreach ( $order_data['line_items'] as $key => $item ) {
      $total = $total +  ($order_data['line_items'][$key]['total']);
      $sub_total = $sub_total +  ($order_data['line_items'][$key]['sub_total']);
	}

$results->order_totals['order_total']['value'] = $total;
$results->order_totals['cart_subtotal']['value'] = $total;

          $results->order_totals['cart_subtotal']['value'] = str_replace("&#x62f;.&#x643;", "", $results->order_totals['cart_subtotal']['value']);
            $results->order_totals['order_total']['value'] = str_replace("&#x62f;.&#x643;", "", $results->order_totals['order_total']['value']);

            $results->order_totals['cart_subtotal']['value'] = strip_tags($results->order_totals['cart_subtotal']['value'] );

            $results->order_totals['cart_subtotal']['value'] = ""+preg_replace('/\D/', '', $results->order_totals['cart_subtotal']['value']) ;
             $results->order_totals['cart_subtotal']['value'] =  "KWD".number_format(  $results->order_totals['cart_subtotal']['value'], 3, '.', '') ;

             $results->order_totals['order_total']['value'] = strip_tags($results->order_totals['order_total']['value'] );
            $results->order_totals['order_total']['value'] = "KWD"+preg_replace('/\D/', '', $results->order_totals['order_total']['value'])  ;
           $results->order_totals['order_total']['value'] =  "KWD".number_format(  $results->order_totals['order_total']['value'], 3, '.', '') ;




           //$results->meta_data['tracking_url']= $this->get_redirect_target($results->meta_data['tracking_url']);


           foreach($results->meta_data as $key => $meta){
             if($meta->key=="tracking_url"){
               $meta->value = $this->get_redirect_target($meta->value );

             }
           }
           if($order->status != 'complete'){
             $expected_delivery = get_option('expected_delivery');
             $results->expected_delivery = '<ul class="expected-delivery-list">
     					<li>'.sprintf(__("For Air conditioners - %s", 'switch'), date('d/m/Y', strtotime($results->date_created.' +'.$expected_delivery['ac'].' day'))).'</li>
     					<li>'.sprintf(__("For Large Home Appliances - %s", 'switch'), date('d/m/Y', strtotime($results->date_created.' +'.$expected_delivery['la'].' day'))).'</li>
     					<li>'.sprintf(__("For Mobiles and other products - %s", 'switch'), date('d/m/Y', strtotime($results->date_created.' +'.$expected_delivery['mobile'].' day'))).'</li>';
     					if($expected_delivery['custom'] != null && $expected_delivery['custom'] != ''){
     						$results->expected_delivery.= '<li>'.__($expected_delivery['custom']).'</li>';
     					}
     				$results->expected_delivery .= '</ul>';
           }
           unset($results->meta_data);

           return $results;
        }
        catch (HttpClientException $e) {

          $error = json_decode($e->getResponse()->getBody());
          echo $e->getResponse()->getBody();
        }

    return array();
  }



  function getorder($args){



           $fingerprint = $args['fingerprint'];

           $userargs = array(
                  'meta_query' => array(
                    array(
                      "key" => 'fingerprint',
                      "value" => $fingerprint
                    )
                  )
                );
                $wp_user_query = new WP_User_Query($userargs);
                $users = $wp_user_query->get_results();

                   if ($users != NULL && count($users)== 0) {
                       $response = new WP_REST_Response($this->error);
                       $response->set_status(400);
                        return $response;
                    }

                $user =  $users[0]->data;
          try{

            $results = $this->woocommerce->get('orders/'.$args['id']);




             $order = wc_get_order($results->id);
             $userId = $order->get_user_id();
             $user = json_decode(json_encode($user), true);

             if ((int) $userId != (int) $user["ID"]){
                 $response = new WP_REST_Response($this->error_permission);
                  $response->set_status(400);
                  return $response;
             }





             $results->date_formatted = date('Y-M-d',strtotime($results->date_created));



             $results->order_status_label = wc_get_order_status_name($results->status);
             $results->order_totals =  $order->get_order_item_totals();



            $results->order_totals['cart_subtotal']['value'] = str_replace("&#x62f;.&#x643;", "", $results->order_totals['cart_subtotal']['value']);
              $results->order_totals['order_total']['value'] = str_replace("&#x62f;.&#x643;", "", $results->order_totals['order_total']['value']);

              $results->order_totals['cart_subtotal']['value'] = strip_tags($results->order_totals['cart_subtotal']['value'] );

              $results->order_totals['cart_subtotal']['value'] = ""+preg_replace('/\D/', '', $results->order_totals['cart_subtotal']['value']) ;
               $results->order_totals['cart_subtotal']['value'] =  "KWD".number_format(  $results->order_totals['cart_subtotal']['value'], 3, '.', '') ;

               $results->order_totals['order_total']['value'] = strip_tags($results->order_totals['order_total']['value'] );
              $results->order_totals['order_total']['value'] = "KWD"+preg_replace('/\D/', '', $results->order_totals['order_total']['value'])  ;
             $results->order_totals['order_total']['value'] =  "KWD".number_format(  $results->order_totals['order_total']['value'], 3, '.', '') ;



             //$results->meta_data['tracking_url']= $this->get_redirect_target($results->meta_data['tracking_url']);


             foreach($results->meta_data as $key => $meta){
               if($meta->key=="tracking_url"){
                 $meta->value = $this->get_redirect_target($meta->value );

               }
             }
             if($order->status != 'complete'){
               $expected_delivery = get_option('expected_delivery');
               $results->expected_delivery = '<ul class="expected-delivery-list">
       					<li>'.sprintf(__("For Air conditioners - %s", 'switch'), date('d/m/Y', strtotime($results->date_created.' +'.$expected_delivery['ac'].' day'))).'</li>
       					<li>'.sprintf(__("For Large Home Appliances - %s", 'switch'), date('d/m/Y', strtotime($results->date_created.' +'.$expected_delivery['la'].' day'))).'</li>
       					<li>'.sprintf(__("For Mobiles and other products - %s", 'switch'), date('d/m/Y', strtotime($results->date_created.' +'.$expected_delivery['mobile'].' day'))).'</li>';
       					if($expected_delivery['custom'] != null && $expected_delivery['custom'] != ''){
       						$results->expected_delivery.= '<li>'.__($expected_delivery['custom']).'</li>';
       					}
       				$results->expected_delivery .= '</ul>';
             }
             unset($results->meta_data);

             echo json_encode($results);
          }
          catch (HttpClientException $e) {

            $error = json_decode($e->getResponse()->getBody());
            echo $e->getResponse()->getBody();
          }



     exit;
    }


public function knet_process_payment($order_id) {

     WC()->session = new WC_Session_Handler();
     WC()->session->init();
     WC()->customer = new WC_Customer( get_current_user_id(), true );
     WC()->cart = new WC_Cart();
     $woocommerce = WC();
        $order = new WC_Order($order_id);
    $order_number = $order->get_order_number();
    $order_amount = $order->get_total();
    $customer_email = $order->get_billing_email();
    $gateway_url = "https://pay.switch.com.kw/pos/crt/";
    $gateway_code = "kpay-test";
    $notify_url = get_home_url() . "?api=true";
    if ( version_compare( WOOCOMMERCE_VERSION, '2.4.0', '>=' ) ) {
      $disclosure_url = str_replace('https', 'https', add_query_arg( 'wc-api', 'wc_gateway_knpay', home_url( '/' ) ));
    } else {
      $disclosure_url = str_replace('https', 'https', WC()->api_request_url( 'wc_gateway_knpay' ));
    }
    $current_lang = get_locale();
    $split_lang = explode("_",$current_lang);
    $lang_code = $split_lang[0];
    //The default method not working.
    //$currency_code = $order->get_currency();
    //$currency_code = get_woocommerce_currency();

    $curl = curl_init($gateway_url);
    curl_setopt( $curl, CURLOPT_POST, true );
    curl_setopt( $curl, CURLOPT_POSTFIELDS,'amount='.$order_amount.'&currency_code=KWD&language='.$lang_code.'&gateway_code='.$gateway_code.'&order_no='.$order_number.'&customer_email='.$customer_email.'&disclosure_url='.$disclosure_url.'&redirect_url='.$notify_url);
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    $response = curl_exec ( $curl );
    $curlresponse = json_decode($response, true);
    if ($response === FALSE) {
      $error_message = curl_error($curl);
      $order->add_order_note('Error in configuration :'.$error_message);
      $order->update_status('cancelled');

      $msg['class'] = 'error';
            $msg['message'] = __('There is a problem with the configuration. Kindly contact the site administrator to resolve the problem.','knpay');

    } else {

      if($curlresponse['url'] != "") {
        $order->add_order_note('Customer redirect to the payment gateway');
        return array('result' => 'success','redirect'  => ($curlresponse['url']));
      } else {
        $error_data = json_encode($curlresponse);
        $order->add_order_note('Error in payment process :'.$error_data);
        $order->update_status('cancelled');

        $msg['class'] = 'error';
              $msg['message'] = __('There is a problem in payment process. Kindly contact the site administrator to resolve the problem','knpay');
      }
    }

    //Add the success and failure message .
      if ( function_exists( 'wc_add_notice' ) ) {
          wc_add_notice( $msg['message'], $msg['class'] );
      } else  {
          if($msg['class']=='success'){
              $woocommerce->add_message( $msg['message']);
          } else {
              $woocommerce->add_error( $msg['message'] );
          }
          $woocommerce->set_messages();
      }

      /* if($lang_code == "ar") {
        $redirect_url = get_home_url() . "/checkout-2/order-received-ar/" . $order_id . "/?key=" .  $order->order_key;
      } else {
        $redirect_url = get_home_url() . "/checkout/order-received/" . $order_id . "/?key=" .  $order->order_key;
      } */

      $redirect_url = $order->get_cancel_order_url();
      return array('result' => 'error','redirect'  => ($redirect_url ));
    }





function createorder($args){
   $woocommerce = WC();
    if($args['language'] == 'ar'){
      add_filter( 'locale', function(){return 'ar';},30 );
    }
    $fingerprint = $args['fingerprint'];
    $order = wc_create_order();
    if(isset($args['fingerprint'])&&$fingerprint!=''){
      $userargs = array(
        'meta_query' => array(
          array(
            "key" => 'fingerprint',
            "value" => $fingerprint
          )
        )
      );
      $wp_user_query = new WP_User_Query($userargs);
      $users = $wp_user_query->get_results();
      if(!empty($users)){
        $user =  $users[0]->data;
        $customer = new WC_Customer($user->ID);
        //Billing Fields
        if(isset($args['billing_first_name'])&&$args['billing_first_name']!='')
          $customer->set_billing_first_name($args['billing_first_name']);

        if(isset($args['billing_last_name'])&&$args['billing_last_name']!='')
          $customer->set_billing_last_name($args['billing_last_name']);

        if(isset($args['billing_company'])&&$args['billing_company']!='')
          $customer->set_billing_company($args['billing_company']);

        if(isset($args['billing_address_1'])&&$args['billing_address_1']!='')
          $customer->set_billing_address_1($args['billing_address_1']);

        if(isset($args['billing_address_2'])&&$args['billing_address_2']!='')
          $customer->set_billing_address_2($args['billing_address_2']);

        if(isset($args['billing_city'])&&$args['billing_city']!='')
          $customer->set_billing_city($args['billing_city']);

        if(isset($args['billing_state'])&&$args['billing_state']!='')
          $customer->set_billing_state($args['billing_state']);

        if(isset($args['billing_postcode'])&&$args['billing_postcode']!='')
          $customer->set_billing_postcode($args['billing_postcode']);

        if(isset($args['billing_country'])&&$args['billing_country']!='')
          $customer->set_billing_country($args['billing_country']);

        if(isset($args['billing_email'])&&$args['billing_email']!='')
          $customer->set_billing_email($args['billing_email']);

        if(isset($args['billing_phone'])&&$args['billing_phone']!='')
          $customer->set_billing_phone($args['billing_phone']);

        //Shipping Fields
        if(isset($args['billing_first_name'])&&$args['billing_first_name']!='')
          $customer->set_shipping_first_name($args['billing_first_name']);

        if(isset($args['billing_last_name'])&&$args['billing_last_name']!='')
          $customer->set_shipping_last_name($args['billing_last_name']);

        if(isset($args['billing_company'])&&$args['billing_company']!='')
          $customer->set_shipping_company($args['billing_company']);

        if(isset($args['billing_address_1'])&&$args['billing_address_1']!='')
          $customer->set_shipping_address_1($args['billing_address_1']);

        if(isset($args['billing_address_2'])&&$args['billing_address_2']!='')
          $customer->set_shipping_address_2($args['billing_address_2']);

        if(isset($args['billing_city'])&&$args['billing_city']!='')
          $customer->set_shipping_city($args['billing_city']);

        if(isset($args['billing_state'])&&$args['billing_state']!='')
          $customer->set_shipping_state($args['billing_state']);

        if(isset($args['billing_postcode'])&&$args['billing_postcode']!='')
          $customer->set_shipping_postcode($args['billing_postcode']);

        if(isset($args['billing_country'])&&$args['billing_country']!='')
          $customer->set_shipping_country($args['billing_country']);


        $customer->save();

        $order->set_customer_id($user->ID);
        $order->set_address( $customer->get_billing(), 'billing' );
        $order->set_address( $customer->get_billing(), 'shipping' );
        }
      }
      else{
        //Guest
        //Billing Fields
        if(isset($args['billing_first_name'])&&$args['billing_first_name']!='')
          $order->set_billing_first_name($args['billing_first_name']);

        if(isset($args['billing_last_name'])&&$args['billing_last_name']!='')
          $order->set_billing_last_name($args['billing_last_name']);

        if(isset($args['billing_company'])&&$args['billing_company']!='')
          $order->set_billing_company($args['billing_company']);

        if(isset($args['billing_address_1'])&&$args['billing_address_1']!='')
          $order->set_billing_address_1($args['billing_address_1']);

        if(isset($args['billing_address_2'])&&$args['billing_address_2']!='')
          $order->set_billing_address_2($args['billing_address_2']);

        if(isset($args['billing_city'])&&$args['billing_city']!='')
          $order->set_billing_city($args['billing_city']);

        if(isset($args['billing_state'])&&$args['billing_state']!='')
          $order->set_billing_state($args['billing_state']);

        if(isset($args['billing_postcode'])&&$args['billing_postcode']!='')
          $order->set_billing_postcode($args['billing_postcode']);

        if(isset($args['billing_country'])&&$args['billing_country']!='')
          $order->set_billing_country($args['billing_country']);

        if(isset($args['billing_email'])&&$args['billing_email']!='')
          $order->set_billing_email($args['billing_email']);

        if(isset($args['billing_phone'])&&$args['billing_phone']!='')
          $order->set_billing_phone($args['billing_phone']);

        //Shipping Fields
        if(isset($args['billing_first_name'])&&$args['billing_first_name']!='')
          $order->set_shipping_first_name($args['billing_first_name']);

        if(isset($args['billing_last_name'])&&$args['billing_last_name']!='')
          $order->set_shipping_last_name($args['billing_last_name']);

        if(isset($args['billing_company'])&&$args['billing_company']!='')
          $order->set_shipping_company($args['billing_company']);

        if(isset($args['billing_address_1'])&&$args['billing_address_1']!='')
          $order->set_shipping_address_1($args['billing_address_1']);

        if(isset($args['billing_address_2'])&&$args['billing_address_2']!='')
          $order->set_shipping_address_2($args['billing_address_2']);

        if(isset($args['billing_city'])&&$args['billing_city']!='')
          $order->set_shipping_city($args['billing_city']);

        if(isset($args['billing_state'])&&$args['billing_state']!='')
          $order->set_shipping_state($args['billing_state']);

        if(isset($args['billing_postcode'])&&$args['billing_postcode']!='')
          $order->set_shipping_postcode($args['billing_postcode']);

        if(isset($args['billing_country'])&&$args['billing_country']!='')
          $order->set_shipping_country($args['billing_country']);

      }
        $order->set_created_via( 'app_checkout' );

        //$items = $woocommerce->cart->get_cart();


        //Cart Start
        $woocommerce->cart->empty_cart();
        $result = array();
        $cart_items = $args['cart'];
        if($cart_items == ""){
             $cart_items = array();
         }
        $cart_items =  json_decode($args["card"]);

        foreach($cart_items as $key => $item){
          if($item->variation_id!="0")
            $addcart = $woocommerce->cart->add_to_cart($item->id,$item->quantity,$item->variation_id);
          else
            $addcart = $woocommerce->cart->add_to_cart($item->id,$item->quantity);

          if(!$addcart){
            unset($cart_items[$key]);
          }

        }

        $items = $woocommerce->cart->get_cart();
        //Cart End

        foreach($items as $key => $item){
          $order->add_product( $item['data'], $item['quantity'] );
        }

        $order->set_payment_method($args['payment_method']);
        $order->set_payment_method_title($args['payment_label']);
        //Calculate Shipping
        if($woocommerce->cart->get_shipping_total()!=0){
          foreach( WC()->session->get('shipping_for_package_0')['rates'] as $method_id => $rate ){
            $order->add_shipping($rate);
          }
          $order->calculate_shipping();
        }
        //Calculate Coupon
        //Apply Coupon code
        if(isset($args['coupon_code'])&&$args['coupon_code']!=''){
          $coupon_code = $args['coupon_code'];
          $coupon = new WC_Coupon($coupon_code);
          if($coupon->is_valid()){
            if ( !$woocommerce->cart->has_discount( $coupon_code ) ){
              $woocommerce->cart->add_discount( $coupon_code );

            }
            $order->apply_coupon($coupon);

          }

        }
        //End Apply Coupon Code
        //Add Order Note
        if(isset($args['order_comments'])&&$args['order_comments']!=''){
          $order->set_customer_note($args['order_comments'],1,true);
        }
        $cart_hash          = md5( wp_json_encode( wc_clean( $woocommerce->cart->get_cart_for_session() ) ) . $woocommerce->cart->total );
        $order->set_cart_hash( $cart_hash );
        $order->set_currency( get_woocommerce_currency() );

        $order->set_prices_include_tax( 'yes' === get_option( 'woocommerce_prices_include_tax' ) );
        $order->set_customer_ip_address( WC_Geolocation::get_ip_address() );
        $order->set_customer_user_agent( wc_get_user_agent() );


        // End Add Order Not
        $order->calculate_totals();
        if(isset($args['delivery_date']) && $args['delivery_date']!=""){
          try{
            $delivery_date = DateTime::createFromFormat('Y-m-d', $args['delivery_date']);
            $delivery_date = $delivery_date->format('d-m-Y');
          }catch(Exception $e){
            $delivery_date="";
          }
        }
        //latitude / longitude
        /*
        if(isset($args['latitude']) && $args['longitude']!=""){
          $latlong = $args['latitude'].",".$args['longitude'];
        }

        $order->add_meta_data("delivery_date",$delivery_date);
        $order->add_meta_data("delivery_time",$args['delivery_time']);
        */
        $order->add_meta_data("wpml_language",$args['language']);
        //$order->add_meta_data("_billing_latlng",$latlong);
        //Add Gift Meta

        foreach($order->get_items() as $item){
          $product = wc_get_product($item['product_id']);
          if(get_post_meta( $item['product_id'], '_gift_ids', true )){
            $item->update_meta_data( '_gift_ids', get_post_meta( $item['product_id'], '_gift_ids', true ) );
          }
        }

        $order->save();


        //Check if COD
        if($args['payment_method']=="cod"){
          $order->payment_complete();
          $order->update_status("processing");
          /*ob_start();
          TOOKAN::create_order($order->get_id());
          $tookanclassechos = ob_get_contents();
          ob_end_clean();*/
          $result = array();
          $result['payment'] = 'cod';
          $result['order_id'] = $order->get_id();
          $orderInfo = $this->getorderbyid($order->get_id());
           $result['order'] = $orderInfo;
          echo json_encode($result);

        }
        else if($args['payment_method']=="knpay_payment"){

          $knet_result = $this->knet_process_payment($order->get_id());
          if($knet_result['result']=='success'){
            $result = array();
            $result['payment'] = 'knet';
            $result['order_id'] = $order->get_id();
             $orderInfo = $this->getorderbyid($order->get_id());
              $result['order'] = $orderInfo;
            $result['url'] = $knet_result['redirect'];
          }

          echo json_encode($result);
        }
        else if($args['payment_method']=="ottumpgs_payment"){
          $mpgs_gateway = WC()->payment_gateways->get_available_payment_gateways()['ottumpgs_payment'];



          $mpgs_result = $mpgs_gateway->process_payment($order->get_id());
          if($mpgs_result['result']=='success'){
            $result = array();
            $result['payment'] = 'ottumpgs_payment';
            $result['order_id'] = $order->get_id();
             $orderInfo = $this->getorderbyid($order->get_id());
             $result['order'] = $orderInfo;
            $result['url'] = $mpgs_result['redirect'];
          }

          echo json_encode($result);
        }





   exit;
  }


 function getcheckoutdata($args){
     $woocommerce = WC();

    //global $wpdb;
    $result = array();
    $woocommerce->cart->empty_cart();
    $cart_items = $args['cart'];
    $coupon_code = $args['coupon_code'];
     if($cart_items == ""){
        $cart_items = array();
      }
     $cart_items =  json_decode($args["card"]);


    $fingerprint = $args['fingerprint'];
    if(isset($args['fingerprint'])&&$fingerprint!=''){
      $userargs = array(
        'meta_query' => array(
          array(
            "key" => 'fingerprint',
            "value" => $fingerprint
          )
        )
      );
      $wp_user_query = new WP_User_Query($userargs);
      $users = $wp_user_query->get_results();
      if(!empty($users)){

        $user =  $users[0]->data;

        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID );


        $customer = new WC_Customer($user->ID);
        $result['billing'] = $customer->get_billing();
        $result['shipping'] = $customer->get_shipping();
      }
    }
    else{
      $result['billing'] = array();
      $result['shipping'] = array();
    }
        $countries =  new WC_Countries;
        $result['countries'] = $countries->get_allowed_countries();
        $woocommerce->cart->empty_cart();
        foreach($cart_items as $key => $item){
          if($item->variation_id!="0")
            $addcart = $woocommerce->cart->add_to_cart($item->id,$item->quantity,$item->variation_id);
          else
            $addcart = $woocommerce->cart->add_to_cart($item->id,$item->quantity);

          if(!$addcart){
            unset($cart_items[$key]);
          }

        }
        $result['app_cart'] = $cart_items;
        //Apply Coupon code
        if(isset($args['coupon_code'])){
          $coupon = new WC_Coupon($coupon_code);
          if($coupon->is_valid()){
            if ( !$woocommerce->cart->has_discount( $coupon_code ) ){
              $woocommerce->cart->add_discount( $coupon_code );

            }
            $result['coupon_valid'] = true;
          }
          else{
            $result['coupon_valid'] = false;
          }

        }
        $items = $woocommerce->cart->get_cart();

        foreach($items as $key => $item){
          $p = array();
          $p['id'] = $item['data']->id;
          $p['name'] = $item['data']->get_name();
          $p['price_html'] = $item['data']->get_price_html();
          $p['image'] = get_the_post_thumbnail_url($item['data']->id,array(600,600));
          $items[$key]['details'] = $p;
        }
        $totals = $woocommerce->cart->get_totals();
        $result['totals'] = $totals;
        $result['cart_items'] = $items;
        //List all Enabled Payment options
        $gateways = WC()->payment_gateways->get_available_payment_gateways();
        $enabled_gateways = [];

        if( $gateways ) {
            foreach( $gateways as $gateway ) {

                if( $gateway->enabled == 'yes' ) {

                    $enabled_gateways[] = $gateway;

                }
            }
        }

        $result['payment_options'] = $enabled_gateways;
        $expected_delivery = get_option('expected_delivery');

        $result['expected_delivery'] = '<ul class="expected-delivery-list">
					<li>'.sprintf(__("For Air conditioners - %s", 'switch'), date('d/m/Y', strtotime('+'.$expected_delivery['ac'].' day'))).'</li>
					<li>'.sprintf(__("For Large Home Appliances - %s", 'switch'), date('d/m/Y', strtotime('+'.$expected_delivery['la'].' day'))).'</li>
					<li>'.sprintf(__("For Mobiles and other products - %s", 'switch'), date('d/m/Y', strtotime('+'.$expected_delivery['mobile'].' day'))).'</li>';
					if($expected_delivery['custom'] != null && $expected_delivery['custom'] != ''){
						$result['expected_delivery'] .= '<li>'.__($expected_delivery['custom']).'</li>';
					}
				$result['expected_delivery'] .= '</ul>';


				$result['expected_delivery'] = str_replace('For ', "", $result['expected_delivery']);
        		$result['expected_delivery'] = str_replace('من أجل', "", $result['expected_delivery']);

         $response = new WP_REST_Response( $result);
          $response->set_status(200);
         return $response;



  }

     function getcard($args){
       WC()->session = new WC_Session_Handler();
         WC()->session->init();
         WC()->customer = new WC_Customer( get_current_user_id(), true );
         WC()->cart = new WC_Cart();
         $woocommerce = WC();

            //global $wpdb;
        $woocommerce->cart->empty_cart();
        $result = array();
        $cart_items = $args['cart'];
        $coupon_code = $args['coupon_code'];
        if($cart_items == ""){
          $cart_items = array();
        }
        $cart_items =  json_decode($args["card"]);


        $index = 0;
        foreach($cart_items as $key => $item){

          if(!is_null($item->variation_id) && $item->variation_id!="0") {
            $addcart = $woocommerce->cart->add_to_cart($item->id,$item->quantity,$item->variation_id);
         } else {
          $addcart = $woocommerce->cart->add_to_cart($item->id,$item->quantity);
         }


           $product = wc_get_product($item->id);
            if ( ! $product->is_purchasable() ) {
                 $cart_items[$index ]["error"] = "not purchasable";
             }

              if ( ! $product->is_in_stock() ) {
                $cart_items[$index ]->error = "not in stock";
              }

                if ( ! $product->has_enough_stock( $quantity ) ) {
                  $cart_items[$index ]->error = "not enough stock";
                }

           $cart_items[$index ]->stock_quantity= $product->stock_quantity;



          if(!$addcart){
       //     unset($cart_items[$key]);
          }
             $index ++;

        }
        $result['app_cart'] = $cart_items;

        //Apply Coupon code
        if(isset($args['coupon_code'])){
          $coupon = new WC_Coupon($coupon_code);
          if($coupon->is_valid()){
            if ( !$woocommerce->cart->has_discount( $coupon_code ) ){
              $woocommerce->cart->add_discount( $coupon_code );

            }
            $result['coupon_valid'] = true;
          }
          else{
            $result['coupon_valid'] = false;
          }

        }
        //End Apply Coupon Code
        $items = $woocommerce->cart->get_cart();
        $sum = 0 ;
        $i = 0;

        foreach($items as $key => $item){
          $p = array();
          $p['id'] = $item['data']->id;
          $p['name'] = $item['data']->get_name();
          $p['price_html'] = $item['data']->get_price_html();
          $p['image'] = get_the_post_thumbnail_url($item['data']->id,array(600,600));
         if ( $p['image'] === false){
            $p['image'] = "";
         }
          $product = wc_get_product($item['data']->id);
           $items[$key]['stock_quantity'] = $product->stock_quantity;
          $items[$key]['details'] = $p;
         $sum += $item['line_subtotal'] ;
          $i++;



        }




        $totals = $woocommerce->cart->get_totals();
          $totals["subtotal"] = ""+$sum;
           $totals["total"] = ""+$sum;
        $result['totals'] = $totals;
        $result['cart_items'] = $items;
         $response = new WP_REST_Response( $result);
         $response->set_status(200);
         return $response;
      }



      function getcardios($args){
             WC()->session = new WC_Session_Handler();
               WC()->session->init();
               WC()->customer = new WC_Customer( get_current_user_id(), true );
               WC()->cart = new WC_Cart();
               $woocommerce = WC();

                  //global $wpdb;
              $woocommerce->cart->empty_cart();
              $result = array();
              $cart_items = $args['cart'];
              $coupon_code = $args['coupon_code'];
              if($cart_items == ""){
                $cart_items = array();
              }
              $cart_items =  json_decode($args["card"]);


              $index = 0;
              foreach($cart_items as $key => $item){

                if(!is_null($item->variation_id) && $item->variation_id!="0") {
                  $addcart = $woocommerce->cart->add_to_cart($item->id,$item->quantity,$item->variation_id);
               } else {
                $addcart = $woocommerce->cart->add_to_cart($item->id,$item->quantity);
               }


                 $product = wc_get_product($item->id);
                  if ( ! $product->is_purchasable() ) {
                       $cart_items[$index ]["error"] = "not purchasable";
                   }

                    if ( ! $product->is_in_stock() ) {
                      $cart_items[$index ]->error = "not in stock";
                    }

                      if ( ! $product->has_enough_stock( $quantity ) ) {
                        $cart_items[$index ]->error = "not enough stock";
                      }

                 $cart_items[$index ]->stock_quantity= $product->stock_quantity;



                if(!$addcart){
             //     unset($cart_items[$key]);
                }
                   $index ++;

              }
              $result['app_cart'] = $cart_items;

              //Apply Coupon code
              if(isset($args['coupon_code'])){
                $coupon = new WC_Coupon($coupon_code);
                if($coupon->is_valid()){
                  if ( !$woocommerce->cart->has_discount( $coupon_code ) ){
                    $woocommerce->cart->add_discount( $coupon_code );

                  }
                  $result['coupon_valid'] = true;
                }
                else{
                  $result['coupon_valid'] = false;
                }

              }
              //End Apply Coupon Code
              $items = $woocommerce->cart->get_cart();
              $sum = 0 ;
              $i = 0;
              $itemArray = [];

              foreach($items as $key => $item){
                $p = array();
                $p['id'] = $item['data']->id;
                $p['name'] = $item['data']->get_name();
                $p['price_html'] = $item['data']->get_price_html();
                $p['image'] = get_the_post_thumbnail_url($item['data']->id,array(600,600));
                if ( $p['image'] === false){
                $p['image'] = "";
                }
                $product = wc_get_product($item['data']->id);
                 $items[$key]['stock_quantity'] = $product->stock_quantity;
                $items[$key]['details'] = $p;
               $sum += $item['line_subtotal'] ;
                $i++;
                $itemArray[] = $items[$key];



              }





              $totals = $woocommerce->cart->get_totals();
               $totals["shipping_total"] = "0";
                $totals["subtotal"] = $sum;
                 $totals["total"] = $sum;
              $result['totals'] = $totals;
              $result['cart_items'] = $itemArray;
               $response = new WP_REST_Response( $result);
               $response->set_status(200);
               return $response;
            }





 function addtocart($args){

     WC()->session = new WC_Session_Handler();
     WC()->session->init();
     WC()->customer = new WC_Customer( get_current_user_id(), true );
     WC()->cart = new WC_Cart();
      $woocommerce = WC();
    $addcart = $woocommerce->cart->add_to_cart($args["id"]);
    if($addcart){
        $success = array('code' => 200, 'id' => $addcart);
       $response = new WP_REST_Response( $this->success);
        $response->set_status(200);
        return $response;
    }
    else{
      $response = new WP_REST_Response( $this->error);
                   $response->set_status(200);
                   return $response;
    }
  }

 function variations($id,$language){
    global $wpdb;
    $product = wc_get_product($id);


   if (method_exists($product, "get_available_variations")) {
        $variations = $product->get_available_variations();
    } else {
    return [];
    }




    foreach($variations as $var_key => $variation){
      foreach($variation['attributes'] as $att_key => $attribute){
        $attribute_advanced = array();
        $attribute_advanced['slug'] = $attribute;
        $attribute_advanced['name'] = str_replace("attribute_","",$att_key);
        //fetch attribute terms
        $term_args = array(
          'taxonomy' => $attribute_advanced['name'],
          "slug" => $attribute_advanced['slug'],
        );
        $terms = get_terms($term_args);
        if(!empty($terms)){
          foreach($terms as $term)
          $attribute_advanced['value_label'] = $term->name;
        }



        if($language=='ar'){
          $sql = $wpdb->prepare(   "SELECT t.value FROM {$wpdb->prefix}icl_string_translations t JOIN {$wpdb->prefix}icl_strings s ON t.string_id = s.id WHERE s.context='WordPress' and s.name=%s AND t.language=%s",
      'taxonomy singular name: ' . wc_attribute_label($attribute_advanced['name']),
      $language);



          $attribute_advanced['label'] = $wpdb->get_var($sql);
        }
        else{
          $attribute_advanced['label'] = wc_attribute_label($attribute_advanced['name']);
        }



        $variations[$var_key]['attribute_advance'][] = $attribute_advanced;



      }
    }
    return $variations;

  }




    function removewishlist($args){
        global $wpdb;
        $fingerprint = $args['fingerprint'];
        $id = $args['id'];
        $result = array();
        if(isset($args['fingerprint'])&&$fingerprint!=''){
          $userargs = array(
            'meta_query' => array(
              array(
                "key" => 'fingerprint',
                "value" => $fingerprint
              )
            )
          );
          $wp_user_query = new WP_User_Query($userargs);
          $users = $wp_user_query->get_results();
          if(!empty($users)){
            $user_id = $users[0]->data->ID;
          }
          else{
                $response = new WP_REST_Response($this->error);
                                 $response->set_status(400);
                                return $response;
          }
        }
        else{
               $response = new WP_REST_Response($this->error);
                                $response->set_status(400);
                               return $response;
        }
        $wishlist = $wpdb->query("delete from wp_wishlist where uid='$user_id' and wishlist='$id'");
        if($wishlist){
             $response = new WP_REST_Response( $this->success);
              $response->set_status(200);
              return $response;
        }
        else{
             $response = new WP_REST_Response($this->error);
                      $response->set_status(400);
                     return $response;
        }

      }



    function getwishlist(){
        global $wpdb;
        $fingerprint = $_GET['fingerprint'];

        $result = array( $fingerprint);
        if(isset($_GET['fingerprint'])&&$fingerprint!=''){
          $userargs = array(
            'meta_query' => array(
              array(
                "key" => 'fingerprint',
                "value" => $fingerprint
              )
            )
          );
          $wp_user_query = new WP_User_Query($userargs);
          $users = $wp_user_query->get_results();

          if(!empty($users)){
            $user_id = $users[0]->data->ID;

          }
          else{
          $response = new WP_REST_Response($this->error);
          $response->set_status(400);
          return $response;

          }
        }
        else{
           $response = new WP_REST_Response($this->error);
            $response->set_status(400);
           return $response;
        }
        $wishlist = $wpdb->get_results("select * from wp_wishlist where uid='$user_id'");
        $items = array();
        if($wpdb->num_rows>0){
          foreach($wishlist as $item){
            $product_id =  $item->wishlist;
            $variation_id =  $item->variation_id;
            $product = wc_get_product($product_id);
            if($product){
              if($product->status=="publish"){
                $wishlist_item = array();
                $wishlist_item['id'] = $product_id;
                $wishlist_item['name'] = $product->get_name();
            $wishlist_item['variation_id'] = $variation_id;




               $wishlist_item['price'] =   $product->get_price_html() ;


                $wishlist_item['image'] = get_the_post_thumbnail_url($product_id,array(600,600));
               $wishlist_item["stock_status"] = $product->stock_status;
                $wishlist_item["stock_quantity"] = $product->stock_quantity;
                $items[] = $wishlist_item;
              }
            }
          }
        }


          $response = new WP_REST_Response( $items);
            $response->set_status(200);
            return $response;
      }



 function addwishlist($args){

    global $wpdb;
     $product_id =  intval($args['product_id']);
      $variation_id =  intval($args['variation_id']);
    $fingerprint = $args['fingerprint'];
    $id = $args['id'];
    if(isset($args['fingerprint'])&&$fingerprint!=''){
      $userargs = array(
        'meta_query' => array(
          array(
            "key" => 'fingerprint',
            "value" => $fingerprint
          )
        )
      );

      $wp_user_query = new WP_User_Query($userargs);
      $users = $wp_user_query->get_results();

      if(!empty($users)){
        $user_id = $users[0]->data->ID;
      }

      else{
        $response = new WP_REST_Response( "empty user");
         $response->set_status(400);
         return $response;
      }
    }
    else{
    $response = new WP_REST_Response( "finger print not defined");
    $response->set_status(400);
    return $response;
    }

    $wishlist = $wpdb->get_results("select * from wp_wishlist where uid='$user_id' and wishlist='$product_id'");
    if($wpdb->num_rows>0){
      $response = new WP_REST_Response( "wishlist exist ");
       $response->set_status(400);
       return $response;
    }


    $product = wc_get_product($product_id);



    $add_variation_id_column = "ALTER TABLE wp_wishlist ADD `variation_id` VARCHAR(255) NULL DEFAULT NULL;";
    $wpdb->query( $add_variation_id_column );


    if($product){
      if($product->status=="publish"){
        $wishlist = $wpdb->insert(
        	'wp_wishlist',
        	array(
        		'uid' => $user_id,
        		'wishlist' => $product_id,
        		'variation_id'=> $variation_id
        	),
        	array(
        		'%d',
        		'%d',
        		'%s'
        	)
        );
        if($wishlist){
           $response = new WP_REST_Response( $this->success);
           $response->set_status(200);
           return $response;
        }
        else{
         $response = new WP_REST_Response($this->error);
         $response->set_status(400);
         return $response;
        }
      }else{
       $error = array('code' => 400, 'message' => 'not published');
            $response = new WP_REST_Response($error);
         $response->set_status(400);
         return $response;
      }
    }
    else{
      $error = array('code' => 400, 'message' => "product doesn't exist");
      $response = new WP_REST_Response($error);
       $response->set_status(400);
       return $response;
    }
  }

 function addreview($args){
    try{
      $id = $args['id'];
      $fingerprint = $args['fingerprint'];
      $rating = $args['rating'];
      $review = $args['review'];
      $name = $args['name'];
      $email = $args['email'];
      if(isset($args['fingerprint'])&&$fingerprint!=''){
        $userargs = array(
          'meta_query' => array(
            array(
              "key" => 'fingerprint',
              "value" => $fingerprint
            )
          )
        );
        $wp_user_query = new WP_User_Query($userargs);
        $users = $wp_user_query->get_results();
        if(!empty($users)){
          $name = $users[0]->data->display_name;
          $email = $users[0]->data->user_email;
        }
      }
      $results = $this->woocommerce->post('products/'.$id.'/reviews',array(
         'lang' => $_GET['language'],
         'review' => $review,
         'rating' => $rating,
         'name' => $name,
         'email' => $email
       ));
        $response = new WP_REST_Response( $results);
        $response->set_status(200);
        return $response;
     }
     catch (Exception $e) {
       $error = json_decode($e->getResponse()->getBody());
       $response = new WP_REST_Response( $error);
       $response->set_status(400);
       return $response;
     }

  }



    function setaddresses($args){

       $fingerprint = $args['fingerprint'];

       if(isset($args['fingerprint'])&&$fingerprint!=''){
         $userargs = array(
           'meta_query' => array(
             array(
               "key" => 'fingerprint',
               "value" => $fingerprint
             )
           )
         );
         $wp_user_query = new WP_User_Query($userargs);
         $users = $wp_user_query->get_results();
         if(!empty($users)){
           $user =  $users[0]->data;
           $customer = new WC_Customer($user->ID);
            if(isset($args['name_title'])&&$args['name_title']!='')
           $customer->update_meta_data("name_title",  $args['name_title'], -1);

           //Billing Fields
           if(isset($args['billing_first_name'])&&$args['billing_first_name']!='')
             $customer->set_billing_first_name($args['billing_first_name']);

           if(isset($args['billing_last_name'])&&$args['billing_last_name']!='')
             $customer->set_billing_last_name($args['billing_last_name']);

           if(isset($args['billing_company'])&&$args['billing_company']!='')
             $customer->set_billing_company($args['billing_company']);

           if(isset($args['billing_address_1'])&&$args['billing_address_1']!='')
             $customer->set_billing_address_1($args['billing_address_1']);

           if(isset($args['billing_address_2'])&&$args['billing_address_2']!='')
             $customer->set_billing_address_2($args['billing_address_2']);

           if(isset($args['billing_city'])&&$args['billing_city']!='')
             $customer->set_billing_city($args['billing_city']);

           if(isset($args['billing_state'])&&$args['billing_state']!='')
             $customer->set_billing_state($args['billing_state']);

           if(isset($args['billing_postcode'])&&$args['billing_postcode']!='')
             $customer->set_billing_postcode($args['billing_postcode']);

           if(isset($args['billing_country'])&&$args['billing_country']!='')
             $customer->set_billing_country($args['billing_country']);

           if(isset($args['billing_email'])&&$args['billing_email']!='')
             $customer->set_billing_email($args['billing_email']);

           if(isset($args['billing_phone'])&&$args['billing_phone']!='')
             $customer->set_billing_phone($args['billing_phone']);

           //Shipping Fields
           if(isset($args['shipping_first_name'])&&$args['shipping_first_name']!='')
             $customer->set_shipping_first_name($args['shipping_first_name']);

           if(isset($args['shipping_last_name'])&&$args['shipping_last_name']!='')
             $customer->set_shipping_last_name($args['shipping_last_name']);

           if(isset($args['shipping_company'])&&$args['shipping_company']!='')
             $customer->set_shipping_company($args['shipping_company']);

           if(isset($args['shipping_address_1'])&&$args['shipping_address_1']!='')
             $customer->set_shipping_address_1($args['shipping_address_1']);

           if(isset($args['shipping_address_2'])&&$args['shipping_address_2']!='')
             $customer->set_shipping_address_2($args['shipping_address_2']);

           if(isset($args['shipping_city'])&&$args['shipping_city']!='')
             $customer->set_shipping_city($args['shipping_city']);

           if(isset($args['shipping_state'])&&$args['shipping_state']!='')
             $customer->set_shipping_state($args['shipping_state']);

           if(isset($args['shipping_postcode'])&&$args['shipping_postcode']!='')
             $customer->set_shipping_postcode($args['shipping_postcode']);

           if(isset($args['shipping_country'])&&$args['shipping_country']!='')
             $customer->set_shipping_country($args['shipping_country']);

            $customer->save();
            $response = new WP_REST_Response( $this->success);
            $response->set_status(200);
            return $response;
         }
         else{
            $response = new WP_REST_Response($this->error);
            $response->set_status(400);
            return $response;

         }
       }
       else{
           $response = new WP_REST_Response($this->error);
           $response->set_status(400);
          return $response;
       }

     }


  function getaddresses(){
    $result = array();
    $fingerprint = $_GET['fingerprint'];
    if(isset($_GET['fingerprint'])&&$fingerprint!=''){
      $userargs = array(
        'meta_query' => array(
          array(
            "key" => 'fingerprint',
            "value" => $fingerprint
          )
        )
      );
      $wp_user_query = new WP_User_Query($userargs);
      $users = $wp_user_query->get_results();
      if(!empty($users)){
        $user =  $users[0]->data;
        $customer = new WC_Customer($user->ID);
        $result['billing'] = $customer->get_billing();
        $result['shipping'] = $customer->get_shipping();
       $data =  $customer->get_meta_data();
       for ($i = 0; $i < count($data); $i++) {
         $item = $data[$i]->get_data();
         if ($item["key"] == "name_title"){
          $result['name_title'] =  $item["value"];
         }
       }

        $countries =  new WC_Countries;
        $result['countries'] = $countries->get_allowed_countries();
         $response = new WP_REST_Response( $result);
         $response->set_status(200);
         return $response;
      }
      else{
        $response = new WP_REST_Response($this->error);
        $response->set_status(400);
         return $response;

      }
    }
    else{
      $response = new WP_REST_Response($this->error);
      $response->set_status(400);
       return $response;
    }

  }


function updateuser(){
    $result = array();
    $fingerprint = $args['fingerprint'];
    if(isset($args['fingerprint'])&&$fingerprint!=''){
      $userargs = array(
        'meta_query' => array(
          array(
            "key" => 'fingerprint',
            "value" => $fingerprint
          )
        )
      );
      $wp_user_query = new WP_User_Query($userargs);
      $users = $wp_user_query->get_results();
      if(!empty($users)){
        $user =  $users[0]->data;
        if(isset($args['first_name'])&&$args['first_name']!=""){
          $user_id = wp_update_user( array( 'ID' => $user->ID, 'first_name' => $args['first_name'] ) );
          if ( is_wp_error( $user_id ) ) {
          	    $response = new WP_REST_Response($this->error);
                      $response->set_status(400);
                      return $response;
          }
        }
        if(isset($args['last_name'])&&$args['last_name']!=""){
          $user_id = wp_update_user( array( 'ID' => $user->ID, 'last_name' => $args['last_name'] ) );
          if ( is_wp_error( $user_id ) ) {
          	 $response = new WP_REST_Response("error_last_name");
             $response->set_status(400);
             return $response;
          }
        }
        if(isset($args['display_name'])&&$args['display_name']!=""){
          $user_id = wp_update_user( array( 'ID' => $user->ID, 'display_name' => $args['display_name'] ) );
          if ( is_wp_error( $user_id ) ) {
          	 $response = new WP_REST_Response("error_display_name");
              $response->set_status(400);
               return $response;
          }
        }
        if(isset($args['user_email'])&&$args['user_email']!=""){
          $user_id = wp_update_user( array( 'ID' => $user->ID, 'user_email' => $args['user_email'] ) );
          if ( is_wp_error( $user_id ) ) {
          	 $response = new WP_REST_Response("error_mail");
             $response->set_status(400);
             return $response;
          }
        }
        if(isset($args['user_pass'])&&$args['user_pass']!=""&&isset($args['current_password'])&&$args['current_password']!=""){
          if ( $user && wp_check_password( $args['current_password'], $user->user_pass, $user->ID) )
             wp_set_password( $args['user_pass'], $user->ID );
          else{
           $response = new WP_REST_Response("error_password");
           $response->set_status(400);
           return $response;
          }
        }

        $result['user_id'] = $user->ID;
        $result['display_name'] = $user->display_name;
        $result['user_email'] = $user->user_email;
        $result['first_name'] = $user->first_name;
        $result['last_name'] = $users->last_name;
          $response = new WP_REST_Response($result);
          $response->set_status(200);
          return $response;
      }
      else{
          $response = new WP_REST_Response($this->error);
          $response->set_status(400);
          return $response;

      }
    }
    else{
         $response = new WP_REST_Response($this->error);
         $response->set_status(400);
         return $response;
    }

  }

    function getuser(){
        $result = array();
        $fingerprint = $_GET['fingerprint'];
        if(isset($_GET['fingerprint'])&&$fingerprint!=''){
          $userargs = array(
            'meta_query' => array(
              array(
                "key" => 'fingerprint',
                "value" => $fingerprint
              )
            )
          );
          $wp_user_query = new WP_User_Query($userargs);
          $users = $wp_user_query->get_results();
          if(!empty($users)){
            $user =  $users[0]->data;
            $result['user_id'] = $user->ID;
            $result['display_name'] = $user->display_name;
            $result['user_email'] = $user->user_email;
            $result['first_name'] = $users[0]->first_name;
            $result['last_name'] = $users[0]->last_name;
            $response = new WP_REST_Response($result);
            $response->set_status(200);
            return $response;
          }
          else{

            $response = new WP_REST_Response($this->error);
            $response->set_status(200);
            return $response;

          }
        }
        else{
           $response = new WP_REST_Response($this->error);
            $response->set_status(200);
           return $response;
        }

      }


 function forgotpassword($args){
            $args['user_login'] = $args['user_login'];
			$success = WC_Shortcode_My_Account::retrieve_password();

			// If successful, redirect to my account with query arg set.
			if ( $success ) {
        $success = array(
          'code' => 'success',
          'message' => __("Confirmation Email has been sent succussfully."),
          'data' => array(
            'status' => 200
          )
        );
         $response = new WP_REST_Response($success);
         $response->set_status(200);
         return $response;

			}
      else{
        $error = array(
          'code' => 'wrong',
          'message' => __("User Email is wrong or does not exist."),
          'data' => array(
            'status' => 400
          )
        );
         $response = new WP_REST_Response($error);
          $response->set_status(400);
         return $response;
      }

  }



      function getorders(){
        $fingerprint = $_GET['fingerprint'];
        if(isset($_GET['fingerprint'])&&$fingerprint!=''){
          $userargs = array(
            'lang' => $_GET['language'],
            'meta_query' => array(
              array(
                "key" => 'fingerprint',
                "value" => $fingerprint
              )
            )
          );
          $wp_user_query = new WP_User_Query($userargs);
          $users = $wp_user_query->get_results();
          if(!empty($users)){
            $user =  $users[0]->data;
            try{
              $results = $this->woocommerce->get('orders',array(
                 'per_page' => "100",
                 'customer' => $user->ID,
               ));
               foreach($results as $result){
                 $result->date_formatted = date('Y-M-d',strtotime($result->date_created));
                 $result->order_status_label = wc_get_order_status_name($result->status);
                   unset($result->meta_data);
               }

            $response = new WP_REST_Response($results);
               $response->set_status(200);
               return $response;
            }
            catch (HttpClientException $e) {
              $error = json_decode($e->getResponse()->getBody());
               $response = new WP_REST_Response($e->getResponse()->getBody());
               $response->set_status(400);
               return $response;
            }
          }
          else{
             $response = new WP_REST_Response($this->error);
             $response->set_status(400);
             return $response;
          }
        }
        else{
           $response = new WP_REST_Response($this->error);
           $response->set_status(400);
           return $response;
        }

      }



    function register($args){

      try{
          if(isset($args['token'])&&$args['token']!=''){
            $token = $args['token'];
          }
          else{
            $token = '';
          }
          $fingerprint = $this->generateFingerprint();



          $results = $this->woocommerce->post('customers',array(
             'email' => $args['email'],
             'password' => $args['password'],
             'first_name' => $args['first_name'],
              'last_name' => $args['last_name'],
             'meta_data' => array(
               array(
                 'key' => 'fingerprint',
                 'value' => $fingerprint
               ),
               array(
                 'key' => 'token',
                 'value' => $token
               ),
             )
           ));


            $response = new WP_REST_Response($results);
           $response->set_status(200);
           return $response;
        }
        catch (Exception $e) {
           $error = json_decode($e->getResponse()->getBody(true));
           $response = new WP_REST_Response( $error);
           $response->set_status(400);
          return $response;
        }

      }




function generateFingerprint(){
    $repeated = true;
    $fingerprint = "";
    do{
      $fingerprint = md5(uniqid());
      $userargs = array(
        'meta_query' => array(
          array(
            'key' => 'fingerprint',
            'value' => $fingerprint
          )
        )
      );
      $count = count(get_users($userargs));
      if($count == 0)
        $repeated = false;
    }while($repeated);
    return $fingerprint;
  }

public function login($args){

    $userdata = get_user_by('email', $args['email']);
    if($userdata){
      $result = wp_check_password($args['password'], $userdata->user_pass);
      if($result){

        $fingerprint = get_user_meta($userdata->ID, 'fingerprint', true);

        if(!$fingerprint){

          $fingerprint = $this->generateFingerprint();
          add_user_meta($userdata->ID,'fingerprint',$fingerprint);

        }
       // wp_set_current_user( $userdata->ID );
       // wp_set_auth_cookie( $userdata->ID );


         $results = $this->woocommerce->get('customers/'.$userdata->ID);
         $results->fingerprint = $fingerprint;

        $response = new WP_REST_Response($results);
        $response->set_status(200);

        return $response;
      }else{
        $error = array(
          'code' => 'wrong',
          'message' => __("User Email or Password is wrong."),
          'data' => array(
            'status' => 400
          )
        );
           $response = new WP_REST_Response($error);
               $response->set_status(400);

               return $response;
      }
    }else{
      $error = array(
        'code' => 'wrong',
        'message' => __("User Email or Password is wrong."),
        'data' => array(
          'status' => 400
        )
      );
        $response = new WP_REST_Response($error);
        $response->set_status(400);
        return $response;

    }

  }














public function get_menu($request) {
    $menu = wp_get_nav_menu_items("Menu-English-Mobile-Application");
    $language = $request->get_param('language');
    if ($language == "ar"){
    $menu = wp_get_nav_menu_items("Menu-Arabic-Mobile-Application");
    }





     $mainNav = $this->buildTree($menu);
     $menuItems = array();



       foreach ( $mainNav as $item  ) {
        $menuItem = array();
        $menuItem['title'] = $item->title;
          if ($menuItem['title'] != "all categories"){


              $img_data = get_post_meta( $item->ID);


              $content = $img_data['_ubermenu_settings'][0];
              $response = unserialize( $content );

             $image_id = $response['item_image'];


                $thumbnail_url  = get_post_meta(  $item->ID, '_thumbnail_id',  true );

              if (strlen($thumbnail_url)>0){
                 $menuItem['image'] =   wp_get_attachment_image_src($thumbnail_url, 'large' )[0];
              }



        $childrens = [];
        foreach ( $item->children as $children  ) {
            $id = $children->db_id;
             $element = array();
             $img_data = get_post_meta( $id);
             $content = $img_data['_ubermenu_settings'][0];
             $response = unserialize( $content );

             $image_id = $response['item_image'];

            $element['type'] = $children->type_label;

            if ( $language == "ar"){
             $element['type'] = "Page";
            }

             $element['id'] =  $children->object_id;


  $thumbnail_url  = get_post_meta(    $id, '_thumbnail_id',  true );

          //  $element['image'] =  wp_get_attachment_image_src($image_id, 'large' )[0];
             if (strlen($thumbnail_url)>0){
                                $element['image'] =   wp_get_attachment_image_src($thumbnail_url, 'large' )[0];
                }


            $element['title'] = $children->title;
              $element['url'] = $children->url;
             $element['slug'] = basename($children->url);


   $pos = stripos(  $element['url'] , "collection");
   $pos1 = stripos(  $element['url'] , "category");
   $pos2 = stripos(  $element['url'] , "page");
       if ($pos !== false) {
         $element['type'] = "Collection";
       }

       if ($pos1 !== false) {
                $element['type'] = "Category";
              }

              if ($pos2 !== false) {
                       $element['type'] = "Page";
                        $element['slug'] = $children->url;
                     }



if ( $element['type'] == "Page"){
  $element['slug'] = $children->url;
}


 $element['slug']  =  preg_replace('{/$}', '', $element['slug'] );

             if ($children->children){
             $subchildrens = array();
              foreach ( $children->children as $subchildren  ) {
                         $id = $subchildren->db_id;
                          $data = array();
                          $img_data = get_post_meta( $id);
                          $content = $img_data['_ubermenu_settings'][0];
                          $response = unserialize( $content );
                          $image_id = $response['item_image'];


  $thumbnail_url  = get_post_meta(    $id, '_thumbnail_id',  true );
                         $data['image'] =  wp_get_attachment_image_src($thumbnail_url, 'large' )[0];
                         $data['title'] = $subchildren->title;
 $element['id'] =  $id;
                          $data['url'] = $subchildren->url;
                           $data['slug'] = $subchildren->url;

 $data['slug']  =  preg_replace('{/$}', '', $data['slug'] );
                           $subchildrens [] = $data;
              }
                 $element['subchildrens']   =    $subchildrens;
             }
          $childrens[] =  $element;
         }

        $menuItem['childrens'] =    $childrens;
        $menuItems[] =  $menuItem;
       }
}

    $data = array();





    $response = new WP_REST_Response($menuItems);
    $response->set_status(200);

    return $response;


      }




public function updateproduct($request) {
$product_id = $request->get_param('id');
$file = $request->get_param('imagepath');
$filename = $request->get_param('filename');
$language = $request->get_param('language');
$product = wc_get_product($product_id);


if (!$product){
      $response = new WP_REST_Response("error");
      $response->set_status(200);
      return $response;
}
$product_id = apply_filters( 'wpml_object_id', $product->get_id(), 'product', false,  $language);


       $meta = get_post_meta($product_id,"_product_image_gallery");
       $ids = explode(",", $meta[0]);
      foreach ($ids as $id) {
        $attachment_meta =   wp_get_attachment_metadata($id);
              update_attached_file( $id, $path );
             wp_update_attachment_metadata( $id,  $attachment_meta );
       }

      $response = new WP_REST_Response("ok");
      $response->set_status(200);
      return $response;
}

      function buildTree(array &$flatNav, $parentId = 0) {
          $branch = [];

          foreach ($flatNav as &$navItem) {
            if($navItem->menu_item_parent == $parentId) {
              $children = $this->buildTree($flatNav, $navItem->ID);
              if($children) {
                $navItem->children = $children;
              }

              $branch[$navItem->menu_order] = $navItem;
              unset($navItem);
            }
          }

          return $branch;
      }



 function compare($args){
      $woocommerce = WC();
    global $wpdb;

    $result = array();
    $ids = $args['products'];

    $atts = $wpdb->get_results("SELECT post_id,meta_key, meta_value FROM wp_yQt5PKU_postmeta WHERE post_id in ($ids) and meta_key='_product_attributes'");
    foreach ($atts as $a) {

          if ($a->meta_value !== "a:0:{}") {
              $temp = unserialize($a->meta_value);
              foreach ($temp as $t) {
                  if (!in_array($t['name'], $comp))
                      $comp[] = $t['name'];
              }
          }
    }

    $compare_array = array();
    $compare_list = explode(",", $ids);
    foreach ($compare_list as $p){
      $compare_object = array();
      $product = wc_get_product($p);
      $compare_object['id'] = $p;
      $compare_object['image'] = get_the_post_thumbnail_url($p,array(600,600));
      $compare_object['name'] = $product->get_title();


      if (strlen($product->regular_price)>0 && $product->regular_price !== $product->price){
         $compare_object['price'] =  "<div><del>KWD ".number_format( $product->regular_price, 3, '.', '')."</del> KWD".number_format( $product->price, 3, '.', '')." </div>"; //$product->get_price_html() ;
      } else {
           $compare_object['price'] =  "<div> KWD".number_format( $product->price, 3, '.', '')." </div>"; //$product->get_price_html() ;
      }

      foreach ($comp as $c){
        $attribute_label = wc_attribute_label($c);
        if($_GET['language']=='ar'){
          $sql = $wpdb->prepare(   "SELECT t.value FROM {$wpdb->prefix}icl_string_translations t JOIN {$wpdb->prefix}icl_strings s ON t.string_id = s.id WHERE s.context='WordPress' and s.name=%s AND t.language=%s",
      'taxonomy singular name: ' . $attribute_label,
      $_GET['language']);
          $attribute_label = $wpdb->get_var($sql);
        }

        $s[$attribute_label] = $product->get_attribute($c)!=""?$product->get_attribute($c):"-";
      }
      $compare_array[] = $compare_object;
    }

    $response = new WP_REST_Response($compare_array);
    $response->set_status(200);

    return $response;
  }





public function get_category_detail($request) {
        $slug_param = $request->get_param('slug');
        $language_param = $request->get_param('language');
          $language = $request->get_param('language');
        $slug = str_replace("page-","",$slug_param);
        $term = get_term_by('slug', $slug, 'product_cat');
       $data = array();



       if( strlen($slug_param) == 0 || $term == false){
         $data  = array();
         $data["attributes"] = [];
         $data["products"] = [];

            $response = new WP_REST_Response($data);
           $response->set_status(200);

           return $response;
       }




  $min_price = $request->get_param('min_price');
   $max_price = $request->get_param('max_price');
   $page = $request->get_param('page');

   $args = array(
        'post_type'      => 'product',
              'paged'          => $page,
                 'posts_per_page' => 15,
               'post_status' => 'publish',
   );


 $args["meta_query1"][] = array(
        'key' => '_stock_status',
        'value' => 'instock',
        'compare' => '=',
    );


    if (isset($min_price) && isset($max_price)) {

              $args['meta_query'][] = array(
                  'relation' => 'OR',
                  array(
                      'key' => '_price',
                      'value' => array($min_price, $max_price),
                      'type' => 'DECIMAL',
                      'compare' => 'BETWEEN',

                  ),
                  array(
                      'key' => '_price',
                      'value' => $min_price,
                      'compare' => '=',
                  ),
                  array(
                      'key' => '_price',
                      'value' => $max_price,
                      'compare' => '='
                  ),

              );
          }



            /* Calculate Attributes*/
              $atts_tax = wc_get_attribute_taxonomies();
              foreach($atts_tax as $t){
                  $attribute = 'pa_' . $t->attribute_name;

                  if (null !==($request->get_param($attribute))) {

                      $args['tax_query'][] = array(
                          'relation' => 'AND',
                          array(
                              'taxonomy' => 'pa_'.$t->attribute_name,
                              'field' => 'slug',
                              'terms' => explode(',', $request->get_param('pa_'.$t->attribute_name)),
                              'include_children' => true
                          )
                      );
                  }
              }


                 if(isset($term->name)){
                    $args['tax_query'][] = array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'term_id',
                            'terms' => explode(',', $term->term_id),
                            'include_children' => true
                        )
                    );
                  }

                   if(isset($_GET['product_cat'])){
                        $args['tax_query'][] = array(
                            'relation' => 'AND',
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'slug',
                                'terms' => explode(',', $_GET['product_cat']),
                                'include_children' => true
                            )
                        );
                      }

                $order = $request->get_param('sort');

                  if (isset($order)) {
                      if ($request->get_param('sort') == 'price') {
                        $args['orderby'] = 'meta_value_num';
                        $args['meta_key'] = '_price';
                        $args['order'] = 'ASC';
                      }
                      else if ($request->get_param('sort') == 'price-desc'){
                        $args['orderby'] = 'meta_value_num';
                        $args['meta_key'] = '_price';
                        $args['order'] = 'DESC';
                      }
                      else {
                          $args['orderby'] = $request->get_param('sort');
                      }
                  } else {
                      $args['orderby'] = 'date';
                      $args['order'] = 'DESC';
                  }




  $pids = [];
 $language = $request->get_param('language');

  $loop = new WP_Query( $args );

    $products = array();


    foreach($loop->posts as $post){

      $product = wc_get_product($post->ID);

       $product_id = apply_filters( 'wpml_object_id', $product->get_id(), 'product', false,  $language);
       $product = wc_get_product($product_id );


     $item = array();
     $item["id"] = $product->id;
      $pids[] = $product->id;
     $item["name"] = htmlspecialchars_decode($product->name);;
     $item["date_modified"] = $product->date_modified;
     $item["date_created"] = $product->date_created;
     $item["short_description"] = $product->short_description;
     if (strlen($product->sale_price) == 0 ){
     $product->sale_price = $product->price;
     }
       $item["price"] = number_format( $product->price, 3, '.', '');
      $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
 $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                 $item["stock_status"] = $product->stock_status;
          $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
         $item["express"]  = 0;
                  $item["best_seller"]  = 0;
                  $item["flash_deal"]  =0;


                  $collections =  wp_get_post_terms($product->id,"collection");

                     for ($j = 0; $j < count($collections); $j++) {
                       if  ($collections[$j]->name == "Express"){
                        $item["express"] = 1;
                       }
                         if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                      $item["best_seller"] = 1;
                        }
                       if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                        $item["flash_deal"] = 1;
                       }
                     }





     	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

       	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

       	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





              $item["warranty"] = $warranty;
              $item["flash_deal_date"] = $date;

     $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
     if(  $item["id"] ){
       $products[] = $item;
     }
 }

     $data['products'] = $products;



       // Get Attributes
          $tax_names = $tax_display = $t_slug_name = [];
          $atts_tax = wc_get_attribute_taxonomies();



          foreach ($atts_tax as $at) {

              $at->attribute_name = 'pa_' . $at->attribute_name ;
              $at->attribute_label = __($at->attribute_label,'switch');
              if ($at->attribute_public) {
                  $tax_names[] =  $at->attribute_name;
                  //$t_slug_name[$at->attribute_name] = wc_attribute_label(  $at->attribute_name, '' );
              }
          }


          $category_array = new stdClass();
          $category_array->attribute_name = "product_cat";
          $category_array->attribute_label = __('Category', 'switch');
          $atts_tax[]= $category_array;


          $tax_names[] = 'product_cat';


          $terms = wp_get_object_terms($pids, $tax_names, array('fields' => 'all_with_object_id'));

          $taxonomies = [];
          foreach ($terms as $index => $term) {

              foreach($atts_tax as $key => $z){

                if($z->attribute_name == $term->taxonomy){
                  if(sizeof($z->values)==0){
                    $z->values[] = $term;
                  }
                  $inloop = false;
                  foreach($z->values as $value){
                    if ($value->slug == $term->slug) {
                      $inloop = true;
                    }
                  }
                  if(!$inloop){
                    $z->values[] = $term;
                  }
                }
              }
          }
          // Remove empty values
          foreach($atts_tax as $key => $z){
            if($z->values==null){
              unset($atts_tax[$key]);
            }

          }


    $data['attributes'] = $atts_tax;


    if (count($atts_tax) ==1){
        $attributes = array( 'id:1' =>  $atts_tax[0]);
                $data['attributes'] =  $attributes;
    }

    $response = new WP_REST_Response($data);
    $response->set_status(200);
      $response->header( 'Content-Type', "application/json; charset=UTF-8" );

    return $response;

      }





public function get_collection_detail($request) {
        $language_param = $request->get_param('language');
          $language = $request->get_param('language');
        $data = array();




  $min_price = $request->get_param('min_price');
   $max_price = $request->get_param('max_price');
  $page = $request->get_param('page');

   $args = array(
        'post_type'      => 'product',
                'paged'          => $page,
                'posts_per_page' => 10,
               'post_status' => 'publish',
   );

 $args["meta_query1"][] = array(
        'key' => '_stock_status',
        'value' => 'instock',
        'compare' => '=',
    );

 if(isset($_GET['slug'])){
      if($_GET['slug']!="undefined"&&$_GET['slug']!=""){
        $args['tax_query'][] = array(
            'taxonomy' => 'collection',
                 'terms' => array($_GET['slug']),
                 'field' => 'slug'
        );
      }

    }

    if (isset($min_price) && isset($max_price)) {

              $args['meta_query'][] = array(
                  'relation' => 'OR',
                  array(
                      'key' => '_price',
                      'value' => array($min_price, $max_price),
                      'type' => 'DECIMAL',
                      'compare' => 'BETWEEN',

                  ),
                  array(
                      'key' => '_price',
                      'value' => $min_price,
                      'compare' => '=',
                  ),
                  array(
                      'key' => '_price',
                      'value' => $max_price,
                      'compare' => '='
                  ),

              );
          }

            /* Calculate Attributes*/
              $atts_tax = wc_get_attribute_taxonomies();
              foreach($atts_tax as $t){
                   $attribute = 'pa_' . $t->attribute_name;
                 if (null !==($request->get_param($attribute))) {

                      $args['tax_query'][] = array(
                          'relation' => 'AND',
                          array(
                              'taxonomy' => 'pa_'.$t->attribute_name,
                              'field' => 'slug',
                              'terms' => explode(',', $request->get_param('pa_'.$t->attribute_name)),
                              'include_children' => true
                          )
                      );
                  }
              }


                 if(isset($term->name)){
                    $args['tax_query'][] = array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'term_id',
                            'terms' => explode(',', $term->term_id),
                            'include_children' => true
                        )
                    );
                  }

                $order = $request->get_param('sort');

                  if (isset($order)) {
                      if ($request->get_param('sort') == 'price') {
                        $args['orderby'] = 'meta_value_num';
                        $args['meta_key'] = '_price';
                        $args['order'] = 'ASC';
                      }
                      else if ($request->get_param('sort') == 'price-desc'){
                        $args['orderby'] = 'meta_value_num';
                        $args['meta_key'] = '_price';
                        $args['order'] = 'DESC';
                      }
                      else {
                          $args['orderby'] = $request->get_param('sort');
                      }
                  } else {
                      $args['orderby'] = 'date';
                      $args['order'] = 'DESC';
                  }


  $pids = [];
 $language = $request->get_param('language');


  $loop = new WP_Query( $args );

    $products = array();

    foreach($loop->posts as $post){

      $product = wc_get_product($post->ID);
       $product_id = apply_filters( 'wpml_object_id', $product->get_id(), 'product', false,  $language);
       $product = wc_get_product($product_id );
     $item = array();
     $item["id"] = $product->id;
      $pids[] = $product->id;
     $item["name"] = htmlspecialchars_decode($product->name);;
     $item["date_modified"] = $product->date_modified;
     $item["date_created"] = $product->date_created;
     $item["short_description"] = $product->short_description;
       if (strlen($product->sale_price) == 0 ){
          $product->sale_price = $product->price;
          }
       $item["price"] = number_format( $product->price, 3, '.', '');
      $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
 $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                 $item["stock_status"] = $product->stock_status;
          $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
         $item["express"]  = 0;
                  $item["best_seller"]  = 0;
                  $item["flash_deal"]  =0;


                  $collections =  wp_get_post_terms($product->id,"collection");

                     for ($j = 0; $j < count($collections); $j++) {
                       if  ($collections[$j]->name == "Express"){
                        $item["express"] = 1;
                       }
                         if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                      $item["best_seller"] = 1;
                        }
                       if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                        $item["flash_deal"] = 1;
                       }
                     }





     	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

       	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

       	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





              $item["warranty"] = $warranty;
              $item["flash_deal_date"] = $date;

     $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
     if(  $item["id"] ){
       $products[] = $item;
     }
 }

     $data['products'] = $products;



       // Get Attributes
          $tax_names = $tax_display = $t_slug_name = [];
          $atts_tax = wc_get_attribute_taxonomies();



          foreach ($atts_tax as $at) {

              $at->attribute_name = 'pa_' . $at->attribute_name ;
              $at->attribute_label = __($at->attribute_label,'switch');
              if ($at->attribute_public) {
                  $tax_names[] =  $at->attribute_name;
                  //$t_slug_name[$at->attribute_name] = wc_attribute_label(  $at->attribute_name, '' );
              }
          }


          $category_array = new stdClass();
          $category_array->attribute_name = "product_cat";
          $category_array->attribute_label = __('Category', 'switch');
          $atts_tax[]= $category_array;


          $tax_names[] = 'product_cat';


          $terms = wp_get_object_terms($pids, $tax_names, array('fields' => 'all_with_object_id'));

          $taxonomies = [];
          foreach ($terms as $index => $term) {

              foreach($atts_tax as $key => $z){

                if($z->attribute_name == $term->taxonomy){
                  if(sizeof($z->values)==0){
                    $z->values[] = $term;
                  }
                  $inloop = false;
                  foreach($z->values as $value){
                    if ($value->slug == $term->slug) {
                      $inloop = true;
                    }
                  }
                  if(!$inloop){
                    $z->values[] = $term;
                  }
                }
              }
          }
          // Remove empty values
          foreach($atts_tax as $key => $z){
            if($z->values==null){
              unset($atts_tax[$key]);
            }

          }


    $data['attributes'] = $atts_tax;


    if (count($atts_tax) ==1){
           $attributes = array( 'id:1' =>  $atts_tax[0]);
                   $data['attributes'] =  $attributes;
       }

    $response = new WP_REST_Response($data);
    $response->header( 'Content-Type', "application/json; charset=UTF-8" );
    $response->set_status(200);
    return $response;

      }


public function get_page_detail($request) {
        $slug_param = $request->get_param('slug');
        $post_id = url_to_postid($slug_param );
        $post= get_post( $post_id);
 $slug = $request->get_param('slug');

         $language = $request->get_param('language');
        $handlers = new HandlerContainer();
        $self = $this;
        $handlers->add('vc_single_image', function(ShortcodeInterface $s)  use($self) {
             $item = array();
              $item['url'] = $s->getParameter('image');
             $item['category'] = basename($s->getParameter('link'));
             $self->page_banner_images[] =  $item;

    return "";
    });

     $handlers->add('product_slider_widget', function(ShortcodeInterface $s)  use($self) {
         $self->pageProducts =   $self->pageProducts.",".$s->getParameter('mobile_products');
          return "";
      });

     $handlers->add('vc_images_carousel', function(ShortcodeInterface $s)  use($self) {


if ($s->getParent()->getParent()->getParameter('el_id') == "mobile_banners"){

                $item = array();
                  $item['images'] = $s->getParameter('images');
                   $output = $s->getParameter('custom_links');
                   $output = str_replace("#E-8_","",   $output);
                   $output = urldecode(base64_decode( $output));
                   $item['links'] = $output ;

                     $range = $s->getParent()->getParent()->getParameter('el_class');


                    $pos = strpos( $range,"range");

                    if ($pos !== false) {
                         $items = explode("=", $range);
                          $item['range'] = $items[1];

                    }


                  $self->page_mobile_banners = $item;
} else {

                  $item = array();
                  $item['images'] = $s->getParameter('images');
                   $output = $s->getParameter('custom_links');
                   $output = str_replace("#E-8_","",   $output);
                   $output = urldecode(base64_decode( $output));
                   $item['links'] = $output ;
                  $self->shop_carousel = $item;
          }


        return "";
        });






    $processor = new Processor(new RegularParser(), $handlers);
    $processor->process($post->post_content);
    $data = array();





 foreach ( $this->page_banner_images as $item  ) {
           $url =     wp_get_attachment_image_src($item["url"], "large" )[0];
             if (!is_null ($url)){
               $item["url"] =  $url ;
               $data['page_banner_images'] [] = $item ;
             }
        }



         $banners_mobile_image = explode(",", $this->page_mobile_banners['images']);
           $banners_mobile_image_links = explode(",", $this->page_mobile_banners['links']);

          $shop_images = explode(",", $this->shop_carousel['images']);
          $shop_images_links= explode(",", $this->shop_carousel['links']);

       $i=0;
        foreach ( $shop_images as $image_id  ) {
           $url =  wp_get_attachment_image_src($image_id, 'large' )[0];
             if (!is_null ($url)){
                $item = array();
                $item["url"] =  $url ;
                $item["category"] =  basename( $banners_mobile_image_links[$i]) ;
                 $data['shop_carousel'] [] = $item ;
             }
             $i++;
        }

        $i=0;
                foreach ( $banners_mobile_image as $image_id  ) {
                   $url =  wp_get_attachment_image_src($image_id, 'large' )[0];
                     if (!is_null ($url)){
                        $item = array();
                        $item["url"] =  $url ;
                        $item["category"] =  basename($banners_mobile_image_links[$i]) ;
                         $data['mobile_banners'] [] = $item ;
                     }
                     $i++;
                }


           $range = $this->page_mobile_banners["range"];
          if (empty($range)) {
           $range = 2;
           }
           if (count($banners_mobile_image) > 0){
            $array1 = array_slice($data['mobile_banners'], 0, $range);
            $array2 = array_slice($data['mobile_banners'], $range, count($data['mobile_banners']));
             $data['mobile_banners']  = [];
            $data['mobile_banners'] [] =  $array1;
            $data['mobile_banners'] [] =  $array2;
            }



         $product_ids =  explode(",", $this->pageProducts);


     $products = array();

    $min_price = $request->get_param('min_price');
      $max_price = $request->get_param('max_price');
  $page = $request->get_param('page');



      $args = array(
           'post_type'      => 'product',
            'post__in' => $product_ids,
             'posts_per_page' => -1,
            'post_status' => 'publish'
      );


       if (isset($min_price) && isset($max_price)) {

                 $args['meta_query'][] = array(
                     'relation' => 'OR',
                     array(
                         'key' => '_price',
                         'value' => array($min_price, $max_price),
                         'type' => 'DECIMAL',
                         'compare' => 'BETWEEN',

                     ),
                     array(
                         'key' => '_price',
                         'value' => $min_price,
                         'compare' => '=',
                     ),
                     array(
                         'key' => '_price',
                         'value' => $max_price,
                         'compare' => '='
                     ),

                 );
             }

               /* Calculate Attributes*/
                 $atts_tax = wc_get_attribute_taxonomies();
                 foreach($atts_tax as $t){
                    $attribute = 'pa_' . $t->attribute_name;
                    if (null !==($request->get_param($attribute))) {

                         $args['tax_query'][] = array(
                             'relation' => 'AND',
                             array(
                                 'taxonomy' => 'pa_'.$t->attribute_name,
                                 'field' => 'slug',
                                 'terms' => explode(',', $request->get_param('pa_'.$t->attribute_name)),
                                 'include_children' => true
                             )
                         );
                     }
                 }

            if(isset($_GET['collection'])){
                if($_GET['collection']!="undefined"&&$_GET['collection']!=""){
               $args['tax_query'][] = array(
                'taxonomy' => 'collection',
               'terms' => $_GET['collection'],
                  'field' => 'id'
        );
      }

    }
                    if(isset($term->name)){
                       $args['tax_query'][] = array(
                           'relation' => 'AND',
                           array(
                               'taxonomy' => 'product_cat',
                               'field' => 'term_id',
                               'terms' => explode(',', $term->term_id),
                               'include_children' => true
                           )
                       );
                     }

                   $order = $request->get_param('sort');

                     if (isset($order)) {
                         if ($request->get_param('sort') == 'price') {
                           $args['orderby'] = 'meta_value_num';
                           $args['meta_key'] = '_price';
                           $args['order'] = 'ASC';
                         }
                         else if ($request->get_param('sort') == 'price-desc'){
                           $args['orderby'] = 'meta_value_num';
                           $args['meta_key'] = '_price';
                           $args['order'] = 'DESC';
                         }
                         else {
                             $args['orderby'] = $request->get_param('sort');
                         }
                     } else {
                         $args['orderby'] = 'date';
                         $args['order'] = 'DESC';
                     }


     $pids = [];



     $loop = new WP_Query( $args );

       $products = array();


       foreach($loop->posts as $post){
    $product = wc_get_product($post->ID);



       $product_id = apply_filters( 'wpml_object_id', $product->get_id(), 'product', false,  $language );
       $product = wc_get_product($product_id );
     $item = array();
     $item["id"] = $product->id;
        $pids[] = $product->id;
     $item["name"] = htmlspecialchars_decode($product->name);;
     $item["date_modified"] = $product->date_modified;
     $item["date_created"] = $product->date_created;
     $item["short_description"] = $product->short_description;

       if (strlen($product->sale_price) == 0 ){
          $product->sale_price = $product->price;
          }
      $item["price"] = number_format( $product->price, 3, '.', '');
      $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
   $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                  $item["stock_status"] = $product->stock_status;
          $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
                  $item["permalink"] = $product->get_permalink();
         $item["express"]  = 0;
                  $item["best_seller"]  = 0;
                  $item["flash_deal"]  =0;

                  $collections =  wp_get_post_terms($product->id,"collection");
                  for ($j = 0; $j < count($collections); $j++) {
                    if  ($collections[$j]->name == "Express"){
                     $item["express"] = 1;
                    }
                      if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                   $item["best_seller"] = 1;
                     }
                    if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                     $item["flash_deal"] = 1;
                    }
                  }

     	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

       	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

       	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





              $item["warranty"] = $warranty;
              $item["flash_deal_date"] = $date;

     $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
     if(  $item["id"] ){
       $products[] = $item;
     }
 }


       // Get Attributes
          $tax_names = $tax_display = $t_slug_name = [];
          $atts_tax = wc_get_attribute_taxonomies();



          foreach ($atts_tax as $at) {

              $at->attribute_name = 'pa_' . $at->attribute_name ;
              $at->attribute_label = __($at->attribute_label,'switch');
              if ($at->attribute_public) {
                  $tax_names[] =  $at->attribute_name;
                  //$t_slug_name[$at->attribute_name] = wc_attribute_label(  $at->attribute_name, '' );
              }
          }


          $category_array = new stdClass();
          $category_array->attribute_name = "product_cat";
          $category_array->attribute_label = __('Category', 'switch');
          $atts_tax[]= $category_array;


          $tax_names[] = 'product_cat';


          $terms = wp_get_object_terms($pids, $tax_names, array('fields' => 'all_with_object_id'));

          $taxonomies = [];
          foreach ($terms as $index => $term) {

              foreach($atts_tax as $key => $z){

                if($z->attribute_name == $term->taxonomy){
                  if(sizeof($z->values)==0){
                    $z->values[] = $term;
                  }
                  $inloop = false;
                  foreach($z->values as $value){
                    if ($value->slug == $term->slug) {
                      $inloop = true;
                    }
                  }
                  if(!$inloop){
                    $z->values[] = $term;
                  }
                }
              }
          }
          // Remove empty values
          foreach($atts_tax as $key => $z){
            if($z->values==null){
              unset($atts_tax[$key]);
            }

          }


      $data['attributes'] = $atts_tax;
        if (count($atts_tax) ==1){
               $attributes = array( 'id:1' =>  $atts_tax[0]);
                       $data['attributes'] =  $attributes;
           }



          $products = array_slice($products, ($page -1 ) * 10 , $page * 10);


     $data['products'] = $products;

    $response = new WP_REST_Response($data);
    $response->set_status(200);

    return $response;

      }

     public function get_product_detail($request) {
        $id = $request->get_param('id');
         $language = $request->get_param('language');
         $product_id = apply_filters( 'wpml_object_id', $id, 'product', false, $language   );

         $a = get_post_meta( $product_id);
         $product   = wc_get_product($product_id);
       //  var_dump($product);


$gallery = array();
        $meta = get_post_meta($id,"_product_image_gallery");
          $ids = explode(",", $meta[0]);
             foreach ($ids as $id) {
              $link = wp_get_attachment_image_src($id, "large" )[0];
              if ($link != null){
              $gallery [] = $link;
              }
              }



         $item = array();
         $item["id"] = $product_id;
         $item["gallery"] =  $gallery;
         $attributes = array();

         $product_attributes = $product->get_attributes();

         // Iterating through each WC_Product_attribute object
         foreach( $product_attributes as $attribute_taxonomy => $product_attribute){
            $element = array();
             $name = $product_attribute->get_name();
             $attribute_data = $product_attribute->get_data();

             $items =  wc_get_product_terms( $product_id,  $name );
              $element_attribute["key"]=   $name;
                $element_attribute["value"]=   $items ;
                  $slugs = array();
                  foreach ($items as &$item_value) {
                  $slugs[] = $this->createSlug($item_value);
                    }
                $element_attribute["slugs"]=     $slugs;


            $attributes[] = $element_attribute;
         }



          $variations = $this->variations($product_id,  $language);

          $variations_items = array();




         foreach ($variations as &$variation) {
          $variations_item = array();
          $variations_item["display_price"] = $variation["display_price"];
          $variations_item["display_regular_price"] = $variation["display_regular_price"];
         $max_quantity = $variation["max_qty"];
          $min_quantity = $variation["min_qty"];

         if ($variation["max_qty"] == ""){
           $max_quantity = 0;
         }


          if ($variation["min_qty"] == ""){
                $min_quantity = 0;
            }
          $variations_item["max_qty"] = $max_quantity;
          $variations_item["min_qty"] =  $min_quantity;
          $variations_item["variation_id"] = $variation["variation_id"];
          $variations_item["min_qty"] = $variation["min_qty"];

            $variations_item_attribute = array();
            foreach( $variation["attributes"] as $attribute_taxonomy => $product_attribute){
                 $element["key"]=   $attribute_taxonomy;
                  $element["value"]=   $product_attribute ;
                    $variations_item_attribute[] =  $element ;
         }
            $variations_item["attributes"] = $variations_item_attribute;
            $variations_items [] =  $variations_item;
         }

          $item["variations_adv"] =   $variations_items;

         $item["attributes"] = $attributes;
          $item["default_attributes"] = $product->default_attributes;
         $item["name"] = htmlspecialchars_decode($product->name);
         $item["date_modified"] = $product->date_modified;
         $item["date_created"] = $product->date_created;
         $item["permalink"] = $product->get_permalink();
           if (strlen($product->sale_price) == 0 ){
              $product->sale_price = $product->price;
              }
         $item["short_description"] = $product->short_description;
             $item["description"] = $product->description;
         $item["price"] = number_format( $product->price, 3, '.', '');
         $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
      $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                      $item["stock_status"] = $product->stock_status;
          $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
         $item["express"]  = 0;
         $item["best_seller"]  = 0;
         $item["flash_deal"]  =0;

          $brand = get_the_terms($id, 'pa_brand');


       if (is_array($brand) && count($brand) > 0){
            $t_id = $brand[0]->term_id;
              $term_meta = get_option("taxonomy_$t_id");
            $logo_image_id = $term_meta['brand_logo'];
            $link = wp_get_attachment_url($logo_image_id);
             $item["brand_logo"] = $link;
       }



         $collections =  wp_get_post_terms($product->id,"collection");

         for ($j = 0; $j < count($collections); $j++) {
           if  ($collections[$j]->name == "Express"){
            $item["express"] = 1;
           } else

           if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
               $item["best_seller"] = 1;
           } else
            if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                 $item["flash_deal"] = 1;
            }
         }



  	$warranty = get_post_meta( $id,"_warranty_panel",true);

  	$date = get_post_meta($id,"flash_deal_date_deadline",true);

  	$time = get_post_meta($id,"flash_deal_time_deadline",true);



if ($date === false){
        $date = "";
}

if ($warranty === false){
        $warranty = "";
}

if ($time === false){
        $time = "";
}

         $item["warranty"] = $warranty;
         $item["flash_deal_date"] = $date;
          $item["attachment_id"] = $product->image_id;
         $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];



$related_product_ids = wc_get_related_products($id);
   $meta_data = $product->get_meta_data();
 //  var_dump($meta_data);
        foreach ( $meta_data as $value){
          if ($value->key == "extra-content") {
           $item["extra_content"] = $value->value;
          }
        }

$related_products = array();
for ($i = 0; $i < count($related_product_ids); $i++) {
       $product_id = apply_filters( 'wpml_object_id', $related_product_ids[$i], 'product', false, $args["language"] );
              $product   = wc_get_product(  $product_id );


     $itemrelated = array();
     $itemrelated["id"] = $product->id;
       $itemrelated["permalink"] = $product->get_permalink();
     $itemrelated["name"] = htmlspecialchars_decode($product->name);;
     $itemrelated["date_modified"] = $product->date_modified;
     $itemrelated["date_created"] = $product->date_created;
          $itemrelated["description"] = $product->description;
     $itemrelated["short_description"] = $product->short_description;
     $itemrelated["price"] = $product->price;
     $itemrelated["sale_price"] = $product->sale_price;
     $itemrelated["regular_price"] = floatval($product->regular_price);
                    $itemrelated["stock_status"] = $product->stock_status;
          $itemrelated["stock_quantity"] = $product->stock_quantity;
        $itemrelated["express"]  = 0;
                 $itemrelated["best_seller"]  = 0;
                 $itemrelated["flash_deal"]  =0;

                 $collections =  wp_get_post_terms($product->id,"collection");
                 for ($j = 0; $j < count($collections); $j++) {
                   if  ($collections[$j]->name == "Express"){
                    $itemrelated["express"] = 1;
                   } else
                     if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                  $itemrelated["best_seller"] = 1;
                    } else
                   if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                    $itemrelated["flash_deal"] = 1;
                   }
                 }

    	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

      	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

      	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





             $itemrelated["warranty"] = $warranty;
             $itemrelated["flash_deal_date"] = $date;

     $itemrelated["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
     if(  $itemrelated["id"] ){
      $related_products[] = $itemrelated;
     }
 }

      $item["related_products"]  = $related_products;


$multiline = 0;
$document = new DOMDocument;
$document->loadHTML($item["extra_content"]);
$xpath = new DOMXPath($document);
$trs = $xpath->query('//tr');
$array = [];
foreach ($trs as $key => $tr) {
    $td = $xpath->query('td', $tr);
    foreach ($td as $value) {
        $array[$key][] = $value->nodeValue;
        $count = ceil( strlen($value->nodeValue)) / 50;
        if ($count >1){
             $multiline += ($count - 1) * 22;
        }
       }
       }



      $item["height"] = (count($array) * 22)  + $multiline;
        $response = new WP_REST_Response($item);
         $response->set_status(200);


       return $response;


     }


public  function createSlug($str, $delimiter = '-'){

    $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
    return $slug;

}

   public function get_home_page($request) {
        $language = $request->get_param('language');
        if ($language != "ar"){
        $language = "en" ;
        }
          $data = $this->cache->get('home_page_'.$language);
              if ($data){
           $response = new WP_REST_Response($data);
            $response->set_status(200);
           //    return $response;
             }



          $post = get_post(110074);
        if ($language == "ar"){
         $post = get_post(110339);
        }











   $handlers = new HandlerContainer();
   $self = $this;


   $handlers->add('vc_single_image', function(ShortcodeInterface $s)  use($self) {


if ($s->getParent()->getParent()->getParameter('el_id') == "today-selection"){
           $item = array();
           $item['url'] = $s->getParameter('image');
           $item['category'] = basename($s->getParameter('link'));
            $title = $s->getParent()->getParent()->getParameter('el_class');

             $pos = strpos( $title,"mob-title");

              if ($pos !== false) {

                $items = explode("=",  $title);
                $self->header_titles['today-selection'] = $items[1];
              }
           $self->todayselection[] =  $item;
       }



       if ($s->getParent()->getParent()->getParameter('el_id') == "section2"){
                  $item = array();
                  $item['url'] = $s->getParameter('image');
                  $item['category'] = basename($s->getParameter('link'));
                     $title = $s->getParent()->getParent()->getParameter('el_class');
                 $pos = strpos( $title,"mob-title");
                    if ($pos !== false) {
                        $items = explode("=",  $title);
                        $self->header_titles['section2'] = $items[1];
                    }
                  $self->section2[] =  $item;
              }


       if ($s->getParent()->getParent()->getParameter('el_id') == "recommendedforyou"){
                  $item = array();
                  $item['url'] = $s->getParameter('image');
                  $item['category'] = basename($s->getParameter('link'));
                  $title = $s->getParent()->getParent()->getParameter('el_class');
                  $pos = strpos( $title,"mob-title");
                if ($pos !== false) {
                $items = explode("=",  $title);
                  $item['parent_title'] =  $items[1];
                  $self->header_titles['recommendedforyou'] = $items[1];
                }
                  $self->recommendedforyou[] =  $item;
              }


       if ($s->getParent()->getParent()->getParameter('el_id') == "secondee_banner"){
             $item = array();
             $item['url'] = $s->getParameter('image');
              $item['category'] = basename($s->getParameter('link'));
              $self->secondee_banners[] =  $item;

          }







        if ($s->getParent()->getParent()->getParameter('el_id') == "second_pic"){
                  $item = array();
                  $item['url'] = $s->getParameter('image');
                  $item['category'] = basename($s->getParameter('link'));
                  $self->second_pics[] =  $item;

              }

      if ($s->getParent()->getParent()->getParameter('el_id') == "final_pic"){
              $item = array();
              $item['url'] = $s->getParameter('image');
              $item['category'] = basename($s->getParameter('link'));
              $self->final_pics[] =  $item;

          }
          if ($s->getParent()->getParent()->getParameter('el_id') == "new_pic"){
           $item = array();
           $item['url'] = $s->getParameter('image');
           $item['category'] = basename($s->getParameter('link'));
           $self->new_pics[] =  $item;

       }


     if ($s->getParent()->getParent()->getParameter('el_id') == "first_banner"){
         $item = array();
         $item['url'] = $s->getParameter('image');
         $item['category'] = basename($s->getParameter('link'));
          $item['link'] = $s->getParameter('link');

         $item['link']  = preg_replace('{/$}', '', $item['link'] );


          $pos = stripos( $item['link'] , "collection");
       if ($pos !== false) {
         $item['type'] = "Collection";
       } else {
         $item['type'] = "Category";
       }

         $title = $s->getParent()->getParent()->getParameter('el_class');
         $pos = strpos( $title,"mob-title");
           if ($pos !== false) {
               $items = explode("=",  $title);
                $self->header_titles['sliders'] = $items[1];
            }


         $self->sliders[] =  $item;

     }


       if ($s->getParent()->getParent()->getParameter('el_id') == "recomm_banner"){
             $item = array();
             $item['url'] = $s->getParameter('image');
             $item['category_name'] = strip_tags(preg_replace('#\[[^\]]+\]#', '',$s->getText()));
             $item['category_name']  = htmlspecialchars_decode(preg_replace('~[\r\n]+~', '',  $item['category_name'] ));
              $item['category'] = basename($s->getParameter('link'));
             $self->recomm_banners[] =  $item;

         }

      if ($s->getParent()->getParent()->getParameter('el_id') == "second_carousel"){
                   $item = array();
                   $item['url'] = $s->getParameter('image');
                   $item['category'] = basename($s->getParameter('link'));
                     $pos = stripos( $item['link'] , "collection");
                          if ($pos !== false) {
                            $item['type'] = "Collection";
                          } else {
                            $item['type'] = "Category";
                          }

                     $title = $s->getParent()->getParent()->getParameter('el_class');

                            $pos = strpos( $title,"mob-title");

                             if ($pos !== false) {

                                $items = explode("=",  $title);
                              $self->header_titles['second_carousel'] = $items[1];
                             }

                    $self->second_carousel[] =  $item;
           }




      if ($s->getParent()->getParent()->getParameter('el_id') == "third_carousel"){
                   $item = array();
                   $item['url'] = $s->getParameter('image');
                     $item['category'] = basename($s->getParameter('link'));
         $pos = stripos( $item['link'] , "collection");
       if ($pos !== false) {
         $item['type'] = "Collection";
       } else {
         $item['type'] = "Category";
       }

        $title = $s->getParent()->getParent()->getParameter('el_class');

        $pos = strpos( $title,"mob-title");

         if ($pos !== false) {

            $items = explode("=",  $title);
          $self->header_titles['third_carousel'] = $items[1];
         }



                   $self->third_carousel[] =  $item;

                 }

     if ($s->getParent()->getParent()->getParameter('el_id') == "home_categories"){
            $item = array();
            $item['url'] = $s->getParameter('image');
            $item['category_name'] = strip_tags(preg_replace('#\[[^\]]+\]#', '',$s->getText()));
            $item['category_name']  = htmlspecialchars_decode(preg_replace('~[\r\n]+~', '',  $item['category_name'] ));
            $item['category'] = basename($s->getParameter('link'));
            $title = $s->getParent()->getParent()->getParameter('el_class');
         $pos = strpos( $title,"mob-title");
           if ($pos !== false) {
               $items = explode("=",  $title);
                $self->header_titles['home_categories'] = $items[1];
            }
            $self->home_categories[] = $item;
       }

        if ($s->getParent()->getParent()->getParameter('el_id') == "widget_categoriesBrands"){
              $item = array();
              $item['url'] = $s->getParameter('image');
              $item['category'] = basename($s->getParameter('link'));
              $self->category_brand_image[] =   $item;
           }
            if ($s->getParent()->getParent()->getParameter('el_id') == "brand_categories"){
                        $item = array();
                       $item['category_name'] =  strip_tags(preg_replace('#\[[^\]]+\]#', '',$s->getText()));
                        $item['category_name'] =   str_replace(array("\r", "\n"), '',  $item['category_name'] );
                       $item['url'] = $s->getParameter('image');
                       $item['category'] = basename($s->getParameter('link'));
                       $self->brands[] =   $item;

               }

       return "";
   });


   $handlers->add('product_slider_widget', function(ShortcodeInterface $s)  use($self) {



      if ($s->getParent()->getParent()->getParameter('el_id') == "widget_DealsYouCannotMiss"){
                  $self->special_deals = $s->getParameter('mobile_products');
         }

         if ($s->getParent()->getParent()->getParameter('el_id') == "widget_HealthCareEssentials"){
                        $self->health_deals = $s->getParameter('mobile_products');
               }

      if ($s->getParent()->getParent()->getParameter('el_id') == "new_arrival"){
                  $self->recent_deals = $s->getParameter('product_ids');
         }

       return "";
   });


 $handlers->add('kapee_products_grid_carousel', function(ShortcodeInterface $s)  use($self) {




  if ($s->getParent()->getParent()->getParameter('el_id') == "widget_flashDeals"){
               $self->flash_deals = $s->getParameter('product_ids');
      }

      if ($s->getParent()->getParent()->getParameter('el_id') == "widget_recent"){
                  $self->recent_deals = $s->getParameter('product_ids');
         }


       return "";
   });






   $handlers->add('products', function(ShortcodeInterface $s)  use($self) {
      if ($s->getParent()->getParent()->getParameter('el_id') == "widget_extraMegaSale"){
                  $self->extra_mega_sales= $s->getParameter('ids');
         }

          if ($s->getParent()->getParent()->getParameter('el_id') == "widget_exclusiveDeals"){
                 $self->exclusive_deals = $s->getParameter('ids');
               }
               if ($s->getParent()->getParent()->getParameter('el_id') == "mega_sales"){
                             $self->mega_sales = $s->getParameter('ids');
                           }


       return "";
   });





   $processor = new Processor(new RegularParser(), $handlers);
    $processor->process($post->post_content);


       $data = array();
         $size = 'large';
         $index = 0;
       foreach ( $this->sliders as $item  ) {
          $url =     wp_get_attachment_image_src($item["url"], $size )[0];
            if (!is_null ($url)){
              $item["url"] =  $url ;
              $data['sliders'] [] = $item ;
            }
   $index ++;
       }

  $index = 0;
           foreach ( $this->recommendedforyou as $item  ) {
              $url =     wp_get_attachment_image_src($item["url"], $size )[0];
               if (!is_null ($url)){
                  $item["url"] =  $url ;
                  $item["type"] =  "Category" ;
                 $data['recommendedforyou'] [] = $item ;
               }
      $index ++;
           }

   $index = 0;
           foreach ( $this->todayselection as $item  ) {
              $url =     wp_get_attachment_image_src($item["url"], $size )[0];
               if (!is_null ($url)){
                  $item["url"] =  $url ;
                  $item["type"] =  "Category" ;
                 $data['todayselection'] [] = $item ;
               }
      $index ++;
           }

 $index = 0;
           foreach ( $this->section2 as $item  ) {
              $url =     wp_get_attachment_image_src($item["url"], $size )[0];
               if (!is_null ($url)){
                  $item["url"] =  $url ;
                  $item["type"] =  "Category" ;
                 $data['section2'] [] = $item ;
               }
      $index ++;
           }

        $index = 0;
           foreach ( $this->secondee_banners as $item  ) {
              $url =     wp_get_attachment_image_src($item["url"], $size )[0];
               if (!is_null ($url)){
                  $item["url"] =  $url ;
                 $data['secondee_banners'] [] = $item ;
               }
      $index ++;
           }

          foreach ( $this->final_pics as $item  ) {
                  $url =     wp_get_attachment_image_src($item["url"], $size )[0];
                    if (!is_null ($url)){
                      $item["url"] =  $url ;
                      $data['final_pics'] [] = $item ;
                    }
           $index ++;
               }
              foreach ( $this->second_pics as $item  ) {
                      $url =     wp_get_attachment_image_src($item["url"], $size )[0];
                        if (!is_null ($url)){
                          $item["url"] =  $url ;
                          $data['second_pics'] [] = $item ;
                        }
               $index ++;
                   }


           foreach ( $this->new_pics as $item  ) {
                  $url =     wp_get_attachment_image_src($item["url"], $size )[0];
                    if (!is_null ($url)){
                      $item["url"] =  $url ;
                      $data['new_pics'] [] = $item ;
                    }
           $index ++;
               }




         foreach ( $this->recomm_banners as $item  ) {
              $url =     wp_get_attachment_image_src($item["url"], $size )[0];
                if (!is_null ($url)){
                  $item["url"] =  $url ;
                  $data['recomm_banners'] [] = $item ;
                }
       $index ++;
           }


        foreach ( $this->category_brand_image as $item  ) {
              $url =     wp_get_attachment_image_src($item["url"], $size )[0];
                if (!is_null ($url)){
                  $item["url"] = $url;
                  $data['category_brand_image'] [] =  $item;
                }

           }


           for ($i = 0; $i < count($this->brands); $i++) {
            if ($this->brands[$i]){
            $image = wp_get_attachment_image_src($this->brands[$i]["url"],"large");
                 $this->brands[$i]["url"] = $image[0];
                 $data['brands'] [] =  $this->brands[$i];
               }
               }


   for ($i = 0; $i < count($this->home_categories); $i++) {
    if ($this->home_categories[$i]){
    $image = wp_get_attachment_image_src($this->home_categories[$i]["url"],"large");
         $this->home_categories[$i] ["url"] = $image[0];
         $data['home_categories'] [] = $this->home_categories[$i];
       }
       }




       for ($i = 0; $i < count($this->second_carousel); $i++) {
        if ($this->second_carousel[$i]){
             $image = wp_get_attachment_image_src($this->second_carousel[$i]["url"],"large" );
             $this->second_carousel[$i]["url"] = $image[0];
             $data['section1'] [] =  $this->second_carousel[$i];
           }





           }

            for ($i = 0; $i < count($this->third_carousel); $i++) {
                if ($this->third_carousel[$i]){
                $image = wp_get_attachment_image_src($this->third_carousel[$i]["url"],"large");
                 $this->third_carousel[$i]["url"] = $image[0];
                 $this->third_carousel[$i]["type"] = "Category";
                 $data['section3'] [] =  $this->third_carousel[$i];
                   }
                   }

   $flash_deals = explode(",", $this->flash_deals);
   $recent_deals = explode(",", $this->recent_deals);
   $special_deals = explode(",", $this->special_deals);
   $health_deals = explode(",", $this->health_deals);
   $mega_sales = explode(",", $this->mega_sales);
   $extra_mega_sales = explode(",", $this->extra_mega_sales);
   $exclusive_deals = explode(",", $this->exclusive_deals);




   $mega_sales_products = array();
   for ($i = 0; $i < count($mega_sales); $i++) {
         $product_id = apply_filters( 'wpml_object_id', $mega_sales[$i], 'product', false, $language );
          $product   = wc_get_product(  $product_id );
        $item = array();
        $item["id"] = $product->id;
        $item["name"] = htmlspecialchars_decode($product->name);;
        $item["date_modified"] = $product->date_modified;
        $item["date_created"] = $product->date_created;
        $item["short_description"] = $product->short_description;
         $item["price"] = number_format( $product->price, 3, '.', '');
         $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
  $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                    $item["stock_status"] = $product->stock_status;
             $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
           $item["express"]  = 0;
                    $item["best_seller"]  = 0;
                    $item["flash_deal"]  =0;

                    $collections =  wp_get_post_terms($product->id,"collection");
                    for ($j = 0; $j < count($collections); $j++) {
                      if  ($collections[$j]->name == "Express"){
                       $item["express"] = 1;
                      }
                        if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                     $item["best_seller"] = 1;
                       }
                      if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                       $item["flash_deal"] = 1;
                      }
                    }

       	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

         	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

         	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





                $item["warranty"] = $warranty;
                $item["flash_deal_date"] = $date;

        $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
        if(  $item["id"] ){
         $mega_sales_products[] = $item;
        }
    }



   $exclusive_deals_products = array();
   for ($i = 0; $i < count($exclusive_deals); $i++) {
         $product_id = apply_filters( 'wpml_object_id', $exclusive_deals[$i], 'product', false, $language);
         $product   = wc_get_product(  $product_id );
        $item = array();
        $item["id"] = $product->id;
        $item["name"] = htmlspecialchars_decode($product->name);;
        $item["date_modified"] = $product->date_modified;
        $item["date_created"] = $product->date_created;
        $item["short_description"] = $product->short_description;
         $item["price"] = number_format( $product->price, 3, '.', '');
          $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
   $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                    $item["stock_status"] = $product->stock_status;
             $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
            $item["express"]  = 0;
                     $item["best_seller"]  = 0;
                     $item["flash_deal"]  =0;

                     $collections =  wp_get_post_terms($product->id,"collection");
                     for ($j = 0; $j < count($collections); $j++) {
                       if  ($collections[$j]->name == "Express"){
                        $item["express"] = 1;
                       }
                         if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                      $item["best_seller"] = 1;
                        }
                       if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                        $item["flash_deal"] = 1;
                       }
                     }

        	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

          	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

          	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





                 $item["warranty"] = $warranty;
                 $item["flash_deal_date"] = $date;
        $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
        if(  $item["id"] ){
         $exclusive_deals_products[] = $item;
        }
    }



    $flash_products = array();
   for ($i = 0; $i < count($flash_deals); $i++) {
        $product_id = apply_filters( 'wpml_object_id', $flash_deals[$i], 'product', false, $language );
        $product   = wc_get_product(  $product_id );
        $item = array();
        $item["id"] = $product->id;
        $item["name"] = htmlspecialchars_decode($product->name);;
        $item["date_modified"] = $product->date_modified;
        $item["date_created"] = $product->date_created;
        $item["short_description"] = $product->short_description;
         $item["price"] = number_format( $product->price, 3, '.', '');
         $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
   $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                $item["stock_status"] = $product->stock_status;
             $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;

           $item["express"]  = 0;
                    $item["best_seller"]  = 0;
                    $item["flash_deal"]  =0;
                    $collections =  wp_get_post_terms($product->id,"collection");
                    for ($j = 0; $j < count($collections); $j++) {
                      if  ($collections[$j]->name == "Express"){
                       $item["express"] = 1;
                      }
                        if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                     $item["best_seller"] = 1;
                       }
                      if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                       $item["flash_deal"] = 1;
                      }
                    }
       	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

         	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

         	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





                $item["warranty"] = $warranty;
                $item["flash_deal_date"] = $date;
        $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
        if(  $item["id"] ){
         $flash_products[] = $item;
        }
    }


    $recent_products = array();
   for ($i = 0; $i < count($recent_deals); $i++) {
        $product_id = apply_filters( 'wpml_object_id', $recent_deals[$i], 'product', false, $language );
        $product   = wc_get_product(  $product_id );
        $item = array();
        $item["id"] = $product->id;
        $item["name"] = htmlspecialchars_decode($product->name);;
        $item["date_modified"] = $product->date_modified;
        $item["date_created"] = $product->date_created;
        $item["short_description"] = $product->short_description;
         $item["price"] = number_format( $product->price, 3, '.', '');
         $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
   $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                 $item["stock_status"] = $product->stock_status;
             $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
            $item["express"]  = 0;
                     $item["best_seller"]  = 0;
                     $item["flash_deal"]  =0;
                     $collections =  wp_get_post_terms($product->id,"collection");
                     for ($j = 0; $j < count($collections); $j++) {
                       if  ($collections[$j]->name == "Express"){
                        $item["express"] = 1;
                       }
                         if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                      $item["best_seller"] = 1;
                        }
                       if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                        $item["flash_deal"] = 1;
                       }
                     }
        	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

          	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

          	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





                 $item["warranty"] = $warranty;
                 $item["flash_deal_date"] = $date;
        $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
        if(  $item["id"] ){
         $recent_products[] = $item;
        }
    }

   $extra_mega_sales_products = array();
   for ($i = 0; $i < count($extra_mega_sales); $i++) {
         $product_id = apply_filters( 'wpml_object_id', $extra_mega_sales[$i], 'product', false, $language);
        $product   = wc_get_product(  $product_id );
        $item = array();
        $item["id"] = $product->id;
        $item["name"] = htmlspecialchars_decode($product->name);;
        $item["date_modified"] = $product->date_modified;
        $item["date_created"] = $product->date_created;
        $item["short_description"] = $product->short_description;
         $item["price"] = number_format( $product->price, 3, '.', '');
         $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
   $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                 $item["stock_status"] = $product->stock_status;
             $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
        $item["express"]  = 0;
                 $item["best_seller"]  = 0;
                 $item["flash_deal"]  =0;
                 $collections =  wp_get_post_terms($product->id,"collection");
                 for ($j = 0; $j < count($collections); $j++) {
                   if  ($collections[$j]->name == "Express"){
                    $item["express"] = 1;
                   }
                     if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                  $item["best_seller"] = 1;
                    }
                   if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                    $item["flash_deal"] = 1;
                   }
                 }
    	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

      	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

      	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





             $item["warranty"] = $warranty;
             $item["flash_deal_date"] = $date;
        $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
        if(  $item["id"] ){
         $extra_mega_sales_products[] = $item;
        }
    }

   $special_products = array();
   for ($i = 0; $i < count($special_deals); $i++) {
         $product_id = apply_filters( 'wpml_object_id', $special_deals[$i], 'product', false, $language );
        $product   = wc_get_product(  $product_id );
        $product   = wc_get_product($special_deals[$i]);
        $item = array();
        $item["id"] = $product->id;
        $item["name"] = htmlspecialchars_decode($product->name);;
        $item["date_modified"] = $product->date_modified;
        $item["date_created"] = $product->date_created;
        $item["short_description"] = $product->short_description;
         $item["price"] = number_format( $product->price, 3, '.', '');
         $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
   $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
                                $item["stock_status"] = $product->stock_status;
             $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
       $item["express"]  = 0;
                $item["best_seller"]  = 0;
                $item["flash_deal"]  =0;
                $collections =  wp_get_post_terms($product->id,"collection");
                for ($j = 0; $j < count($collections); $j++) {
                  if  ($collections[$j]->name == "Express"){
                   $item["express"] = 1;
                  }
                    if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                 $item["best_seller"] = 1;
                   }
                  if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                   $item["flash_deal"] = 1;
                  }
                }
   	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

     	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

     	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);







            $item["warranty"] = $warranty;
            $item["flash_deal_date"] = $date;
        $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
        if(  $item["id"] ){
         $special_products[] = $item;
        }
    }




   $health_products = array();
   for ($i = 0; $i < count($health_deals); $i++) {
        $product_id = apply_filters( 'wpml_object_id', $health_deals[$i], 'product', false, $language );
        $product   = wc_get_product(  $product_id );
        $item = array();
        $item["id"] = $product->id;
        $item["name"] = htmlspecialchars_decode($product->name);;
        $item["date_modified"] = $product->date_modified;
        $item["date_created"] = $product->date_created;
        $item["short_description"] = $product->short_description;
         $item["price"] = number_format( $product->price, 3, '.', '');
         $item["sale_price"] = number_format( $product->sale_price, 3, '.', '');
         $item["regular_price"] = number_format( floatval($product->regular_price), 3, '.', '');
           $item["stock_status"] = $product->stock_status;
             $item["stock_quantity"] = $product->stock_quantity;
                $item["status"] = $product->status;
        $item["express"]  = 0;
                 $item["best_seller"]  = 0;
                 $item["flash_deal"]  =0;
                 $collections =  wp_get_post_terms($product->id,"collection");
                 for ($j = 0; $j < count($collections); $j++) {
                   if  ($collections[$j]->name == "Express"){
                    $item["express"] = 1;
                   }
                     if (strpos(strtolower($collections[$j]->name), 'best sell') !== false) {
                                  $item["best_seller"] = 1;
                    }
                   if (strpos(strtolower($collections[$j]->name), 'flash deal') !== false) {
                                    $item["flash_deal"] = 1;
                   }
                 }
    	$warranty = get_post_meta( $product->id,"_warranty_panel",true);

      	$date = get_post_meta($product->id,"flash_deal_date_deadline",true);

      	$time = get_post_meta($product->id,"flash_deal_time_deadline",true);





             $item["warranty"] = $warranty;
             $item["flash_deal_date"] = $date;
        $item["image"] = wp_get_attachment_image_src($product->image_id, "large" )[0];
        if(  $item["id"] ){
         $health_products[] = $item;
        }
    }




  $data['header_titles']  = $this->header_titles;
       $data['flash_deals']  = $flash_products;
       $data['recent_deals']  = $recent_products;
        $data['mega_sales']  = $mega_sales_products;

       $data['deals_you_cannot_miss']  = $special_products;
        $data['health_deals']  = $health_products;
        $data['extra_mega_sales']  = $extra_mega_sales_products;
        $data['exclusive_deals']  = $exclusive_deals_products;

         $this->cache->set('home_page_'.$language,$data, strtotime('+ 1 day'));
       $response = new WP_REST_Response($data);
       $response->set_status(200);

          return $response;
      }
   }

$planetConectUsAPI = new PlanetConectUsAPI();




