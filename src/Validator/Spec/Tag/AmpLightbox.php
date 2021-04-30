<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */

namespace AmpProject\Validator\Spec\Tag;

use AmpProject\Attribute;
use AmpProject\Extension;
use AmpProject\Format;
use AmpProject\Layout;
use AmpProject\Validator\Spec\SpecRule;
use AmpProject\Validator\Spec\Tag;

final class AmpLightbox extends Tag
{
    const SPEC = [
        SpecRule::TAG_NAME => Extension::LIGHTBOX,
        SpecRule::ATTRS => [
            [
                SpecRule::NAME => Attribute::ANIMATE_IN,
                SpecRule::VALUE_CASEI => [
                    'fade-in',
                    'fly-in-bottom',
                    'fly-in-top',
                ],
            ],
            [
                SpecRule::NAME => Attribute::ANIMATION,
                SpecRule::VALUE_CASEI => [
                    'fade-in',
                    'fly-in-bottom',
                    'fly-in-top',
                ],
            ],
            [
                SpecRule::NAME => Attribute::CONTROLS,
            ],
            [
                SpecRule::NAME => Attribute::FROM,
            ],
            [
                SpecRule::NAME => Attribute::SCROLLABLE,
                SpecRule::DISABLED_BY => [
                    Attribute::AMP4EMAIL,
                ],
            ],
            [
                SpecRule::NAME => '[open]',
            ],
        ],
        SpecRule::ATTR_LISTS => [
            'extended-amp-global',
        ],
        SpecRule::AMP_LAYOUT => [
            SpecRule::SUPPORTED_LAYOUTS => [
                Layout::NODISPLAY,
            ],
        ],
        SpecRule::HTML_FORMAT => [
            Format::AMP,
            Format::AMP4EMAIL,
        ],
        SpecRule::REQUIRES_EXTENSION => [
            Extension::LIGHTBOX,
        ],
    ];
}
