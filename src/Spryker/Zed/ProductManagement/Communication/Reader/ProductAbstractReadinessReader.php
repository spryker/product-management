<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer;

class ProductAbstractReadinessReader implements ProductAbstractReadinessReaderInterface
{
    /**
     * @param array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractReadinessProviderPluginInterface> $productAbstractReadinessExpanderPlugins
     */
    public function __construct(
        protected array $productAbstractReadinessExpanderPlugins = [],
    ) {
    }

     /**
      * @param \Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer $productAbstractReadinessRequestTransfer
      *
      * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer>
      */
    public function read(
        ProductAbstractReadinessRequestTransfer $productAbstractReadinessRequestTransfer,
    ): ArrayObject {
        $productReadinessTransfers = new ArrayObject();
        foreach ($this->productAbstractReadinessExpanderPlugins as $productAbstractReadinessExpanderPlugin) {
            $productReadinessTransfers = $productAbstractReadinessExpanderPlugin->provide($productAbstractReadinessRequestTransfer, $productReadinessTransfers);
        }

        return $productReadinessTransfers;
    }
}
