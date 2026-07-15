<?php
namespace Database\Seeders;

use App\Models\Article;
use App\Models\Enquiry;
use App\Models\EquipmentItem;
use App\Models\Project;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(['email'=>'admin@humelix.com'], [
            'name'=>'HUMELIX LIMITED Admin',
            'password'=>Hash::make('password123'),
            'role'=>'super_admin',
            'region'=>'Global',
            'is_active'=>true,
        ]);

        foreach ([
            'hero_headline'=>'Engineering Comfort. Powering Reliability.',
            'hero_subtext'=>'HVAC, solar, electrical, maintenance and equipment solutions for residential, commercial and industrial clients worldwide.',
            'founder_snapshot'=>'At HUMELIX LIMITED, our mission is simple: deliver safe, precise and reliable engineering solutions that improve comfort, power reliability and operational performance.',
            'footer_copyright'=>'© '.date('Y').' HUMELIX LIMITED. All rights reserved.',
            'company_email'=>'info@humelix.com',
            'support_email'=>'support@humelix.com',
            'phone_primary'=>'+234 900 123 4567',
            'phone_secondary'=>'+234 901 234 5678',
            'whatsapp_number'=>'+2349001234567',
        ] as $key => $value) SiteSetting::setValue($key, $value, 'homepage');

        foreach ([
            ['Industrial Plant – Lagos','industrial-plant-lagos','Lagos','Factories','HVAC System Installation','Industrial HVAC Project',true],
            ['Office Complex – Abuja','office-complex-abuja','Abuja','Offices','VRF System Installation','Commercial Building Systems',true],
            ['High-Rise Building – Dubai','high-rise-building-dubai','Dubai','Towers','Centralized Cooling System','Tower & High-Rise Cooling',true],
            ['Warehouse Facility – Port Harcourt','warehouse-facility-port-harcourt','Port Harcourt','Warehouses','Ventilation & Cooling System','Industrial HVAC Project',true],
        ] as $projectIndex => [$title,$slug,$location,$sector,$system,$clientType,$featured]) {
            $project = Project::firstOrCreate(['slug'=>$slug], [
                'title'=>$title,'location'=>$location,'sector'=>$sector,'system_type'=>$system,'client_type'=>$clientType,'is_featured'=>$featured,'status'=>'published',
                'challenge'=>'Client required a reliable climate solution with minimal interruption to facility operations.',
                'solution'=>'HUMELIX LIMITED completed site assessment, selected suitable equipment and delivered a phased installation plan.',
                'result'=>'Improved system reliability, better comfort and a maintainable structure.',
                'equipment_used'=>'HVAC equipment, ducting support, control systems and safety accessories.',
            ]);
            $projectPreviewFields = [
                'industrial-plant-lagos' => ['country' => 'Nigeria', 'service_division' => 'Humelix HVAC Installation', 'duration' => '5 weeks'],
                'office-complex-abuja' => ['country' => 'Nigeria', 'service_division' => 'Humelix HVAC Installation', 'duration' => '3 weeks'],
                'high-rise-building-dubai' => ['country' => 'UAE', 'service_division' => 'Humelix HVAC Installation', 'duration' => '8 weeks'],
                'warehouse-facility-port-harcourt' => ['country' => 'Nigeria', 'service_division' => 'Humelix Electrical & Maintenance', 'duration' => '4 weeks'],
            ][$slug] ?? [];
            if ($projectPreviewFields) {
                $project->fill([
                    'country' => $project->country ?: $projectPreviewFields['country'],
                    'service_division' => $project->service_division ?: $projectPreviewFields['service_division'],
                    'duration' => $project->duration ?: $projectPreviewFields['duration'],
                ])->save();
            }
            if (! $project->image_path) {
                $project->update(['image_path' => \App\Support\UchContent::projectFallbackImages()[$projectIndex]]);
            }
        }

        foreach ([
            ['UGOCHUKWU HUMBLE CHIEMELA','Founder & Lead Engineer','Global Operations','6+ years field practice','Provides founder-level direction for Humelix engineering delivery, client communication and safety-conscious field execution across service divisions. His public profile remains ready for verified personal details from the client.'],
            ['Chinedu Okafor','Senior Engineer','Lagos','5+ years','Supports technical assessment, installation coordination and practical handover for HVAC, electrical and maintenance-related client requests.'],
            ['Aisha Ibrahim','Project Manager','Abuja','4+ years','Coordinates project communication, scheduling and service follow-up so teams can deliver with clearer accountability and safer site organization.'],
            ['Emeka Nwosu','Field Technician','Port Harcourt','3+ years','Supports field installation, inspection and aftercare tasks with attention to site cleanliness, safe work habits and client handover.'],
            ['Nneka Adeyemi','Operations Support Lead','Global Support','4+ years','Supports enquiry handling, regional coordination and internal communication for Humelix service requests across multiple operating locations.'],
        ] as $i => [$name,$role,$region,$experience,$bio]) {
            $member = TeamMember::firstOrCreate(['name'=>$name], [
                'role'=>$role,'region'=>$region,'experience'=>$experience,'certifications'=>null,
                'bio'=>$bio,
                'is_visible'=>true,'sort_order'=>$i+1,
            ]);
            if (! $member->bio || $member->bio === 'Responsible for professional engineering delivery, field coordination and client support.') {
                $member->update(['bio' => $bio, 'certifications' => null]);
            }
            if ($name === 'UGOCHUKWU HUMBLE CHIEMELA' && ! $member->photo_path) {
                $member->update(['photo_path' => 'images/generated/careers/careers-engineers-inspecting-systems.jpg']);
            }
        }

        foreach ([
            ['Facility Manager','Lagos','Professional team, excellent service and top-quality installation. Highly recommended.'],
            ['Operations Director','Abuja','Their response was fast and the installation process was organized from start to finish.'],
            ['Property Manager','Port Harcourt','HUMELIX LIMITED handled our site requirement professionally and delivered clean work.'],
        ] as [$role,$location,$comment]) {
            Review::firstOrCreate(['comment'=>$comment], [
                'client_name'=>$role,'client_role'=>$role,'location'=>$location,'project_category'=>'Engineering Installation','rating'=>5,'is_approved'=>true,
            ]);
        }

        Article::firstOrCreate(['slug'=>'how-to-maintain-commercial-ac-systems'], [
            'author_id'=>$admin->id,'title'=>'How to Maintain Commercial HVAC Systems','excerpt'=>'A practical guide for facility managers who want reliable building performance.',
            'featured_image_path'=>'images/generated/services/service-hvac-installation.jpg',
            'content'=>'<p>Commercial HVAC systems need scheduled inspection, filter cleaning, drainage checks and performance testing. Preventive maintenance reduces breakdowns and improves system lifespan.</p><h2>Recommended Checks</h2><ul><li>Inspect filters monthly.</li><li>Check drainage lines.</li><li>Monitor cooling performance.</li><li>Schedule professional servicing.</li></ul>',
            'status'=>'published','published_at'=>now(),
        ]);

        foreach ([
            ['Commercial HVAC Equipment Package','HVAC Equipment','Request-based HVAC equipment package for commercial installation planning.','Cooling equipment, controls and installation accessories for quotation-led projects.','images/generated/equipment/equipment-ac-units.jpg',10],
            ['Solar Panel System Kit','Solar Panels','Solar panel system components for residential and commercial project enquiries.','Panel supply, mounting support and project-specific specification review.','images/generated/equipment/equipment-solar-panels.jpg',20],
            ['Inverter and Battery Backup Set','Inverters & Batteries','Power reliability equipment for backup and solar-linked installations.','Inverter, battery and protection components supplied after site requirement review.','images/generated/equipment/equipment-inverters.jpg',30],
            ['Electrical Components Pack','Electrical Components','Electrical accessories and control components for safe installation work.','Breakers, isolators, cabling accessories and support components for managed projects.','images/generated/equipment/equipment-electrical-components.jpg',40],
            ['Home Installation Accessories','Home Installation Products','Accessories for appliance, comfort and residential installation requests.','Installation kits, fittings and approved accessories quoted according to client scope.','images/generated/equipment/equipment-home-installation-products.jpg',50],
        ] as [$name,$category,$shortDescription,$specification,$imagePath,$sortOrder]) {
            EquipmentItem::firstOrCreate(['name'=>$name], [
                'category'=>$category,
                'short_description'=>$shortDescription,
                'specification'=>$specification,
                'availability_status'=>'available_on_request',
                'image_path'=>$imagePath,
                'sort_order'=>$sortOrder,
                'is_published'=>true,
            ]);
        }

        foreach ([
            ['Humelix Safety Briefing Placeholder','humelix-safety-briefing-placeholder','Safety','Safety briefing video placeholder for future uploaded or embedded media.','images/generated/safety/safety-toolbox-talks.jpg',10],
            ['Vendor Equipment Demo Placeholder','vendor-equipment-demo-placeholder','Vendor / Equipment','Equipment demonstration placeholder for future catalogue videos.','images/generated/equipment/equipment-tools-accessories.jpg',20],
            ['Field Installation Highlight Placeholder','field-installation-highlight-placeholder','Field Work','Field work highlight placeholder for future project media.','images/generated/home/home-engineering-team-worksite.jpg',30],
        ] as [$title,$slug,$category,$caption,$thumbnail,$sortOrder]) {
            Video::firstOrCreate(['slug'=>$slug], [
                'title'=>$title,
                'caption'=>$caption,
                'description'=>'Draft placeholder record used to prepare the admin catalogue dashboard. Replace with real media from the admin page when available.',
                'category'=>$category,
                'video_type'=>'external',
                'thumbnail_path'=>$thumbnail,
                'status'=>'draft',
                'is_featured'=>false,
                'sort_order'=>$sortOrder,
            ]);
        }

        Enquiry::firstOrCreate(['phone'=>'+2348000000000'], [
            'source'=>'chat_assistant','name'=>'John Doe','email'=>'john@example.com','location'=>'Lagos','building_type'=>'Office Building',
            'service_needed'=>'Commercial Building Systems','urgency'=>'This week','message'=>'We need an assessment for a new office engineering project.','status'=>'new',
        ]);
    }
}
