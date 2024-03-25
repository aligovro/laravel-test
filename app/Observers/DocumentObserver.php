<?php

namespace App\Observers;

use App\Models\Document;
use App\Notifications\DocumentChange;
use Illuminate\Support\Facades\Notification;

class DocumentObserver
{
    /**
     * Handle the Document "created" event.
     */
    public function created(Document $document): void
    {
        //
    }

    /**
     * Handle the Document "updated" event.
     */
    public function updating(Document $document): void
    {
        if ($document->isDirty(['name', 'content'])) {
            $this->updatePreviousVersions($document);
            Notification::send($document->users, new DocumentChange($document, 'updated'));
        }
    }

    /**
     * Handle the Document "deleted" event.
     */
    public function deleting(Document $document): void
    {
        $this->updatePreviousVersions($document);
        Notification::send($document->users, new DocumentChange($document, 'deleted'));
    }

    /**
     * Handle the Document "restored" event.
     */
    public function restored(Document $document): void
    {
        //
    }

    /**
     * Handle the Document "force deleted" event.
     */
    public function forceDeleted(Document $document): void
    {
        //
    }

    private function updatePreviousVersions(Document $document): void
    {
        $previousVersions = $document->previous_versions ?? [];
        $previousVersions[] = [
            'name' => $document->getOriginal('name'),
            'content' => $document->getOriginal('content'),
            'created_at' => now()->toDateTimeString(),
        ];
        $document->update(['previous_versions' => $previousVersions]);
    }
}
