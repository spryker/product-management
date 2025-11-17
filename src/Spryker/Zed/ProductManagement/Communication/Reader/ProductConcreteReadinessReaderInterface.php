<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteReadinessRequestTransfer;

interface ProductConcreteReadinessReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteReadinessRequestTransfer $productConcreteReadinessRequestTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer>
     */
    public function read(
        ProductConcreteReadinessRequestTransfer $productConcreteReadinessRequestTransfer,
    ): ArrayObject;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductConcreteTransfer> $concreteProductCollection
     *
     * @return array<int, \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer>>
     */
    public function readBulk(ArrayObject $concreteProductCollection): array;
}
