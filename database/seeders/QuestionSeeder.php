<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    /**
     * Seed the application's database with questions.
     */
    public function run(): void
    {
        // Family Social Factor - Section 1: Family Social (12 pertanyaan dengan skala likert 1-5)
        $familySocialQuestions = [
            [
                'type' => 'family_social',
                'section' => 'family_social',
                'scale_type' => 'likert_5',
                'question_text' => 'Keluarga saya benar-benar berusaha membantu saya',
                'order' => 1,
            ],
            [
                'type' => 'family_social',
                'section' => 'family_social',
                'scale_type' => 'likert_5',
                'question_text' => 'Saya mendapatkan bantuan dan dukungan emosional yang saya butuhkan dari keluarga saya',
                'order' => 2,
            ],
            [
                'type' => 'family_social',
                'section' => 'family_social',
                'scale_type' => 'likert_5',
                'question_text' => 'Saya dapat membicarakan masalah saya dengan keluarga saya',
                'order' => 3,
            ],
            [
                'type' => 'family_social',
                'section' => 'family_social',
                'scale_type' => 'likert_5',
                'question_text' => 'Keluarga bersedia membantu saya membuat keputusan',
                'order' => 4,
            ],
            [
                'type' => 'family_social',
                'section' => 'family_social',
                'scale_type' => 'likert_5',
                'question_text' => 'Teman-teman saya benar-benar berusaha membantu saya',
                'order' => 5,
            ],
            [
                'type' => 'family_social',
                'section' => 'family_social',
                'scale_type' => 'likert_5',
                'question_text' => 'Saya dapat mengandalkan teman-teman saya ketika terjadi masalah',
                'order' => 6,
            ],
            [
                'type' => 'family_social',
                'section' => 'family_social',
                'scale_type' => 'likert_5',
                'question_text' => 'Saya memiliki teman yang bisa diajak berbagi suka dan duka',
                'order' => 7,
            ],
            [
                'type' => 'family_social',
                'section' => 'family_social',
                'scale_type' => 'likert_5',
                'question_text' => 'Saya dapat membicarakan masalah saya dengan teman-teman saya',
                'order' => 8,
            ],
            [
                'type' => 'family_social',
                'section' => 'family_social',
                'scale_type' => 'likert_5',
                'question_text' => 'Ada orang spesial yang selalu ada saat saya membutuhkan',
                'order' => 9,
            ],
            [
                'type' => 'family_social',
                'section' => 'family_social',
                'scale_type' => 'likert_5',
                'question_text' => 'Ada orang yang spesial yang bisa saya ajak berbagi suka dan duka',
                'order' => 10,
            ],
            [
                'type' => 'family_social',
                'section' => 'family_social',
                'scale_type' => 'likert_5',
                'question_text' => 'Saya memiliki seseorang yang istimewa yang merupakan sumber kenyamanan bagi saya',
                'order' => 11,
            ],
            [
                'type' => 'family_social',
                'section' => 'family_social',
                'scale_type' => 'likert_5',
                'question_text' => 'Ada orang spesial dalam hidup saya yang peduli dengan perasaan saya',
                'order' => 12,
            ],
        ];

        // Family Social Factor - Section 2: DASS-21 (21 pertanyaan dengan skala 0-3)
        $dass21Questions = [
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya merasa sulit untuk menenangkan diri',
                'order' => 1,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya cenderung bereaksi berlebihan terhadap situasi',
                'order' => 2,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya merasa bahwa saya sering gugup',
                'order' => 3,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya mendapati diri saya gelisah',
                'order' => 4,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya merasa kesulitan untuk bersantai',
                'order' => 5,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya tidak toleran terhadap apa pun yang menghalangi saya untuk melanjutkan apa yang sedang saya kerjakan',
                'order' => 6,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya merasa saya agak sensitif',
                'order' => 7,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya merasakan kekeringan pada mulut saya',
                'order' => 8,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya mengalami kesulitan bernapas (misalnya, napas yang terlalu cepat, sesak napas saat tidak melakukan aktivitas fisik)',
                'order' => 9,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya mengalami gemetar (misalnya di tangan)',
                'order' => 10,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya khawatir tentang situasi di mana saya mungkin panik dan mempermalukan diri saya sendiri',
                'order' => 11,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya merasa hampir panik',
                'order' => 12,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya menyadari tindakan jantung saya tanpa adanya aktivitas fisik (misalnya, rasa detak jantung meningkat, jantung tidak berdetak)',
                'order' => 13,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya merasa takut tanpa alasan yang jelas',
                'order' => 14,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya tidak bisa merasakan perasaan positif sama sekali',
                'order' => 15,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya merasa sulit untuk mengambil inisiatif untuk melakukan sesuatu',
                'order' => 16,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya merasa tidak ada yang bisa saya nantikan',
                'order' => 17,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya merasa sedih dan sedih sekali',
                'order' => 18,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya tidak mampu untuk menjadi antusias terhadap apa pun',
                'order' => 19,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya merasa tidak terlalu berharga sebagai manusia',
                'order' => 20,
            ],
            [
                'type' => 'family_social',
                'section' => 'dass21',
                'scale_type' => 'dass21',
                'question_text' => 'Saya merasa hidup ini tidak berarti',
                'order' => 21,
            ],
        ];

        // Insert Family Social Questions
        foreach ($familySocialQuestions as $question) {
            Question::create($question);
        }

        // Insert DASS-21 Questions
        foreach ($dass21Questions as $question) {
            Question::create($question);
        }

        // Self Efficacy - Section 1: Self-efficacy (10 pertanyaan dengan skala likert 1-4)
        $selfEfficacyQuestions = [
            [
                'type' => 'self_efficacy',
                'section' => 'self_efficacy',
                'scale_type' => 'likert_4',
                'question_text' => 'Saya selalu dapat menyelesaikan masalah yang sulit jika saya berusaha cukup keras',
                'order' => 1,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'self_efficacy',
                'scale_type' => 'likert_4',
                'question_text' => 'Jika ada yang menentang saya, saya dapat menemukan sarana dan cara untuk mendapatkan apa yang saya inginkan',
                'order' => 2,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'self_efficacy',
                'scale_type' => 'likert_4',
                'question_text' => 'Mudah bagi saya untuk tetap berpegang teguh pada tujuan saya dan mencapai sasaran saya',
                'order' => 3,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'self_efficacy',
                'scale_type' => 'likert_4',
                'question_text' => 'Saya yakin bahwa saya dapat menangani kejadian-kejadian yang tidak terduga secara efisien',
                'order' => 4,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'self_efficacy',
                'scale_type' => 'likert_4',
                'question_text' => 'Berkat kecerdikan saya, saya tahu cara menangani situasi yang tidak terduga',
                'order' => 5,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'self_efficacy',
                'scale_type' => 'likert_4',
                'question_text' => 'Saya dapat menyelesaikan sebagian besar masalah jika saya berupaya keras',
                'order' => 6,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'self_efficacy',
                'scale_type' => 'likert_4',
                'question_text' => 'Saya bisa tetap tenang saat menghadapi kesulitan karena saya bisa mengandalkan kemampuan mengatasi masalah saya',
                'order' => 7,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'self_efficacy',
                'scale_type' => 'likert_4',
                'question_text' => 'Ketika saya dihadapkan pada suatu masalah, saya biasanya dapat menemukan beberapa solusi',
                'order' => 8,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'self_efficacy',
                'scale_type' => 'likert_4',
                'question_text' => 'Jika saya berada dalam masalah, saya biasanya dapat memikirkan solusinya',
                'order' => 9,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'self_efficacy',
                'scale_type' => 'likert_4',
                'question_text' => 'Saya biasanya dapat menangani apa pun yang datang kepada saya',
                'order' => 10,
            ],
        ];

        // Self Efficacy - Section 2: Well-being (18 pertanyaan dengan skala likert 1-7)
        $wellBeingQuestions = [
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Saya cenderung dipengaruhi oleh orang-orang yang memiliki pendapat kuat',
                'order' => 1,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Saya memiliki kepercayaan diri terhadap pendapat saya sendiri, bahkan jika pendapat tersebut berbeda dengan pendapat kebanyakan orang lain',
                'order' => 2,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Saya menilai diri saya sendiri berdasarkan apa yang saya anggap penting, bukan berdasarkan nilai-nilai yang menurut orang lain penting',
                'order' => 3,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Tuntutan kehidupan sehari-hari sering membuat saya sedih',
                'order' => 4,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Secara umum, saya merasa bertanggung jawab atas situasi di mana saya tinggal',
                'order' => 5,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Saya pandai mengelola tanggung jawab dalam kehidupan sehari-hari',
                'order' => 6,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Bagi saya, hidup adalah proses pembelajaran, perubahan, dan pertumbuhan yang berkelanjutan',
                'order' => 7,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Saya pikir penting untuk memiliki pengalaman baru yang menantang cara berpikir saya tentang diri saya sendiri dan dunia',
                'order' => 8,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Saya sudah lama menyerah untuk membuat perbaikan atau perubahan besar dalam hidup saya',
                'order' => 9,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Mempertahankan hubungan dekat merupakan hal yang sulit dan membuat saya frustasi',
                'order' => 10,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Orang-orang akan menggambarkan saya sebagai orang yang suka memberi, bersedia berbagi waktu dengan orang lain',
                'order' => 11,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Saya belum pernah mengalami hubungan yang hangat dan saling percaya dengan orang lain',
                'order' => 12,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Beberapa orang mengembara tanpa tujuan dalam hidup, tetapi saya bukan salah satu dari mereka',
                'order' => 13,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Saya menjalani hidup satu hari demi satu hari dan tidak terlalu memikirkan masa depan',
                'order' => 14,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Kadang-kadang saya merasa seolah-olah saya telah melakukan semua yang harus dilakukan dalam hidup',
                'order' => 15,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Saya menyukai sebagian besar bagian dari kepribadian saya',
                'order' => 16,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Ketika saya melihat kisah hidup saya, saya senang dengan bagaimana segala sesuatunya berjalan sejauh ini',
                'order' => 17,
            ],
            [
                'type' => 'self_efficacy',
                'section' => 'well_being',
                'scale_type' => 'likert_7',
                'question_text' => 'Dalam banyak hal, saya merasa kecewa dengan pencapaian saya dalam hidup',
                'order' => 18,
            ],
        ];

        // Insert Self Efficacy Questions
        foreach ($selfEfficacyQuestions as $question) {
            Question::create($question);
        }

        // Insert Well-being Questions
        foreach ($wellBeingQuestions as $question) {
            Question::create($question);
        }
    }
}
