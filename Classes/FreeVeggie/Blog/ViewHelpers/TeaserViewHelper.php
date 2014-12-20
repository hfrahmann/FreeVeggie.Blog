<?php
namespace FreeVeggie\Blog\ViewHelpers;

/*                                                                        *
 * This script belongs to freeVeggie.Blog
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * This view helper crops the text of a blog post in a meaningful way.
 *
 * @api
 */
class TeaserViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * @Flow\Inject
     * @var \FreeVeggie\Blog\Service\ContentService
     */
    protected $contentService;

    /**
     * Render a teaser
     *
     * @param \TYPO3\TYPO3CR\Domain\Model\NodeInterface $node
     * @return string cropped text
     */
    public function render(NodeInterface $node) {
        return $this->contentService->renderTeaser($node);
    }
}


?>