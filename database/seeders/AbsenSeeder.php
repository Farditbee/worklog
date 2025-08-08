<?php

namespace Database\Seeders;

use App\Models\Absen;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AbsenSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Pastikan ada user dengan tenaga_ahli
        $users = [
            ['name' => 'Ahmad S1', 'email' => 'ahmad.s1@example.com', 'tenaga_ahli' => 's1', 'role' => 'user'],
            ['name' => 'Budi S2', 'email' => 'budi.s2@example.com', 'tenaga_ahli' => 's2', 'role' => 'user'],
            ['name' => 'Citra S3', 'email' => 'citra.s3@example.com', 'tenaga_ahli' => 's3', 'role' => 'user'],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, ['password' => bcrypt('password')])
            );
        }

        // Ambil project yang ada
        $projects = Project::all();
        $users = User::whereNotNull('tenaga_ahli')->get();

        if ($projects->count() > 0 && $users->count() > 0) {
            // Buat data absen sample
            $absenData = [
                [
                    'tanggal' => Carbon::now()->subDays(5),
                    'user_id' => $users->where('tenaga_ahli', 's1')->first()->id,
                    'project_id' => $projects->first()->id,
                    'kegiatan' => 'Pelaksanaan rapat bersama Pak R wasu membahas tentang Rucker was wory'
                ],
                [
                    'tanggal' => Carbon::now()->subDays(3),
                    'user_id' => $users->where('tenaga_ahli', 's2')->first()->id,
                    'project_id' => $projects->first()->id,
                    'kegiatan' => 'Melaksanakan pekerjaan laporan dumas impas'
                ],
                [
                    'tanggal' => Carbon::now()->subDays(2),
                    'user_id' => $users->where('tenaga_ahli', 's3')->first()->id,
                    'project_id' => $projects->first()->id,
                    'kegiatan' => 'Proses persiapan setup server yang dilakukan oleh tenaga Ahli Infrastuktur Moh Syukron tim BWI'
                ],
                [
                    'tanggal' => Carbon::now()->subDays(1),
                    'user_id' => $users->where('tenaga_ahli', 's1')->first()->id,
                    'project_id' => $projects->first()->id,
                    'kegiatan' => 'Set up meja kerja kursi dan ruangan serta Leo telah selesai di laksanakan semua sudah ready'
                ],
            ];

            foreach ($absenData as $data) {
                Absen::firstOrCreate(
                    [
                        'tanggal' => $data['tanggal']->format('Y-m-d'),
                        'user_id' => $data['user_id'],
                        'project_id' => $data['project_id']
                    ],
                    $data
                );
            }
        }
    }
}