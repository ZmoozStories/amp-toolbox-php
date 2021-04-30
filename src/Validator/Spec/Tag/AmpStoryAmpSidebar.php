<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */

namespace AmpProject\Validator\Spec\Tag;

use AmpProject\Extension;
use AmpProject\Format;
use AmpProject\Layout;
use AmpProject\Validator\Spec\SpecRule;
use AmpProject\Validator\Spec\Tag;

final class AmpStoryAmpSidebar extends Tag
{
    const SPEC = [
        SpecRule::TAG_NAME => Extension::SIDEBAR,
        SpecRule::SPEC_NAME => 'amp-story >> amp-sidebar',
        SpecRule::MANDATORY_PARENT => Extension::STORY,
        SpecRule::SPEC_URL => 'https://amp.dev/documentation/components/amp-sidebar/',
        SpecRule::AMP_LAYOUT => [
            SpecRule::SUPPORTED_LAYOUTS => [
                Layout::NODISPLAY,
            ],
        ],
        SpecRule::DEPRECATION => 'anchor tags or amp-story-player controls',
        SpecRule::DEPRECATION_URL => 'https://github.com/ampproject/amphtml/issues/33293',
        SpecRule::HTML_FORMAT => [
            Format::AMP,
        ],
        SpecRule::REQUIRES_EXTENSION => [
            Extension::SIDEBAR,
        ],
        SpecRule::MARK_DESCENDANTS => [
            SpecRule::MARKER => [
                'AUTOSCROLL',
            ],
        ],
    ];
}
