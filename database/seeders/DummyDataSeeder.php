<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\CandidateProfile;
use App\Models\Interview;
use App\Models\Job;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $candidateRole = Role::firstOrCreate(['name' => 'Candidate']);

        // 1. Create Job Postings
        $jobsData = [
            [
                'title' => 'Senior Backend Developer (Laravel & Go)',
                'company_name' => 'PT TechNova Asia Digital',
                'division' => 'Engineering',
                'location' => 'Jakarta Selatan',
                'work_type' => 'Hybrid',
                'salary' => 'Rp 15.000.000 - Rp 22.000.000',
                'description' => "Kami mencari Senior Backend Developer berpengalaman untuk merancang, mengoptimalkan, dan memelihara arsitektur microservices performa tinggi di perusahaan fintech terkemuka.\n\nTanggung Jawab:\n- Mengembangkan API RESTful dan GraphQL yang aman dan scalable.\n- Mengoptimalkan performa database PostgreSQL & Redis.\n- Berkolaborasi dengan tim DevOps dan Frontend.",
                'requirements' => "1. Minimal 4 tahun pengalaman menggunakan PHP (Laravel) & Go.\n2. Menguasai PostgreSQL, MySQL, Redis, dan Docker.\n3. Memahami konsep Microservices, CI/CD, dan Unit Testing.\n4. Pengalaman dengan AWS / Google Cloud menjadi nilai tambah.",
                'benefits' => 'Asuransi Swasta, Laptop Kerja (MacBook Pro), Tunjangan Kesehatan, Work From Anywhere 2 hari/minggu.',
                'deadline' => now()->addDays(30),
                'status' => 'active',
            ],
            [
                'title' => 'Frontend Engineer (React.js & Vue.js)',
                'company_name' => 'PT GlobalCorp Digital',
                'division' => 'Engineering',
                'location' => 'Bandung',
                'work_type' => 'Remote',
                'salary' => 'Rp 10.000.000 - Rp 16.000.000',
                'description' => "Bergabunglah dengan tim produk kami untuk membangun antarmuka pengguna yang responsif, cepat, dan intuitif untuk ribuan pengguna harian.",
                'requirements' => "1. Minimal 2 tahun pengalaman menggunakan React.js / Next.js / Vue.js.\n2. Menguasai HTML5, CSS3, Tailwind CSS, dan TypeScript.\n3. Paham state management (Redux / Pinia).\n4. Memiliki portofolio aplikasi web yang menarik.",
                'benefits' => 'Tunjangan Internet, Jam Kerja Fleksibel, Bonus Tahunan.',
                'deadline' => now()->addDays(20),
                'status' => 'active',
            ],
            [
                'title' => 'UI/UX Designer',
                'company_name' => 'FinServe Digital Indonesia',
                'division' => 'Design',
                'location' => 'Jakarta Pusat',
                'work_type' => 'Full-time',
                'salary' => 'Rp 9.000.000 - Rp 14.000.000',
                'description' => "Mencari UI/UX Designer kreatif yang mampu menerjemahkan ide bisnis menjadi wireframe, prototype, dan desain visual yang memesona.",
                'requirements' => "1. Pengalaman 2+ tahun sebagai UI/UX Designer.\n2. Mahir Figma, Adobe XD, dan Prototyping.\n3. Mampu melakukan User Research & Usability Testing.\n4. Wajib menyertakan link Portofolio.",
                'benefits' => 'BPJS Kesehatan & Ketenagakerjaan, Pelatihan & Sertifikasi, Kopi Gratis di Kantor.',
                'deadline' => now()->addDays(15),
                'status' => 'active',
            ],
            [
                'title' => 'Product Manager',
                'division' => 'Product',
                'location' => 'Jakarta Selatan',
                'work_type' => 'Full-time',
                'salary' => 'Rp 18.000.000 - Rp 25.000.000',
                'description' => "Product Manager akan bertanggung jawab memimpin roadmap produk dari tahap ideasi hingga rilis pasar.",
                'requirements' => "1. Minimal 3 tahun pengalaman sebagai Product Manager / Product Owner di industri teknologi.\n2. Memahami metodologi Agile/Scrum.\n3. Kemampuan analisis data (Mixpanel, Google Analytics, SQL).\n4. Kemampuan komunikasi dan kepemimpinan yang baik.",
                'benefits' => 'Opsi Saham Karyawan (ESOP), Asuransi Keluarga, Budget Buku & Kursus.',
                'deadline' => now()->addDays(40),
                'status' => 'active',
            ],
            [
                'title' => 'Data Analyst',
                'division' => 'Data Science',
                'location' => 'Surabaya',
                'work_type' => 'Hybrid',
                'salary' => 'Rp 8.000.000 - Rp 13.000.000',
                'description' => "Menganalisis data transaksi dan perilaku pengguna untuk memberikan wawasan bisnis yang mendukung pengambilan keputusan strategis.",
                'requirements' => "1. Pendidikan min. S1 Matematika/Statistika/Ilmu Komputer.\n2. Menguasai SQL, Python/R, dan Tableau/Power BI.\n3. Mampu membuat visualisasi data yang mudah dipahami manajemen.",
                'benefits' => 'BPJS, Tunjangan Transportasi, Laptop Kerja.',
                'deadline' => now()->addDays(25),
                'status' => 'active',
            ],
            [
                'title' => 'DevOps & Cloud Infrastructure Engineer',
                'division' => 'Engineering',
                'location' => 'Jakarta Barat',
                'work_type' => 'Remote',
                'salary' => 'Rp 14.000.000 - Rp 20.000.000',
                'description' => "Mengelola infrastruktur cloud berbasis AWS, mengotomatiskan pipeline CI/CD, dan memastikan uptime aplikasi 99.9%.",
                'requirements' => "1. Minimal 3 tahun pengalaman dengan AWS / GCP / Kubernetes.\n2. Mahir Terraform, Ansible, Docker, Jenkins / GitHub Actions.\n3. Pengalaman mengelola Linux Server & monitoring (Prometheus/Grafana).",
                'benefits' => 'Full Remote Work, Tunjangan Peralatan Kerja, Asuransi Swasta.',
                'deadline' => now()->addDays(12),
                'status' => 'active',
            ],
            [
                'title' => 'Digital Marketing Specialist',
                'division' => 'Marketing',
                'location' => 'Tangerang',
                'work_type' => 'Full-time',
                'salary' => 'Rp 7.500.000 - Rp 11.000.000',
                'description' => "Mengelola kampanye iklan digital (Meta Ads, Google Ads, TikTok Ads) serta strategi SEO/SEM perusahaan.",
                'requirements' => "1. Minimal 2 tahun di bidang Digital Marketing.\n2. Mahir Meta Business Manager, Google Ads, SEO Analytics.\n3. Mampu membuat laporan ROI kampanye pemasaran secara akurat.",
                'benefits' => 'Bonus Performa, Ruang Istirahat & Game, BPJS.',
                'deadline' => now()->addDays(18),
                'status' => 'active',
            ],
            [
                'title' => 'HR Talent Acquisition Specialist',
                'division' => 'Human Resources',
                'location' => 'Jakarta Selatan',
                'work_type' => 'Full-time',
                'salary' => 'Rp 8.000.000 - Rp 12.000.000',
                'description' => "Bertanggung jawab atas proses end-to-end recruitment karyawan baru untuk berbagai divisi bisnis.",
                'requirements' => "1. S1 Psikologi / Hukum / Manajemen SDM.\n2. Pengalaman min. 2 tahun di bidang Tech Recruitment.\n3. Mahir interviewing technique (BEI) dan alat tes psikologi.",
                'benefits' => 'BPJS Kesehatan, Jenjang Karir Jelas, Outing Kantor.',
                'deadline' => now()->addDays(14),
                'status' => 'active',
            ],
            [
                'title' => 'Mobile Developer (Flutter)',
                'division' => 'Engineering',
                'location' => 'Yogyakarta',
                'work_type' => 'Hybrid',
                'salary' => 'Rp 9.000.000 - Rp 15.000.000',
                'description' => "Membangun aplikasi mobile iOS dan Android menggunakan framework Flutter terbaru.",
                'requirements' => "1. Min 2 tahun pengalaman Flutter & Dart.\n2. Memahami BLoC / Provider / Riverpod state management.\n3. Pernah mempublikasikan aplikasi di Play Store & App Store.",
                'benefits' => 'Jam Kerja Fleksibel, Lingkungan Kerja Santai, Kopi Bebas.',
                'deadline' => now()->addDays(22),
                'status' => 'active',
            ],
            [
                'title' => 'Frontend Developer Intern (Magang Web)',
                'division' => 'Engineering',
                'location' => 'Bandung',
                'work_type' => 'Internship',
                'salary' => 'Rp 3.500.000 - Rp 5.000.000',
                'description' => "Kesempatan magang 6 bulan untuk mahasiswa/fresh graduate yang ingin mengasah kemampuan HTML, Tailwind CSS, dan JavaScript/React di dunia industri nyata.",
                'requirements' => "1. Mahasiswa tingkat akhir atau fresh graduate IT/Sistem Informasi.\n2. Paham dasar Web Development (HTML, CSS, JS).\n3. Memiliki semangat belajar tinggi dan komunikatif.",
                'benefits' => 'Uang Saku Bulanan, Sertifikat Magang Resmi, Mentoring Langsung dari Senior Engineer.',
                'deadline' => now()->addDays(35),
                'status' => 'active',
            ],
            [
                'title' => 'UI/UX Design Intern (Magang Desain)',
                'division' => 'Design',
                'location' => 'Jakarta Selatan',
                'work_type' => 'Internship',
                'salary' => 'Rp 3.000.000 - Rp 4.500.000',
                'description' => "Program magang UI/UX untuk membantu perancangan prototype dan user testing produk digital.",
                'requirements' => "1. Menguasai Figma & Prototyping dasar.\n2. Menyertakan link portofolio desain (Behance / Dribbble / Figma).",
                'benefits' => 'Uang Saku Bulanan, Mentoring, Peluang Direkrut Karyawan Tetap.',
                'deadline' => now()->addDays(28),
                'status' => 'active',
            ],
            [
                'title' => 'QA Automation Engineer',
                'division' => 'Engineering',
                'location' => 'Jakarta Selatan',
                'work_type' => 'Hybrid',
                'salary' => 'Rp 10.000.000 - Rp 15.000.000',
                'description' => "Membuat script otomatisasi pengujian software menggunakan Cypress, Playwright, atau Selenium.",
                'requirements' => "1. Minimal 2 tahun di bidang Software QA.\n2. Mahir JavaScript / Python untuk testing automation.\n3. Pengalaman API testing (Postman, JMeter).",
                'benefits' => 'Laptop MBP, BPJS, Tunjangan Makan.',
                'deadline' => now()->subDays(5), // Expired job test
                'status' => 'closed',
            ],
        ];

        $createdJobs = [];
        foreach ($jobsData as $data) {
            $createdJobs[] = Job::firstOrCreate(
                ['title' => $data['title'], 'company_name' => $data['company_name'] ?? 'PT TechNova Asia Digital'],
                $data
            );
        }

        // 2. Create Candidate Users with Rich Profiles
        $candidatesData = [
            [
                'name' => 'Budi Pratama',
                'email' => 'budi.pratama@gmail.com',
                'phone' => '081298765432',
                'last_education' => 'S1 Teknik Informatika - ITB',
                'current_position' => 'Fullstack Web Developer',
                'summary' => 'Developer berpengalaman 4+ tahun dalam membangun aplikasi web bisnis menggunakan Laravel, Vue.js, dan MySQL. Terbiasa dengan arsitektur REST API dan CI/CD.',
                'skills' => ['php', 'laravel', 'vue.js', 'mysql', 'tailwind css', 'git', 'docker', 'rest api'],
                'experiences' => [
                    ['title' => 'Senior Web Developer', 'company' => 'PT Toko Teknologi Indonesia', 'start_date' => '2022-01', 'end_date' => 'Sekarang', 'is_current' => true, 'description' => 'Mengembangkan sistem backend e-commerce berbasis Laravel.'],
                    ['title' => 'Junior Programmer', 'company' => 'PT Solusi Digital Pro', 'start_date' => '2020-03', 'end_date' => '2021-12', 'is_current' => false, 'description' => 'Membuat modul administrasi dan laporan keuangan.'],
                ],
                'educations' => [
                    ['institution' => 'Institut Teknologi Bandung (ITB)', 'degree' => 'S1', 'field_of_study' => 'Teknik Informatika', 'start_year' => '2016', 'end_year' => '2020', 'gpa' => '3.75'],
                ],
                'languages' => [
                    ['name' => 'Bahasa Indonesia', 'proficiency' => 'Native'],
                    ['name' => 'English', 'proficiency' => 'Professional Working'],
                ],
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@gmail.com',
                'phone' => '082155443322',
                'last_education' => 'S1 Desain Komunikasi Visual - ISI Yogyakarta',
                'current_position' => 'UI/UX Designer & Product Researcher',
                'summary' => 'Designer kreatif berpengalaman 3 tahun berfokus pada desain antarmuka mobile & web modern berbasis user-centered design.',
                'skills' => ['figma', 'ui/ux designer', 'adobe xd', 'prototyping', 'wireframing', 'user research', 'html5', 'css3'],
                'experiences' => [
                    ['title' => 'UI/UX Designer Lead', 'company' => 'Studio Desain Kreatif', 'start_date' => '2021-06', 'end_date' => 'Sekarang', 'is_current' => true, 'description' => 'Memimpin pembuatan design system untuk aplikasi kesehatan.'],
                ],
                'educations' => [
                    ['institution' => 'Institut Seni Indonesia Yogyakarta', 'degree' => 'S1', 'field_of_study' => 'Desain Komunikasi Visual', 'start_year' => '2017', 'end_year' => '2021', 'gpa' => '3.82'],
                ],
                'languages' => [
                    ['name' => 'Bahasa Indonesia', 'proficiency' => 'Native'],
                    ['name' => 'English', 'proficiency' => 'Conversational'],
                ],
            ],
            [
                'name' => 'Rian Hidayat',
                'email' => 'rian.hidayat@yahoo.com',
                'phone' => '085711223344',
                'last_education' => 'S1 Sistem Informasi - Universitas Indonesia',
                'current_position' => 'DevOps Engineer',
                'summary' => 'Insinyur cloud dan otomatisasi infrastruktur berpengalaman mengelola klaster Kubernetes dan CI/CD pipeline di AWS.',
                'skills' => ['aws', 'kubernetes', 'docker', 'terraform', 'jenkins', 'linux', 'python', 'go'],
                'experiences' => [
                    ['title' => 'DevOps Specialist', 'company' => 'Cloud Infrastructure Asia', 'start_date' => '2021-02', 'end_date' => 'Sekarang', 'is_current' => true, 'description' => 'Mengelola 50+ mikroservis di AWS EKS.'],
                ],
                'educations' => [
                    ['institution' => 'Universitas Indonesia', 'degree' => 'S1', 'field_of_study' => 'Sistem Informasi', 'start_year' => '2016', 'end_year' => '2020', 'gpa' => '3.60'],
                ],
                'languages' => [
                    ['name' => 'Bahasa Indonesia', 'proficiency' => 'Native'],
                    ['name' => 'English', 'proficiency' => 'Fluent'],
                ],
            ],
            [
                'name' => 'Dewi Anggraini',
                'email' => 'dewi.anggraini@gmail.com',
                'phone' => '081388990011',
                'last_education' => 'S1 Statistika - Universitas Gadjah Mada',
                'current_position' => 'Data Analyst',
                'summary' => 'Analis Data berbakat yang mahir mengolah big data menggunakan SQL, Python, dan Tableau untuk keputusan bisnis berbasis data.',
                'skills' => ['sql', 'python', 'tableau', 'power bi', 'excel', 'statistics', 'r', 'data visualization'],
                'experiences' => [
                    ['title' => 'Data Analyst Consultant', 'company' => 'Data Insights Corp', 'start_date' => '2022-03', 'end_date' => 'Sekarang', 'is_current' => true, 'description' => 'Membuat dashboard peramalan penjualan bulanan.'],
                ],
                'educations' => [
                    ['institution' => 'Universitas Gadjah Mada', 'degree' => 'S1', 'field_of_study' => 'Statistika', 'start_year' => '2017', 'end_year' => '2021', 'gpa' => '3.90'],
                ],
                'languages' => [
                    ['name' => 'Bahasa Indonesia', 'proficiency' => 'Native'],
                    ['name' => 'English', 'proficiency' => 'Advanced'],
                ],
            ],
            [
                'name' => 'Fajar Nugraha',
                'email' => 'fajar.nugraha@gmail.com',
                'phone' => '087733445566',
                'last_education' => 'S1 Teknik Elektro - ITS Surabaya',
                'current_position' => 'Frontend React Engineer',
                'summary' => 'Frontend developer dengan fokus utama pada performa web, optimasi SEO, dan arsitektur TypeScript/React.',
                'skills' => ['react.js', 'typescript', 'next.js', 'tailwind css', 'redux', 'javascript', 'git'],
                'experiences' => [
                    ['title' => 'Frontend Web Engineer', 'company' => 'PT Digital Inovasi Bangsa', 'start_date' => '2021-08', 'end_date' => 'Sekarang', 'is_current' => true, 'description' => 'Membangun platform SaaS dengan Next.js & Tailwind.'],
                ],
                'educations' => [
                    ['institution' => 'Institut Teknologi Sepuluh Nopember', 'degree' => 'S1', 'field_of_study' => 'Teknik Elektro', 'start_year' => '2016', 'end_year' => '2020', 'gpa' => '3.52'],
                ],
                'languages' => [
                    ['name' => 'Bahasa Indonesia', 'proficiency' => 'Native'],
                ],
            ],
        ];

        $createdCandidates = [];
        foreach ($candidatesData as $cData) {
            $user = User::firstOrCreate(
                ['email' => $cData['email']],
                [
                    'name' => $cData['name'],
                    'password' => Hash::make('S3cur3#P@ssw0rd!2026'),
                ]
            );
            $user->assignRole($candidateRole);

            CandidateProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $cData['phone'],
                    'summary' => $cData['summary'],
                    'skills' => $cData['skills'],
                    'experiences' => $cData['experiences'],
                    'educations' => $cData['educations'],
                    'languages' => $cData['languages'],
                ]
            );

            $createdCandidates[] = $user;
        }

        // 3. Create Applications & Interviews
        $statuses = ['pending', 'reviewed', 'interview', 'accepted', 'rejected'];

        foreach ($createdCandidates as $candidateIndex => $candidate) {
            // Apply to 2 - 3 jobs for each candidate
            $appliedJobs = array_slice($createdJobs, $candidateIndex % 3, 3);

            foreach ($appliedJobs as $jobIndex => $job) {
                $status = $statuses[($candidateIndex + $jobIndex) % count($statuses)];

                $application = Application::firstOrCreate(
                    [
                        'user_id' => $candidate->id,
                        'job_id' => $job->id,
                    ],
                    [
                        'status' => $status,
                    ]
                );

                // Create interview if status is interview
                if ($status === 'interview') {
                    Interview::create([
                        'application_id' => $application->id,
                        'scheduled_at' => now()->addDays(rand(1, 10))->setHour(rand(9, 15))->setMinute(0),
                        'type' => rand(0, 1) ? 'online' : 'offline',
                        'location_or_link' => rand(0, 1) ? 'https://meet.google.com/abc-defg-hij' : 'Ruang Wawancara Lt. 3 Kantor Utama',
                        'notes' => 'Harap hadir 10 menit lebih awal dan menyiapkan dokumen identitas serta portofolio karya terbaru Anda.',
                        'status' => 'scheduled',
                    ]);
                }

                // Bookmark some jobs
                if (rand(0, 1)) {
                    SavedJob::firstOrCreate([
                        'user_id' => $candidate->id,
                        'job_id' => $job->id,
                    ]);
                }
            }
        }

        // 4. Create Company Profiles for Trusted Companies
        $companiesList = [
            ['company_name' => 'PT TechNova Asia Digital', 'industry' => 'Teknologi & Informasi', 'address' => 'Jakarta Selatan', 'is_verified' => true],
            ['company_name' => 'PT GlobalCorp Digital', 'industry' => 'Konsultan IT & Multi Perusahaan', 'address' => 'Bandung', 'is_verified' => true],
            ['company_name' => 'FinServe Digital Indonesia', 'industry' => 'Keuangan & Fintech', 'address' => 'Jakarta Pusat', 'is_verified' => true],
            ['company_name' => 'EduSmart Tech Indonesia', 'industry' => 'Teknologi Pendidikan', 'address' => 'Yogyakarta', 'is_verified' => true],
            ['company_name' => 'AeroLogistics Transport', 'industry' => 'Logistik & Transportasi', 'address' => 'Surabaya', 'is_verified' => true],
            ['company_name' => 'TokoKreatif E-Commerce', 'industry' => 'Ritel & E-Commerce', 'address' => 'Tangerang', 'is_verified' => true],
        ];

        // Ensure default HR owner user exists for company profiles
        $hrOwner = User::firstOrCreate(
            ['email' => 'hr.official@technova.id'],
            [
                'name' => 'HR Official TechNova',
                'password' => Hash::make('S3cur3#P@ssw0rd!2026'),
            ]
        );
        $hrRole = Role::firstOrCreate(['name' => 'HR']);
        $hrOwner->assignRole($hrRole);

        foreach ($companiesList as $comp) {
            \App\Models\CompanyProfile::firstOrCreate(
                ['company_name' => $comp['company_name']],
                [
                    'user_id' => $hrOwner->id,
                    'industry' => $comp['industry'],
                    'address' => $comp['address'],
                    'is_verified' => $comp['is_verified'],
                    'description' => 'Perusahaan terkemuka mitra resmi rekrutmen TalentFlow.',
                ]
            );
        }
    }
}
