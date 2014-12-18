<?php
namespace FreeVeggie\Blog\TypoScript;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "FreeVeggie.Blog".       *
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TypoScript\Exception as TypoScriptException;
use TYPO3\Eel\FlowQuery\FlowQuery as FlowQuery;
use TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation;

/**
 *
 */
class ListImplementation extends TemplateImplementation {

    /**
     * An internal cache for the built list items array.
     *
     * @var array
     */
    protected $items;

    /**
     * Internal cache for the rootDocument tsValue.
     *
     * @var FlowQuery
     */
    protected $listRootDocumentQuery;

    /**
     * @return FlowQuery
     */
    public function getListRootDocumentQuery() {
        if($this->listRootDocumentQuery == NULL) {
            $this->listRootDocumentQuery = $this->tsValue('listRootDocumentQuery');
        }
        return $this->listRootDocumentQuery;
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
     * @return array
     */
    public function getItems() {
        if ($this->items === NULL) {
            $this->items = $this->buildItems();
        }

        return $this->items;
    }

    /**
     * @return array
     */
    protected function buildItems() {
        $query =  $this->getListRootDocumentQuery();
        $query = $query->children('[instanceof '.$this->getFilter().']');
        $query = $query->sort('publishDate', 'DESC');
        $result = $query->get();
        return array($this->getFilter(), $result);
    }

}

?>