<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

interface ProductManagementToTranslatorFacadeInterface
{
    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string;
}
