<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\TabContentProvider;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractFormTabContentProviderInterface
{
    public function getTabName(): string;

    public function getPriority(): int;

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|null $productAbstractTransfer
     *
     * @return array<string>
     */
    public function provideTabContent(?ProductAbstractTransfer $productAbstractTransfer = null): array;
}
