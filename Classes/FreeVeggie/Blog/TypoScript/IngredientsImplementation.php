<?php
namespace FreeVeggie\Blog\TypoScript;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "FreeVeggie.Blog".       *
 */

use TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation;

/**
 *
 */
class IngredientsImplementation extends TemplateImplementation {


    /**
     * Generates a
     *
     * @return mixed
     */
    public function getIngredients() {
        $ingredientsJSON = $this->tsValue('ingredientsJSON');

        $ingredients = json_decode($ingredientsJSON, true);

        if(is_array($ingredients)) {
            return $ingredients;
        }
        return array();
    }

}

?>