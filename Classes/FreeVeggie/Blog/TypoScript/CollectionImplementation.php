<?php
namespace FreeVeggie\Blog\TypoScript;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "FreeVeggie.Blog".       *
 */

use TYPO3\Eel\FlowQuery\FlowQuery;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TypoScript\Exception as TypoScriptException;
use TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation;

/**
 *
 */
class CollectionImplementation extends TemplateImplementation {

    protected $defaultPageFilter = 'TYPO3.Neos:Document';

    /**
     * An internal cache for the built list items array.
     *
     * @var array
     */
    protected $items;

    /**
     * Internal cache for the listRootDocument tsValue.
     *
     * @var NodeInterface
     */
    protected $listRootDocument;

    /**
     * @return NodeInterface
     */
    public function getListRootDocument() {
        if($this->listRootDocument == NULL) {
            $this->listRootDocument = $this->tsValue('listRootDocument');
        }
        return $this->listRootDocument;
    }

    /**
     * NodeType filter for nodes displayed in menu
     *
     * @return string
     */
    public function getFilter() {
        $filter = $this->tsValue('filter');
        if ($filter === NULL) {
            $filter = 'TYPO3.Neos:Document';
        }
        return $filter;
    }

    /**
     * Main API method which sends the to-be-rendered data to Fluid
     *
     * @return NodeInterface[]
     */
    public function getItems() {
        if ($this->items === NULL) {
            if($this->getListRootDocument() != NULL) {
                $this->buildItems($this->getListRootDocument());
                $this->items = $this->sortItems($this->items);
            } else {
                $this->items = array();
            }
        }
        return $this->items;
    }

    /**
     * @param NodeInterface $node
     * @return void
     */
    protected function buildItems(NodeInterface $node) {
        $childNodes =  $node->getChildNodes($this->defaultPageFilter);
        /** @var NodeInterface $childNode */
        foreach($childNodes as $childNode) {
            if($this->canShowItem($childNode)) {
                $this->items[] = $childNode;
            }
            $this->buildItems($childNode);
        }
    }

    /**
     * @param NodeInterface $item
     * @return bool
     */
    protected function canShowItem(NodeInterface $item) {
        return ($item->isVisible() && $item->getNodeType()->getName() == $this->getFilter());
    }

    /**
     * @param NodeInterface[] $items
     * @return NodeInterface[]
     */
    protected function sortItems(array $items) {
        $itemsCopy = $items;
        usort($itemsCopy, function($itemA, $itemB) {
            $dateProperty = 'publishDate';

            if($itemA->hasProperty($dateProperty) == FALSE || $itemB->hasProperty($dateProperty) == FALSE) {
                return 0;
            }

            /** @var \Datetime $dateA */
            $dateA = $itemA->getProperty($dateProperty);
            /** @var \Datetime $dateB */
            $dateB = $itemB->getProperty($dateProperty);

            if($dateA->getTimestamp() == $dateB->getTimestamp()) {
                return 0;
            }
            return ($dateA->getTimestamp() < $dateB->getTimestamp()) ? 1 : -1;
        });
        return $itemsCopy;
    }

}

?>