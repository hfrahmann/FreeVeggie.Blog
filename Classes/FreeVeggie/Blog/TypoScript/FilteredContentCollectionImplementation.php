<?php
namespace FreeVeggie\Blog\TypoScript;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "FreeVeggie.Blog".       *
 */
use TYPO3\Neos\TypoScript\ContentCollectionImplementation;

/**
 * TypoScript implementation to render ContentCollections. Will render needed
 * metadata for removed nodes.
 */
class FilteredContentCollectionImplementation extends ContentCollectionImplementation {

    /**
     * @return string
     */
    public function getFilter()
    {
        return $this->tsValue('filter');
    }

    /**
     * @return array
     */
    public function getCollection() {
        $contentCollectionNode = $this->getContentCollectionNode();
        if ($contentCollectionNode === NULL) {
            return array();
        }

        if ($contentCollectionNode->getContext()->getWorkspaceName() === 'live') {
            return $contentCollectionNode->getChildNodes($this->getFilter());
        }

        return array_merge($contentCollectionNode->getChildNodes($this->getFilter()), $this->getRemovedChildNodes());
    }

}
