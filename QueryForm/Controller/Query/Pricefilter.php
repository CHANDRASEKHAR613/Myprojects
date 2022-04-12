<?php

namespace Codilar\QueryForm\Controller\Query;

use Magento\Framework\App\Action\Action;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Setup\Exception;
use \Psr\Log\LoggerInterface;
use Magento\Catalog\Model\Product\Attribute\Repository as AttributeRepository;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use \Magento\Store\Api\StoreRepositoryInterface;

class Pricefilter extends Action
{
    /**
     * @var AttributeRepository
     */
    protected $attributeRepository;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Product
     */
    protected $product;

    protected $timezoneInterface;

    protected $dateTime;

    protected $storeRepository;

    protected $storeModelInterface;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface  $storeModelInterface,
        StoreRepositoryInterface                    $storeRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        TimezoneInterface                           $timezoneInterface,
        AttributeRepository                         $attributeRepository,
        ProductRepositoryInterface                  $productRepository,
        LoggerInterface                             $loggerResponse,
        CollectionFactory                           $productCollectionFactory,
        Product                                     $product,
        Action                                      $action,
        Context                                     $context
    )
    {
        $this->storeModelInterface = $storeModelInterface;
        $this->storeRepository = $storeRepository;
        $this->dateTime = $dateTime;
        $this->timezoneInterface = $timezoneInterface;
        $this->attributeRepository = $attributeRepository;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->action = $action;
        $this->logger = $loggerResponse;
        $this->productRepository = $productRepository;
        $this->product = $product;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            foreach ($this->getStoreList() as $value) {
            $collection = $this->_productCollectionFactory->create()
                ->addAttributeToSelect('*');
                  // $value['store_id'];
                // $storeId =;
                $collection->addAttributeToFilter('sku', 'testproduct');
                $collection->addStoreFilter($value['store_id']);
                echo '<pre>';
                foreach ($collection as $products) {
                    $cpriceFitler = $products->getPriceFilter();
                    $PriceFilterValue = $products->getPriceFilter();
                    $currentTime = $this->dateTime->gmtDate('H:i:s');
                    $productUpdated = $products->getUpdatedAt();//Product last updated time
                   // $m15 = $this->dateTime->gmtDate('H:i:s', $after15Mins);
                    $start = strtotime($currentTime);
                    $end = strtotime($productUpdated);
                    $minutes = ($start - $end) / 60;
                    /*echo "The difference in minutes is ".floor($minutes)." minutes.";
                    echo  $PriceFilterValue.'current price filter value ';*/
                    echo '<pre>';
                    /*var_dump($products->getStoreIds());
                    echo $products->getFinalPrice() . 'price';
                    echo $products->getName();
                 */
                    if (floor($minutes) <= 15) {
//-----------------------------------------------------------------------------
                        $products->getCustomAttribute('price_filter');
                        $PriceFilterValue = $products->getPriceFilter();
                        // echo $PriceFilterValue;
                        $name = $products->getName();
                        $cpriceFitler = $products->getPriceFilter();
                        $price = $products->getSpecialPrice();
                        if (empty($price)) {
                            $price = $products->getFinalPrice();
                        }
                        $arr = $this->getAttributeValues();
                        $index1 = 1;
                        $index0 = 0;
                        while (isset($arr[$index1][$index0])) {
                            $name = $arr[$index1][$index0];

                            $exploded = (explode("-", $arr[$index1][$index0]));
                            echo '<pre>';
                            // print_r($exploded);

                            //print_r($exploded)."exploded";
                            if ($price >= $exploded[0] && $price <= $exploded[1]) {
                                $finalvalue = $arr[$index1][1];
                                break;
                            }
                            $index1++;
                        }//end while

                        echo $products->getFinalPrice() . ".....";
                        echo $cpriceFitler . 'current value';
                        echo '<pre>';
                        echo $finalvalue . 'needed value';
                        // echo $products->getName();
                        if (!($PriceFilterValue == $finalvalue)) {
                            // echo $finalvalue . '-----Updated Value  ----->' . $name;
                            $products->setCustomAttribute('price_filter', $finalvalue);
                            $products->save();
                        }//end if
                    }//end if
                }
            }
        } catch (Exception $e) {
            $this->loggerResponse->critical($e->getMessage());
        }//end try
    }

    public function getAttributeValues()
    {
        $item = [];
        $attribute = $this->attributeRepository->get('price_filter')->getOptions();
        foreach ($attribute as $value) {
            $item[] = [$value->getLabel(), $value->getValue()];
        }
        return $item;
    }

    public function getStoreList()
    {
        $storess = [];
        $storeList = $this->storeModelInterface->getStores();
        // = $this->storeRepository->getList();
        foreach ($storeList as $store) {
            $storess[] = ['store_id' => $store->getId(), 'store_code' => $store->getCode()];

        }
        return $storess;
    }
}
