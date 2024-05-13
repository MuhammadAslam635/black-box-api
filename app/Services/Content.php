<?php

namespace App\Services;

use App\Models\Content as ContentModel;

class Content
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        // Initialize any dependencies or configurations here if needed.
    }

    /**
     * Create a new content item in the system.
     *
     * @param array $data The data used to create a new content item.
     * @return ContentModel The newly created content instance.
     */
    public function create(array $data): ContentModel
    {
        // Validate or sanitize the input data if necessary.

        // Create and return the new content instance.
        return ContentModel::create([
            'user_id' => $data['user_id'] ?? null,
            'type' => $data['type'] ?? null,
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'file_path' => $data['file_path'] ?? null,
        ]);
    }
    public function edit(int $id, array $data): ?ContentModel
    {
        $content = ContentModel::find($id);

        if ($content) {
            $content->update([
                'user_id' => $data['user_id'] ?? $content->user_id,
                'type' => $data['type'] ?? $content->type,
                'title' => $data['title'] ?? $content->title,
                'description' => $data['description'] ?? $content->description,
                'file_path' => $data['file_path'] ?? $content->file_path,
            ]);
        }

        return $content;
    }
    public function delete(int $id): bool
    {
        $content = ContentModel::find($id);

        if ($content) {
            return $content->delete();
        }

        return false;
    }
}
