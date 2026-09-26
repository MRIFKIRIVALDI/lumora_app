<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoQuizQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        foreach (DB::table('quizzes')->get() as $quiz) {
            if (DB::table('quiz_questions')->where('quiz_id', $quiz->id)->exists()) continue;
            DB::table('quiz_questions')->insert([
                ['quiz_id'=>$quiz->id,'question_text'=>'Berapakah hasil 2 + 2?','options'=>json_encode(['A'=>'3','B'=>'4','C'=>'5','D'=>'6']),'correct_option'=>'B','points'=>10,'created_at'=>$now,'updated_at'=>$now],
                ['quiz_id'=>$quiz->id,'question_text'=>'Pilih jawaban yang paling tepat: kegiatan belajar dilakukan untuk ...','options'=>json_encode(['A'=>'Menambah pengetahuan','B'=>'Menghindari tugas','C'=>'Datang terlambat','D'=>'Tidak berdiskusi']),'correct_option'=>'A','points'=>10,'created_at'=>$now,'updated_at'=>$now],
            ]);
        }
    }
}
