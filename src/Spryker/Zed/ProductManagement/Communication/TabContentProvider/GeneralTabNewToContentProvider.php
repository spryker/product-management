<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\TabContentProvider;

use Generated\Shared\Transfer\ProductAbstractTransfer;

class GeneralTabNewToContentProvider extends AbstractGeneralTabContentProvider
{
    protected const int PRIORITY = 70;

    public function getPriority(): int
    {
        return static::PRIORITY;
    }

    public function provideTabContent(?ProductAbstractTransfer $productAbstractTransfer = null): array
    {
        return ['@ProductManagement/Product/_partials/general-tab-new-to.twig'];
    }
}
