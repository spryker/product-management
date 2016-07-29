<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\FormBuilderInterface;

class AttributeVariantForm extends AttributeAbstractForm
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options = [])
    {
        $name = $builder->getName();
        $attributes = $options[self::OPTION_ATTRIBUTE];
        $isDisabled = $attributes[$name][self::NAME_DISABLED];
        $config = $this->getValueFieldConfig($name, $attributes);

        $input = new Select2ComboBoxType();
        $config['multiple'] = true; //be able to select multiple values of super attributes;
        $config['attr']['style'] .= ' width: 250px';
        $config['choices'] = [];
        $config['attr']['class'] .= ' ajax';
        $config['attr']['tags'] = false; //don't allow undefined values
        $config['read_only'] = true;

        if ($isDisabled) {
            $config['disabled'] = true;
        }

        $builder->add(self::FIELD_VALUE, $input, $config);

        $builder->get(self::FIELD_VALUE)->resetViewTransformers();

        return $this;
    }

}