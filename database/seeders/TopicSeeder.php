<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;

class TopicSeeder extends Seeder
{
    public function run()
    {
        // Temporarily disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table safely
        Topic::truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $topics = [
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
        ];

        foreach ($topics as $topic) {
            Topic::create([
                'name' => $topic,
            ]);
        }
    }
}
