<?php

namespace SS6\ShopBundle\Tests\Unit\Model\Cart;

use SS6\ShopBundle\Model\Cart\Cart;
use SS6\ShopBundle\Model\Cart\CartService;
use SS6\ShopBundle\Model\Cart\Item\CartItem;
use SS6\ShopBundle\Model\Customer\CustomerIdentifier;
use SS6\ShopBundle\Model\Pricing\Vat\Vat;
use SS6\ShopBundle\Model\Pricing\Vat\VatData;
use SS6\ShopBundle\Model\Product\Product;
use SS6\ShopBundle\Model\Product\ProductData;
use SS6\ShopBundle\Tests\Test\FunctionalTestCase;

class CartServiceTest extends FunctionalTestCase {

	public function testAddProductToCartInvalidFloatQuantity() {
		$cartService = $this->getContainer()->get(CartService::class);
		/* @var $cartService \SS6\ShopBundle\Model\Cart\CartService */

		$customerIdentifier = new CustomerIdentifier('randomString');
		$cartItems = [];
		$cart = new Cart($cartItems);

		$price = 100;
		$vat = new Vat(new VatData('vat', 21));

		$productData = new ProductData();
		$productData->name = ['cs' => 'Product 1'];
		$productData->price = $price;
		$productData->vat = $vat;
		$product = Product::create($productData);

		$this->setExpectedException('SS6\ShopBundle\Model\Cart\Exception\InvalidQuantityException');
		$cartService->addProductToCart($cart, $customerIdentifier, $product, 1.1);
	}

	public function testAddProductToCartInvalidZeroQuantity() {
		$cartService = $this->getContainer()->get(CartService::class);
		/* @var $cartService \SS6\ShopBundle\Model\Cart\CartService */

		$customerIdentifier = new CustomerIdentifier('randomString');
		$cartItems = [];
		$cart = new Cart($cartItems);

		$price = 100;
		$vat = new Vat(new VatData('vat', 21));
		$productData = new ProductData();
		$productData->name = ['cs' => 'Product 1'];
		$productData->price = $price;
		$productData->vat = $vat;
		$product = Product::create($productData);

		$this->setExpectedException('SS6\ShopBundle\Model\Cart\Exception\InvalidQuantityException');
		$cartService->addProductToCart($cart, $customerIdentifier, $product, 0);
	}

	public function testAddProductToCartInvalidNegativeQuantity() {
		$cartService = $this->getContainer()->get(CartService::class);
		/* @var $cartService \SS6\ShopBundle\Model\Cart\CartService */

		$customerIdentifier = new CustomerIdentifier('randomString');
		$cartItems = [];
		$cart = new Cart($cartItems);

		$price = 100;
		$vat = new Vat(new VatData('vat', 21));
		$productData = new ProductData();
		$productData->name = ['cs' => 'Product 1'];
		$productData->price = $price;
		$productData->vat = $vat;
		$product = Product::create($productData);

		$this->setExpectedException('SS6\ShopBundle\Model\Cart\Exception\InvalidQuantityException');
		$cartService->addProductToCart($cart, $customerIdentifier, $product, -10);
	}

	public function testAddProductToCartNewProduct() {
		$cartService = $this->getContainer()->get(CartService::class);
		/* @var $cartService \SS6\ShopBundle\Model\Cart\CartService */

		$customerIdentifier = new CustomerIdentifier('randomString');
		$cartItems = [];
		$cart = new Cart($cartItems);

		$price = 100;
		$vat = new Vat(new VatData('vat', 21));
		$productData = new ProductData();
		$productData->name = ['cs' => 'Product 1'];
		$productData->price = $price;
		$productData->vat = $vat;
		$product = Product::create($productData);

		$quantity = 2;

		$result = $cartService->addProductToCart($cart, $customerIdentifier, $product, $quantity);
		$this->assertTrue($result->getIsNew());
		$this->assertSame($quantity, $result->getAddedQuantity());
	}

	public function testAddProductToCartSameProduct() {
		$cartService = $this->getContainer()->get(CartService::class);
		/* @var $cartService \SS6\ShopBundle\Model\Cart\CartService */

		$customerIdentifier = new CustomerIdentifier('randomString');

		$price = 100;
		$vat = new Vat(new VatData('vat', 21));
		$productData = new ProductData();
		$productData->name = ['cs' => 'Product 1'];
		$productData->price = $price;
		$productData->vat = $vat;
		$product = Product::create($productData);

		$cartItem = new CartItem($customerIdentifier, $product, 1, '0.0');
		$cartItems = [$cartItem];
		$cart = new Cart($cartItems);
		$quantity = 2;

		$result = $cartService->addProductToCart($cart, $customerIdentifier, $product, $quantity);
		$this->assertFalse($result->getIsNew());
		$this->assertSame($quantity, $result->getAddedQuantity());
	}

	public function testCleanCart() {
		$cartService = $this->getContainer()->get(CartService::class);
		/* @var $cartService \SS6\ShopBundle\Model\Cart\CartService */

		$customerIdentifier = new CustomerIdentifier('randomString');

		$price = 100;
		$vat = new Vat(new VatData('vat', 21));
		$productData = new ProductData();
		$productData->name = ['cs' => 'Product 1'];
		$productData->price = $price;
		$productData->vat = $vat;
		$product = Product::create($productData);

		$cartItem = new CartItem($customerIdentifier, $product, 1, '0.0');
		$cartItems = [$cartItem];
		$cart = new Cart($cartItems);

		$cartService->cleanCart($cart);

		$this->assertTrue($cart->isEmpty());
	}

	public function testMergeCarts() {
		$cartService = $this->getContainer()->get(CartService::class);
		/* @var $cartService \SS6\ShopBundle\Model\Cart\CartService */

		$price = 100;
		$vat = new Vat(new VatData('vat', 21));
		$productData1 = new ProductData();
		$productData1->name = ['cs' => 'Product 1'];
		$productData1->price = $price;
		$productData1->vat = $vat;
		$product1 = Product::create($productData1);

		$productData2 = new ProductData();
		$productData2->name = ['cs' => 'Product 2'];
		$productData2->price = $price;
		$productData2->vat = $vat;
		$product2 = Product::create($productData2);

		$sessionId1 = 'abc123';
		$sessionId2 = 'def456';
		$customerIdentifier1 = new CustomerIdentifier($sessionId1);
		$customerIdentifier2 = new CustomerIdentifier($sessionId2);

		$cartItem = new CartItem($customerIdentifier1, $product1, 2, '0.0');
		$cartItems = [$cartItem];
		$mainCart = new Cart($cartItems);

		$cartItem1 = new CartItem($customerIdentifier2, $product1, 3, '0.0');
		$cartItem2 = new CartItem($customerIdentifier2, $product2, 1, '0.0');
		$cartItems = [$cartItem1, $cartItem2];
		$mergingCart = new Cart($cartItems);

		$cartService->mergeCarts($mainCart, $mergingCart, $customerIdentifier1);

		foreach ($mainCart->getItems() as $item) {
			if ($item->getSessionId() !== $customerIdentifier1->getSessionId()) {
				$this->fail('Merged cart contain cartItem with wrong sessionId');
			}
		}

		$this->assertSame(2, $mergingCart->getItemsCount());
	}

}
