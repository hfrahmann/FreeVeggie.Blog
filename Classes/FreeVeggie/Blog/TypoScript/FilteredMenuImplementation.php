<?php
namespace FreeVeggie\Blog\TypoScript;

use TYPO3\Neos\TypoScript\MenuImplementation;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TypoScript\Exception as TypoScriptException;


class FilteredMenuImplementation extends MenuImplementation
{
    /**
     * @return int
     */
    public function getNumberOfItems() {
        $numberOfItems = $this->tsValue('numberOfItems');
        if ($numberOfItems > 0 == FALSE) {
            $numberOfItems = 5;
        }
        return $numberOfItems;
    }

    /**
     * @return array limited Items
     */
    protected function buildItems() {
        $items = parent::buildItems();

        $itemCollectionCount = count($this->getItemCollection());
        if($itemCollectionCount == 0) {
            $items = array_slice($items, 0, $this->getNumberOfItems());
        }

        return $items;
    }

}

?>