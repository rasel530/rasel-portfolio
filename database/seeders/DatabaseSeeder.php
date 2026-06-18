<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdminUser();
        $this->seedProfile();
        $this->seedSkills();
        $this->seedExperiences();
        $this->seedEducations();
        $this->seedProjects();
        $this->seedTrainings();
        $this->seedMessages();
        $this->seedSiteSettings();
    }

    /**
     * Seed the initial administrator. The password is generated at seed
     * time and printed ONCE to the console so it can be recorded safely;
     * it is never committed or hardcoded.
     */
    private function seedAdminUser(): void
    {
        $plainPassword = str()->password(24);

        User::updateOrCreate(
            ['email' => 'admin@raselbepari.com'],
            [
                'name' => 'Rasel Bepari',
                'password' => Hash::make($plainPassword),
                'role' => User::ROLE_ADMIN,
            ]
        );

        $this->command->info('------------------------------------------------------------');
        $this->command->info('  Admin account created / updated.');
        $this->command->info('  Email:    admin@raselbepari.com');
        $this->command->info('  Password: '.$plainPassword);
        $this->command->info('  Record this now — it is NOT stored anywhere.');
        $this->command->info('  Use "Forgot password?" to reset it anytime.');
        $this->command->info('------------------------------------------------------------');
    }

    private function seedProfile(): void
    {
        Profile::updateOrCreate(
            ['email' => 'raselbepari.dev@gmail.com'],
            [
                'name' => 'Rasel Bepari',
                'title' => 'Full Stack Developer',
                'tagline' => 'I build clean, scalable, and delightful web experiences.',
                'summary' => 'I am a passionate Full Stack Developer with a strong focus on building robust, scalable web applications. I specialize in PHP, Laravel, and modern JavaScript frameworks, turning complex problems into elegant, user-friendly solutions. With a keen eye for detail and a commitment to clean code, I love bringing ideas to life through thoughtful design and efficient engineering.',
                'email' => 'raselbepari.dev@gmail.com',
                'phone' => '01914287530',
                'address' => 'S/3, 3RD FLOOR CRESCENT HOMES, 150/1 SHAH ALI BAG, MIRPUR-1, DHAKA-1216., Mirpur TSO, Mirpur, Dhaka 1216',
                'photo' => null,
                'facebook' => 'https://facebook.com/',
                'linkedin' => 'https://linkedin.com/',
                'github' => 'https://github.com/',
                'twitter' => 'https://twitter.com/',
                'resume_url' => null,
            ]
        );
    }

    private function seedSkills(): void
    {
        $skills = [
            ['name' => 'PHP', 'category' => 'Backend', 'proficiency' => 92, 'sort_order' => 1],
            ['name' => 'Laravel', 'category' => 'Backend', 'proficiency' => 95, 'sort_order' => 2],
            ['name' => 'MySQL', 'category' => 'Database', 'proficiency' => 88, 'sort_order' => 3],
            ['name' => 'PostgreSQL', 'category' => 'Database', 'proficiency' => 78, 'sort_order' => 4],
            ['name' => 'JavaScript', 'category' => 'Frontend', 'proficiency' => 90, 'sort_order' => 5],
            ['name' => 'Vue.js', 'category' => 'Frontend', 'proficiency' => 85, 'sort_order' => 6],
            ['name' => 'React', 'category' => 'Frontend', 'proficiency' => 80, 'sort_order' => 7],
            ['name' => 'HTML5 & CSS3', 'category' => 'Frontend', 'proficiency' => 95, 'sort_order' => 8],
            ['name' => 'Tailwind CSS', 'category' => 'Frontend', 'proficiency' => 88, 'sort_order' => 9],
            ['name' => 'Git & GitHub', 'category' => 'Tools', 'proficiency' => 90, 'sort_order' => 10],
            ['name' => 'Docker', 'category' => 'Tools', 'proficiency' => 72, 'sort_order' => 11],
            ['name' => 'REST API', 'category' => 'Backend', 'proficiency' => 90, 'sort_order' => 12],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['name' => $skill['name']],
                $skill
            );
        }
    }

    private function seedExperiences(): void
    {
        $experiences = [
            [
                'position' => 'Senior Full Stack Developer',
                'company' => 'Tech Solutions Ltd.',
                'description' => 'Leading a team of developers to build and maintain scalable web applications. Architected RESTful APIs, optimized database performance, and mentored junior developers while delivering high-quality software solutions.',
                'start_date' => '2022-03-01',
                'end_date' => null,
                'is_current' => true,
                'sort_order' => 1,
            ],
            [
                'position' => 'Software Developer',
                'company' => 'Digital Innovation Inc.',
                'description' => 'Developed and maintained multiple client web applications using Laravel and Vue.js. Collaborated with cross-functional teams to deliver projects on time and improve code quality through code reviews.',
                'start_date' => '2020-06-01',
                'end_date' => '2022-02-28',
                'is_current' => false,
                'sort_order' => 2,
            ],
            [
                'position' => 'Junior Web Developer',
                'company' => 'WebCraft Agency',
                'description' => 'Built responsive websites and implemented UI designs. Gained hands-on experience with PHP, JavaScript, and modern frontend workflows while contributing to a variety of client projects.',
                'start_date' => '2019-01-01',
                'end_date' => '2020-05-31',
                'is_current' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::updateOrCreate(
                [
                    'position' => $experience['position'],
                    'company' => $experience['company'],
                ],
                $experience
            );
        }
    }

    private function seedEducations(): void
    {
        $educations = [
            [
                'degree' => 'B.Sc. in Computer Science & Engineering',
                'institution' => 'University of Dhaka',
                'description' => 'Specialized in software engineering and web technologies. Completed multiple projects focusing on full-stack development and algorithms.',
                'start_year' => 2015,
                'end_year' => 2019,
                'is_current' => false,
                'result' => 'CGPA 3.75 / 4.00',
                'sort_order' => 1,
            ],
            [
                'degree' => 'Higher Secondary Certificate (Science)',
                'institution' => 'Dhaka City College',
                'description' => 'Completed higher secondary education with a focus on mathematics, physics, and computer science.',
                'start_year' => 2012,
                'end_year' => 2014,
                'is_current' => false,
                'result' => 'GPA 4.80 / 5.00',
                'sort_order' => 2,
            ],
        ];

        foreach ($educations as $education) {
            Education::updateOrCreate(
                [
                    'degree' => $education['degree'],
                    'institution' => $education['institution'],
                ],
                $education
            );
        }
    }

    private function seedProjects(): void
    {
        $projects = [
            [
                'title' => 'E-Commerce Platform',
                'description' => 'A full-featured online store with product catalog, cart, secure checkout, order management, and an admin dashboard. Built for performance and scalability.',
                'image' => null,
                'demo_url' => 'https://example.com',
                'source_url' => 'https://github.com/',
                'technologies' => 'Laravel, Vue.js, MySQL, Stripe API, Redis',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Task Management App',
                'description' => 'A collaborative task management application with real-time updates, drag-and-drop boards, team workspaces, and notifications to keep teams productive.',
                'image' => null,
                'demo_url' => 'https://example.com',
                'source_url' => 'https://github.com/',
                'technologies' => 'Laravel, React, PostgreSQL, WebSockets',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Portfolio CMS',
                'description' => 'A custom content management system for managing personal portfolios with a flexible admin panel, media uploads, and a customizable public theme.',
                'image' => null,
                'demo_url' => 'https://example.com',
                'source_url' => 'https://github.com/',
                'technologies' => 'Laravel, Livewire, Tailwind CSS',
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'title' => 'Real-Time Chat Application',
                'description' => 'A modern chat application featuring instant messaging, typing indicators, file sharing, and group conversations powered by WebSockets.',
                'image' => null,
                'demo_url' => 'https://example.com',
                'source_url' => 'https://github.com/',
                'technologies' => 'Laravel, Vue.js, Pusher, Redis',
                'is_featured' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['title' => $project['title']],
                $project
            );
        }
    }

    private function seedTrainings(): void
    {
        $trainings = [
            [
                'title' => 'Laravel Advanced Certification',
                'organization' => 'Laravel LLC',
                'description' => 'Comprehensive certification covering advanced Laravel framework concepts, architecture, and best practices.',
                'long_description' => "This certification validated deep expertise in the Laravel framework, including:\n\n- Advanced Eloquent ORM techniques and relationships\n- Queue and job processing at scale\n- Testing strategies with PHPUnit and Pest\n- API development and authentication (Sanctum/Passport)\n- Performance optimization and profiling\n- Laravel Nova and package development\n\nThe program consisted of rigorous hands-on projects and a final examination.",
                'duration' => '3 months',
                'start_year' => 2023,
                'end_year' => 2023,
                'certificate_url' => 'https://example.com/laravel-cert.pdf',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'AWS Certified Solutions Architect',
                'organization' => 'Amazon Web Services',
                'description' => 'Professional-level certification for designing distributed systems on AWS.',
                'long_description' => "Earned the AWS Certified Solutions Architect – Associate certification, demonstrating the ability to:\n\n- Design resilient and high-availability architectures\n- Select appropriate AWS services based on requirements\n- Implement cost-optimization strategies\n- Configure networking, security, and data storage solutions\n\nThis certification is recognized globally as a benchmark for cloud architecture competence.",
                'duration' => '6 months',
                'start_year' => 2022,
                'end_year' => 2022,
                'certificate_url' => 'https://example.com/aws-cert.pdf',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Full Stack Web Development Bootcamp',
                'organization' => 'Programming Hero',
                'description' => 'Intensive bootcamp covering HTML, CSS, JavaScript, React, Node.js, and MongoDB.',
                'long_description' => "A 6-month intensive program that covered the complete full-stack development lifecycle:\n\n- Frontend: HTML5, CSS3, JavaScript ES6+, React.js\n- Backend: Node.js, Express, REST API design\n- Database: MongoDB, Mongoose\n- DevOps: Git, deployment, CI/CD basics\n\nCompleted multiple real-world projects including an e-commerce platform and a social media app.",
                'duration' => '6 months',
                'start_year' => 2019,
                'end_year' => 2019,
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($trainings as $training) {
            Training::updateOrCreate(
                ['title' => $training['title']],
                $training
            );
        }
    }

    private function seedMessages(): void
    {
        if (Message::count() > 0) {
            return;
        }

        $messages = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'subject' => 'Project Inquiry',
                'message' => 'Hi Rasel, I came across your portfolio and I am impressed with your work. We are looking for a developer for an upcoming project. Are you available for a chat?',
                'phone' => '+1234567890',
                'is_read' => false,
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael@example.com',
                'subject' => 'Job Opportunity',
                'message' => 'Hello, we have an exciting full-stack role that matches your skill set perfectly. Would love to discuss this opportunity with you.',
                'phone' => null,
                'is_read' => true,
            ],
        ];

        foreach ($messages as $message) {
            Message::create($message);
        }
    }

    private function seedSiteSettings(): void
    {
        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'nav_items' => [
                    ['label' => 'Home', 'url' => '#home'],
                    ['label' => 'About', 'url' => '#about'],
                    ['label' => 'Skills', 'url' => '#skills'],
                    ['label' => 'Experience', 'url' => '#experience'],
                    ['label' => 'Education', 'url' => '#education'],
                    ['label' => 'Projects', 'url' => '#projects'],
                    ['label' => 'Contact', 'url' => '#contact'],
                ],
                'show_nav_cta' => true,
                'nav_cta_label' => 'Hire Me',
                'nav_cta_url' => '#contact',
                'notification_email' => null,
                'stats_items' => [
                    ['value' => '{experience_count}', 'label' => 'Years Experience'],
                    ['value' => '{project_count}', 'label' => 'Projects Completed'],
                    ['value' => '{skill_count}', 'label' => 'Technologies'],
                    ['value' => '50', 'label' => 'Happy Clients'],
                ],
                'section_content' => [
                    'about'      => ['subtitle' => 'Get To Know Me', 'title' => 'About Me', 'empty' => ''],
                    'skills'     => ['subtitle' => 'What I Know', 'title' => 'My Skills', 'empty' => 'Skills coming soon.'],
                    'experience' => ['subtitle' => 'My Journey', 'title' => 'Work Experience', 'empty' => 'Experience details coming soon.'],
                    'education'  => ['subtitle' => 'My Background', 'title' => 'Education', 'empty' => 'Education details coming soon.'],
                    'projects'   => ['subtitle' => 'My Work', 'title' => 'Featured Projects', 'empty' => 'Projects coming soon.'],
                    'training'   => ['subtitle' => 'My Certifications', 'title' => 'Training & Certifications', 'empty' => 'Training details coming soon.'],
                    'contact'    => ['subtitle' => 'Get In Touch', 'title' => 'Contact Me', 'empty' => ''],
                ],
                'hero_content' => [
                    'greeting'     => "Hello, I'm",
                    'download_cv'  => 'Download CV',
                    'contact_me'   => 'Contact Me',
                    'typed_titles' => 'Full Stack Developer, Software Engineer',
                ],
                'contact_content' => [
                    'heading'      => "Let's Talk About Your Project",
                    'description'  => "Feel free to reach out for collaborations or just a friendly hello. I'm always open to discussing new projects, creative ideas, or opportunities to be part of your vision.",
                    'send_message' => 'Send Message',
                    'name'         => 'Your Name',
                    'email'        => 'Your Email',
                    'subject'      => 'Subject',
                    'phone'        => 'Phone (optional)',
                    'message'      => 'Your Message',
                ],
                'labels' => [
                    'present'  => 'Present',
                    'current'  => 'Current',
                    'featured' => 'Featured',
                ],
                'admin_branding' => [
                    'title'      => 'Rasel Bepari',
                    'mark'       => 'RB',
                    'panel_name' => 'Admin Panel',
                ],
                'footer_about' => 'Full Stack Developer building clean, scalable, and delightful web experiences.',
                'footer_copyright' => '© {year} {name}. All rights reserved. Designed & built with care.',
                'footer_nav_items' => [
                    ['label' => 'Home', 'url' => '#home'],
                    ['label' => 'About', 'url' => '#about'],
                    ['label' => 'Projects', 'url' => '#projects'],
                    ['label' => 'Contact', 'url' => '#contact'],
                ],
                'meta_title' => 'Rasel Bepari | Full Stack Developer',
                'meta_description' => 'Rasel Bepari — Full Stack Developer specializing in PHP, Laravel, and modern JavaScript frameworks.',
                'meta_keywords' => 'laravel, full stack developer, php, web developer, software engineer',
            ]
        );
    }
}
