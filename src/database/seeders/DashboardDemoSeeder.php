<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ClassModel;
use App\Models\ClassMember;
use App\Models\Event;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo users
        $users = [];
        $users[] = User::create([
            'name' => 'Dimas Pratama',
            'email' => 'dimas@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $users[] = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $users[] = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $users[] = User::create([
            'name' => 'Rina Wijaya',
            'email' => 'rina@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $users[] = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create class
        $class = ClassModel::create([
            'name' => 'Ilmu Komputer 2023 - Kelas B',
            'code' => 'CS-2023-B',
            'major' => 'Ilmu Komputer',
            'batch' => '2023',
            'section' => 'B',
            'description' => 'Kelas B Ilmu Komputer Angkatan 2023',
            'join_code' => Str::random(8),
            'is_active' => true,
        ]);

        // Create class members with birthdays
        foreach ($users as $index => $user) {
            ClassMember::create([
                'class_id' => $class->id,
                'user_id' => $user->id,
                'role' => $index === 0 ? 'admin' : 'member',
                'nickname' => $user->name,
                'birthday' => now()->addDays(rand(0, 7))->subYears(20),
                'student_id' => '2023' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'attendance_count' => rand(10, 16),
                'total_sessions' => 16,
                'joined_at' => now()->subMonths(6),
            ]);
        }

        // Create featured event
        Event::create([
            'class_id' => $class->id,
            'created_by' => $users[0]->id,
            'title' => 'Gathering Kelas - Piknik Akhir Semester',
            'description' => 'Yuk ikutan gathering kelas di Taman Wisata! Ada games, doorprize, dan makan bareng. Jangan lupa daftar ya!',
            'type' => 'social',
            'event_date' => now()->addDays(3)->setTime(14, 0),
            'location' => 'Taman Wisata Kampus',
            'link' => null,
            'rsvp_count' => 3,
            'is_featured' => true,
        ]);

        // Create more upcoming events
        Event::create([
            'class_id' => $class->id,
            'created_by' => $users[1]->id,
            'title' => 'Deadline Tugas Algoritma',
            'description' => 'Jangan lupa submit tugas implementasi sorting algorithm!',
            'type' => 'deadline',
            'event_date' => now()->addDays(5)->setTime(23, 59),
            'location' => null,
            'link' => 'https://elearning.university.ac.id',
            'rsvp_count' => 0,
            'is_featured' => false,
        ]);

        Event::create([
            'class_id' => $class->id,
            'created_by' => $users[0]->id,
            'title' => 'Kuliah Tamu: AI in Industry',
            'description' => 'Kuliah tamu dari praktisi industri tentang implementasi AI di perusahaan startup',
            'type' => 'academic',
            'event_date' => now()->addDays(7)->setTime(10, 0),
            'location' => 'Ruang Auditorium Lantai 3',
            'link' => 'https://meet.google.com/xxx-yyyy-zzz',
            'rsvp_count' => 5,
            'is_featured' => false,
        ]);

        // Create active poll
        $poll = Poll::create([
            'class_id' => $class->id,
            'created_by' => $users[0]->id,
            'question' => 'Tempat gathering kelas semester ini?',
            'description' => 'Vote tempat favorit kalian untuk gathering akhir semester!',
            'allow_multiple' => false,
            'is_anonymous' => false,
            'ends_at' => now()->addDays(5),
            'is_active' => true,
            'total_votes' => 8,
        ]);

        PollOption::create([
            'poll_id' => $poll->id,
            'option_text' => '🏖️ Pantai Anyer',
            'votes_count' => 3,
            'order' => 1,
        ]);

        PollOption::create([
            'poll_id' => $poll->id,
            'option_text' => '⛰️ Gunung Tangkuban Perahu',
            'votes_count' => 5,
            'order' => 2,
        ]);

        PollOption::create([
            'poll_id' => $poll->id,
            'option_text' => '🏕️ Camping di Ranca Upas',
            'votes_count' => 0,
            'order' => 3,
        ]);

        // Create posts
        Post::create([
            'class_id' => $class->id,
            'user_id' => $users[0]->id,
            'content' => '📢 Reminder guys! Jangan lupa daftar gathering akhir semester ya. Kuota terbatas!',
            'type' => 'announcement',
            'is_pinned' => true,
            'likes_count' => 12,
            'comments_count' => 3,
        ]);

        Post::create([
            'class_id' => $class->id,
            'user_id' => $users[1]->id,
            'content' => 'Ada yang punya catatan kuliah Database minggu lalu? Aku absen karena sakit 🥺',
            'type' => 'question',
            'is_pinned' => false,
            'likes_count' => 5,
            'comments_count' => 8,
        ]);

        Post::create([
            'class_id' => $class->id,
            'user_id' => $users[2]->id,
            'content' => 'Selamat ulang tahun Rina! 🎉🎂 Semoga panjang umur, sehat selalu, dan sukses kuliahnya! 🎊',
            'type' => 'celebration',
            'is_pinned' => false,
            'likes_count' => 18,
            'comments_count' => 12,
        ]);

        Post::create([
            'class_id' => $class->id,
            'user_id' => $users[3]->id,
            'content' => 'Guys, ada rekomendasi tempat makan enak deket kampus yang murah meriah? Butuh variasi nih 😅',
            'type' => 'discussion',
            'is_pinned' => false,
            'likes_count' => 7,
            'comments_count' => 15,
        ]);

        Post::create([
            'class_id' => $class->id,
            'user_id' => $users[4]->id,
            'content' => 'Alhamdulillah finally lulus UTS semua! Siapa yang mau nonton bareng buat pelepas stress? 🎬',
            'type' => 'discussion',
            'is_pinned' => false,
            'likes_count' => 9,
            'comments_count' => 6,
        ]);

        $this->command->info('✅ Dashboard demo data seeded successfully!');
        $this->command->info('🔑 Login dengan:');
        $this->command->info('   Email: dimas@example.com');
        $this->command->info('   Password: password');
    }
}
