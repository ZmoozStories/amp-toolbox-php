<?php

namespace AmpProject\Tooling\Validator\SpecGenerator;

use Nette\PhpGenerator\Dumper as NetteDumper;

final class Dumper
{
    use ClassNames;
    use ConstantNames;

    /** @var NetteDumper */
    private $dumper;

    /** @var string[] */
    const SPEC_RULE_KEYS = [
        'addValueToSet',
        'allowAllDeclarationInStyle',
        'allowEmpty',
        'allowImportant',
        'allowRelative',
        'alsoRequiresAttr',
        'alsoRequiresTagWarning',
        'alternativeNames',
        'ampLayout',
        'atRuleSpec',
        'attrLists',
        'attributeName',
        'attrs',
        'bentoSupportedVersion',
        'cdata',
        'cdataRegex',
        'childTagNameOneof',
        'childTags',
        'cssDeclaration',
        'cssSpec',
        'declaration',
        'declarationList',
        'declarationListSvg',
        'definesDefaultHeight',
        'definesDefaultWidth',
        'deprecatedAllowDuplicates',
        'deprecatedVersion',
        'deprecation',
        'deprecationUrl',
        'descendantTagList',
        'descriptiveName',
        'disabledBy',
        'disallowedAncestor',
        'disallowedCdataRegex',
        'disallowedValueRegex',
        'dispatchKey',
        'docCssBytes',
        'enabledBy',
        'errorMessage',
        'excludes',
        'expandVendorPrefixes',
        'explicitAttrsOnly',
        'extensionSpec',
        'extensionType',
        'feature',
        'firstChildTagNameOneof',
        'fontUrlSpec',
        'format',
        'htmlFormat',
        'ifValueRegex',
        'imageUrlSpec',
        'implicit',
        'issuesAsError',
        'mandatory',
        'mandatoryAlternatives',
        'mandatoryAncestor',
        'mandatoryAncestorSuggestedAlternative',
        'mandatoryAnyof',
        'mandatoryCdata',
        'mandatoryLastChild',
        'mandatoryMinNumChildTags',
        'mandatoryNumChildTags',
        'mandatoryOneof',
        'mandatoryParent',
        'markDescendants',
        'marker',
        'maxBytes',
        'maxBytesIsWarning',
        'maxBytesPerInlineStyle',
        'maxBytesSpecUrl',
        'mediaQuerySpec',
        'name',
        'namedId',
        'properties',
        'protocol',
        'pseudoClass',
        'referencePoints',
        'regex',
        'requires',
        'requiresAncestor',
        'requiresExtension',
        'requiresUsage',
        'satisfies',
        'selectorSpec',
        'siblingsDisallowed',
        'specName',
        'specUrl',
        'specificity',
        'supportedLayouts',
        'tagName',
        'tagSpecName',
        'trigger',
        'type',
        'unique',
        'uniqueWarning',
        'urlBytesIncluded',
        'validateAmp4ads',
        'validateKeyframes',
        'value',
        'valueCasei',
        'valueDocCss',
        'valueDocSvgCss',
        'valueOneofSet',
        'valueProperties',
        'valueRegex',
        'valueRegexCasei',
        'valueUrl',
        'version',
        'versionName',
        'whitespaceOnly',
    ];

    /**
     * Dump a value so it can be used for code generation.
     *
     * @param mixed         $value      Value to dump.
     * @param int           $level      Indentation level to use.
     * @param array<string> $parentKeys Optional. Array of parent keys.
     * @return string Dump of the provided variable.
     */
    public function dump($value, $level, $parentKeys = [])
    {
        if ($this->dumper === null) {
            $this->dumper = new NetteDumper();
        }

        $extraIndentation = str_pad('', $level * 4, ' ');

        if (is_array($value)) {
            if (count($value) === 0) {
                return "[]";
            }

            $line = '';
            foreach ($value as $subKey => $subValue) {
                $line .= "{$extraIndentation}    ";

                if (is_string($subKey)) {
                    $line .= "{$this->dumpWithKey($subKey, $subValue, $level + 1, $parentKeys)},\n";
                } else {
                    $line .= "{$this->dump($subValue, $level + 1, $parentKeys)},\n";
                }
            }

            return "[\n" . $line . "{$extraIndentation}]";
        }

        return "{$this->getValueString($value)}";
    }

    /**
     * Dump a key-value pair so it can be used for code generation.
     *
     * @param string        $key        Key to dump.
     * @param mixed         $value      Value to dump.
     * @param int           $level      Indentation level to use.
     * @param array<string> $parentKeys Optional. Array of parent keys.
     * @return string Dump of the provided variable.
     */
    public function dumpWithKey($key, $value, $level, $parentKeys = [])
    {
        if (is_string($value) && strpos($value, '[\'') === 0) {
            $json = json_decode(str_replace('\'', '"', $value), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $json;
            }
        }

        $value = $this->replaceConstants($key, $value, $parentKeys);

        if (is_string($key)) {
            array_unshift($parentKeys, $key);
        }

        if (in_array($key, self::SPEC_RULE_KEYS, true)) {
            $key = "SpecRule::{$this->getConstantName($key)}";
        }

        return "{$this->getValueString($key)} => {$this->dump($value, $level, $parentKeys)}";
    }

    /**
     * Get the string representation of a value.
     *
     * This takes into account the optional callback that might have been passed in.
     *
     * @param mixed $value Value to get the string representation of.
     * @return string String representation of the value.
     */
    private function getValueString($value)
    {
        if ($this->dumper === null) {
            $this->dumper = new NetteDumper();
        }

        $valueString = $this->filterValueStrings($value);

        if ($valueString === false) {
            $valueString = $this->dumper->dump($value);
        }

        return $valueString;
    }

