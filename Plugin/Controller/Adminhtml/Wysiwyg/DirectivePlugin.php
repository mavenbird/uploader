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
* @package    Mavenbird_ImageUploader
* @author     Mavenbird Team
* @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
* @license    http://mavenbird.com/Mavenbird-Module-License.txt
*/
namespace Mavenbird\ImageUploader\Plugin\Controller\Adminhtml\Wysiwyg;

use Magento\Cms\Controller\Adminhtml\Wysiwyg\Directive;
use Magento\Cms\Model\Template\Filter;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Url\DecoderInterface;
use Magento\Framework\Filesystem\Driver\File as FileDriver;
use Mavenbird\ImageUploader\Helper\ImageHelper;

class DirectivePlugin
{
    /**
     * @var DecoderInterface
     */
    private $urlDecoder;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var RawFactory
     */
    private $resultRawFactory;

    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @var FileDriver
     */
    private $fileDriver;

    /**
     * DirectivePlugin constructor.
     * @param DecoderInterface $urlDecoder
     * @param Filter $filter
     * @param RawFactory $resultRawFactory
     * @param ImageHelper $imageHelper
     * @param FileDriver $fileDriver
     */
    public function __construct(
        DecoderInterface $urlDecoder,
        Filter $filter,
        RawFactory $resultRawFactory,
        ImageHelper $imageHelper,
        FileDriver $fileDriver
    ) {
        $this->urlDecoder = $urlDecoder;
        $this->filter = $filter;
        $this->resultRawFactory = $resultRawFactory;
        $this->imageHelper = $imageHelper;
        $this->fileDriver = $fileDriver;
    }

    /**
     * Handle vector images for media storage thumbnails
     *
     * @param Directive $subject
     * @param callable $proceed
     * @return Raw
     */
    public function aroundExecute(Directive $subject, callable $proceed)
    {
        try {
            $directive = $subject->getRequest()->getParam('___directive');
            $directive = $this->urlDecoder->decode($directive);
            $imagePath = $this->filter->filter($directive);

            if (!$this->imageHelper->isVectorImage($imagePath)) {
                throw new LocalizedException(__('This is not a vector image'));
            }

            /** @var Raw $resultRaw */
            $resultRaw = $this->resultRawFactory->create();
            $resultRaw->setHeader('Content-Type', 'image/svg+xml');
            $resultRaw->setContents($this->fileDriver->fileGetContents($imagePath));

            return $resultRaw;
        } catch (\Exception $e) {
            return $proceed();
        }
    }
}
