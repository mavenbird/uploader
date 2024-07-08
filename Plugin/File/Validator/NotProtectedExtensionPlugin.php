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
namespace Mavenbird\ImageUploader\Plugin\File\Validator;

use Magento\MediaStorage\Model\File\Validator\NotProtectedExtension;
use Mavenbird\ImageUploader\Helper\ImageHelper;

class NotProtectedExtensionPlugin
{
    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * NotProtectedExtensionPlugin constructor.
     * @param ImageHelper $imageHelper
     */
    public function __construct(
        ImageHelper $imageHelper
    ) {
        $this->imageHelper = $imageHelper;
    }

    /**
     * Remove vector images from protected extensions list
     *
     * @param NotProtectedExtension $subject
     * @param array|string $result
     * @return array
     */
    public function afterGetProtectedFileExtensions(NotProtectedExtension $subject, $result): array
    {
        $vectorExtensions = $this->imageHelper->getVectorExtensions();

        if (is_string($result)) {
            $result = explode(',', $result);
        }

        foreach ($result as $key => $extension) {
            if (in_array($extension, $vectorExtensions)) {
                unset($result[$key]);
            }
        }

        return $result;
    }
}
