<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Helper;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAttributeHelperInterface
{
    public function getProductAbstractSuperAttributesCount(ProductAbstractTransfer $productAbstractTransfer): int;
}
