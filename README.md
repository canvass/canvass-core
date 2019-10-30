# Canvass Core
A Form-Building library for PHP.

## Installation
You can install this package with composer:
```bash
composer require canvass/canvass-core
```

## Concepts and Terms
There are several concepts to understand to fully use Canvass.

### Form
The purpose of Canvass is to be able to visually create an html `<form>`. The form record handles the `<form>` element and a little bit more. Inside the form are fields.

### Field
A field represents most of the elements within a form. It includes more-obvious elements like `<input>`, `<select>` and `<fieldset>`, but it also includes other elements such as dividers (`<hr>`) and columns.

#### NestedField
Some fields can only nest inside other fields (ex. `<option>` nests under a `<select>`). Other field types can be both top-level and nested fields (just about any field can nest inside a column).

### FieldData
The form fields database table has columns for such data points as
label, html id, html classes, name and value attributes, etc.... Not every field type uses all of the fields, but the main columns represent the typical data needed for a field.

#### Attributes
But several fields have extra attributes that can be used (ex. `<input type="number">` can have extra attributes like required, min, max and step). These type of attributes are stored as json in the attributes column. 

### FieldType
Each field has a specific type (just referred to as type) and a general type. The general type tends to correspond to the appropriate html element that would respresent the field (ex. a text field's type is "text" and its general type is "input"). Many of the fields have the same specific and general type.

### Action
Instead of controllers to add/manipulate form and field data, Canvass uses actions that can be integrated into web frameworks as needed. There are actions for listing (Index), adding (Create/Store), updating (Edit/Update), resorting (Move Up/Down), as well as for deleting and validating.

### Forge
Canvass is meant to be flexible and able to be integrated into any framework. But that requires abstractions and deferring of details. Instead of sprinkling the library with deferring details, most of it is located in the Forge class. The Forge class allows the developer to set concrete implementations of the interfaces that Canvass uses.

Below is example code for using Canvass\Forge in the Laravel framework:
```php
\Canvass\Forge::setFormClosure(static function () {
    return new \CanvassLaravel\Model\Form();
});

\Canvass\Forge::setRequestDataClosure(
    static function (array $fields = null) {
        if (null === $fields) {
            return request()->all();
        }

        return request()->only($fields);
    }
);

\Canvass\Forge::setLoggerClosure(static function (\Throwable $e) {
    \Log::error($e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
});
```

Canvass then takes advantage of the concrete implementations by calling methods on Forge:

```php
// Would return new \CanvassLaravel\Model\Form()
$form = \Canvass\Forge::form();

//or
$request_data = \Canvass\Forge::requestData(['label', 'classes']);
```

### Validation
A form's data is only as good as its validation, so Canvass has several mechanisms for creating validation rules, some used in building the forms/fields and some for handling form submissions.

*Please note: you must supply a validation library in order to use Canvass.*

#### Validate
The `Validate` interface is the way that Canvass can use the dev-supplied validation library to validate submitted data.

#### ValidationMap
The validation rules that Canvass uses might not match up with your framework's/library's validation library. Implementing the `ValidationMap` allows you to convert Canvass rules to whatever format is necessary for validation.

#### ValidateFieldAction
The `ValidateFieldAction` interface is used on the form building side to ensure that the specific field type's data is valid before database insertion.

### CanvassPaint
Canvass Core is agnostic when it comes to actually creating the form html since it doesn't know what the developer uses for html rendering. A developer can use [CanvassPaint](https://github.com/canvass/canvass-paint) to create the html by supplying an appropriate renderer.

There are two Paint libraries currently supported by the Canvass team: [CanvassPaint\Blade](https://github.com/canvass/canvass-paint-blade) and [CanvassPaint\Twig](https://github.com/canvass/canvass-paint-twig).

But new Paint libraries can be made by implementing and using the `RenderFunction` interface and using it in `\CanvassPaint\Action\RenderForm`. The Blade and Twig libraries can help guide the implementer on how to set up the various field views.

## Extending Canvass
You can add new Field types to extend Canvass.

### Adding a new Field
- Add a folder that will have FieldData, FieldType and Validate files
- Add the parent path to Forge:
```php
\Canvass\Forge::addFieldPath('/the/file/path', '\The\Namespace\Path');
```
- Add a view file for the type in the appropriate folder:
 ```
 views/vendor/canvass/form_field/partials/types
```

### Example Extension
Let's build a select field, DatabaseSelect, with a set of options coming from a database connection. The database has a simple table called, retrievables, with id, owner_id, and name columns.

We'll add three files to a folder labelled as DatabaseSelect: 
```
App/Canvass/Fields/DatabaseSelect/
    FieldData.php
    FieldType.php
    Validate.php
```

The three files might look like this:

#### FieldData.php
If we don't create a FieldData class, Canvass will fall back to the \Canvass\Support\FieldData class. But in this case, we need to specify extra logic for retrieving data using the FieldDataRetrievable interface.

```php
<?php

namespace App\Canvass\Fields\DatabaseSelect;

use \Canvass\Contract\FieldDataRetrievable;

final class FieldData extends \Canvass\Support\FieldData
    implements FieldDataRetrievable
{
    /*
     * Implementing FieldDataRetrievable::retrieveAdditionalData()
     * allows us to pull in any extra data needed before rendering the field.
     */
    public function retrieveAdditionalData(): FieldDataRetrievable
    {
        $field = $this->getField();
        
        $form = $field->getFormModel();
        
        // Get the db rows filtered by this form's owner id
        $model = new Retrievable();
        
        $rows = $model->where(
            'owner_id',
            $form->getData('owner_id')
        )->get();

        /*
         *  We'll need to add nested <option> fields to the select
         *  based on the rows retrieved. So let's loop through the
         *  rows and create the appropriate data structures.
         */
        foreach ($rows as $index => $row) {
            $form_field = \Canvass\Forge::field();

            $form_field->setFormModel($form);

            $data = [
                'label' => $row->name,
                'value' => $row->id,
                'type' => 'option',
                'general_type' => 'option',
                'sort' => $index + 1,
            ];

            foreach ($data as $key => $value) {
                $form_field->setData($key, $value);
            }

            $form_field->setData('form_id', $field->getData('form_id'));

            $form_field->setData('parent_id', $field->getId());

            $this->addNestedField($form_field);
        }

        return $this;
    }
}
```

#### FieldType.php
The field type class allows us to specify what should be used as the field's type and general type. The general type should match up with an existing general type in order to render.

```php
<?php

namespace App\Canvass\Fields\DatabaseSelect;

final class FieldType implements \Canvass\Contract\FieldType
{
    public function getType(): string
    {
        return 'database-select';
    }

    public function getGeneralType(): string
    {
        return 'select';
    }
}
```

#### Validation.php
The validate class ensures that the field has the correct data by generating the proper validation rules that your framework/library will use to validate the supplied data.

```php
<?php

namespace App\Canvass\Fields\DatabaseSelect;

use Canvass\Contract\FieldData;
use Canvass\Field\AbstractField\AbstractValidateFieldAction;
use Canvass\Support\Validation\Builder;

final class Validate extends AbstractValidateFieldAction
{
    protected $attributes_validation_rules = [
        'required' => ['required' => false,],
    ];

    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    )
    {
        $builder = Builder::start()
            ->required($field->hasAttribute('required'));

        foreach ($field['children'] as $child) {
            $builder->addOneOf($child['value']);
        }

        $rules[$field['name']] = [
            'field' => $field,
            'rules' => $builder->build()
        ];
    }

    public function getDataColumnsMatchedWithRequiredBoolean(): array
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
            'wrap_classes' => false,
            'classes' => false,
        ];
    }

    public function convertAttributesData($attributes): array
    {
        $return = [];

        if (! empty($attributes['required'])) {
            $return['required'] = 'required';
        }

        return $return;
    }
}
```
