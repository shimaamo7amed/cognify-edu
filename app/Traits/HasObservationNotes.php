<?php

namespace App\Traits;

use App\Models\ObservationCaseNote;

trait HasObservationNotes
{
    public $notes;
    public string $noteText = '';

    public function loadNotes(): void
    {
        $this->notes = ObservationCaseNote::with('author')
            ->where('observation_child_case_id', $this->requestCase->id)
            ->latest()
            ->get();
    }

    public function addNote(): void
    {
        $this->validate([
            'noteText' => 'required|string|min:3',
        ]);

        ObservationCaseNote::create([
            'observation_child_case_id' => $this->requestCase->id,
            'note' => $this->noteText,
            'author_id' => auth()->id(),
            'author_type' => auth()->user()::class,
        ]);

        $this->noteText = '';
        $this->loadNotes();
    }
}
