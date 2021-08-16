<?php

namespace App\Classes\Assessments;

use App\Assessment;
use App\ContentBuilder;
use App\ContentBuilderType;

class DestroyAssessment
{
    protected $assessment;

    public function __construct(Assessment $assessment)
    {
        $this->assessment = $assessment;
    }

    public function destroy()
    {
        $this->removeParticipantsAndInstructors();

        $this->deletePageContents();

        $this->removePages();

        $this->assessment->delete();
    }

    protected function removeParticipantsAndInstructors()
    {
        $this->assessment->editors()->detach();

        $this->assessment->participants()->detach();
    }

    protected function removePages ()
    {
        $this->assessment->pages->each->delete();
    }

    protected function deletePageContents()
    {
        foreach ($this->assessment->pages as $page) {
            $assessmentPageContents = $page->assessmentPageContents;

            $assessmentPageContents->each(function ($contentItem) {
                $contentItem->assessmentPageContentItems->each(function ($item) {
                    if ($item->type === 'ContentBuilder') {
                        $contentBuilder = ContentBuilder::find($item->model_id);
        
                        $contentBuilder->parts->each(function ($part) {
                            $type = ContentBuilderType::find($part->content_builder_type_id)->type;
        
                            $typeClassName = 'App\\' . ucfirst($type) . 'Part';
        
                            $partType = $typeClassName::wherePartId($part->id)->first();
        
                            $destroyClassName = 'App\Classes\ContentTypes\Destroy' . ucfirst($type);
        
                            (new $destroyClassName($partType))->delete();
        
                            $part->delete();
                        });
        
                        $contentBuilder->delete();
                    }
                });
            });

            $assessmentPageContents->each(function ($content) {
                $content->assessmentPageContentItems->each->delete();
            });

            $assessmentPageContents->each->delete();
        }
    }
}