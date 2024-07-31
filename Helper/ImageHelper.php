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
namespace Mavenbird\ImageUploader\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class ImageHelper extends AbstractHelper
{
    const XML_PATH_VECTOR_EXTENSIONS = 'mavenbird_imageuploader/extensions/vector';
    const XML_PATH_WEB_IMAGE_EXTENSIONS = 'mavenbird_imageuploader/extensions/web_image';

    /**
     * Check if the file is a vector image
     *
     * @param $file
     * @return bool
     */
    public function isVectorImage($file)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (empty($extension) && file_exists($file)) {
            $mimeType = mime_content_type($file);
            $extension = str_replace('image/', '', $mimeType);
        }

        return in_array($extension, $this->getVectorExtensions());
    }

    /**
     * Get vector image extensions
     *
     * @return array
     */
    public function getVectorExtensions()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_VECTOR_EXTENSIONS, 'store') ?: [];
    }
    
    /**
     * Get web image extensions
     *
     * @return array
     */
    public function getWebImageExtensions()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_WEB_IMAGE_EXTENSIONS, 'store') ?: [];
    }
}