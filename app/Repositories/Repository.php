<?php

namespace App\Repositories;

use Carbon\Carbon;

class Repository
{
    public function hydrateEntity(string $entityName, $data)
    {
        $entityName = sprintf('App\Entities\%s', $entityName);
        $entity     = new $entityName();
        foreach ($data as $fieldName => $datum) {
            $methodName = sprintf('set%s', str_replace('_', '', ucwords($fieldName, '_')));
            if (method_exists($entity, $methodName)) {
                if (in_array($fieldName, ['created_at', 'updated_at'])) {
                    $datum = new Carbon($datum);
                }
                $entity->$methodName($datum);
            }
        }

        return $entity;
    }
}
