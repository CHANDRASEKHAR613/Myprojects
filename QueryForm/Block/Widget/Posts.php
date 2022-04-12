<?php
namespace Codilar\QueryForm\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;


class Posts extends Template
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlInterface;

    protected $_template = "widget/posts.phtml";

    public function __construct(
        StoreManagerInterface $storeManager,
        UrlInterface $urlInterface,
        Context $context,
        array   $data = []
    )
    
    {   $this->storeManager = $storeManager;
        $this->urlInterface = $urlInterface;
        parent::__construct($context, $data);

    }//end __construct()



    public function getPostUrl()
    {
        return $this->getUrl('query/query/save');
    }

    public function getBaseUrl()
    {
        return  $storeUrl = $this->storeManager->getStore()->getBaseUrl();
    }

}
