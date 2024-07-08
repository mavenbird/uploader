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
* @package    Mavenbird_OrderInformation
* @author     Mavenbird Team
* @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
* @license    http://mavenbird.com/Mavenbird-Module-License.txt
*/
namespace Mavenbird\ImageUploader\Plugin\Wysiwyg\Images;

use Magento\Cms\Model\Wysiwyg\Images\Storage;
use Mavenbird\ImageUploader\Helper\ImageHelper;

class StoragePlugin
{
    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * StoragePlugin constructor.
     * @param ImageHelper $imageHelper
     */
    public function __construct(
        ImageHelper $imageHelper
    ) {
        $this->imageHelper = $imageHelper;
    }

    /**
     * Skip resizing vector images
     *
     * @param Storage $storage
     * @param callable $proceed
     * @param string $source
     * @param bool $keepRatio
     * @return string
     */
    public function aroundResizeFile(Storage $storage, callable $proceed, string $source, bool $keepRatio = true): string
    {
        if ($this->imageHelper->isVectorImage($source)) {
            return $source;
        }

        return $proceed($source, $keepRatio);
    }

    /**
     * Return original file path as thumbnail for vector images
     *
     * @param Storage $storage
     * @param callable $proceed
     * @param string $filePath
     * @param bool $checkFile
     * @return string
     */
    public function aroundGetThumbnailPath(Storage $storage, callable $proceed, string $filePath, bool $checkFile = false): string
    {
        if ($this->imageHelper->isVectorImage($filePath)) {
            return $filePath;
        }

        return $proceed($filePath, $checkFile);
    }
}
