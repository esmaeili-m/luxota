<?php

namespace App\Traits;

trait LogsRelations
{
    /**
     * Return array of changed related fields (like city_id, rank_id)
     * with readable old/new values.
     */
    public function getRelatedChanges(string $eventName = null): array
    {
        if (!property_exists($this, 'loggableRelations')) {
            return [];
        }

        $changes = [];

        foreach ($this->loggableRelations as $field => $relationInfo) {
            $relatedModel = $relationInfo['model'];
            $label = $relationInfo['label'] ?? $field;
            $displayField = $relationInfo['key'] ?? 'id';

            $oldId = $this->getOriginal($field); // قبل از تغییر
            $newId = $this->{$field};            // بعد از تغییر

            // اگر عملیات "create" بود، همه رو بررسی کن
            $shouldLog = $eventName === 'created' || $this->isDirty($field);

            if (!$shouldLog) {
                continue;
            }

            $old = $oldId ? optional($relatedModel::find($oldId))->{$displayField} : null;
            $new = $newId ? optional($relatedModel::find($newId))->{$displayField} : null;

            if ($old !== $new) {
                $changes[$label] = [
                    'old' => $old,
                    'new' => $new,
                ];
            }
        }

        return $changes;
    }
}
