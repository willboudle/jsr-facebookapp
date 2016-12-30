<?php
require_once('../../app/Mage.php'); //Path to Magento
umask(0);
//Mage::app('admin');
Mage::app('admin');

/**
 * Create some attributes to help control what happens.
 * $storetype = Changed for webstores.
 */

//https://www.jsrdirect.com/JSR/facebookapp/facebook-app.php?sku=sku

$bandsku  = Mage::app()->getRequest()->getParam('sku');
// Deprecated   $catid = Mage::app()->getRequest()->getParam('catid');

$siteurl = $storetype  =  Mage::app()->getRequest()->getParam('siteurl') ?: "jsrdirect.com";

/* Change store type for webstores */
$storetype  =  Mage::app()->getRequest()->getParam('store') ?: "bands";

if($siteurl == 'jsrdirect.com'){
    $finalurl = $siteurl.'/'.$storetype;
}else{
    $finalurl = $siteurl;
};

/* Change sort order & sortby if customer wants different sort order */
$sortby =  Mage::app()->getRequest()->getParam('sortby')?:"created_at"; //position,sku,name,price
$sortorder = Mage::app()->getRequest()->getParam('sortorder')?:"DESC"; //asc or desc

// load the category by the band sku attribute.
$_category = Mage::getModel('catalog/category')->loadbyattribute('band_sku',$bandsku);

// get the product collection based on the $bandsku.......  !!!! Maybe in the future we can load them by the cateogry instead?

$_products = Mage::getModel('catalog/product')->getCollection()
    ->addAttributeToSelect('*')
    ->addAttributeToFilter('sku', array('like' => $bandsku.'%'))
    ->setPageSize(350)
    ->setCurPage(1)
    ->addAttributeToFilter('visibility', 4) // Only catalog, search visiblity  //->addVisibleInCatalogFilterToCollection()
    ->addAttributeToFilter(
    'status',
    array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED) 
        //replace DISABLED to ENABLED for products with status enabled
)
    ->addAttributeToSort($sortby, $sortorder)
    ;



?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $_category->getName() ?> Merchandise</title>
    <link rel="stylesheet" href="css/style.css"/>
    <?php
    $fbstyle = "//jsrdirect.com/media/bands/".$bandsku."/css/fbstyle.css";
    //   if(file_exists($fbstyle)){
           echo '<link rel="stylesheet" href="'.$fbstyle.'"/>';
    //   }else{ echo $fbstyle;};
        ?>
</head>

<body>
<div id="fb-root"></div>
    <script>
            (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=299512750077237&version=v2.0";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <div id="container">
        <header>
            <a href='https://www.<?php echo $finalurl."/".$_category->getUrlPath() ?>' target='_blank'>
            <!-- Get the category image if it exists. -->
                <?php if ($_category->getImage()){echo "<img src='".$_category->getImageUrl()."' />";}; ?>
            <!--  the category description -->
                <?php if ($_category->getDescription()){echo $_category->getDescription();}; ?>
            </a>
        </header>

        <div id="content">
        <?php

            foreach($_products as $_product){

                $productUrl = "https://www.".$finalurl."/".$_category->getUrlKey()."/".$_product->getUrlKey()."";

                echo " <div class='item'>";
                    echo "<a href='http://www.".$finalurl."/".$_category->getUrlKey()."/".$_product->getUrlKey()."?utm_source=".$bandsku."&utm_medium=facebook&utm_campaign=webstores' target='_blank' class='productimage'>";
                        //echo "<img src='https://jsrdirect.com/media/catalog/product".$_product->getImage()."' /> ";
                        echo "<img src='//d2x72n33pz0l2n.cloudfront.net/media/catalog/product".$_product->getImage()."' /> ";
                            echo "<p class='text'>";
                                echo $_product->getName()."<br>";
                                echo $_product->getSku()."<br>";
                                echo "<strong>$ ".$_product->getFinalPrice()."</strong>";
                echo "<br />";
                               // echo "<iframe src='//www.facebook.com/plugins/like.php?href=".urlencode($productUrl)."&amp;width&amp;layout=button&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId=299512750077237' scrolling='no' frameborder='0' style='border:none; overflow:hidden; height:35px;' allowTransparency='true'></iframe>";
                               echo "<span class='fb-like' data-href='".$productUrl."' data-send='false' data-layout='button_count' data-width='90' data-show-faces='false' data-share='true' data-font='tahoma' style='margin-left: auto; margin-right: auto; text-align: center;'></span>";
                            echo "</p>";
                    echo "</a>";
                echo "</div><!-- end item -->";
            }

            ?>
        </div><!-- end content -->

        <footer>

        </footer>
    </div>
</body>
</html>