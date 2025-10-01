<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserSkill;
use App\Models\UserExperience;
use App\Models\UserProject;
use App\Models\UserEducation;
use App\Models\UserCertification;
use Carbon\Carbon;

class UserPortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pakistani names (70% - 35 users)
        $pakistaniNames = [
            'Ahmad Hassan', 'Fatima Ali', 'Muhammad Usman', 'Ayesha Khan', 'Ali Raza',
            'Sara Ahmed', 'Hassan Sheikh', 'Zainab Malik', 'Omar Farooq', 'Aisha Siddiqui',
            'Bilal Khan', 'Maryam Hassan', 'Tariq Ali', 'Nida Ahmed', 'Saad Malik',
            'Hina Sheikh', 'Waseem Khan', 'Saima Ali', 'Fahad Ahmed', 'Rabia Malik',
            'Imran Sheikh', 'Sadia Khan', 'Kamran Ali', 'Nazia Ahmed', 'Rizwan Malik',
            'Saba Sheikh', 'Adnan Khan', 'Farah Ali', 'Shahid Ahmed', 'Noreen Malik',
            'Junaid Sheikh', 'Tahira Khan', 'Naveed Ali', 'Sana Ahmed', 'Asif Malik'
        ];

        // UK names (10% - 5 users)
        $ukNames = [
            'James Thompson', 'Emma Wilson', 'Oliver Brown', 'Sophie Davis', 'Harry Taylor'
        ];

        // Indian names (10% - 5 users)
        $indianNames = [
            'Rajesh Kumar', 'Priya Sharma', 'Amit Patel', 'Deepika Singh', 'Vikram Gupta'
        ];

        // Bangladeshi names (10% - 5 users)
        $bangladeshiNames = [
            'Rahman Ahmed', 'Fatema Begum', 'Karim Hossain', 'Nasrin Akter', 'Mizanur Rahman'
        ];

        // Pakistani cities
        $pakistaniCities = [
            'Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar', 'Quetta', 'Sialkot', 'Gujranwala'
        ];

        // UK cities
        $ukCities = [
            'London', 'Manchester', 'Birmingham', 'Liverpool', 'Leeds', 'Sheffield', 'Bristol', 'Newcastle', 'Nottingham', 'Leicester'
        ];

        // Indian cities
        $indianCities = [
            'Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Kolkata', 'Hyderabad', 'Pune', 'Ahmedabad', 'Jaipur', 'Surat'
        ];

        // Bangladeshi cities
        $bangladeshiCities = [
            'Dhaka', 'Chittagong', 'Sylhet', 'Rajshahi', 'Khulna', 'Barisal', 'Rangpur', 'Mymensingh', 'Comilla', 'Jessore'
        ];

        // Professions
        $professions = [
            'Full Stack Developer', 'UI/UX Designer', 'Mobile App Developer', 'Data Scientist',
            'Digital Marketing Specialist', 'Graphic Designer', 'DevOps Engineer', 'Product Manager',
            'Content Writer', 'Photographer', 'Video Editor', 'Business Analyst', 'Sales Manager',
            'Project Manager', 'Quality Assurance Engineer', 'System Administrator', 'Database Administrator',
            'Frontend Developer', 'Backend Developer', 'Cloud Architect', 'Cybersecurity Specialist',
            'Machine Learning Engineer', 'Web Designer', 'Social Media Manager', 'SEO Specialist',
            'Financial Analyst', 'HR Specialist', 'Operations Manager', 'Consultant', 'Freelancer'
        ];

        // Skills database
        $allSkills = [
            'PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'Angular', 'Node.js', 'Python', 'Django', 'Flask',
            'Java', 'Spring Boot', 'C#', '.NET', 'Ruby', 'Rails', 'Go', 'Rust', 'Swift', 'Kotlin',
            'HTML5', 'CSS3', 'SASS', 'SCSS', 'Bootstrap', 'Tailwind CSS', 'jQuery', 'TypeScript',
            'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'Elasticsearch', 'SQLite', 'Oracle',
            'AWS', 'Azure', 'Google Cloud', 'Docker', 'Kubernetes', 'Jenkins', 'Git', 'GitHub',
            'Figma', 'Adobe XD', 'Sketch', 'Photoshop', 'Illustrator', 'InDesign', 'Premiere Pro',
            'After Effects', 'Blender', 'Maya', 'Cinema 4D', 'Unity', 'Unreal Engine',
            'WordPress', 'Shopify', 'Magento', 'WooCommerce', 'Drupal', 'Joomla',
            'REST API', 'GraphQL', 'Microservices', 'Agile', 'Scrum', 'DevOps', 'CI/CD',
            'Machine Learning', 'Deep Learning', 'TensorFlow', 'PyTorch', 'Pandas', 'NumPy',
            'Data Analysis', 'Business Intelligence', 'Tableau', 'Power BI', 'Excel', 'Google Analytics',
            'Digital Marketing', 'SEO', 'SEM', 'Social Media Marketing', 'Email Marketing', 'Content Marketing',
            'Project Management', 'Leadership', 'Communication', 'Problem Solving', 'Teamwork', 'Time Management'
        ];

        // Company names
        $companies = [
            'TechCorp Solutions', 'Digital Innovations Ltd', 'Creative Minds Agency', 'DataFlow Systems',
            'CloudTech Enterprises', 'WebCraft Studios', 'MobileFirst Solutions', 'DesignHub Creative',
            'CodeCraft Technologies', 'PixelPerfect Design', 'DataDriven Analytics', 'CloudScale Solutions',
            'InnovateTech Labs', 'CreativeFlow Agency', 'TechNest Solutions', 'DigitalWave Studios',
            'CodeForge Technologies', 'DesignCraft Studio', 'DataVault Systems', 'CloudBridge Solutions',
            'TechFlow Innovations', 'CreativeEdge Agency', 'CodeCraft Studios', 'DataMine Analytics',
            'CloudFirst Technologies', 'DesignLab Creative', 'TechNest Solutions', 'DigitalCraft Agency',
            'CodeFlow Technologies', 'PixelCraft Design', 'DataStream Analytics', 'CloudTech Solutions'
        ];

        // Universities
        $universities = [
            'University of Karachi', 'Lahore University of Management Sciences', 'Quaid-i-Azam University',
            'National University of Sciences and Technology', 'University of the Punjab', 'Aga Khan University',
            'University of Cambridge', 'Oxford University', 'Imperial College London', 'University College London',
            'Indian Institute of Technology', 'University of Delhi', 'Jawaharlal Nehru University',
            'University of Dhaka', 'Bangladesh University of Engineering and Technology', 'North South University'
        ];

        // Certifications
        $certifications = [
            'AWS Certified Solutions Architect', 'Google Cloud Professional', 'Microsoft Azure Fundamentals',
            'Certified Scrum Master', 'PMP Certification', 'Google Analytics Certified', 'HubSpot Content Marketing',
            'Adobe Certified Expert', 'Cisco CCNA', 'CompTIA Security+', 'Certified Ethical Hacker',
            'Salesforce Administrator', 'Google Ads Certified', 'Facebook Blueprint Certified',
            'HubSpot Inbound Marketing', 'Google Digital Marketing', 'Microsoft Office Specialist',
            'Oracle Database Administrator', 'Red Hat Certified Engineer', 'VMware Certified Professional'
        ];

        // Project types
        $projectTypes = [
            'E-commerce Website', 'Mobile Application', 'Web Application', 'Desktop Software',
            'Data Analytics Dashboard', 'Machine Learning Model', 'API Development', 'CMS Development',
            'Portfolio Website', 'Blog Platform', 'Social Media App', 'Inventory Management System',
            'Customer Relationship Management', 'Learning Management System', 'Real Estate Platform',
            'Food Delivery App', 'Healthcare Management System', 'Banking Application', 'Travel Booking System'
        ];

        // Combine all names
        $allNames = array_merge($pakistaniNames, $ukNames, $indianNames, $bangladeshiNames);
        $allCities = array_merge($pakistaniCities, $ukCities, $indianCities, $bangladeshiCities);

        // Create 50 users
        for ($i = 0; $i < 50; $i++) {
            $name = $allNames[$i];
            $username = Str::slug($name) . '_' . ($i + 1);
            $email = Str::slug($name) . ($i + 1) . '@example.com';
            $city = $allCities[array_rand($allCities)];
            $profession = $professions[array_rand($professions)];
            $portfolioSlug = 'portfolio-' . Str::slug($name) . '-' . ($i + 1);

            // Create user
            $user = User::create([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'password' => Hash::make('password123'),
                'status' => 'active',
                'role' => 'user',
                'profession' => $profession,
                'bio' => $this->generateBio($profession, $name),
                'countries_id' => $this->getCountryId($city),
                'avatar' => null, // No avatar - will show initials
                'portfolio_slug' => $portfolioSlug,
                'portfolio_private' => rand(0, 1), // Mix of public and private
                'portfolio_primary_color' => $this->getRandomColor(),
                'portfolio_secondary_color' => $this->getRandomColor(),
                'date' => Carbon::now()->subDays(rand(1, 365)),
            ]);

            // Add skills (5-15 skills per user)
            $this->addSkills($user, $allSkills);

            // Add experience (2-5 experiences per user)
            $this->addExperience($user, $companies, $professions);

            // Add projects (3-8 projects per user)
            $this->addProjects($user, $projectTypes, $allSkills);

            // Add education (1-3 education entries per user)
            $this->addEducation($user, $universities);

            // Add certifications (2-6 certifications per user)
            $this->addCertifications($user, $certifications);
        }
    }

    private function generateBio($profession, $name)
    {
        $bios = [
            "Passionate {$profession} with extensive experience in delivering high-quality solutions. I love creating innovative products that make a difference in people's lives.",
            "Experienced {$profession} specializing in modern technologies and best practices. Committed to continuous learning and professional growth.",
            "Creative {$profession} with a strong background in problem-solving and team collaboration. I enjoy tackling complex challenges and delivering exceptional results.",
            "Dedicated {$profession} with a proven track record of successful project delivery. Passionate about technology and its potential to transform businesses.",
            "Results-driven {$profession} with expertise in multiple technologies. I believe in the power of clean code and user-centered design.",
            "Innovative {$profession} with a focus on scalable solutions and best practices. Always eager to learn new technologies and methodologies.",
            "Professional {$profession} with strong analytical skills and attention to detail. Committed to delivering excellence in every project.",
            "Dynamic {$profession} with experience across various industries. I thrive in fast-paced environments and enjoy working with diverse teams."
        ];

        return $bios[array_rand($bios)];
    }

    private function getCountryId($city)
    {
        // This is a simplified approach - in reality, you'd query the countries table
        $countryMap = [
            'Karachi' => 1, 'Lahore' => 1, 'Islamabad' => 1, 'Rawalpindi' => 1, 'Faisalabad' => 1,
            'London' => 2, 'Manchester' => 2, 'Birmingham' => 2, 'Liverpool' => 2, 'Leeds' => 2,
            'Mumbai' => 3, 'Delhi' => 3, 'Bangalore' => 3, 'Chennai' => 3, 'Kolkata' => 3,
            'Dhaka' => 4, 'Chittagong' => 4, 'Sylhet' => 4, 'Rajshahi' => 4, 'Khulna' => 4
        ];

        return $countryMap[$city] ?? 1; // Default to Pakistan if not found
    }

    private function getRandomColor()
    {
        $colors = [
            '#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6f42c1',
            '#e83e8c', '#fd7e14', '#20c997', '#6c757d', '#343a40', '#f8f9fa'
        ];

        return $colors[array_rand($colors)];
    }

    private function addSkills($user, $allSkills)
    {
        $userSkills = array_rand($allSkills, rand(5, 15));
        $proficiencyLevels = ['beginner', 'intermediate', 'advanced', 'expert'];

        foreach ($userSkills as $skillIndex) {
            UserSkill::create([
                'user_id' => $user->id,
                'skill_name' => $allSkills[$skillIndex],
                'proficiency_level' => $proficiencyLevels[array_rand($proficiencyLevels)],
                'description' => 'Proficient in ' . $allSkills[$skillIndex] . ' with hands-on experience.',
                'fas_icon' => 'fas fa-code',
                'status' => 'active'
            ]);
        }
    }

    private function addExperience($user, $companies, $professions)
    {
        $experienceCount = rand(2, 5);
        $employmentTypes = ['full_time', 'part_time', 'contract', 'freelance'];

        for ($i = 0; $i < $experienceCount; $i++) {
            $startDate = Carbon::now()->subYears(rand(1, 5))->subMonths(rand(0, 11));
            $endDate = $i === 0 ? null : $startDate->copy()->addMonths(rand(6, 24));
            $isCurrent = $i === 0;

            UserExperience::create([
                'user_id' => $user->id,
                'company_name' => $companies[array_rand($companies)],
                'job_title' => $professions[array_rand($professions)],
                'employment_type' => $employmentTypes[array_rand($employmentTypes)],
                'location' => $this->getRandomCity(),
                'description' => $this->generateExperienceDescription($professions[array_rand($professions)]),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_current' => $isCurrent,
                'achievements' => implode(', ', $this->generateAchievements()),
                'technologies_used' => implode(', ', array_slice($this->getAllSkills(), 0, rand(3, 8))),
                'status' => 'active',
                'sort_order' => $i + 1
            ]);
        }
    }

    private function addProjects($user, $projectTypes, $allSkills)
    {
        $projectCount = rand(3, 8);
        $projectStatuses = ['completed', 'in_progress', 'planning'];
        $projectTypesList = ['personal', 'professional', 'freelance', 'open_source'];

        for ($i = 0; $i < $projectCount; $i++) {
            $projectType = $projectTypes[array_rand($projectTypes)];
            $technologies = array_rand($allSkills, rand(3, 8));
            $startDate = Carbon::now()->subMonths(rand(1, 12));
            $endDate = rand(0, 1) ? $startDate->copy()->addMonths(rand(1, 6)) : null;

            UserProject::create([
                'user_id' => $user->id,
                'project_name' => $projectType . ' - ' . ($i + 1),
                'description' => $this->generateProjectDescription($projectType),
                'project_type' => $projectTypesList[array_rand($projectTypesList)],
                'status' => $projectStatuses[array_rand($projectStatuses)],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'project_url' => 'https://example.com/project' . ($i + 1),
                'github_url' => 'https://github.com/' . $user->username . '/project' . ($i + 1),
                'demo_url' => 'https://demo.example.com/project' . ($i + 1),
                'technologies' => array_map(function($index) use ($allSkills) {
                    return $allSkills[$index];
                }, $technologies),
                'client_name' => rand(0, 1) ? 'Client ' . ($i + 1) : null,
                'role' => 'Lead Developer',
                'team_size' => rand(1, 8),
                'key_features' => 'Responsive design, User authentication, Real-time updates, Mobile optimization',
                'challenges_solved' => 'Performance optimization, Cross-browser compatibility, Scalability issues',
                'visibility' => 'public',
                'featured' => rand(0, 1) == 1
            ]);
        }
    }

    private function addEducation($user, $universities)
    {
        $educationCount = rand(1, 3);
        $degrees = ['Bachelor of Science', 'Master of Science', 'Bachelor of Arts', 'Master of Arts', 'Diploma', 'Certificate', 'PhD'];
        $educationLevels = ['bachelor', 'master', 'doctorate', 'diploma', 'certificate'];

        for ($i = 0; $i < $educationCount; $i++) {
            $startDate = Carbon::now()->subYears(rand(2, 6))->subMonths(rand(0, 11));
            $endDate = $startDate->copy()->addYears(rand(2, 4));
            $isCurrent = $i === 0 && rand(0, 1);

            UserEducation::create([
                'user_id' => $user->id,
                'institution_name' => $universities[array_rand($universities)],
                'degree' => $degrees[array_rand($degrees)],
                'field_of_study' => $this->getRandomField(),
                'education_level' => $educationLevels[array_rand($educationLevels)],
                'start_date' => $startDate,
                'end_date' => $isCurrent ? null : $endDate,
                'is_current' => $isCurrent,
                'grade' => rand(0, 1) ? 'A+' : 'A',
                'description' => $this->generateEducationDescription(),
                'activities' => 'Student Council, Debate Team, Coding Club, Sports Team',
                'location' => $this->getRandomCity(),
                'status' => 'active',
                'sort_order' => $i + 1
            ]);
        }
    }

    private function addCertifications($user, $certifications)
    {
        $certCount = rand(2, 6);
        $userCerts = array_rand($certifications, min($certCount, count($certifications)));

        foreach ($userCerts as $certIndex) {
            $issueDate = Carbon::now()->subDays(rand(30, 1095));
            $expiryDate = $issueDate->copy()->addDays(rand(365, 1095));
            $doesNotExpire = rand(0, 1) == 1;

            UserCertification::create([
                'user_id' => $user->id,
                'name' => $certifications[$certIndex],
                'issuing_organization' => $this->getRandomIssuer(),
                'issue_date' => $issueDate,
                'expiry_date' => $doesNotExpire ? null : $expiryDate,
                'does_not_expire' => $doesNotExpire,
                'credential_id' => 'CERT-' . strtoupper(Str::random(8)),
                'credential_url' => 'https://verify.example.com/cert/' . strtoupper(Str::random(8)),
                'description' => 'Professional certification demonstrating expertise in the field.',
                'skills_gained' => 'Technical skills, Problem solving, Industry best practices, Professional development',
                'status' => 'active',
                'sort_order' => $certIndex + 1
            ]);
        }
    }

    private function generateExperienceDescription($position)
    {
        $descriptions = [
            "Led development of scalable web applications using modern technologies and best practices.",
            "Collaborated with cross-functional teams to deliver high-quality software solutions on time and within budget.",
            "Implemented agile methodologies and continuous integration practices to improve development efficiency.",
            "Mentored junior developers and conducted code reviews to ensure code quality and knowledge sharing.",
            "Designed and developed responsive user interfaces that enhanced user experience and engagement.",
            "Optimized application performance and implemented security best practices to ensure robust solutions.",
            "Managed project timelines and coordinated with stakeholders to deliver successful project outcomes.",
            "Researched and implemented new technologies to improve system architecture and development processes."
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function generateAchievements()
    {
        $achievements = [
            "Increased team productivity by 30% through process improvements",
            "Reduced application load time by 50% through optimization",
            "Led a team of 5 developers on a critical project",
            "Implemented automated testing reducing bugs by 40%",
            "Successfully delivered 15+ projects on time and within budget",
            "Improved user satisfaction scores by 25%",
            "Mentored 3 junior developers who were promoted",
            "Reduced server costs by 35% through cloud optimization"
        ];

        $selectedAchievements = array_rand($achievements, rand(2, 4));
        return array_map(function($index) use ($achievements) {
            return $achievements[$index];
        }, $selectedAchievements);
    }

    private function getAllSkills()
    {
        return [
            'PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'Angular', 'Node.js', 'Python', 'Django', 'Flask',
            'Java', 'Spring Boot', 'C#', '.NET', 'Ruby', 'Rails', 'Go', 'Rust', 'Swift', 'Kotlin',
            'HTML5', 'CSS3', 'SASS', 'SCSS', 'Bootstrap', 'Tailwind CSS', 'jQuery', 'TypeScript',
            'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'Elasticsearch', 'SQLite', 'Oracle',
            'AWS', 'Azure', 'Google Cloud', 'Docker', 'Kubernetes', 'Jenkins', 'Git', 'GitHub'
        ];
    }

    private function getRandomCity()
    {
        $cities = [
            'Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar', 'Quetta',
            'London', 'Manchester', 'Birmingham', 'Liverpool', 'Leeds', 'Sheffield', 'Bristol', 'Newcastle',
            'Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Kolkata', 'Hyderabad', 'Pune', 'Ahmedabad',
            'Dhaka', 'Chittagong', 'Sylhet', 'Rajshahi', 'Khulna', 'Barisal', 'Rangpur', 'Mymensingh'
        ];

        return $cities[array_rand($cities)];
    }

    private function generateProjectDescription($projectType)
    {
        $descriptions = [
            "A comprehensive {$projectType} built with modern technologies and responsive design. Features include user authentication, real-time updates, and mobile optimization.",
            "An innovative {$projectType} that solves real-world problems through intuitive user interface and robust backend architecture.",
            "A scalable {$projectType} designed for high performance and user engagement. Includes advanced features and seamless user experience.",
            "A professional {$projectType} with clean code architecture and comprehensive documentation. Built with security and performance in mind.",
            "An elegant {$projectType} featuring modern design patterns and best practices. Optimized for both desktop and mobile platforms."
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomField()
    {
        $fields = [
            'Computer Science', 'Software Engineering', 'Information Technology', 'Data Science',
            'Business Administration', 'Marketing', 'Design', 'Engineering', 'Mathematics',
            'Economics', 'Psychology', 'Communication', 'Management', 'Finance'
        ];

        return $fields[array_rand($fields)];
    }

    private function generateEducationDescription()
    {
        $descriptions = [
            "Comprehensive program covering fundamental concepts and practical applications in the field.",
            "Rigorous curriculum with focus on theoretical knowledge and hands-on experience.",
            "Advanced studies with emphasis on research and innovation in the discipline.",
            "Professional program designed to prepare students for industry challenges and opportunities.",
            "Specialized training with practical projects and industry collaboration."
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomIssuer()
    {
        $issuers = [
            'Amazon Web Services', 'Google Cloud', 'Microsoft', 'Cisco', 'Adobe',
            'HubSpot', 'Salesforce', 'Oracle', 'CompTIA', 'Project Management Institute',
            'Scrum Alliance', 'Facebook', 'LinkedIn', 'Coursera', 'Udemy'
        ];

        return $issuers[array_rand($issuers)];
    }
}
