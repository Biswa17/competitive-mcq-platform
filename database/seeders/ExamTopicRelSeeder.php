<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamTopicRel;
use App\Models\Exam;
use App\Models\Topic;

class ExamTopicRelSeeder extends Seeder
{
    public function run()
    {
        // Get the GATE CSE exam
        $gateExam = Exam::where('name', 'GATE CSE')->first();

        if (!$gateExam) {
            return;
        }

        // Get all topics
        $topics = Topic::whereIn('name', [
            'General Aptitude',
            'Engineering Mathematics',
            'Digital Logic',
            'Computer Organization and Architecture',
            'Programming and Data Structures',
            'Algorithms',
            'Theory of Computation',
            'Compiler Design',
            'Operating Systems',
            'Databases',
            'Computer Networks'
        ])->get();

        // Insert relationships
        foreach ($topics as $topic) {
            ExamTopicRel::create([
                'exam_id' => $gateExam->id,
                'topic_id' => $topic->id,
            ]);
        }
    }
}
