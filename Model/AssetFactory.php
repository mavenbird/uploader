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
namespace Mavenbird\ImageUploader\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\MediaGalleryApi\Api\Data\AssetInterfaceFactory;
use Mavenbird\ImageUploader\Helper\ImageHelper;

class AssetFactory extends AssetInterfaceFactory
{
    /**
     * @var AssetInterfaceFactory
     */
    private $assetFactory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * AssetFactory constructor.
     * @param AssetInterfaceFactory $assetFactory
     * @param Filesystem $filesystem
     * @param ImageHelper $imageHelper
     */
    public function __construct(
        AssetInterfaceFactory $assetFactory,
        Filesystem $filesystem,
        ImageHelper $imageHelper
    ) {
        $this->assetFactory = $assetFactory;
        $this->filesystem = $filesystem;
        $this->imageHelper = $imageHelper;
    }

    /**
     * Set height and width for SVG images when saving to DB
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data = [])
    {
        if ((empty($data['width']) || empty($data['height']))
            && isset($data['path'])
            && $this->imageHelper->isVectorImage($data['path'])
        ) {
            $absolutePath = $this->getMediaDirectory()->getAbsolutePath($data['path']);
            $width = 300;
            $height = 150;

            $svg = simplexml_load_file($absolutePath);
            if (!empty($svg['width']) && !empty($svg['height'])) {
                $width = (int)($svg['width']);
                $height = (int)($svg['height']);
            }

            $data['width'] = $width;
            $data['height'] = $height;
        }

        return $this->assetFactory->create($data);
    }

    /**
     * Retrieve media directory instance with read access
     *
     * @return ReadInterface
     */
    private function getMediaDirectory()
    {
        return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
    }
}
