<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

interface ProductManagementToPriceInterface
{
    public function getNetPriceModeIdentifier(): string;

    public function getGrossPriceModeIdentifier(): string;

    public function getDefaultPriceMode(): string;
}
