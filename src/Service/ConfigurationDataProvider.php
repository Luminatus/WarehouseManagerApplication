<?php

namespace Lumie\WarehouseManagerApplication\Service;

use Lumie\WarehouseManagerApplication\Entity\Brand;
use Lumie\WarehouseManagerApplication\Entity\Product\AbstractProduct;
use Lumie\WarehouseManagerApplication\Entity\Product\Headphone;
use Lumie\WarehouseManagerApplication\Entity\Product\Keyboard;
use Lumie\WarehouseManagerApplication\Entity\Warehouse;
use Lumie\WarehouseManagerApplication\Structure\Enum\HeadphoneAudioType;
use Lumie\WarehouseManagerApplication\Structure\Enum\HeadphoneConnectionType;
use Lumie\WarehouseManagerApplication\Structure\Enum\KeyboardLayout;

class ConfigurationDataProvider implements DataProviderInterface
{
    protected bool $isLoaded;

    public function __construct(
        protected EntityManagerInterface $em,
        protected array $configuration
    ) {
        $this->isLoaded = false;
    }

    public function load(bool $force = false)
    {
        if (!$this->isLoaded || $force) {
            try {
                $this->em->clear();

                $this->loadBrands();
                $this->loadProducts();
                $this->loadWarehouses();

                $this->isLoaded = true;
            } catch (\Throwable $th) {
                $this->em->clear();
                throw $th;
            }
        }
    }

    protected function loadBrands()
    {
        foreach ($this->configuration['brands'] as $brandConfig) {
            $brand = new Brand($brandConfig['id']);
            $brand
                ->setName($brandConfig['name'])
                ->setQualityRating($brandConfig['qualityRating']);

            $this->em->persist($brand);
        }
    }

    protected function loadProducts()
    {
        foreach ($this->configuration['products'] as $productConfig) {
            $productClass = $this->getProductClass($productConfig);

            if (!$productClass) {
                throw new \Exception("Invalid product type");
            }

            /** @var AbstractProduct $product */
            $product = new $productClass($productConfig['id']);
            $product
                ->setName($productConfig['name'])
                ->setPrice($productConfig['price'])
                ->setProductNumber($productConfig['productNumber'])
                ->setBrand($this->em->get(Brand::class, $productConfig['brand']));

            $this->loadExtraProductFields($product, $productConfig);

            $this->em->persist($product);
        }
    }

    protected function loadWarehouses()
    {

        foreach ($this->configuration['warehouses'] as $warehouseConfig) {
            $warehouse = new Warehouse($warehouseConfig['id']);

            $warehouse
                ->setName($warehouseConfig['name'])
                ->setAddress($warehouseConfig['address'])
                ->setCapacity($warehouseConfig['capacity']);

            foreach ($warehouseConfig['products'] as $productConfig) {
                $product = $this->em->get(AbstractProduct::class, $productConfig['productId']);

                $warehouse->addProduct($product, $productConfig['quantity']);
            }

            $this->em->persist($warehouse);
        }
    }

    protected function getProductClass($config)
    {
        return match ($config['type']) {
            'keyboard' => Keyboard::class,
            'headphone' => Headphone::class,
            default => null,
        };
    }

    protected function loadExtraProductFields(AbstractProduct $product, array $productConfig)
    {
        if ($product instanceof Keyboard) {
            $product->setIsRGB($productConfig['isRGB']);
            $product->setLayout(KeyboardLayout::from($productConfig['layout']));
        } elseif ($product instanceof Headphone) {
            $product->setAudioType(HeadphoneAudioType::from($productConfig['audioType']));
            $product->setConnectionType(HeadphoneConnectionType::from($productConfig['connectionType']));
        }
    }
}
