<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;

interface ProductManagementToProductImageInterface
{
    public function saveProductImage(ProductImageTransfer $productImageTransfer): ProductImageTransfer;

    public function saveProductImageSet(ProductImageSetTransfer $productImageSetTransfer): ProductImageSetTransfer;

    /**
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getProductImagesSetCollectionByProductAbstractId($idProductAbstract): array;

    /**
     * @param int $idProduct
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getProductImagesSetCollectionByProductId($idProduct): array;
}
