<?php

/**
  * @author Osman Abdelsalam Mohamed <dev.o.alsalam@gmail.com>
  */

namespace App\Traits\ShoppingCart;

trait Cart {

	/**
     * The name of the cart.
     *
     * @var string
     */
	private $cartName = "cart.items";

	/**
     * The names of cart array indexes.
     *
     * @var array
     */
	private $cartKeyNames = [
		'product_key_name' => 'product_id',
		'quantity_key_name' => 'qty'
	];


	/**
     * check if cart exsist or not. if exsist it will return true else false.
     *
     * @return boolean
     */
	public function cartExsist() {
		return session()->has($this->cartName);
	}

	/**
     * Get an array representing the cart items.
     *
     * @return array
     */
	public function getCart() {
		if($this->cartExsist()) {
			return session($this->cartName);
		}else {
			return null;
		}
	}

	/**
     * check if cart has product or not; accept product id and return true if product exsist or false if not.
     *
     * @param  mixed $p_id
     * @return boolean
     */
	public function cartHas($p_id) {

		if($this->cartExsist()) {
			$found = false;
			$cart = session($this->cartName);
			foreach($cart as $item) {
				if($item[$this->cartKeyNames['product_key_name']] == $p_id) {
					$found = true;
					break;
				}
			}
			return $found;
		}else {
			return false;
		}
	}

	/**
     * Get an integer representing the quantity of passed product id.
     *
     * @param  mixed $p_id
     * @return integer
     */
	public function getProductQty($p_id) {
		if($this->cartExsist()) {
			$items = session($this->cartName);
			foreach($items as $item) {
				if($item[$this->cartKeyNames['product_key_name']] == $p_id) {
					return $item[$this->cartKeyNames['quantity_key_name']];
				}
			}
			return -1;
		}else {
			return null;
		}
	}

	/**
     * Update the quantity of passed product if exsist by old quantity plus passed quantity.
     * If cart exsist updated cart will be return. else null will be return.
     *
     * @param  mixed  $p_id
     * @param  integer  $qty
     * @return mixed
     */
	public function setProductQty($p_id,$qty) {
		if($this->cartExsist()) {
			$items = session()->pull($this->cartName);
			$newItems = [];
			foreach($items as $item) {
				if($item[$this->cartKeyNames['product_key_name']] == $p_id) {
					$item[$this->cartKeyNames['quantity_key_name']] = $qty;
				}
				array_push($newItems, $item);
			}
			session()->put($this->cartName,$newItems);

			return $this->getCart();
		}else {
			return null;
		}
	}

	/**
     * Create new Item of passed product with its quantity.
     *
     * @param  mixed $product_id
     * @param  integer $qty
     * @return array
     */
	public function createNewItem($product_id,$qty) {
		return [
			$this->cartKeyNames['product_key_name']=>$product_id,
			$this->cartKeyNames['quantity_key_name']=>$qty
		];
	}

	/**
     * Add new product if not exsist in cart. if product exsist update its quantity.
     * if cart not exsist; the function will create new cart and add new product to it, then return 3.
     * if cart exsists and product not exsist in it; the function will create new product to cart, then return 2.
     * if cart exsists and product exsist in it; the function will update its quantity, then return 1.
     *
     * @param  mixed $p_id
     * @param  mixed $qty
     * @return integer
     */
	public function addProduct($p_id,$qty) {
		
		if($this->cartExsist()) {
			if($this->cartHas($p_id)){
				$this->setProductQty($p_id,$this->getProductQty($p_id)+$qty);
				return 1;
			}else{
				$cart = session()->pull($this->cartName);
				array_push($cart, $this->createNewItem($p_id,$qty));
				session()->put($this->cartName,$cart);
				return 2;
			}
		}else {
			$p = [];
			array_push($p, $this->createNewItem($p_id,$qty));
			session()->put($this->cartName,$p);
			return 3;
		}
	}

	/**
     * Remove the product from cart.
     * If cart not exsist; the function will return -1.
     * If cart exsist; the function will remove the product then return sizeof new cart.
     *
     * @param  mixed $p_id
     * @return integer
     */
	public function removeProduct($p_id) {
		if($this->cartExsist()) {
			$cart = session()->pull($this->cartName);
			$newCart = [];
			foreach($cart as $item) {
				if($item[$this->cartKeyNames['product_key_name']] == $p_id) {
					continue;
				}else {
					array_push($newCart, $item);
				}
			}
			if(sizeof($newCart) > 0) {
				session()->put($this->cartName,$newCart);
				return sizeof($newCart);
			} else {
				return 0;
			}
		}else {
			return -1;
		}
	}

	/**
     * Get sizeof products in cart.
     *
     * @return integer
     */
	public function getSizeByProducts() {
		if($this->cartExsist()) {
			return sizeof(session($this->cartName));
		}else {
			return 0;
		}
	}

	/**
     * Get sizeof items in cart (the summision of products quantity).
     *
     * @return integer
     */
	public function getSizeByItemPerProduct() {
		if($this->cartExsist()) {
			$size = 0;
			$cart = session($this->cartName);
			foreach($cart as $item) {
				$size += $this->getProductQty($item[$this->cartKeyNames['product_key_name']]);
			}
			return $size;
		}else {
			return 0;
		}
	}

	/**
     * Destroy the cart.
     *
     * @return void
     */
	public function cartDestroy() {
		if($this->cartExsist()) {
			session()->forget($this->cartName);
		}
	}

}
