<?php

return [
    'title' => 'Configuraciones Generales',
    'navigation_label' => 'Configuraciones Generales',
    'label_plural' => 'Configuraciones Generales',
    'label_singular' => 'Configuración General',
    'navigation_group' => 'Configuraciones',
    'create_button' => 'Crear Configuración',
    'edit_button' => 'Editar Configuración',
    'delete_button' => 'Eliminar Configuración',
    'save_button' => 'Guardar',
    'cancel_button' => 'Cancelar',
    'success_created' => 'Configuración creada exitosamente',
    'success_updated' => 'Configuración actualizada exitosamente',
    'success_deleted' => 'Configuración eliminada exitosamente',
    'confirm_delete' => '¿Está seguro que desea eliminar esta configuración?',
    'fields' => [
        'name' => 'Nombre',
        'value' => 'Valor',
        'description' => 'Descripción',
        'type' => 'Tipo',
        'created_at' => 'Creado el',
        'updated_at' => 'Actualizado el',
    ],
    'placeholders' => [
        'name' => 'Ingrese el nombre de la configuración',
        'value' => 'Ingrese el valor de la configuración',
        'description' => 'Ingrese la descripción de la configuración',
        'type' => 'Seleccione el tipo de configuración',
    ],
    'validation' => [
        'name_required' => 'El campo nombre es obligatorio',
        'name_unique' => 'El nombre ya ha sido tomado',
        'value_required' => 'El campo valor es obligatorio',
        'type_required' => 'El campo tipo es obligatorio',
    ],
];