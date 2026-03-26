<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductAbstractTransfer;

class ProductFormAddDataProvider extends AbstractProductFormDataProvider
{
    /**
     * @param array|null $priceDimension
     *
     * @return array
     */
    public function getData(?array $priceDimension = null)
    {
        return $this->getDefaultFormFields($priceDimension);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|null $productAbstractTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(?ProductAbstractTransfer $productAbstractTransfer = null)
    {
        $formOptions = parent::getOptions($productAbstractTransfer);

        return $this->expandFormOptions($formOptions, $productAbstractTransfer);
    }

    /**
     * @param array<string, mixed> $formOptions
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|null $productAbstractTransfer
     *
     * @return array<string, mixed>
     */
    protected function expandFormOptions(array $formOptions, ?ProductAbstractTransfer $productAbstractTransfer = null): array
    {
        foreach ($this->productAbstractFormOptionsExpanderPlugins as $productAbstractFormOptionsExpanderPlugin) {
            $formOptions = $productAbstractFormOptionsExpanderPlugin->expand($formOptions, $productAbstractTransfer ?? new ProductAbstractTransfer());
        }

        return $formOptions;
    }
}
