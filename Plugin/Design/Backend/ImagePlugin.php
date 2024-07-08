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
namespace Mavenbird\ImageUploader\Plugin\Design\Backend;

use Magento\Theme\Model\Design\Backend\Image;
use Mavenbird\ImageUploader\Helper\ImageHelper;

class ImagePlugin
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
     * Extend allowed extensions for theme files (logo, favicon, etc.)
     *
     * @param Image $subject
     * @param array $extensions
     * @return array
     */
    public function afterGetAllowedExtensions(Image $subject, array $extensions): array
    {
        $extensions = array_merge(
            $extensions,
            array_values($this->imageHelper->getVectorExtensions()),
            array_values($this->imageHelper->getWebImageExtensions())
        );

        return $extensions;
    }
}
