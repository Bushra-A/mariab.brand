<?php

include_once 'config/config.php';
include_once 'lib/database.php';
//print_r(_readMenu('https://www.mariab.pk/eid-collections.html'));

 //_readMenu($url);
function _readMenu($url){

    $html = file_get_html($url);
   
    $mainList = [];
    $m=0;
    foreach($html->find('ol.nav-primary li.level0') as $menuItem ){
      $url = $menuItem->find('a',0)->href;
      $name = $menuItem->find('a span',0)->innertext;
      $key = strtolower(str_replace([' ','  '], '_', $name  ));
      $mainList[$key] = [
        'name'=> $name,
        'url'=> $url,
        ];

      if(count($menuItem->find('ul.level0 li')) > 0){
        //$subMenuList = [];
        foreach($menuItem->find('ul.level0 li') as $subMenuItem){
          $url = $subMenuItem->find('a',0)->href;
          $name = $subMenuItem->find('a span',0)->innertext;
          if($name){
            $key = strtolower(str_replace([' ','  '], '_', $name  ));
            $mainList[$key] = [
              'name'=> $name,
              'url'=> $url 
            ];
          }
        }//foreach
        if( count($mainList) > 0 ) {
          $batchData = [];
          $b = 0;
          foreach($mainList as $key => $data){
            $batchData[$b] = $data;
            $b++;
          }
          insert($batchData, 'categories');
        }
      }//if
      $m++;
    } //foreach
    
     return $mainList; 
 }
//next function read all url in every page 
$productsList = [];
for($p=1; $p<7; $p++){
  $productsList = readAllCategories('https://www.mariab.pk/eid-collections.html?p='.$p); 
  print_r($productsList);
  if( count($productsList) > 0 ) {
    insert($productsList, 'products');

  }
 }
 


function readAllCategories($url){
  $html = file_get_html($url);
  $products = [];
  $p=0;
  foreach($html->find('ul.products-grid') as $uls) {
      //echo $uls->outertext ;
    foreach($uls->find('li.item') as $li) {
      
      $url = $li->find('h3.product-name a')[0]->href;
      $product_title = $li->find('h3.product-name a',0)->innertext;
      $product_imgsrc = $li->find('div.product-image a img',0)->src;
      $price = $li->find('span.price',0)->innertext;
    
      if(checkDuplicateProduct($url)){
           // update price of this product    // calling below function
          updateProductPriceByURL( $price, $url);
          
        }
      
      else{
        $products[$p] = [];
        $products[$p]['product_title'] = $product_title;
        $products[$p]['product_price'] = $price;
        $products[$p]['product_link'] = $url;
        $products[$p]['product_imgsrc'] = $product_imgsrc;
        
        $p++; 
      }
    }
    return $products;
}

  

//print_r(_readSingleProduct('http4s://www.mariab.pk/dw-2180-pink.html'));

// function _readSingleProduct($url){
   
//     $html = file_get_html($url);
//     $productInfo = $html->find('div.product-shop-inner')[0]; 

// // get image list 
//     $imagesList = [];
//     $im=0;
//     foreach($html->find('div.more-views ul li') as $images){
//       $imagesList[$im] = $images->find('a',0)->href;
//       $im++;
//     } 

// // get colorlist
//     $colorsList = [];
//     $c=0;
//     foreach($productInfo->find('.color-product') as $colors){
//       $colorsList[$c]['url'] = $colors->find('img',0)->src;
//       $colorsList[$c]['title'] = $colors->find('img',0)->colorattr;
//       $c++;
//     }

//     $singleProductOnj = [];
//     $mp=0;
//     $singleProductOnj = [
//         'product_title' => $productInfo->find('div.product-name span', 0)->innertext,
//         'product_link' =>$productInfo->find('div.hover-pro-class a', 0)->href,
//         'product_price' => $productInfo->find('div.regular-price span.price')->innertext,
//         'salePrice' => $productInfo->find('span.price', 0)->innertext,
//         'size' => $productInfo->find('span.swatch', 0)->innertext,
//         'weight' => $productInfo->find('div.product-weight p', 0)->innertext,
//         'product_description' => $productInfo->find('div.short-description', 0)->innertext,
//         'otherInfo' => $productInfo->find('div.other-information', 0)->innertext,
//         'shipingInfo' => $productInfo->find('div.shipping-info', 0)->innertext,
//         'color' => $colorsList,
//         'images' => $imagesList,
//         'product_id' => $productInfo->find('div.product-essential', 0)->innertext,
//         // $key = strtolower(str_replace([' ','  '], '_', $singleProductOnj)),
//        ];
       
//        $singleProductOnj[] = [
//         'product_title'=> $product_title,
//         'product_link'=> $product_link, 
//         'product_price'=> $product_price,
//         'product_description'=> $product_description,
//         'product_id'=> $product_id,
         
//       ];
      
//         $batchData = [];
//         $b = 0;
//         foreach($singleProductOnj as $data){
//           $batchData[$b] = $data;
//           $b++;
//         insert($batchData, 'products');
//       }
//     $mp++;
//        return $singleProductOnj;
//     }
}
?>