<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Generated\Shared\Transfer\CurrencyTransfer;

interface ProductManagementToCurrencyInterface
{
    public function getCurrent(): CurrencyTransfer;

    /**
     * @return array<\Generated\Shared\Transfer\StoreWithCurrencyTransfer>
     */
    public function getAllStoresWithCurrencies(): array;

    public function getDefaultCurrencyForCurrentStore(): CurrencyTransfer;
}
