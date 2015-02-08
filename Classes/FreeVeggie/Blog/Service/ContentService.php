<?php
namespace FreeVeggie\Blog\Service;

/*                                                                        *
 * This script belongs to the FLOW3 package "Blog".                      *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * A service which can render specific views of blog related content
 *
 * @Flow\Scope("singleton")
 */
class ContentService {

    const TEASER_MAX_LENGTH = 400;

    /**
     * Renders the given Node as a teaser text with up to 600 characters, with all <p> and <a> tags removed.
     *
     * @param NodeInterface $node
     * @param string $maxTextLength
     * @return mixed
     */
    public function renderTeaser(NodeInterface $node, $maxTextLength = self::TEASER_MAX_LENGTH) {
        $stringToTruncate = '';

        if($maxTextLength <= 0) {
            $maxTextLength = self::TEASER_MAX_LENGTH;
        }

        foreach ($node->getNode('main')->getChildNodes('TYPO3.Neos.NodeTypes:Text') as $contentNode) {
            foreach ($contentNode->getProperties() as $propertyValue) {
                if (!is_object($propertyValue) || method_exists($propertyValue, '__toString')) {
                    $stringToTruncate .= $propertyValue;
                }
            }
        }

        /*
        $jumpPosition = strpos($stringToTruncate, '</p>');
        if ($jumpPosition !== FALSE && $jumpPosition < self::TEASER_MAX_LENGTH) {
            return $this->stripUnwantedTags(substr($stringToTruncate, 0, $jumpPosition + 4));
        }
        */

        if (strlen($stringToTruncate) > $maxTextLength) {
            return substr($this->stripUnwantedTags($stringToTruncate), 0, $maxTextLength+1) . ' ...';
        } else {
            return $this->stripUnwantedTags($stringToTruncate);
        }

    }

    /**
     * If the content starts with <p> and ends with </p> these tags are stripped.
     *
     * @param string $content The original content
     * @return string The stripped content
     */
    protected function stripUnwantedTags($content) {
        $content = trim($content);
        $content = preg_replace(array('/\\<a [^\\>]+\\>/', '/\<\\/a\\>/', '/\\<span style[^\\>]+\\>/'), '', $content);
        $content = preg_replace(array('/\\<h1 [^\\>]+\\>/', '/\<\\/a\\>/', '/\\<span style[^\\>]+\\>/'), '', $content);
        $content = preg_replace(array('/\\<h2 [^\\>]+\\>/', '/\<\\/a\\>/', '/\\<span style[^\\>]+\\>/'), '', $content);
        $content = preg_replace(array('/\\<h3 [^\\>]+\\>/', '/\<\\/a\\>/', '/\\<span style[^\\>]+\\>/'), '', $content);
        $content = str_replace('&nbsp;', ' ', $content);

        if (substr($content, 0, 3) === '<p>' && substr($content, -4, 4) === '</p>') {
            $content = substr($content, 3, -4);
        }
        return trim($content);
    }
}

?>