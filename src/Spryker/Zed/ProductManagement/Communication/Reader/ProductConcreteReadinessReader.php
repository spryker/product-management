<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteReadinessRequestTransfer;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;

class ProductConcreteReadinessReader implements ProductConcreteReadinessReaderInterface
{
    /**
     * @param \Spryker\Zed\ProductManagement\Communication\Reader\ProductAbstractReadinessReaderInterface $productAbstractReadinessReader
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     * @param array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteReadinessProviderPluginInterface> $productConcreteReadinessExpanderPlugins
     */
    public function __construct(
        protected ProductAbstractReadinessReaderInterface $productAbstractReadinessReader,
        protected ProductManagementToProductInterface $productFacade,
        protected array $productConcreteReadinessExpanderPlugins = [],
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteReadinessRequestTransfer $productConcreteReadinessRequestTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer>
     */
    public function read(
        ProductConcreteReadinessRequestTransfer $productConcreteReadinessRequestTransfer,
    ): ArrayObject {
        $productReadinessTransfers = new ArrayObject();
        foreach ($this->productConcreteReadinessExpanderPlugins as $productConcreteReadinessExpanderPlugin) {
            $productReadinessTransfers = $productConcreteReadinessExpanderPlugin->provide($productConcreteReadinessRequestTransfer, $productReadinessTransfers);
        }

        return $productReadinessTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductConcreteTransfer> $concreteProductCollection
     *
     * @return array<int, \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer>>
     */
    public function readBulk(ArrayObject $concreteProductCollection): array
    {
        $concreteProductReadinesses = [];
        foreach ($concreteProductCollection as $productConcrete) {
            $concreteProductReadinesses[$productConcrete->getIdProductConcreteOrFail()] = $this->read(
                (new ProductConcreteReadinessRequestTransfer())
                    ->setProductConcrete($productConcrete),
            );
        }

        return $concreteProductReadinesses;
    }
}
