<?php

return [
    'title' => 'General Settings',
    'navigation_label' => 'General Settings',
    'label_plural' => 'General Settings',
    'label_singular' => 'General Setting',
    'navigation_group' => 'Settings',
    'create_button' => 'Create Setting',
    'edit_button' => 'Edit Setting',
    'delete_button' => 'Delete Setting',
    'save_button' => 'Save',
    'cancel_button' => 'Cancel',
    'success_created' => 'Setting created successfully',
    'success_updated' => 'Setting updated successfully',
    'success_deleted' => 'Setting deleted successfully',
    'confirm_delete' => 'Are you sure you want to delete this setting?',
    'fields' => [
        'name' => 'Name',
        'value' => 'Value',
        'description' => 'Description',
        'type' => 'Type',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
    ],
    'placeholders' => [
        'name' => 'Enter setting name',
        'value' => 'Enter setting value',
        'description' => 'Enter setting description',
        'type' => 'Select setting type',
    ],
    'validation' => [
        'name_required' => 'The name field is required',
        'name_unique' => 'The name has already been taken',
        'value_required' => 'The value field is required',
        'type_required' => 'The type field is required',
    ],
];
