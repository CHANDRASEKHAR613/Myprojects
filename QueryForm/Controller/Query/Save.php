<?php

namespace Codilar\QueryForm\Controller\Query;

use Codilar\QueryForm\Model\Query;
use Codilar\QueryForm\Model\ResourceModel\Query as QueryResourceModel;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Codilar\QueryForm\Model\ResourceModel\QueryCollection\Collection;
use Magento\Framework\Controller\Result\JsonFactory;

class Save extends Action
{
    protected $resultJsonFactory;

    private $query;

    private $collection;

    private $queryResourceModel;


    public function __construct(
        Context $context,
        Query $query,
        Collection $collection,
        JsonFactory $resultJsonFactory,
        QueryResourceModel $queryResourceModel
    ) {
        $this->collection = $collection;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->query = $query;
        $this->queryResourceModel = $queryResourceModel;
        parent::__construct($context);
    }
    public function execute()
    {
        try {
        $params = $this->getRequest()->getParams();
        
        $resultJson = $this->resultJsonFactory->create();
        $query = $this->query->setData($params);
      

          $flag = $this->queryResourceModel->save($query);  
       
         $saveddata = $this->collection->getLastItem();

        if ($params['name'] == $saveddata['name'] && $params['email'] == $saveddata['email'] && 
        $params['phoneno'] == $saveddata['phoneno'] && $params['whatsapp'] == $saveddata['whatsapp'] && 
        $params['pincode'] == $saveddata['pincode']) {
            
                // created custom event:- form_submit_event
                $this->_eventManager->dispatch('form_submit_event',['query'=>$params]);
         
            return $resultJson->setData([
                'success'
                
            ]);
        }else {
            return $resultJson->setData([
                'not-saved'
            ]);
        }
           // $this->messageManager->addSuccessMessage(__("Your Query added Successfully,We will reach You Soon !!!"));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__("Something went wrong"));
        }
    }
}
