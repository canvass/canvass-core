# Canvass
A Form-Building library for PHP.

## Installation
You can install this package with composer:
```bash
composer require canvass/canvass-core
```

## Concepts and Terms
There are several concepts to understand to fully use Canvass.

### Form

### Field
#### Attributes

### NestedField

### FieldData
#### FieldDataRetrievable

### FieldType
#### Type
#### General Type

### Action

### Forge

### Validation
#### Validate
#### ValidationMap
#### ValidateFieldAction

### CanvassPaint


## Extending Canvass
You can add new Field types to extend Canvass.

### Adding a new Field
- Add a folder that will have FieldData, FieldType and Validation files
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

We'll add it right into the Laravel app at: 
```
laravel-dir/app/Canvass/Fields/DatabaseSelect/
    FieldData.php
    FieldType.php
    Validation.php
```

The three files might look like this:

#### FieldData.php
If we don't create a FieldData class, Canvass will fallback to the \Canvass\Support\FieldData class. But in this case, we need to specify extra logic for retrieving data using the FieldDataRetrievable interface.

```php
<?php

namespace App\Canvass\Fields\DatabaseSelect;

use \Canvass\Contract\FieldDataRetrievable;

final class FieldData extends \Canvass\Support\FieldData
    implements FieldDataRetrievable
{
    /*
     * Implementing FieldDataRetrievable allows us to pull in
     * any extra data needed before rendering the field
     */
    public function retrieveAdditionalData(): FieldDataRetrievable
    {
        $field = $this->getField();
        
        $form = $field->getFormModel();
        
        // Get the db rows filtered by this form's owner id
        $rows = \App\Retrievable::where(
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
The validate class ensures that the field has the correct data by generating the proper validation rules that your web-app framework will use to validate the supplied data.

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
