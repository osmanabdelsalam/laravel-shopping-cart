# laravel-shopping-cart
Shopping cart for E-commerce applications.

## Introduction

This file is a shopping cart trait for laravel framework.

## Installation

To get started with this trait:
   1. Clone the file.
   2. Create folder in your laravel apllication in path App\Traits\ShoppingCart.
   3. Copy and paste the file Cart.php in ShoppingCart directory.
   4. use the trait in your controller. inside the controller class -- use \App\Traits\ShoppingCart\Cart; --
   5. finally all Cart trait methods now available in your controller just call them.
   
## Basic Usage

You can use it in your laravel ecommerce application to handle the shopping cart of your application.

## Example

Suppose you have ShoppingCartController in your controllers:

class ShoppingCartController extends Controller {
  use \App\Traits\ShoppingCart\Cart;
  
  public function addProductToCartRequest(Request $request) {
     $product_id = $request->product_id;
     $quantity = $request->quantity;
     
     // do your logic against product. Then:
     
     $this->addProduct($product_id,$quantity);
     
     // return statement.
  }
  
  public function destroyShoppingCartRequest() {
    $this->cartDestroy();
    return back();
  }
  
  public function removeProductFromCartRequest($product_id) {
    $this->removeProduct($product_id);
    return back();
  }
  
  public function incementProductRequest($product_id) {
    $oldQty = $this->getProductQty($product_id);
    $this->setProductQty($product_id,$oldQty+1);
    return back();
  }
  
  public function decrementProductRequest($product_id) {
    $oldQty = $this->getProductQty($product_id);
    $this->setProductQty($product_id,$oldQty-1);
    return back();
  }
  
  public function getCartSizeRequest() {
    $cartSize = $this->getSizeByProducts();
    // do your code ...
  }
  
  public function getItemsSizeRequest() {
    $cartSize = $this->getSizeByItemPerProduct();
    // do your code ...
  }
  
}
