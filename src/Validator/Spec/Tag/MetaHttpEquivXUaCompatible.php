<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */

namespace AmpProject\Validator\Spec\Tag;

use AmpProject\Attribute;
use AmpProject\Format;
use AmpProject\Tag as Element;
use AmpProject\Validator\Spec\SpecRule;
use AmpProject\Validator\Spec\Tag;

final class MetaHttpEquivXUaCompatible extends Tag
{
    /**
     * ID of the tag.
     *
     * @var string
     */
    const ID = 'meta http-equiv=X-UA-Compatible';

    /**
     * Array of spec rules.
     *
     * @var array
     */
    const SPEC = [
        SpecRule::TAG_NAME => Element::META,
        SpecRule::SPEC_NAME => 'meta http-equiv=X-UA-Compatible',
        SpecRule::ATTRS => [
            [
                SpecRule::NAME => Attribute::CONTENT,
                SpecRule::MANDATORY => true,
                SpecRule::VALUE_PROPERTIES => [
                    SpecRule::PROPERTIES => [
                        [
                            SpecRule::NAME => 'ie',
                            SpecRule::VALUE => 'edge',
                        ],
                        [
                            SpecRule::NAME => 'chrome',
                            SpecRule::VALUE => '1',
                        ],
                    ],
                ],
            ],
            [
                SpecRule::NAME => Attribute::HTTP_EQUIV,
                SpecRule::MANDATORY => true,
                SpecRule::DISPATCH_KEY => 'NAME_VALUE_DISPATCH',
                SpecRule::VALUE_CASEI => [
                    'x-ua-compatible',
                ],
            ],
        ],
        SpecRule::SPEC_URL => 'https://amp.dev/documentation/guides-and-tutorials/learn/spec/amphtml/#html-tags',
        SpecRule::MANDATORY_ANCESTOR => Element::HEAD,
        SpecRule::HTML_FORMAT => [
            Format::AMP,
            Format::AMP4ADS,
        ],
        SpecRule::DESCRIPTIVE_NAME => 'meta http-equiv=X-UA-Compatible',
    ];
}