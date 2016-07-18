<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductFormAttributeValues extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_VALUE = 'value';

    const LABEL = 'label';
    const MULTIPLE = 'multiple';
    const CUSTOM = 'custom';

    /**
     * @var array
     */
    protected $attributeValues;

    /**
     * @var string
     */
    protected $validationGroup;

    /**
     * @param array $attributeValues
     */
    public function __construct(array $attributeValues, $validationGroup)
    {
        $this->attributeValues = $attributeValues;
        $this->validationGroup = $validationGroup;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ProductFormAttributeValues';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            'required' => false,
            'cascade_validation' => true,
            'validation_groups' => [$this->validationGroup]
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addValueField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options = [])
    {
        $name = $builder->getName();
        $label = $name;
        $isDisabled = true;

        if (isset($this->attributeValues[$name])) {
            $label = $this->attributeValues[$name][self::LABEL];
            $isDisabled = $this->attributeValues[$name][self::CUSTOM] === true;
        }

        $builder
            ->add(self::FIELD_VALUE, 'text', [
                'label' => $label,
                'disabled' => $isDisabled
            ]);

        return $this;
    }

}
