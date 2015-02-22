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
        $content = '';

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


        if (strlen($stringToTruncate) > $maxTextLength) {
            $content = substr($this->stripUnwantedTags($stringToTruncate), 0, $maxTextLength);
        } else {
            $content = $this->stripUnwantedTags($stringToTruncate);
        }

        if (strlen($stringToTruncate) > $maxTextLength) {
            $content .= ' ...';
        }
        if (substr($content, -4, 4) != '</p>') {
            $content .= '</p>';
        }

        return $content;
    }

    /**
     * If the content starts with <p> and ends with </p> these tags are stripped.
     *
     * @param string $content The original content
     * @return string The stripped content
     */
    protected function stripUnwantedTags($content) {
        $content = strip_tags($content, '<p></p>');
        $content = str_replace('&nbsp;', ' ', $content);

        return trim($content);
    }
}

?>