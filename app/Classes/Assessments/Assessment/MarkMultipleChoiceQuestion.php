<?php

namespace App\Classes\Assessments\Assessment;

use App\MultipleChoiceQuestion;
use App\Classes\Assessments\Assessment\MarkQuestion;

class MarkMultipleChoiceQuestion extends MarkQuestion
{
    protected $allPossibleAnswers;

    public function mark()
    {
        $evalItems = $this->getEvalItems();

        $score = 0;
        
        if (is_int($evalItems['participantAnswerData'])) {
            $evalItems['participantAnswerData'] = [$evalItems['participantAnswerData']];
        }

        if (count($evalItems['participantAnswerData']) === 0) {
            $score = 0;
        } else {
            $score = $this->evaluateScore($evalItems);
        }

        if ($score < 0) {
            $score = 0;
        }
        
        $this->persistScore($score, null, null);
    }

    protected function evaluateScore($evalItems)
    {
        $score = 0;

        $numCorrectAnswers = array_filter($evalItems['allPossibleAnswers'], function ($answer) {
            return $answer['isCorrect'];
        });

        if (count($numCorrectAnswers) === 1) {
            if (array_search($evalItems['allPossibleAnswers'][0], $evalItems['participantAnswerData'])) {
                return $evalItems['questionScore'];
            }

            return 0;
        }

        foreach ($evalItems['allPossibleAnswers'] as $answer) {
            // This a correct answer which the participant has selected as one of their answers.
            if ($answer['isCorrect'] && in_array($answer['id'], $evalItems['participantAnswerData'])) {
                $score += $evalItems['scorePerAnswer'];
            } 
            // This is not a correct answer and the participant has NOT selected it as one of their answers.
            else if (!$answer['isCorrect'] && !in_array($answer['id'], $evalItems['participantAnswerData'])) {
                $score += $evalItems['scorePerAnswer'];
            } 
            // The remaining scenarios will lead to a deduction of marks.
            else {
                $score -= $evalItems['scorePerAnswer'];
            }
        }

        return $score;
    }

    protected function getAllPossibleAnswers()
    {
        return MultipleChoiceQuestion::whereQuestionId($this->question->id)
            ->first()
            ->answers
            ->map(function ($answer) {
                return [
                    'id' => $answer->id,
                    'isCorrect' => $answer->is_correct
                ];
            });
    }

    protected function getEvalItems()
    {
        $questionScore = $this->findContentItem()->question_score;
        $allPossibleAnswers = $this->getAllPossibleAnswers();
        $allPossibleAnswersCount = $this->getAllPossibleAnswers()->count();

        return [
            'allPossibleAnswers' => $allPossibleAnswers->toArray(),
            'allPossibleAnswersCount' => $allPossibleAnswersCount,
            'questionScore' => $questionScore,
            'scorePerAnswer' => $questionScore / $allPossibleAnswersCount,
            'correctAnswers' => $allPossibleAnswers->filter->isCorrect->pluck('id')->toArray(),
            'participantAnswerData' => $this->participantAnswers['answers']['data']
        ];
    }
}