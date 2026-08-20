<?php

namespace Database\Seeders;

use App\Models\EducationEntry;
use App\Models\Experience;
use App\Models\OrganizationEntry;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedProfile();
        $this->seedSkills();
        $this->seedExperiences();
        $this->seedProjects();
        $this->seedEducation();
        $this->seedOrganizations();
    }

    private function seedProfile(): void
    {
        Profile::updateOrCreate(['id' => 1], [
            'name' => 'M. Fadhlan',
            'role' => 'Backend / Fullstack Developer',
            'tagline_en' => 'Building REST APIs, rental systems, and payment integrations for production web applications.',
            'tagline_id' => 'Membangun REST API, sistem rental, dan integrasi payment untuk aplikasi web production.',
            'summary_en' => 'Backend / Fullstack Developer with experience building production web applications using Laravel, React.js, Node.js, and MySQL. Experienced in developing REST APIs, designing relational databases, integrating third-party services such as Midtrans, Google OAuth, and Dacast, and building admin dashboards. Alongside working as a Software Engineer, also has experience building web applications as a freelance developer for organizations and small businesses.',
            'summary_id' => 'Backend / Fullstack Developer dengan pengalaman membangun aplikasi web production menggunakan Laravel, React.js, Node.js, dan MySQL. Berpengalaman mengembangkan REST API, merancang database relasional, mengintegrasikan layanan pihak ketiga seperti Midtrans, Google OAuth, dan Dacast, serta membangun dashboard administrasi. Selain bekerja sebagai Software Engineer, juga memiliki pengalaman mengembangkan aplikasi web sebagai freelance developer untuk kebutuhan organisasi dan UMKM.',
            'email' => 'mfadhlan1721@gmail.com',
            'phone' => '+62 838-3573-3486',
            'location' => 'Aceh Besar, Indonesia',
            'github_url' => 'https://github.com/simax1721',
            'linkedin_url' => 'https://www.linkedin.com/in/m-fadhlan-217897282/',
        ]);
    }

    private function seedSkills(): void
    {
        $categories = [
            'AI-Assisted Development' => [
                'highlighted' => true,
                'skills' => ['Vibe Coding', 'Claude Code', 'Codex'],
            ],
            'Backend' => [
                'highlighted' => false,
                'skills' => ['Laravel', 'PHP', 'Node.js', 'REST API', 'Authentication (Sanctum, OAuth)'],
            ],
            'Frontend' => [
                'highlighted' => false,
                'skills' => ['React.js', 'JavaScript', 'HTML', 'CSS', 'Bootstrap'],
            ],
            'Database' => [
                'highlighted' => false,
                'skills' => ['MySQL', 'PostgreSQL', 'Firebase'],
            ],
            'Tools' => [
                'highlighted' => false,
                'skills' => ['Git', 'GitHub', 'Postman'],
            ],
        ];

        $categoryOrder = 0;
        foreach ($categories as $categoryName => $data) {
            $category = SkillCategory::updateOrCreate(
                ['name' => $categoryName],
                ['order' => $categoryOrder++, 'highlighted' => $data['highlighted']],
            );

            foreach ($data['skills'] as $skillOrder => $skillName) {
                Skill::updateOrCreate(
                    ['name' => $skillName, 'skill_category_id' => $category->id],
                    ['order' => $skillOrder],
                );
            }
        }
    }

    private function seedExperiences(): void
    {
        $experiences = [
            [
                'title' => 'Software Engineer',
                'company' => 'PT. ADOC Kreatif Sinema',
                'period' => 'Apr 2026 - Present',
                'tech_stack' => ['Laravel', 'React.js', 'MySQL', 'REST API', 'Midtrans', 'Google OAuth', 'Dacast'],
                'bullets_en' => [
                    'Designed and built 40+ RESTful API endpoints (Laravel + Sanctum) for a rental-based film streaming platform, integrating a React frontend.',
                    'Implemented multi-method authentication: email/password, Google OAuth, and session-based auth (Sanctum SPA) across subdomains.',
                    'Designed a MySQL schema with 25 tables and 14 relationships between entities (including many-to-many), covering the film catalog, ratings, transactions, and content access system.',
                    'Built a content rental system with automatic 24-hour access per transaction.',
                    'Integrated the Midtrans (Snap API) payment gateway for end-to-end payments, including webhook notifications for transaction status.',
                    'Integrated the Dacast video streaming service for film playback.',
                    'Built an admin dashboard (54+ endpoints) for managing content, users, and transaction/viewing reports.',
                    'Designed an additional role-based access system for content partners (creators), letting them view transaction & viewing reports scoped only to their own content.',
                ],
                'bullets_id' => [
                    'Merancang dan membangun 40+ RESTful API endpoint (Laravel + Sanctum) untuk platform streaming film berbasis sistem rental, mengintegrasikan frontend React.',
                    'Mengimplementasikan autentikasi multi-metode: email/password, Google OAuth, dan session-based auth (Sanctum SPA) lintas subdomain.',
                    'Merancang skema database MySQL dengan 25 tabel dan 14 relasi antar entitas (termasuk relasi many-to-many), mencakup katalog film, rating, transaksi, dan sistem akses konten.',
                    'Membangun sistem rental konten dengan masa akses otomatis 24 jam per transaksi.',
                    'Mengintegrasikan payment gateway Midtrans (Snap API) untuk proses pembayaran end-to-end, termasuk webhook notifikasi status transaksi.',
                    'Mengintegrasikan layanan streaming video Dacast untuk pemutaran konten film.',
                    'Mengembangkan dashboard admin (54+ endpoint) untuk pengelolaan konten, pengguna, dan laporan transaksi/tontonan.',
                    'Merancang sistem role-based access tambahan untuk mitra konten (creator), memungkinkan akses laporan transaksi & tontonan yang di-scope hanya ke konten milik masing-masing mitra.',
                ],
            ],
            [
                'title' => 'Freelance Fullstack Developer',
                'company' => 'Self-employed',
                'period' => 'Dec 2024 - Present',
                'tech_stack' => ['Laravel', 'React.js', 'PHP', 'MySQL', 'Firebase', 'ESP32'],
                'bullets_en' => [
                    'Built web applications to client requirements, from needs analysis through implementation.',
                    'Designed databases, REST APIs, and admin dashboards.',
                    'Communicated directly with clients to define system requirements.',
                    'Handled deployment, maintenance, and bug fixes.',
                ],
                'bullets_id' => [
                    'Mengembangkan aplikasi web sesuai kebutuhan klien mulai dari analisis kebutuhan hingga implementasi.',
                    'Merancang database, REST API, dan dashboard administrasi.',
                    'Berkomunikasi langsung dengan klien untuk menentukan kebutuhan sistem.',
                    'Melakukan deployment, maintenance, dan perbaikan aplikasi.',
                ],
            ],
            [
                'title' => 'Web Designer (Internship)',
                'company' => 'Program Studi Teknologi Informasi - UIN Ar-Raniry',
                'period' => 'Mar 2023 - Apr 2023',
                'tech_stack' => ['Laravel', 'PHP'],
                'bullets_en' => [
                    'Redesigned the Information Technology Study Program website using Laravel and PHP.',
                    'Improved the interface and site structure.',
                ],
                'bullets_id' => [
                    'Mendesain ulang website Program Studi Teknologi Informasi menggunakan Laravel dan PHP.',
                    'Meningkatkan tampilan antarmuka dan struktur website.',
                ],
            ],
            [
                'title' => 'Internship',
                'company' => 'PTIPD UIN Ar-Raniry Banda Aceh',
                'period' => 'Jan 2019 - Mar 2019',
                'tech_stack' => ['PHP', 'CodeIgniter'],
                'bullets_en' => [
                    'Built a student computer certificate verification application using PHP and CodeIgniter.',
                ],
                'bullets_id' => [
                    'Mengembangkan aplikasi pengecekan sertifikat komputer mahasiswa menggunakan PHP dan CodeIgniter.',
                ],
            ],
        ];

        foreach ($experiences as $order => $exp) {
            Experience::updateOrCreate(
                ['title' => $exp['title'], 'company' => $exp['company']],
                [...$exp, 'order' => $order],
            );
        }
    }

    private function seedProjects(): void
    {
        $projects = [
            [
                'title' => 'Aceh Cinema',
                'subtitle_en' => 'Aceh Film Streaming, Now Closer',
                'subtitle_id' => 'Streaming Film Aceh, Kini Lebih Dekat',
                'description_en' => 'An Aceh film streaming platform built on a rental model, with Fiction & Documentary catalogs, payment gateway integration, and end-to-end video streaming.',
                'description_id' => 'Platform streaming film Aceh berbasis sistem rental, dengan katalog Fiksi & Dokumenter, integrasi payment gateway, dan layanan streaming video end-to-end.',
                'tech_stack' => ['Laravel', 'React.js', 'MySQL', 'Midtrans', 'Google OAuth', 'Dacast'],
                'bullets_en' => [
                    'Backend development & REST API',
                    'Rental system with automatic access expiry',
                    'Payment & streaming integration (Midtrans, Dacast)',
                    'Multi-subdomain architecture: acehcinema.com (public), api.acehcinema.com (admin dashboard & API), creator.acehcinema.com (content partner portal)',
                    'Creator portal — content partners log in to view transaction & viewing reports scoped to their own content',
                ],
                'bullets_id' => [
                    'Backend development & REST API',
                    'Rental system dengan masa akses otomatis',
                    'Payment & streaming integration (Midtrans, Dacast)',
                    'Arsitektur multi-subdomain: acehcinema.com (publik), api.acehcinema.com (admin dashboard & API), creator.acehcinema.com (portal mitra konten)',
                    'Creator portal — mitra konten login untuk memantau laporan transaksi & tontonan yang di-scope ke konten miliknya sendiri',
                ],
                'featured' => true,
                'demo_url' => 'https://acehcinema.com',
            ],
            [
                'title' => 'HydroSmart IoT',
                'subtitle_en' => 'IoT Monitoring System',
                'subtitle_id' => 'IoT Monitoring System',
                'description_en' => 'Real-time monitoring system for air temperature, water temperature, humidity, light intensity, TDS, and pH.',
                'description_id' => 'Sistem monitoring suhu udara, suhu air, kelembapan, intensitas cahaya, TDS, dan pH secara real-time.',
                'tech_stack' => ['ESP32', 'Firebase', 'React.js'],
                'bullets_en' => [
                    'Monitoring system for air temperature, water temperature, humidity, light intensity, TDS, and pH.',
                    'Sends sensor data to Firebase in real time.',
                    'Web-based monitoring dashboard.',
                    'Multiple sensors integrated via ESP32.',
                ],
                'bullets_id' => [
                    'Sistem monitoring suhu udara, suhu air, kelembapan, intensitas cahaya, TDS, dan pH.',
                    'Mengirim data sensor secara real-time ke Firebase.',
                    'Dashboard monitoring berbasis web.',
                    'Integrasi berbagai sensor menggunakan ESP32.',
                ],
                'featured' => false,
            ],
            [
                'title' => 'Berkah Bibit',
                'subtitle_en' => 'Plant Seedling E-commerce',
                'subtitle_id' => 'E-commerce Bibit Tanaman',
                'description_en' => 'A website for a plant seedling business, complete with an admin dashboard and product catalog management.',
                'description_id' => 'Website usaha penjualan bibit tanaman lengkap dengan dashboard administrasi dan manajemen katalog produk.',
                'tech_stack' => ['Laravel', 'React.js', 'MySQL', 'Midtrans'],
                'bullets_en' => [
                    'Admin dashboard.',
                    'Product management system.',
                    'Content and product catalog management.',
                    'Payment integration.',
                ],
                'bullets_id' => [
                    'Dashboard administrasi.',
                    'Sistem manajemen produk.',
                    'Manajemen konten dan katalog produk.',
                    'Payment Integration.',
                ],
                'featured' => false,
            ],
            [
                'title' => 'Amanah Aceh',
                'subtitle_en' => 'Organization Website & Content System',
                'subtitle_id' => 'Organization Website & Content System',
                'description_en' => 'An organization website with a content management system, profile, and leadership structure.',
                'description_id' => 'Website organisasi dengan sistem manajemen konten, profil, dan struktur kepengurusan.',
                'tech_stack' => ['Laravel', 'PHP', 'React.js', 'MySQL'],
                'bullets_en' => [
                    'Organization content & news management',
                    'Admin dashboard',
                    'Organization profile & structure',
                ],
                'bullets_id' => [
                    'Manajemen konten & berita organisasi',
                    'Dashboard admin',
                    'Profil & struktur organisasi',
                ],
                'featured' => false,
            ],
        ];

        foreach ($projects as $order => $project) {
            Project::updateOrCreate(
                ['title' => $project['title']],
                [...$project, 'order' => $order],
            );
        }
    }

    private function seedEducation(): void
    {
        EducationEntry::updateOrCreate(
            ['degree' => 'Bachelor of Information Technology', 'institution' => 'Universitas Islam Negeri Ar-Raniry'],
            ['period' => 'Oct 2020 - Feb 2025', 'order' => 0],
        );
    }

    private function seedOrganizations(): void
    {
        OrganizationEntry::updateOrCreate(
            ['role' => 'Head of Talent & Interest Division', 'organization' => 'HIMA-TI UIN Ar-Raniry'],
            [
                'year' => '2023',
                'description_en' => 'Ran the IT Club program to build students\' technical skills.',
                'description_id' => 'Menjalankan program IT Club untuk meningkatkan kemampuan teknis mahasiswa.',
                'order' => 0,
            ],
        );
    }
}
