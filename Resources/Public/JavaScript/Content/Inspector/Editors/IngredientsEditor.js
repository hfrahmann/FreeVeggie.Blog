define(
[
    'emberjs',
    'Library/jquery-with-dependencies',
    'Shared/Utility',
    'text!./IngredientsEditor.html',
    'text!./IngredientElement.html'
],
function (Ember, $, Utility, template, templateIngredient) {
    return Ember.View.extend({
        template: Ember.Handlebars.compile(template),
        ingredients: [],
        ingredientsCount: 0,

        didInsertElement: function() {
            this._super();
            this._deserializeValue();
        },

        ingredientsView: Ember.CollectionView.extend({
            tagName: 'div',
            content: [],

            init: function () {
                this._super();
                this.set('ingredients', Ember.A());
            },

            itemViewClass: Ember.View.extend({
                tagName: 'div',
                classNames: ['fv-inspector-ingredient'],
                template: Ember.Handlebars.compile(templateIngredient),
                remove: function() {
                    this.get('_parentView._parentView').removeIngredient(this.get('content'));
                },

                watchName: function(){
                    this.get('_parentView._parentView')._updateValue();
                }.observes('content.name'),
                watchAmount: function(){
                    this.get('_parentView._parentView')._updateValue();
                }.observes('content.amount'),
                watchUnit: function(){
                    this.get('_parentView._parentView')._updateValue();
                }.observes('content.unit')
            }),

            emptyView: Ember.View.extend({
                tagName: 'div',
                classNames: ['fv-ember-noingredients'],
                template: Ember.Handlebars.compile('No ingredients')
            })

        }),

        addIngredient: function () {
            newIngredient = {"identifier": 0, "name": "", "amount": "", "unit": ""};
            this._addIngredientObject(newIngredient);
            this._updateValue();
        },

        removeIngredient: function(ingredient) {
            ingredientToRemove = this.get('ingredients').findProperty('identifier', ingredient.identifier);
            this.get('ingredients').removeObject(ingredientToRemove);
            this._updateValue();
        },

        _addIngredientObject: function (ingredient) {
            ingredientsCount = this.get('ingredientsCount');
            ingredientsCount += 1;
            this.set('ingredientsCount', ingredientsCount);

            ingredient.identifier = ingredientsCount;

            this.get('ingredients').pushObject(ingredient);
        },

        _updateValue: function() {
            value = JSON.stringify(this.get('ingredients'));
            this.set('value', value);
        },

        _deserializeValue: function() {
            var value = this.get('value');

            if (!value || !Utility.isValidJsonString(value)) {
                return;
            }

            this.set('ingredients', Ember.A());

            ingredients = $.parseJSON(value);

            for(i=0;i<ingredients.length;i++) {
                this._addIngredientObject(ingredients[i]);
            }
        }
    })
});