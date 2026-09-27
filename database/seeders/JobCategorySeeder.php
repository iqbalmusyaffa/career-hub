<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Teknologi & IT',
                'slug' => 'teknologi-it',
                'icon' => 'fa-solid fa-laptop-code',
                'badge_color' => 'blue',
                'subtext' => 'Software, Web Developer, DevOps, & IT Support',
                'description' => 'Bidang teknologi informasi mencakup pengembangan perangkat lunak, sistem cloud, keamanan siber, dan infrastruktur IT.',
                'sort_order' => 1,
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Keuangan & Akuntansi',
                'slug' => 'keuangan-akuntansi',
                'icon' => 'fa-solid fa-calculator',
                'badge_color' => 'emerald',
                'subtext' => 'Accounting, Finance, Tax, & Auditor',
                'description' => 'Pengelolaan keuangan korporat, perpajakan, audit internal, perbankan, dan perencanaan anggaran finansial.',
                'sort_order' => 2,
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Pemasaran & Penjualan',
                'slug' => 'pemasaran-penjualan',
                'icon' => 'fa-solid fa-bullhorn',
                'badge_color' => 'amber',
                'subtext' => 'Digital Marketing, Sales, & Business Development',
                'description' => 'Strategi pemasaran digital, penjualan B2B/B2C, ekspansi pasar, branding, dan manajemen hubungan pelanggan.',
                'sort_order' => 3,
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Administrasi & SDM',
                'slug' => 'administrasi-sdm',
                'icon' => 'fa-solid fa-users-gear',
                'badge_color' => 'indigo',
                'subtext' => 'Human Resources, Rekrutmen, & Staff Admin',
                'description' => 'Manajemen sumber daya manusia, rekrutmen talenta, operasional kantor, kompensasi, dan kepatuhan ketenagakerjaan.',
                'sort_order' => 4,
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Kreatif & Desain',
                'slug' => 'kreatif-desain',
                'icon' => 'fa-solid fa-palette',
                'badge_color' => 'purple',
                'subtext' => 'UI/UX Designer, Graphic Design, & Video Editor',
                'description' => 'Perancangan antarmuka pengguna (UI/UX), ilustrasi visual, desain grafis, animasi, dan produksi konten multimedia.',
                'sort_order' => 5,
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Operasional & Logistik',
                'slug' => 'operasional-logistik',
                'icon' => 'fa-solid fa-truck-fast',
                'badge_color' => 'sky',
                'subtext' => 'Supply Chain, Warehouse, & Logistik Pengiriman',
                'description' => 'Pengelolaan rantai pasok (supply chain), pergudangan, distribusi pengiriman armada, dan efisiensi operasional bisnis.',
                'sort_order' => 6,
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Magang & Entry-Level',
                'slug' => 'magang-entry-level',
                'icon' => 'fa-solid fa-graduation-cap',
                'badge_color' => 'teal',
                'subtext' => 'Program Internship Kampus & Lulusan Baru',
                'description' => 'Peluang magang bersertifikat industri dan posisi awal karir bagi mahasiswa dan fresh graduate untuk percepatan karir.',
                'sort_order' => 7,
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Layanan & CS',
                'slug' => 'layanan-cs',
                'icon' => 'fa-solid fa-headset',
                'badge_color' => 'rose',
                'subtext' => 'Customer Service, Support Agent, & Helpdesk',
                'description' => 'Layanan bantuan pelanggan omnichannel, technical support, call center, dan penyelesaian solusi pelanggan.',
                'sort_order' => 8,
                'is_active' => true,
                'status' => 'active',
            ],
        ];

        foreach ($categories as $cat) {
            JobCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }
    }
}
