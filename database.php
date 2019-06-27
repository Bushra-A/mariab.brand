<?php
require_once './config/db.config.php';

 function insert( $data, string $tableName){
    DB::insert($tableName, $data);
  }

  function updateProductPriceByURL($product_price, $product_link){
    
    // $status = DB::update(
    //   'products', // table name
    //   array('product_price' => $product_price), // data which will update 
    //   'product_link', // where column name
    //   $product_link // where column value
    // );

    DB::query("UPDATE products SET product_price='$product_price' WHERE product_link=%s", $product_link);

    print_r($status);
    
  }
  function checkDuplicateProduct($product_link){

    DB::query("SELECT * FROM products WHERE product_link=%s", $product_link);
    echo $counter = DB::count();
    if($counter>0){
      return true;
    }else{
      return false;
    }

  }
  
?>