    /**
     * Filtering callback to use for ensuring constants are not put between quotes.
     *
     * @param mixed $value Value to filter.
     * @return string|false String to use, or false if fallback to the regular variable dumper should be used.
     */
    public function filterValueStrings($value)
    {
        if (!is_string($value)) {
            return false;
        }

        if (
            strpos($value, 'AtRule::') === 0
            ||
            strpos($value, 'Attribute::') === 0
            ||
            strpos($value, 'AttributeList::') === 0
            ||
            strpos($value, 'AttributeList\\') === 0
            ||
            strpos($value, 'CssRuleset::') === 0
            ||
            strpos($value, 'CssRuleset\\') === 0
            ||
            strpos($value, 'DocRuleset::') === 0
            ||
            strpos($value, 'DocRuleset\\') === 0
            ||
            strpos($value, 'DeclarationList::') === 0
            ||
            strpos($value, 'DeclarationList\\') === 0
            ||
            strpos($value, 'DescendantTagList::') === 0
            ||
            strpos($value, 'DescendantTagList\\') === 0
            ||
            strpos($value, 'Element::') === 0
            ||
            strpos($value, 'Error::') === 0
            ||
            strpos($value, 'Error\\') === 0
            ||
            strpos($value, 'ErrorCode::') === 0
            ||
            strpos($value, 'Extension::') === 0
            ||
            strpos($value, 'Format::') === 0
            ||
            strpos($value, 'Internal::') === 0
            ||
            strpos($value, 'Layout::') === 0
            ||
            strpos($value, 'Protocol::') === 0
            ||
            strpos($value, 'SpecRule::') === 0
            ||
            strpos($value, 'self::') === 0
            ||
            strpos($value, 'static::') === 0
            ||
            strpos($value, '::class') !== false
            ||
            strpos($value, '::ID') !== false
        ) {
            return $value;
        }

        return false;
    }

    private function replaceConstants($specRule, $value, $parentKeys)
    {
        if (!is_string($value) && !is_array($value)) {
            return $value;
        }

        switch ($specRule) {
            case 'alsoRequiresAttr':
            case 'alternativeNames':
            case 'disabledBy':
            case 'enabledBy':
            case 'mandatoryAnyof':
            case 'mandatoryOneof':
                $attributes = [];
                foreach ($value as $attribute) {
                    if (is_string($attribute) && strpos($attribute, '[') !== 0) {
                        $attribute = $this->getAttributeConstant($this->getConstantName($attribute));
                    }
                    $attributes[] = $attribute;
                }
                return $attributes;
            case 'ampLayout':
                if (array_key_exists('supportedLayouts', $value)) {
                    foreach ($value['supportedLayouts'] as $index => $layout) {
                        $value['supportedLayouts'][$index] = $this->getLayoutConstant($this->getConstantName($layout));
                    }
                }
                return $value;
            case 'attrLists':
                $attributeLists = [];
                foreach ($value as $attributeList) {
                    $className = $this->getClassNameFromId($attributeList);
                    $attributeLists[] = "AttributeList\\{$className}::ID";
                }
                return $attributeLists;
            case 'attrs':
                $attributes = [];
                foreach ($value as $name => $specRules) {
                    $key = $name;
                    if (strpos($name, '[') !== 0) {
                        $key = $this->getAttributeConstant($this->getConstantName($name));
                    }
                    $attributes[$key] = $specRules;
                }
                return $attributes;
            case 'declarationList':
                $declarationLists = [];
                foreach ($value as $declarationList) {
                    $className = $this->getClassNameFromId($declarationList);
                    $declarationLists[] = "DeclarationList\\{$className}::ID";
                }
                return $declarationLists;
            case 'descendantTagList':
                $className = $this->getClassNameFromId($value);
                return "DescendantTagList\\{$className}::ID";
            case 'htmlFormat':
                $formats = [];
                foreach ($value as $format) {
                    $formats[] = $this->getFormatConstant($this->getConstantName($format));
                }
                return $formats;
            case 'name':
                if (! is_string($value) || empty($parentKeys[0])) {
                    return $value;
                }
                if ($parentKeys[0] === 'atRuleSpec') {
                    return $this->getAtRuleConstant($this->getConstantName($value));
                }
                if (
                    $parentKeys[0] !== 'extensionSpec'
                    && $parentKeys[0] !== 'properties'
                    && strpos($value, '[') !== 0
                ) {
                    return $this->getAttributeConstant($this->getConstantName($value));
                }
                return $value;
            case 'protocol':
                $protocols = [];
                foreach ($value as $protocol) {
                    $protocols[] = $this->getProtocolConstant($this->getConstantName($protocol));
                }
                return $protocols;
            case 'mandatoryAncestor':
            case 'mandatoryAncestorSuggestedAlternative':
            case 'mandatoryParent':
            case 'tagName':
                $constant = $this->getTagConstant($this->getConstantName($value));
                if (strpos($value, '$') !== 0 && strpos($value, ' ') === false) {
                    return $constant;
                }
                return $value;
            case 'requiresExtension':
                $extensions = [];
                foreach ($value as $extension) {
                    $extensions[] = $this->getTagConstant($this->getConstantName($extension));
                }
                return $extensions;
            case 'valueCasei':
                if (!empty($parentKeys[0]) && $parentKeys[0] === 'i-amphtml-layout') {
                    foreach ($value as $index => $layout) {
                        $constant = $this->getLayoutConstant($this->getConstantName($layout));
                        if (defined("AmpProject\\{$constant}")) {
                            $value[$index] = $constant;
                        }
                    }
                }
                return $value;
            default:
                return $value;
        }
    }
}
