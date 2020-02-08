<?php
namespace Magenable\CustomerRestrict\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Model\Product;

class CartAddRestrict implements ObserverInterface
{
    protected $cart;
    protected $messageManager;
    protected $redirect;
    protected $request;
    protected $product;
    protected $_session;
    protected $response_factory;
    protected $url;

    public function __construct(
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        RedirectInterface $redirect,
        Cart $cart,
        ManagerInterface $messageManager,
        \Magento\Framework\App\Request\Http $request,
        Product $product,
        \Magento\Customer\Model\Session $session
    ) {
        $this->response_factory = $responseFactory;
        $this->url = $url;
        $this->redirect = $redirect;
        $this->cart = $cart;
        $this->messageManager = $messageManager;
        $this->request = $request;
        $this->product = $product;
        $this->_session = $session;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //$postValues = $this->request->getPostValue();
        //$cartItemsCount = $this->cart->getQuote()->getItemsCount();
        $quote_items = $this->cart->getQuote()->getAllItems();

        //$event = $observer->getEvent();
        //$customer = $event->getCustomer();

        //file_put_contents('f:/qwerty.log', print_r($this->_session->getCustomer()->getId(), true));

        foreach ($quote_items as $item) {
            if ($item->getHasChildren()) {
                continue;
            }
            $product = $item->getProduct();

            if ($item->getTotalQty() >= 2) {
                //$this->request->setParam('product', false);

                $itemId = $item->getItemId();
                //$params[$itemId]['qty'] = 1;
                //$this->cart->updateItems($params);
                //$this->cart->saveQuote();
                //$this->cart->save();

                $this->messageManager->addErrorMessage(__('You can not add product.'));
                break;
            }

            //file_put_contents('f:/qwerty'.$product->getId().'.log', $item->getTotalQty());
        }

        /*if ($cartItemsCount >= 2) {
            $observer->getRequest()->setParam('product', false);
            $this->messageManager->addErrorMessage(__('You can not add product.'));
        }*/

        return $this;
    }

}