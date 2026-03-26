<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\TabContentProvider;

abstract class AbstractGeneralTabContentProvider implements ProductAbstractFormTabContentProviderInterface
{
    protected const string TAB_NAME = 'general';

    public function getTabName(): string
    {
        return static::TAB_NAME;
    }
}
