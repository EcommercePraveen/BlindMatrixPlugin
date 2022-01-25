<?php
   /*
   Plugin Name: BlindMatrix API HUB
   Plugin URI: https://www.blindmatrix.com
   description: BLINDMATRIX API HUB
   Version: 1.0
   Author: Praveen Kumar - Technical Architect 
   Author URI: https://www.blindmatrix.com
   */

include(dirname( __FILE__ ) . '/common.php');
require( dirname( __FILE__ ) . '/functions.php' );


?>
<?php function myplugin_options_page()
{
?>
  <div>
  <?php screen_icon(); ?>

  <form method="post" action="options.php">
  <?php settings_fields( 'myplugin_options_group' ); ?>
  <table class = "blindmatrix-api">
  <tr>
  <td>
  <img src="https://blindmatrix.com/wp-content/uploads/2020/05/blindmatrix-logo.png"/>
  <h1>API HUB</h1>
  </td>
  </tr>
  </table>
  <table class="blindmatrix-api-innerpage">
  <tr valign="top">
  <th scope="row"><label for="Api_Url">API URL</label></th>
  <td><input class="api-url-name" type="text" id="Api_Url" name="Api_Url" value="<?php echo get_option('Api_Url'); ?>" /></td>
  </tr>
  <tr valign="top">
  <th scope="row"><label for="Api_Name">API NAME</label></th>
  <td><input class="api-name" type="text" id="Api_Name" name="Api_Name" value="<?php echo get_option('Api_Name'); ?>" /></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
} ?>
<?php
function sd_display_sub_menu_page()
{
	?>
	 <html>

<body>
  <div id="toastmsg">
  <img src="<?php  echo plugin_dir_url( __DIR__ );?>BlindMatrix-Api/assets/image/verified.svg"/>Copied Successfully
</div>
 <table class="short-code-heading">
  <tr>
  <td class="source-code-tit">
  <img src="https://blindmatrix.com/wp-content/uploads/2020/05/blindmatrix-logo.png"/>
  <h1>API Source Code</h1>
  </td>
  </tr>
  </table>
  
    <div style="overflow-x:auto">
        <table class="customers">
            <thead>
                <tr style="text-align:left;">
                    <th>Source Code Slug</th>
                    <th>API Source Code</th>
                    <th>Source Code Slug</th>
                    <th>API Source Code</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="shortcode-title">BM-Products-home-page</td>
                    <td class="short-code-css" >[BlindMatrix source="BM-Products"] 
                    <button onclick='copyfunc(`[BlindMatrix source="BM-Products"]`,`btn1`)' id="btn1" class="cusbtn"><img src="<?php  echo plugin_dir_url( __DIR__ );?>BlindMatrix-Api/assets/image/copy.svg"/>copy</button>
                    </td>
                    <td class="shortcode-title">Shop-by-color-home-page</td>
                    <td class="short-code-css" >[BlindMatrix source="Shop-by-color"]
                    <button onclick='copyfunc(`[BlindMatrix source="Shop-by-color"]`,`btn3`)' id="btn3" class="cusbtn"><img src="<?php  echo plugin_dir_url( __DIR__ );?>BlindMatrix-Api/assets/image/copy.svg"/>copy</button> 
                    </td>
                </tr>
                <tr>
                    <td class="shortcode-title">Shop-by-material-home-page</td>
                    <td class="short-code-css" >[BlindMatrix source="Shop-by-material"] 
                    <button onclick='copyfunc(`[BlindMatrix source"Shop-by-material"]`,`btn1`)' id="btn1" class="cusbtn"><img src="<?php  echo plugin_dir_url( __DIR__ );?>BlindMatrix-Api/assets/image/copy.svg"/>copy</button>
                    </td>
                    <td class="shortcode-title">Product-Menu-Footer-footer-menu</td>
                    <td class="short-code-css" >[BlindMatrix source="Product-Menu-Footer"]
                    <button onclick='copyfunc(`[BlindMatrix source="Product-Menu-Footer"]`,`btn2`)' id="btn2" class="cusbtn"><img src="<?php  echo plugin_dir_url( __DIR__ );?>BlindMatrix-Api/assets/image/copy.svg"/>copy</button>        
                    </td>
                </tr>
                <tr>
                    <td class="shortcode-title">BM-Product-List-product-page</td>
                    <td class="short-code-css" >[BlindMatrix source="BM-Product-List"]
                    <button onclick='copyfunc(`[BlindMatrix source="BM-Product-List"]`,`btn4`)' id="btn4" class="cusbtn"><img src="<?php  echo plugin_dir_url( __DIR__ );?>BlindMatrix-Api/assets/image/copy.svg"/>copy</button> 
                    </td>
                    <td class="shortcode-title">product-category-page</td>
                    <td class="short-code-css" >[BlindMatrix source="product-category"]
                    <button onclick='copyfunc(`[BlindMatrix source="product-category"]`,`btn5`)' id="btn5" class="cusbtn"><img src="<?php  echo plugin_dir_url( __DIR__ );?>BlindMatrix-Api/assets/image/copy.svg"/>copy</button> 
                    </td>
                </tr>
                <tr>
                    <td class="shortcode-title">Product-View-page</td>
                    <td class="short-code-css" >[BlindMatrix source="Product-View"]
                    <button onclick='copyfunc(`[BlindMatrix source="Product-View"]`,`btn14`)' id="btn14" class="cusbtn"><img src="<?php  echo plugin_dir_url( __DIR__ );?>BlindMatrix-Api/assets/image/copy.svg"/>copy</button>
                    </td>
                    <td class="shortcode-title">Sample-Cart-free sample-section</td>
                    <td class="short-code-css" >[BlindMatrix source="Sample-Cart"]
                    <button onclick='copyfunc(`[BlindMatrix source="Sample-Cart"]`,`btn6`)' id="btn6" class="cusbtn"><img src="<?php  echo plugin_dir_url( __DIR__ );?>BlindMatrix-Api/assets/image/copy.svg"/>copy</button>
                    </td>
                </tr>
                <tr>
                    <td class="shortcode-title">Mobile-Product-Menu-mobile-menu</td>
                    <td class="short-code-css" >[BlindMatrix source="Mobile-Product-Menu"]
                    <button onclick='copyfunc(`[BlindMatrix source="Mobile-Product-Menu"]`,`btn9`)' id="btn9" class="cusbtn"><img src="<?php  echo plugin_dir_url( __DIR__ );?>BlindMatrix-Api/assets/image/copy.svg"/>copy</button>
                    </td>
                    <td class="shortcode-title">Product-Menu-home-menu</td>
                    <td class="short-code-css" >[BlindMatrix source="Product-Menu"]
                    <button onclick='copyfunc(`[BlindMatrix source="Product-Menu"]`,`btn10`)' id="btn10" class="cusbtn"><img src="<?php  echo plugin_dir_url( __DIR__ );?>BlindMatrix-Api/assets/image/copy.svg"/>copy</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
 <!--
<script>
function copyfunc(tdval,btnid){
        var btnids=document.getElementById(btnid);
        btnids.classList.add('activebtn');
        var node = document.createElement( "textarea" );
        btnids.innerHTML = "copied";
        
        //setTimeout(function(){ 
       //     document.getElementById(btnid).innerHTML = "copy";
       //  }, 2000);
        //document.getElementById(btnid).classList.add('copied');
        node.innerHTML = tdval;

        document.body.appendChild( node ); 
        node.select();  
  
    try{ 
        var success = document.execCommand( "copy" );
    } 
    catch(e){ 
        console.log( "browser not compatible" );
    } 
    document.body.removeChild( node );
    }
 </script>
     -->
 
	<?php
	
}

?>