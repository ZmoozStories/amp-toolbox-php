<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */

namespace AmpProject\Validator\Spec\Error;

use AmpProject\Validator\Spec\Error;
use AmpProject\Validator\Spec\SpecRule;

final class DeprecatedTag extends Error
{
    /**
     * Code of the error.
     *
     * @var string
     */
    const CODE = 'DEPRECATED_TAG';

    /**
     * Array of spec data.
     *
     * @var array<array>
     */
    const SPEC = [
        SpecRule::FORMAT => 'The tag \'%1\' is deprecated - use \'%2\' instead.',
        SpecRule::SPECIFICITY => 105,
    ];
}