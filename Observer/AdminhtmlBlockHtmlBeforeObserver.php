<?php
/**
* Mavenbird Technologies Private Limited
*
* NOTICE OF LICENSE
*
* This source file is subject to the EULA
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://mavenbird.com/Mavenbird-Module-License.txt
*
* =================================================================
*
* @category   Mavenbird
* @package    Mavenbird_AdminCategoryProductLink
* @author     Mavenbird Team
* @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
* @license    http://mavenbird.com/Mavenbird-Module-License.txt
*/ 
declare(strict_types=1);

namespace Mavenbird\AdminCategoryProductLink\Observer;

use Exception;
use Magento\Catalog\Block\Adminhtml\Category\Tab\Product;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AdminhtmlBlockHtmlBeforeObserver implements ObserverInterface
{
    protected RequestInterface $request;

    /**
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * Add edit product column
     *
     * @param  Observer $observer
     * @return void
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if (false === ($block instanceof Product)) {
            return;
        }

        $block->addColumn(
            'action',
            [
                'header' => __('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => 'catalog/product/edit',
                            'params' => ['store' => $this->request->getParam('store')]

                        ],
                        'field' => 'id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
    }
}
