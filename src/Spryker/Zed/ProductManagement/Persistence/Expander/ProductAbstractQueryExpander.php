<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Persistence\Expander;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class ProductAbstractQueryExpander implements ProductAbstractQueryExpanderInterface
{
    /**
     * @var array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableQueryCriteriaExpanderPluginInterface>
     */
    protected $productTableQueryCriteriaExpanderPlugins;

    /**
     * @param array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableQueryCriteriaExpanderPluginInterface> $productTableQueryCriteriaExpanderPlugins
     */
    public function __construct(array $productTableQueryCriteriaExpanderPlugins)
    {
        $this->productTableQueryCriteriaExpanderPlugins = $productTableQueryCriteriaExpanderPlugins;
    }

    public function expandQuery(ModelCriteria $query): ModelCriteria
    {
        $queryCriteriaTransfer = $this->buildQueryCriteriaTransfer();
        $query = $this->addJoin($query, $queryCriteriaTransfer);
        $query = $this->addWithColumns($query, $queryCriteriaTransfer);

        return $query;
    }

    protected function buildQueryCriteriaTransfer(): QueryCriteriaTransfer
    {
        $queryCriteriaTransfer = new QueryCriteriaTransfer();

        foreach ($this->productTableQueryCriteriaExpanderPlugins as $productTableQueryCriteriaExpanderPlugin) {
            $queryCriteriaTransfer = $productTableQueryCriteriaExpanderPlugin->expandQueryCriteria($queryCriteriaTransfer);
        }

        return $queryCriteriaTransfer;
    }

    protected function addJoin(
        ModelCriteria $query,
        QueryCriteriaTransfer $queryCriteriaTransfer
    ): ModelCriteria {
        foreach ($queryCriteriaTransfer->getJoins() as $queryJoinTransfer) {
            $joinType = $queryJoinTransfer->getJoinType() ?? Criteria::INNER_JOIN;
            if ($queryJoinTransfer->getRelation()) {
                $query->join($queryJoinTransfer->getRelation(), $joinType);

                if ($queryJoinTransfer->getCondition()) {
                    $query->addJoinCondition($queryJoinTransfer->getRelation(), $queryJoinTransfer->getCondition());
                }

                continue;
            }
            $query->addJoin($queryJoinTransfer->getLeft(), $queryJoinTransfer->getRight(), $joinType);
        }

        return $query;
    }

    protected function addWithColumns(
        ModelCriteria $query,
        QueryCriteriaTransfer $queryCriteriaTransfer
    ): ModelCriteria {
        foreach ($queryCriteriaTransfer->getWithColumns() as $field => $value) {
            if (is_array($value)) {
                $field = array_key_first($value);
                if (!is_string($field)) {
                    continue;
                }
                $query->withColumn($field, $value[$field]);

                continue;
            }
            $query->withColumn($field, $value);
        }

        return $query;
    }
}
