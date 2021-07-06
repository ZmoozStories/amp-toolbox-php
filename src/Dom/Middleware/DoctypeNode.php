<?php

namespace AmpProject\Dom\Middleware;

use AmpProject\Dom\Document;

/**
 * Middleware to secure and restore the doctype node.
 *
 * @package ampproject/amp-toolbox
 */
class DoctypeNode extends BaseDocumentMiddleware
{

    /**
     * Whether we had secured a doctype that needs restoring or not.
     *
     * This is an int as it receives the $count from the preg_replace().
     *
     * @var int
     */
    private $securedDoctype = 0;

    /**
     * Secure the original doctype node.
     *
     * We need to keep elements around that were prepended to the doctype, like comment node used for source-tracking.
     * As DOM_Document prepends a new doctype node and removes the old one if the first element is not the doctype, we
     * need to ensure the original one is not stripped (by changing its node type) and restore it later on.
     *
     * @param string $html HTML string to adapt.
     * @return string Adapted HTML string.
     */
    public function beforeLoad($html)
    {
        return preg_replace(
            Document::HTML_SECURE_DOCTYPE_IF_NOT_FIRST_PATTERN,
            '\1!--amp-\3\4-->',
            $html,
            1,
            $this->securedDoctype
        );
    }

    /**
     * Restore the original doctype node.
     *
     * @param string $html HTML string to adapt.
     * @return string Adapted HTML string.
     */
    public function afterSave($html)
    {
        if (! $this->securedDoctype) {
            return $html;
        }

        return preg_replace(Document::HTML_RESTORE_DOCTYPE_PATTERN, '\1!\3\4>', $html, 1);
    }
}
