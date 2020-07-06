<?php

namespace App\Observers;

use App\User;
use App\Section;

class SectionObserver
{
    /**
     * Handle the section "deleted" event.
     *
     * @param  \App\Section  $section
     * @return void
     */
    public function deleted(Section $section)
    {
        foreach (User::all() as $user) {
            $this->nullifyDeletedSectionId($user, $section);
        }
    }

    protected function nullifyDeletedSectionId(User $user, Section $section)
    {
        if ($user->section_id === $section->id) {
            $user->update([
                'section_id' => null
            ]);
        }
    }
}
