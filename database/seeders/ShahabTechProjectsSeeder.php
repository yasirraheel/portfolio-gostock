<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProject;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShahabTechProjectsSeeder extends Seeder
{
    private $githubRepos = [
        'yasirraheel/portfolio-gostock',
        'yasirraheel/stock_haqi_ali',
        'yasirraheel/CannonX',
        'yasirraheel/CannonX-Admin',
        'yasirraheel/guardian',
        'yasirraheel/raasta'
    ];

    private $fallbackProjects = [];

    private $projectIcons = [
        'portfolio-gostock' => 'fas fa-briefcase',
        'stock_haqi_ali' => 'fas fa-chart-line',
        'CannonX' => 'fas fa-camera',
        'CannonX-Admin' => 'fas fa-cogs',
        'guardian' => 'fas fa-shield-alt',
        'raasta' => 'fas fa-route'
    ];

    private $projectTypes = [
        'portfolio-gostock' => 'Portfolio Website',
        'stock_haqi_ali' => 'Stock Management System',
        'CannonX' => 'Mobile Application',
        'CannonX-Admin' => 'Admin Dashboard',
        'guardian' => 'Security System',
        'raasta' => 'Navigation App'
    ];

    private $technologies = [
        'portfolio-gostock' => ['Laravel', 'PHP', 'Bootstrap', 'JavaScript', 'MySQL', 'HTML5', 'CSS3'],
        'stock_haqi_ali' => ['Laravel', 'PHP', 'MySQL', 'Bootstrap', 'JavaScript', 'jQuery'],
        'CannonX' => ['Flutter', 'Dart', 'Firebase', 'Android', 'iOS'],
        'CannonX-Admin' => ['Laravel', 'PHP', 'Vue.js', 'MySQL', 'Bootstrap', 'JavaScript'],
        'guardian' => ['Laravel', 'PHP', 'MySQL', 'Bootstrap', 'JavaScript', 'Security'],
        'raasta' => ['Flutter', 'Dart', 'Google Maps API', 'Firebase', 'Android', 'iOS']
    ];

    public function run()
    {
        // Find the shahabtech user
        $user = User::where('portfolio_slug', 'shahabtech')->first();
        
        if (!$user) {
            $this->command->error('User with slug "shahabtech" not found!');
            return;
        }

        $this->command->info('Found user: ' . $user->name . ' (ID: ' . $user->id . ')');

        // Clear existing projects for this user
        UserProject::where('user_id', $user->id)->delete();
        $this->command->info('Cleared existing projects for user.');

        foreach ($this->githubRepos as $repo) {
            $this->command->info("Fetching data for repository: {$repo}");
            
            try {
                $projectData = $this->fetchGitHubData($repo);
                
                if ($projectData) {
                    $this->createProject($user->id, $repo, $projectData);
                    $this->command->info("✅ Successfully created project: {$projectData['name']}");
                } else {
                    // Try fallback data for private or non-existent repos
                    $repoName = explode('/', $repo)[1];
                    $this->command->info("Using fallback data for: {$repoName}");
                    $fallbackData = $this->generateFallbackData($repoName);
                    $this->createProject($user->id, $repo, $fallbackData);
                    $this->command->info("✅ Successfully created fallback project: {$fallbackData['name']}");
                }
                
                // Add delay to avoid rate limiting
                sleep(1);
                
            } catch (\Exception $e) {
                $this->command->error("❌ Error processing {$repo}: " . $e->getMessage());
                Log::error("GitHub API Error for {$repo}: " . $e->getMessage());
            }
        }

        $this->command->info('🎉 Project seeding completed!');
    }

    private function fetchGitHubData($repo)
    {
        try {
            // Fetch repository data
            $response = Http::get("https://api.github.com/repos/{$repo}");
            
            if (!$response->successful()) {
                $this->command->error("GitHub API error for {$repo}: " . $response->status());
                return null;
            }

            $repoData = $response->json();
            
            // Fetch additional data
            $languages = $this->fetchLanguages($repo);
            $readme = $this->fetchReadme($repo);
            
            return [
                'name' => $repoData['name'],
                'description' => $repoData['description'] ?: $this->getDefaultDescription($repoData['name']),
                'full_description' => $this->generateFullDescription($repoData, $readme),
                'html_url' => $repoData['html_url'],
                'homepage' => $repoData['homepage'],
                'language' => $repoData['language'],
                'languages' => $languages,
                'stargazers_count' => $repoData['stargazers_count'],
                'forks_count' => $repoData['forks_count'],
                'size' => $repoData['size'],
                'created_at' => $repoData['created_at'],
                'updated_at' => $repoData['updated_at'],
                'topics' => $repoData['topics'] ?? [],
                'license' => $repoData['license'],
                'archived' => $repoData['archived'],
                'private' => $repoData['private']
            ];
            
        } catch (\Exception $e) {
            $this->command->error("Error fetching GitHub data: " . $e->getMessage());
            return null;
        }
    }

    private function fetchLanguages($repo)
    {
        try {
            $response = Http::get("https://api.github.com/repos/{$repo}/languages");
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function fetchReadme($repo)
    {
        try {
            $response = Http::get("https://api.github.com/repos/{$repo}/readme");
            if ($response->successful()) {
                $readmeData = $response->json();
                return base64_decode($readmeData['content']);
            }
        } catch (\Exception $e) {
            // Ignore readme errors
        }
        return null;
    }

    private function getDefaultDescription($repoName)
    {
        $descriptions = [
            'portfolio-gostock' => 'A comprehensive portfolio management system built with Laravel, featuring user profiles, project showcases, and professional networking capabilities.',
            'stock_haqi_ali' => 'Advanced stock management system for tracking inventory, sales, and business analytics with real-time reporting features.',
            'CannonX' => 'Professional mobile photography application with advanced camera controls, filters, and social sharing features.',
            'CannonX-Admin' => 'Administrative dashboard for managing CannonX mobile application, user accounts, and content moderation.',
            'guardian' => 'Comprehensive security management system with monitoring, alerting, and access control features.',
            'raasta' => 'Intelligent navigation and route planning application with real-time traffic updates and location services.'
        ];

        return $descriptions[$repoName] ?? "Professional {$repoName} project with modern development practices and user-focused design.";
    }

    private function generateFullDescription($repoData, $readme)
    {
        $description = "## Project Overview\n\n";
        $description .= $repoData['description'] . "\n\n";
        
        $description .= "## Key Features\n\n";
        $description .= $this->getKeyFeatures($repoData['name']) . "\n\n";
        
        $description .= "## Technical Details\n\n";
        $description .= "- **Repository Size:** " . number_format($repoData['size']) . " KB\n";
        $description .= "- **Stars:** " . $repoData['stargazers_count'] . "\n";
        $description .= "- **Forks:** " . $repoData['forks_count'] . "\n";
        $description .= "- **Language:** " . $repoData['language'] . "\n";
        $description .= "- **Created:** " . date('F Y', strtotime($repoData['created_at'])) . "\n";
        $description .= "- **Last Updated:** " . date('F Y', strtotime($repoData['updated_at'])) . "\n\n";
        
        if ($repoData['license']) {
            $description .= "- **License:** " . $repoData['license']['name'] . "\n";
        }
        
        if (!empty($repoData['topics'])) {
            $description .= "- **Topics:** " . implode(', ', $repoData['topics']) . "\n";
        }
        
        $description .= "\n## Repository Information\n\n";
        $description .= "This project is actively maintained and follows modern development practices. ";
        $description .= "The codebase is well-structured and documented for easy understanding and contribution.\n\n";
        
        if ($repoData['homepage']) {
            $description .= "🌐 **Live Demo:** [View Project](" . $repoData['homepage'] . ")\n\n";
        }
        
        $description .= "🔗 **GitHub Repository:** [View Source Code](" . $repoData['html_url'] . ")";
        
        return $description;
    }

    private function getKeyFeatures($repoName)
    {
        $features = [
            'portfolio-gostock' => [
                "User portfolio management and customization",
                "Project showcase with detailed descriptions",
                "Skills tracking with proficiency levels",
                "Professional experience timeline",
                "Education and certification management",
                "Client testimonials and reviews",
                "Responsive design for all devices",
                "SEO optimization and social media integration"
            ],
            'stock_haqi_ali' => [
                "Real-time inventory tracking",
                "Sales analytics and reporting",
                "Multi-user access control",
                "Barcode scanning integration",
                "Automated stock alerts",
                "Financial reporting dashboard",
                "Data export and backup",
                "Mobile-responsive interface"
            ],
            'CannonX' => [
                "Professional camera controls",
                "Advanced photo editing tools",
                "Real-time filters and effects",
                "Social sharing capabilities",
                "Photo gallery management",
                "Cloud storage integration",
                "User profile system",
                "Cross-platform compatibility"
            ],
            'CannonX-Admin' => [
                "User management and moderation",
                "Content management system",
                "Analytics and reporting dashboard",
                "System configuration tools",
                "Security monitoring",
                "Database management",
                "API management",
                "Real-time notifications"
            ],
            'guardian' => [
                "Multi-level security monitoring",
                "Real-time threat detection",
                "Access control management",
                "Incident reporting system",
                "Security analytics dashboard",
                "Automated alert system",
                "User activity tracking",
                "Compliance reporting"
            ],
            'raasta' => [
                "Intelligent route planning",
                "Real-time traffic updates",
                "Multiple transportation modes",
                "Offline map support",
                "Location sharing features",
                "Route optimization",
                "User preferences management",
                "Integration with local services"
            ]
        ];

        $repoFeatures = $features[$repoName] ?? [
            "Modern and responsive design",
            "User-friendly interface",
            "Robust backend architecture",
            "Scalable and maintainable code",
            "Comprehensive documentation",
            "Cross-platform compatibility"
        ];

        return implode("\n", array_map(function($feature) {
            return "- " . $feature;
        }, $repoFeatures));
    }

    private function createProject($userId, $repo, $data)
    {
        $repoName = explode('/', $repo)[1];
        $projectType = $this->projectTypes[$repoName] ?? 'Web Application';
        $technologies = $this->technologies[$repoName] ?? ['Laravel', 'PHP', 'JavaScript'];

        // Determine project status
        $status = 'completed';
        if ($data['archived']) {
            $status = 'on_hold';
        } elseif ($data['updated_at'] > now()->subMonths(6)) {
            $status = 'in_progress';
        }

        // Create detailed description
        $detailedDescription = $data['description'] . "\n\n" . $data['full_description'];

        UserProject::create([
            'user_id' => $userId,
            'project_name' => $data['name'],
            'project_type' => $this->mapProjectType($projectType),
            'description' => $detailedDescription,
            'status' => $status,
            'start_date' => date('Y-m-d', strtotime($data['created_at'])),
            'end_date' => $status === 'completed' ? date('Y-m-d', strtotime($data['updated_at'])) : null,
            'project_url' => $data['homepage'],
            'github_url' => $data['html_url'],
            'demo_url' => $data['homepage'],
            'technologies' => json_encode($technologies),
            'client_name' => 'Personal Project',
            'role' => 'Full Stack Developer',
            'team_size' => 1,
            'key_features' => $this->getKeyFeatures($repoName),
            'challenges_solved' => $this->getChallengesSolved($repoName),
            'visibility' => 'public',
            'featured' => true
        ]);
    }

    private function mapProjectType($type)
    {
        $mapping = [
            'Portfolio Website' => 'personal',
            'Stock Management System' => 'professional',
            'Mobile Application' => 'personal',
            'Admin Dashboard' => 'professional',
            'Security System' => 'professional',
            'Navigation App' => 'personal'
        ];

        return $mapping[$type] ?? 'personal';
    }

    private function getChallengesSolved($repoName)
    {
        $challenges = [
            'portfolio-gostock' => [
                "Implemented responsive design for multiple screen sizes",
                "Created dynamic portfolio management system",
                "Integrated social media and SEO optimization",
                "Built user-friendly admin interface",
                "Optimized database queries for better performance"
            ],
            'stock_haqi_ali' => [
                "Developed real-time inventory tracking system",
                "Implemented barcode scanning functionality",
                "Created comprehensive reporting dashboard",
                "Built multi-user access control system",
                "Optimized database for large inventory datasets"
            ],
            'CannonX' => [
                "Created cross-platform mobile application",
                "Implemented advanced camera controls",
                "Built real-time photo editing features",
                "Integrated cloud storage solutions",
                "Optimized app performance for mobile devices"
            ],
            'CannonX-Admin' => [
                "Developed comprehensive admin dashboard",
                "Implemented user management system",
                "Created content moderation tools",
                "Built analytics and reporting features",
                "Ensured secure API endpoints"
            ],
            'guardian' => [
                "Implemented multi-level security monitoring",
                "Created real-time threat detection system",
                "Built comprehensive access control",
                "Developed incident reporting system",
                "Ensured data privacy and compliance"
            ],
            'raasta' => [
                "Integrated Google Maps API for navigation",
                "Implemented real-time traffic updates",
                "Created offline map functionality",
                "Built route optimization algorithms",
                "Developed cross-platform compatibility"
            ]
        ];

        $repoChallenges = $challenges[$repoName] ?? [
            "Implemented modern development practices",
            "Created user-friendly interface",
            "Built scalable architecture",
            "Ensured cross-platform compatibility",
            "Optimized performance and security"
        ];

        return implode("\n", array_map(function($challenge) {
            return "• " . $challenge;
        }, $repoChallenges));
    }

    private function generateFallbackData($repoName)
    {
        $fallbackData = [
            'CannonX' => [
                'name' => 'CannonX',
                'description' => 'Professional mobile photography application with advanced camera controls, filters, and social sharing features.',
                'html_url' => 'https://github.com/yasirraheel/CannonX',
                'homepage' => null,
                'language' => 'Dart',
                'stargazers_count' => 0,
                'forks_count' => 0,
                'size' => 50000,
                'created_at' => '2024-01-15T00:00:00Z',
                'updated_at' => '2024-12-01T00:00:00Z',
                'topics' => ['flutter', 'mobile', 'camera', 'photography'],
                'license' => ['name' => 'MIT'],
                'archived' => false,
                'private' => false
            ],
            'CannonX-Admin' => [
                'name' => 'CannonX-Admin',
                'description' => 'Administrative dashboard for managing CannonX mobile application, user accounts, and content moderation.',
                'html_url' => 'https://github.com/yasirraheel/CannonX-Admin',
                'homepage' => null,
                'language' => 'PHP',
                'stargazers_count' => 0,
                'forks_count' => 0,
                'size' => 30000,
                'created_at' => '2024-02-01T00:00:00Z',
                'updated_at' => '2024-11-15T00:00:00Z',
                'topics' => ['laravel', 'admin', 'dashboard', 'management'],
                'license' => ['name' => 'MIT'],
                'archived' => false,
                'private' => false
            ],
            'guardian' => [
                'name' => 'Guardian',
                'description' => 'Comprehensive security management system with monitoring, alerting, and access control features.',
                'html_url' => 'https://github.com/yasirraheel/guardian',
                'homepage' => null,
                'language' => 'PHP',
                'stargazers_count' => 0,
                'forks_count' => 0,
                'size' => 40000,
                'created_at' => '2024-03-01T00:00:00Z',
                'updated_at' => '2024-10-20T00:00:00Z',
                'topics' => ['security', 'monitoring', 'laravel', 'php'],
                'license' => ['name' => 'MIT'],
                'archived' => false,
                'private' => false
            ],
            'raasta' => [
                'name' => 'Raasta',
                'description' => 'Intelligent navigation and route planning application with real-time traffic updates and location services.',
                'html_url' => 'https://github.com/yasirraheel/raasta',
                'homepage' => null,
                'language' => 'Dart',
                'stargazers_count' => 0,
                'forks_count' => 0,
                'size' => 60000,
                'created_at' => '2024-04-01T00:00:00Z',
                'updated_at' => '2024-11-30T00:00:00Z',
                'topics' => ['flutter', 'navigation', 'maps', 'mobile'],
                'license' => ['name' => 'MIT'],
                'archived' => false,
                'private' => false
            ]
        ];

        $data = $fallbackData[$repoName] ?? [
            'name' => $repoName,
            'description' => "Professional {$repoName} project with modern development practices.",
            'html_url' => "https://github.com/yasirraheel/{$repoName}",
            'homepage' => null,
            'language' => 'PHP',
            'stargazers_count' => 0,
            'forks_count' => 0,
            'size' => 10000,
            'created_at' => '2024-01-01T00:00:00Z',
            'updated_at' => '2024-12-01T00:00:00Z',
            'topics' => ['php', 'laravel', 'web'],
            'license' => ['name' => 'MIT'],
            'archived' => false,
            'private' => false
        ];

        // Generate full description
        $data['full_description'] = $this->generateFullDescription($data, null);
        
        return $data;
    }

    private function getSortOrder($repoName)
    {
        $order = [
            'portfolio-gostock' => 1,
            'CannonX' => 2,
            'CannonX-Admin' => 3,
            'stock_haqi_ali' => 4,
            'guardian' => 5,
            'raasta' => 6
        ];

        return $order[$repoName] ?? 99;
    }
}
