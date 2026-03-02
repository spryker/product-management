<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

class ProductManagementToAvailabilityBridge implements ProductManagementToAvailabilityInterface
{
    /**
     * @var \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct($availabilityFacade)
    {
        $this->availabilityFacade = $availabilityFacade;
    }

    public function calculateAvailabilityForProductWithStore(string $sku, StoreTransfer $storeTransfer): Decimal
    {
        return $this->availabilityFacade->calculateAvailabilityForProductWithStore($sku, $storeTransfer);
    }
}
