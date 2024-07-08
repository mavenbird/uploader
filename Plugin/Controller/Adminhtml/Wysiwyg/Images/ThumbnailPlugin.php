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
namespace Mavenbird\ImageUploader\Plugin\Controller\Adminhtml\Wysiwyg\Images;

use Magento\Cms\Controller\Adminhtml\Wysiwyg\Images\Thumbnail;
use Magento\Cms\Helper\Wysiwyg\Images;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Io\File as FileUtils;
use Mavenbird\ImageUploader\Helper\ImageHelper;

class ThumbnailPlugin
{
    /**
     * @var Images
     */
    private $wysiwygImages;

    /**
     * @var RawFactory
     */
    private $resultRawFactory;

    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @var FileUtils
     */
    private $fileUtils;

    /**
     * ThumbnailPlugin constructor.
     * @param Images $wysiwygImages
     * @param RawFactory $resultRawFactory
     * @param ImageHelper $imageHelper
     * @param FileUtils $fileUtils
     */
    public function __construct(
        Images $wysiwygImages,
        RawFactory $resultRawFactory,
        ImageHelper $imageHelper,
        FileUtils $fileUtils
    ) {
        $this->wysiwygImages = $wysiwygImages;
        $this->resultRawFactory = $resultRawFactory;
        $this->imageHelper = $imageHelper;
        $this->fileUtils = $fileUtils;
    }

    /**
     * Handle vector images for media storage thumbnails
     *
     * @param Thumbnail $subject
     * @param callable $proceed
     * @return Raw
     */
    public function aroundExecute(Thumbnail $subject, callable $proceed)
    {
        try {
            $file = $subject->getRequest()->getParam('file');
            $file = $this->wysiwygImages->idDecode($file);
            $thumb = $subject->getStorage()->resizeOnTheFly($file);

            if (!$this->imageHelper->isVectorImage($thumb)) {
                throw new LocalizedException(__('This is not a vector image'));
            }

            /** @var Raw $resultRaw */
            $resultRaw = $this->resultRawFactory->create();
            $resultRaw->setHeader('Content-Type', 'image/svg+xml');
            $resultRaw->setContents($this->fileUtils->read($thumb));

            return $resultRaw;
        } catch (\Exception $e) {
            return $proceed();
        }
    }
}
