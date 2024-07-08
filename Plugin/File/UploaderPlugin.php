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
namespace Mavenbird\ImageUploader\Plugin\File;

use Magento\MediaStorage\Model\File\Uploader;
use Mavenbird\ImageUploader\Helper\ImageHelper;

class UploaderPlugin
{
    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * ImagePlugin constructor.
     * @param ImageHelper $imageHelper
     */
    public function __construct(
        ImageHelper $imageHelper
    ) {
        $this->imageHelper = $imageHelper;
    }

    /**
     * Add web images to the list ollowed extension for media storage
     *
     * @param Uploader $uploader
     * @param array $extensions
     * @return array
     */
    public function beforeSetAllowedExtensions(Uploader $uploader, $extensions = [])
    {
        $extensions = array_merge(
            $extensions,
            array_values($this->imageHelper->getVectorExtensions()),
            array_values($this->imageHelper->getWebImageExtensions()),
        );

        return [$extensions];
    }
}
