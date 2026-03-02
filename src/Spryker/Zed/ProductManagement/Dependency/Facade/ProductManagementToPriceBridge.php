<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

class ProductManagementToPriceBridge implements ProductManagementToPriceInterface
{
    /**
     * @var \Spryker\Zed\Price\Business\PriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\Price\Business\PriceFacadeInterface $priceFacade
     */
    public function __construct($priceFacade)
    {
        $this->priceFacade = $priceFacade;
    }

    public function getNetPriceModeIdentifier(): string
    {
        return $this->priceFacade->getNetPriceModeIdentifier();
    }

    public function getGrossPriceModeIdentifier(): string
    {
        return $this->priceFacade->getGrossPriceModeIdentifier();
    }

    public function getDefaultPriceMode(): string
    {
        return $this->priceFacade->getDefaultPriceMode();
    }
}
