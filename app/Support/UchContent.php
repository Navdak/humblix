<?php
namespace App\Support;

use Illuminate\Support\Facades\Storage;

class UchContent
{
    /** Resolve a bundled public image or an admin-uploaded storage image. */
    public static function imageUrl(?string $path, ?string $fallback = null): ?string
    {
        $path = trim((string) $path);
        $fallback = trim((string) $fallback);

        if ($path !== '') {
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }

            if (str_starts_with($path, 'images/')) {
                return is_file(public_path($path)) ? asset($path) : self::fallbackImageUrl($fallback);
            }

            if (str_starts_with($path, 'storage/')) {
                $storagePath = substr($path, strlen('storage/'));

                return Storage::disk('public')->exists($storagePath) ? asset($path) : self::fallbackImageUrl($fallback);
            }

            return Storage::disk('public')->exists($path)
                ? asset('storage/'.$path)
                : self::fallbackImageUrl($fallback);
        }

        return self::fallbackImageUrl($fallback);
    }

    private static function fallbackImageUrl(?string $fallback): ?string
    {
        $fallback = trim((string) $fallback);

        if ($fallback === '') {
            return null;
        }

        if (str_starts_with($fallback, 'http://') || str_starts_with($fallback, 'https://')) {
            return $fallback;
        }

        if (str_starts_with($fallback, 'images/')) {
            return asset($fallback);
        }

        if (str_starts_with($fallback, 'storage/')) {
            return asset($fallback);
        }

        return asset('storage/'.$fallback);
    }

    /** Resolve a bundled public image or uploaded storage image as an absolute URL for email clients. */
    public static function emailImageUrl(?string $path, ?string $fallback = null): ?string
    {
        $path = trim((string) ($path ?: $fallback));

        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'images/') || str_starts_with($path, 'storage/')) {
            return HumelixLinks::assetUrl($path);
        }

        return HumelixLinks::assetUrl('storage/'.$path);
    }

    public static function projectImage(?string $title): string
    {
        $title = strtolower((string) $title);

        return match (true) {
            str_contains($title, 'office') || str_contains($title, 'abuja') => 'images/generated/projects/project-office-complex-neutral.jpg',
            str_contains($title, 'high-rise') || str_contains($title, 'dubai') => 'images/generated/projects/project-high-rise-cooling-neutral.jpg',
            str_contains($title, 'warehouse') || str_contains($title, 'port harcourt') => 'images/generated/projects/project-warehouse-ventilation-neutral.jpg',
            default => 'images/generated/projects/project-industrial-plant-neutral.jpg',
        };
    }

    public static function teamImage(?string $role): string
    {
        return match (strtolower((string) $role)) {
            'senior engineer' => 'images/generated/careers/careers-engineers-inspecting-systems.jpg',
            'project manager' => 'images/generated/careers/careers-office-admin-culture.jpg',
            'field technician' => 'images/generated/careers/careers-technicians-working.jpg',
            default => 'images/generated/careers/careers-team-collaboration.jpg',
        };
    }

    public static function equipmentImage(?string $category): string
    {
        return self::equipmentCategoryImages()[$category] ?? 'images/generated/equipment/equipment-tools-accessories.jpg';
    }

    public static function videoImage(?string $category): string
    {
        return match (strtolower((string) $category)) {
            'solar' => 'images/generated/services/service-solar-installation.jpg',
            'electrical', 'maintenance' => 'images/generated/services/service-electrical-maintenance.jpg',
            'vendor / equipment', 'product demo' => 'images/generated/services/service-vendor-equipment.jpg',
            'home appliance' => 'images/generated/services/service-home-appliance-installation.jpg',
            'safety' => 'images/generated/safety/safety-ppe.jpg',
            'team', 'branches', 'client work' => 'images/generated/careers/careers-team-collaboration.jpg',
            default => 'images/generated/home/home-engineering-team-worksite.jpg',
        };
    }

    public static function safetyImage(?string $title): string
    {
        return match (strtolower((string) $title)) {
            'risk assessment' => 'images/generated/safety/safety-risk-assessment.jpg',
            'ppe compliance' => 'images/generated/safety/safety-ppe.jpg',
            'electrical isolation' => 'images/generated/safety/safety-electrical-isolation.jpg',
            'working at height' => 'images/generated/safety/safety-working-at-height.jpg',
            'toolbox talks' => 'images/generated/safety/safety-toolbox-talks.jpg',
            'testing and commissioning' => 'images/generated/safety/safety-testing-commissioning.jpg',
            'safe handover' => 'images/generated/safety/safety-safe-handover.jpg',
            default => 'images/generated/safety/safety-safe-handover.jpg',
        };
    }

    public static function pageHeroImage(?string $routeName, ?string $title = null): string
    {
        $routeName = strtolower((string) $routeName);
        $title = strtolower((string) $title);

        if (str_contains($routeName, 'legal')) {
            return match (strtolower((string) request()->route('page'))) {
                'accessibility' => 'images/generated/safety/safety-safe-handover.jpg',
                'privacy-policy', 'cookie-policy' => 'images/generated/equipment/equipment-cctv-security.jpg',
                'terms' => 'images/generated/services/service-electrical-maintenance.jpg',
                default => 'images/generated/home/home-safety-ppe.jpg',
            };
        }

        if (str_contains($routeName, 'services.show')) {
            foreach (self::serviceDivisions() as $service) {
                if (str_contains($title, strtolower($service['title']))) {
                    return $service['image'];
                }
            }
            return 'images/generated/services/service-hvac-installation.jpg';
        }

        if (str_contains($routeName, 'industries.show')) {
            foreach (self::industries() as $industry) {
                if (str_contains($title, strtolower($industry['title']))) {
                    return $industry['image'];
                }
            }
            return 'images/generated/industries/industry-offices.jpg';
        }

        if (str_contains($routeName, 'projects.show')) {
            return self::projectImage($title);
        }

        if (str_contains($routeName, 'safety.topic')) {
            return self::safetyImage($title);
        }

        return match (true) {
            str_contains($routeName, 'about') => 'images/generated/home/home-engineering-team-worksite.jpg',
            str_contains($routeName, 'services') => 'images/generated/services/service-hvac-installation.jpg',
            str_contains($routeName, 'industries'), str_contains($routeName, 'sectors') => 'images/generated/industries/industry-offices.jpg',
            str_contains($routeName, 'projects') => 'images/generated/projects/project-industrial-plant-neutral.jpg',
            str_contains($routeName, 'articles') => 'images/generated/safety/safety-toolbox-talks.jpg',
            str_contains($routeName, 'team') => 'images/generated/careers/careers-engineers-inspecting-systems.jpg',
            str_contains($routeName, 'branches') => 'images/generated/home/home-engineering-team-worksite.jpg',
            str_contains($routeName, 'careers') => 'images/generated/careers/careers-technicians-working.jpg',
            str_contains($routeName, 'equipment') => 'images/generated/home/home-service-preview-equipment.jpg',
            str_contains($routeName, 'safety') => 'images/generated/safety/safety-ppe.jpg',
            str_contains($routeName, 'founder') => 'images/generated/careers/careers-engineers-inspecting-systems.jpg',
            str_contains($routeName, 'contact') => 'images/generated/home/home-engineering-team-worksite.jpg',
            str_contains($routeName, 'reviews') => 'images/generated/careers/careers-team-collaboration.jpg',
            str_contains($routeName, 'videos') => 'images/generated/careers/careers-technicians-working.jpg',
            default => 'images/generated/home/home-engineering-team-worksite.jpg',
        };
    }

    public static function services(): array
    {
        return self::serviceDivisions();
    }

    public static function serviceDivisions(): array
    {
        return [
            [
                'slug' => 'hvac-installation',
                'code' => 'HV',
                'image' => 'images/generated/services/service-hvac-installation.jpg',
                'title' => 'Humelix HVAC Installation',
                'label' => 'Climate systems',
                'excerpt' => 'Professional HVAC installation for homes, offices, towers, factories, hotels, hospitals, warehouses and estates.',
                'details' => 'Humelix plans and installs practical HVAC systems around site conditions, cooling load, building use and long-term service access.',
                'overview' => 'This division supports residential, commercial and industrial clients with professionally selected, safely installed and properly commissioned cooling, ventilation and climate-control systems.',
                'accent' => 'blue',
                'included' => ['Split AC','Floor standing AC','Cassette AC','Ducted systems','VRF/VRV','Chillers','AHU','Ventilation','Cooling load assessment','Commissioning','Maintenance support'],
                'benefits' => ['Better comfort control','Correct system sizing','Energy efficiency','Safe installation','Reliable aftercare'],
                'process' => ['Site consultation','Cooling load and room assessment','System selection and quotation','Professional installation','Testing, commissioning and handover','Maintenance support planning'],
                'clients' => ['Homes','Offices','Estates','Factories','Warehouses','Hospitals','Hotels','Schools','Retail','Data Centres'],
                'faqs' => [
                    ['question' => 'Do you inspect the site before recommending HVAC equipment?', 'answer' => 'Yes. The team reviews space size, heat load, usage, access, power availability and installation constraints before recommending equipment.'],
                    ['question' => 'Can Humelix support both residential and industrial HVAC work?', 'answer' => 'Yes. The division covers homes, offices, estates, commercial buildings, warehouses, factories and other facility types.'],
                    ['question' => 'Do you help with maintenance after installation?', 'answer' => 'Yes. Maintenance support can be discussed during quotation and handover so the system remains reliable after installation.'],
                    ['question' => 'Can you work with existing buildings?', 'answer' => 'Yes. Humelix can assess existing spaces and recommend practical installation or upgrade paths.'],
                ],
                'project_terms' => ['HVAC','AC','cooling','ventilation','VRF','chiller','air conditioning'],
            ],
            [
                'slug' => 'solar-installation',
                'code' => 'SO',
                'image' => 'images/generated/services/service-solar-installation.jpg',
                'title' => 'Humelix Solar Installation',
                'label' => 'Solar energy',
                'excerpt' => 'Reliable solar energy systems designed for homes, businesses, estates and industrial facilities.',
                'details' => 'Humelix designs and installs solar power systems around energy demand, backup expectations, available space and safe electrical integration.',
                'overview' => 'This division supports clients who need cleaner, more resilient power through solar panels, hybrid inverters, batteries and properly planned backup systems.',
                'accent' => 'solar',
                'included' => ['Solar panels','Hybrid inverters','Batteries','Charge controllers','Commercial solar','Rooftop solar','Ground-mounted systems','Energy audits','Backup power'],
                'benefits' => ['Improved backup reliability','Load-aware system sizing','Cleaner power planning','Safe electrical integration','Aftercare support'],
                'process' => ['Energy consultation','Load audit and site inspection','Solar design and equipment proposal','Installation and wiring integration','Testing and handover','Support and maintenance planning'],
                'clients' => ['Homes','Offices','Estates','Factories','Warehouses','Hospitals','Hotels','Schools','Retail','Data Centres'],
                'faqs' => [
                    ['question' => 'Do you calculate the solar capacity needed?', 'answer' => 'Yes. Humelix reviews your loads, usage pattern and backup expectations before proposing a system size.'],
                    ['question' => 'Can solar be installed for businesses and estates?', 'answer' => 'Yes. The division supports homes, offices, estates and larger commercial or industrial facilities.'],
                    ['question' => 'Do you supply batteries and inverters?', 'answer' => 'Yes. Solar projects can include panels, hybrid inverters, batteries, charge controllers and supporting accessories.'],
                    ['question' => 'Will the site be inspected first?', 'answer' => 'A site inspection is recommended to confirm roof or ground space, cable routes, safety access and electrical readiness.'],
                ],
                'project_terms' => ['solar','inverter','battery','backup power','renewable'],
            ],
            [
                'slug' => 'electrical-maintenance',
                'code' => 'EL',
                'image' => 'images/generated/services/service-electrical-maintenance.jpg',
                'title' => 'Humelix Electrical & Maintenance',
                'label' => 'Power safety',
                'excerpt' => 'Electrical installation, inspection, support and maintenance for residential, commercial and industrial clients.',
                'details' => 'Humelix provides electrical installation and maintenance support with attention to safety, fault prevention, practical routing and reliable handover.',
                'overview' => 'This division handles everyday and project-based electrical work, from wiring and lighting to distribution boards, fault tracing, testing and preventive maintenance.',
                'accent' => 'safety',
                'included' => ['Wiring','Sockets','Lighting','Distribution boards','Panels','Earthing','Power upgrades','Fault tracing','Testing','Commissioning','Preventive maintenance'],
                'benefits' => ['Safer electrical systems','Reliable power distribution','Reduced downtime risk','Professional delivery','Aftercare support'],
                'process' => ['Request review','Inspection and fault assessment','Scope definition and quotation','Installation or maintenance work','Testing and commissioning','Handover and support recommendations'],
                'clients' => ['Homes','Offices','Estates','Factories','Warehouses','Hospitals','Hotels','Schools','Retail','Data Centres'],
                'faqs' => [
                    ['question' => 'Can Humelix inspect an existing electrical fault?', 'answer' => 'Yes. The team can review symptoms, inspect the affected area and recommend a safe repair or upgrade path.'],
                    ['question' => 'Do you handle preventive maintenance?', 'answer' => 'Yes. Preventive maintenance can include inspection, testing, tightening, fault checks and performance review.'],
                    ['question' => 'Can you support offices and industrial facilities?', 'answer' => 'Yes. Services cover residential, commercial and industrial environments.'],
                    ['question' => 'Do you test work before handover?', 'answer' => 'Yes. Testing and commissioning are included so the client understands the work completed before handover.'],
                ],
                'project_terms' => ['electrical','maintenance','wiring','lighting','power','distribution','fault'],
            ],
            [
                'slug' => 'vendor',
                'code' => 'VE',
                'image' => 'images/generated/services/service-vendor-equipment.jpg',
                'title' => 'Humelix Vendor / Equipment',
                'label' => 'Equipment supply',
                'excerpt' => 'A procurement and equipment supply division for HVAC, solar, electrical and home installation products.',
                'details' => 'Humelix Vendor supports projects and service requests with equipment sourcing, accessories, spare parts, delivery coordination and after-sales support.',
                'overview' => 'This division helps clients source approved equipment and components for HVAC, solar, electrical and home installation needs through request-based vendor support.',
                'accent' => 'vendor',
                'included' => ['Approved equipment','Spare parts','Accessories','Cables','Inverters','Panels','AC units','Mounting kits','Tools','Components','Delivery','After-sales support'],
                'benefits' => ['Suitable equipment sourcing','Project-ready accessories','Clear supply coordination','Professional delivery','After-sales support'],
                'process' => ['Requirement review','Equipment recommendation','Availability and quotation','Supply or delivery coordination','Installation handoff where needed','After-sales support'],
                'clients' => ['Homes','Offices','Estates','Factories','Warehouses','Hospitals','Hotels','Schools','Retail','Data Centres'],
                'faqs' => [
                    ['question' => 'Is this an online shop?', 'answer' => 'No. Humelix Vendor is a request-based equipment and procurement pathway. Quotes, availability and delivery details are confirmed by the team.'],
                    ['question' => 'Can Humelix source items for HVAC and solar jobs?', 'answer' => 'Yes. Vendor support can cover AC units, solar panels, inverters, batteries, cables, accessories and related components.'],
                    ['question' => 'Can equipment supply be linked with installation?', 'answer' => 'Yes. Where appropriate, Humelix can coordinate equipment supply with HVAC, solar, electrical or appliance installation services.'],
                    ['question' => 'Do you provide after-sales support?', 'answer' => 'Yes. After-sales support can be discussed based on the equipment supplied and project requirements.'],
                ],
                'project_terms' => ['equipment','supply','AC unit','inverter','panel','component','vendor'],
            ],
            [
                'slug' => 'home-appliance-installation',
                'code' => 'HA',
                'image' => 'images/generated/services/service-home-appliance-installation.jpg',
                'title' => 'Home Appliance Installation',
                'label' => 'Home setup',
                'excerpt' => 'Safe installation of everyday home and office appliances by trained technicians.',
                'details' => 'Humelix supports practical home and office setup needs with clean installation, cable management, testing and user handover.',
                'overview' => 'This division helps households, small offices and managed properties install appliances and light technology neatly and safely.',
                'accent' => 'home',
                'included' => ['TV mounting','CCTV','Security cameras','Computer setup','Fans','Smart devices','Appliances','Networking','Brackets','Cable management','Testing and handover'],
                'benefits' => ['Neat installation','Safer mounting and cabling','Reliable device setup','Professional delivery','Aftercare support'],
                'process' => ['Installation request','Site or item review','Scope and accessory confirmation','Installation and setup','Testing and handover','Support guidance'],
                'clients' => ['Homes','Offices','Estates','Hotels','Schools','Retail'],
                'faqs' => [
                    ['question' => 'Can you mount TVs and cameras?', 'answer' => 'Yes. Home appliance installation includes TV mounting, CCTV and security camera setup where site conditions allow.'],
                    ['question' => 'Do you handle cable management?', 'answer' => 'Yes. Cable management is included where practical so the finished setup is safer and neater.'],
                    ['question' => 'Can you install devices in offices?', 'answer' => 'Yes. The service supports homes, small offices, retail spaces and managed properties.'],
                    ['question' => 'Do you test appliances after installation?', 'answer' => 'Yes. Testing and basic handover are part of the service so the client can confirm the setup works.'],
                ],
                'project_terms' => ['appliance','CCTV','camera','TV','networking','smart device'],
            ],
        ];
    }

    public static function legacyServiceRedirects(): array
    {
        return [
            'industrial-ac-installation' => 'hvac-installation',
            'commercial-ac-systems' => 'hvac-installation',
            'residential-ac-installation' => 'hvac-installation',
            'tower-high-rise-cooling' => 'hvac-installation',
            'climate-engineering-consultation' => 'hvac-installation',
            'maintenance-repair' => 'electrical-maintenance',
            'emergency-diagnostics' => 'electrical-maintenance',
            'aftercare-service-contracts' => 'electrical-maintenance',
        ];
    }

    public static function serviceProcess(): array
    {
        return ['Consultation','Site Inspection','Engineering Assessment','Quotation','Installation / Supply','Testing & Handover','Maintenance Support'];
    }

    public static function sectors(): array
    {
        return [
            ['slug'=>'offices','title'=>'Offices','description'=>'Low-noise comfort, zoning and energy-conscious systems for workspaces.'],
            ['slug'=>'towers','title'=>'Towers','description'=>'Multi-floor systems strategy, riser planning and phased installation.'],
            ['slug'=>'factories','title'=>'Factories','description'=>'Heavy-duty engineering systems for industrial workspaces.'],
            ['slug'=>'warehouses','title'=>'Warehouses','description'=>'Ventilation, cooling and equipment support for storage and logistics facilities.'],
            ['slug'=>'hotels','title'=>'Hotels','description'=>'Reliable comfort and power-support planning for guest and service areas.'],
            ['slug'=>'hospitals','title'=>'Hospitals','description'=>'Safety-conscious engineering support for clinical and public spaces.'],
            ['slug'=>'data-centres','title'=>'Data Centres','description'=>'Precision cooling and power reliability support for heat-sensitive equipment rooms.'],
            ['slug'=>'estates','title'=>'Residential Estates','description'=>'Scalable installation and maintenance for estate properties.'],
            ['slug'=>'retail','title'=>'Retail','description'=>'Customer-friendly comfort and equipment support for stores and showrooms.'],
        ];
    }

    public static function industries(): array
    {
        return [
            ['slug'=>'residential-homes','title'=>'Residential Homes','image'=>'images/generated/industries/industry-residential-homes.jpg','description'=>'Comfort, safety and reliable installation support for homes and apartments.','needs'=>['AC installation','Solar backup','CCTV and appliances','Preventive maintenance']],
            ['slug'=>'estates','title'=>'Estates','image'=>'images/generated/industries/industry-estates.jpg','description'=>'Scalable maintenance and installation coordination for managed communities.','needs'=>['Estate-wide HVAC','Backup power','Electrical checks','Vendor supply coordination']],
            ['slug'=>'offices','title'=>'Offices','image'=>'images/generated/industries/industry-offices.jpg','description'=>'Low-noise comfort, zoning and energy-conscious systems for workspaces.','needs'=>['Low-noise cooling','Lighting and sockets','Server room support','Maintenance planning']],
            ['slug'=>'factories','title'=>'Factories','image'=>'images/generated/industries/industry-factories.jpg','description'=>'Heavy-duty engineering systems for industrial workspaces.','needs'=>['Industrial ventilation','Power distribution','Maintenance response','Equipment supply']],
            ['slug'=>'warehouses','title'=>'Warehouses','image'=>'images/generated/industries/industry-warehouses.jpg','description'=>'Ventilation, cooling and equipment support for storage and logistics facilities.','needs'=>['Ventilation','Lighting','Equipment handling','Preventive maintenance']],
            ['slug'=>'hospitals','title'=>'Hospitals','image'=>'images/generated/industries/industry-hospitals.jpg','description'=>'Safety-conscious engineering support for clinical and public spaces.','needs'=>['Reliable cooling','Electrical maintenance','Testing and handover','Site protection']],
            ['slug'=>'hotels','title'=>'Hotels','image'=>'images/generated/industries/industry-hotels.jpg','description'=>'Reliable comfort and power-support planning for guest and service areas.','needs'=>['Guest room comfort','Backup power','Appliance installation','Aftercare']],
            ['slug'=>'schools','title'=>'Schools','image'=>'images/generated/industries/industry-schools.jpg','description'=>'Practical comfort, safety and maintenance support for learning environments.','needs'=>['Classroom comfort','Electrical safety','Fans and devices','Maintenance scheduling']],
            ['slug'=>'retail-stores','title'=>'Retail Stores','image'=>'images/generated/industries/industry-retail-stores.jpg','description'=>'Customer-friendly comfort and equipment support for stores and showrooms.','needs'=>['Shop cooling','Lighting support','CCTV','Fast maintenance']],
            ['slug'=>'data-centres','title'=>'Data Centres','image'=>'images/generated/industries/industry-data-centres.jpg','description'=>'Precision cooling and power reliability support for heat-sensitive equipment rooms.','needs'=>['Cooling resilience','Electrical reliability','Monitoring readiness','Controlled maintenance']],
            ['slug'=>'government','title'=>'Government','image'=>'images/generated/industries/industry-government-buildings.jpg','description'=>'Structured engineering support for public facilities and administrative buildings.','needs'=>['Facility comfort','Electrical support','Documentation','Service continuity']],
            ['slug'=>'religious-centres','title'=>'Religious Centres','image'=>'images/generated/industries/industry-religious-centres.jpg','description'=>'Comfort and equipment planning for worship centres and event-heavy spaces.','needs'=>['Large-space cooling','Audio/power support','Safety checks','Maintenance planning']],
        ];
    }

    public static function industryServices(): array
    {
        return ['HVAC','Solar','Electrical','Maintenance','Vendor','Home Appliance'];
    }

    public static function equipmentCategories(): array
    {
        return ['HVAC Equipment','Solar Panels','Inverters & Batteries','Electrical Components','Mounting Kits','Spare Parts','Tools & Accessories','Home Installation Products'];
    }

    public static function equipmentCategoryImages(): array
    {
        return [
            'HVAC Equipment' => 'images/generated/equipment/equipment-ac-units.jpg',
            'Solar Panels' => 'images/generated/equipment/equipment-solar-panels.jpg',
            'Inverters & Batteries' => 'images/generated/equipment/equipment-inverters.jpg',
            'Electrical Components' => 'images/generated/equipment/equipment-electrical-components.jpg',
            'Mounting Kits' => 'images/generated/equipment/equipment-home-installation-products.jpg',
            'Spare Parts' => 'images/generated/equipment/equipment-batteries.jpg',
            'Tools & Accessories' => 'images/generated/equipment/equipment-tools-accessories.jpg',
            'Home Installation Products' => 'images/generated/equipment/equipment-home-installation-products.jpg',
        ];
    }

    public static function projectFallbackImages(): array
    {
        return [
            'images/generated/projects/project-industrial-plant-neutral.jpg',
            'images/generated/projects/project-office-complex-neutral.jpg',
            'images/generated/projects/project-high-rise-cooling-neutral.jpg',
            'images/generated/projects/project-warehouse-ventilation-neutral.jpg',
        ];
    }

    public static function cultureImages(): array
    {
        return [
            ['title' => 'Technicians working', 'image' => 'images/generated/careers/careers-technicians-working.jpg'],
            ['title' => 'Engineers inspecting systems', 'image' => 'images/generated/careers/careers-engineers-inspecting-systems.jpg'],
            ['title' => 'Team collaboration', 'image' => 'images/generated/careers/careers-team-collaboration.jpg'],
            ['title' => 'Training and safety briefing', 'image' => 'images/generated/careers/careers-training-safety-briefing.jpg'],
            ['title' => 'Office support culture', 'image' => 'images/generated/careers/careers-office-admin-culture.jpg'],
        ];
    }

    public static function trustBadges(): array
    {
        return [
            ['label'=>'6+ Years','text'=>'Field Experience'],
            ['label'=>'500+ Staff','text'=>'Global Operations'],
            ['label'=>'HVAC | Electrical','text'=>'Engineering Solutions'],
            ['label'=>'Active','text'=>'Engineering Team'],
            ['label'=>'Maintenance','text'=>'Aftercare'],
            ['label'=>'Expanding','text'=>'Regional Teams'],
        ];
    }

    public static function whyChoose(): array
    {
        return [
            ['title'=>'Experienced Team','text'=>'Backed by more than 500 staff across global operations and practical field leadership.'],
            ['title'=>'Quality Work','text'=>'Top-quality materials and disciplined installation.'],
            ['title'=>'Safety First','text'=>'Strict engineering safety controls, supervision, PPE use and safe handover practices.'],
            ['title'=>'Fast Response','text'=>'Quick assessment and reliable deployment.'],
            ['title'=>'Strong Aftercare','text'=>'Maintenance and support plans that keep systems performing.'],
            ['title'=>'Global Network','text'=>'Expanding engineering, technical, support and regional teams across multiple locations.'],
        ];
    }

    public static function safetyPillars(): array
    {
        return [
            [
                'mark' => 'SF',
                'title' => 'Safety First',
                'message' => 'No job is successful unless workers, clients and property are protected.',
                'description' => 'Every project starts with risk-aware planning, clear work boundaries and a practical commitment to protecting people and buildings.',
            ],
            [
                'mark' => 'QA',
                'title' => 'Quality Assurance',
                'message' => 'Inspection, testing, commissioning and documented handover on each project.',
                'description' => 'Humelix treats quality as part of safety: work should be checked, tested, explained and handed over in a serviceable condition.',
            ],
            [
                'mark' => 'CC',
                'title' => 'Compliance Culture',
                'message' => 'Risk assessment, PPE, electrical isolation, safe tools and trained technicians.',
                'description' => 'Our site culture is built around controlled work procedures and technicians who understand the importance of safe execution.',
            ],
        ];
    }

    public static function safetyModules(): array
    {
        return [
            ['slug'=>'risk-assessment','title'=>'Risk Assessment','image'=>'images/generated/safety/safety-risk-assessment.jpg','description'=>'Review site conditions, hazards, access constraints and safe work steps before activity begins.','summary'=>['Identify site hazards before work starts','Plan access, isolation and work sequence','Agree practical controls with site teams'],'detail'=>'Risk assessment helps the team understand what could go wrong on site before installation, maintenance or supply work starts. Humelix uses this as a planning habit for practical controls, safe access and clear task sequencing.'],
            ['slug'=>'ppe-compliance','title'=>'PPE Compliance','image'=>'images/generated/safety/safety-ppe.jpg','description'=>'Use suitable personal protective equipment for the task, environment and risk level.','summary'=>['Match PPE to the work activity','Support technicians with clear expectations','Reduce avoidable exposure on client sites'],'detail'=>'PPE expectations are matched to the work environment and task. The goal is to reduce avoidable exposure during HVAC, solar, electrical, equipment handling and home installation activities.'],
            ['slug'=>'electrical-isolation','title'=>'Electrical Isolation','image'=>'images/generated/safety/safety-electrical-isolation.jpg','description'=>'Confirm power isolation and safe conditions before electrical or powered equipment work.','summary'=>['Confirm safer working conditions','Control powered circuits and equipment','Verify readiness before testing or repair'],'detail'=>'Electrical isolation is treated as a critical control before wiring, maintenance, testing or powered equipment work. The team verifies working conditions before proceeding.'],
            ['slug'=>'working-at-height','title'=>'Working at Height','image'=>'images/generated/safety/safety-working-at-height.jpg','description'=>'Plan access, ladders, roof areas and elevated work so technicians can operate safely.','summary'=>['Review ladders, roofs and elevated access','Plan stable work positions','Brief teams before elevated tasks'],'detail'=>'Working at height requires careful access planning, stable work positions and clear communication, especially for solar mounting, high-wall units, ventilation and roof-level work.'],
            ['slug'=>'lockout-tagout','title'=>'Lockout/Tagout','description'=>'Control unexpected energising of equipment during maintenance and installation work.','summary'=>['Control accidental energising','Support maintenance and inspection work','Communicate equipment status clearly'],'detail'=>'Lockout/tagout practices help prevent accidental energising while equipment, circuits or systems are being inspected, installed or serviced.'],
            ['slug'=>'toolbox-talks','title'=>'Toolbox Talks','image'=>'images/generated/safety/safety-toolbox-talks.jpg','description'=>'Brief teams before work so hazards, responsibilities and client-site rules are understood.','summary'=>['Align the team before work starts','Review hazards and responsibilities','Confirm site-specific client rules'],'detail'=>'Toolbox talks create a simple communication moment before work starts. The team aligns on hazards, task sequence, PPE, access, roles and site-specific expectations.'],
            ['slug'=>'fire-prevention','title'=>'Fire Prevention','description'=>'Control ignition risks, cable routing, hot work exposure and housekeeping during projects.','summary'=>['Reduce ignition and cable risks','Keep work areas clean and controlled','Handle batteries, tools and materials carefully'],'detail'=>'Fire prevention focuses on awareness, housekeeping and safe handling of power, materials and tools. It is especially important around electrical work, batteries, wiring and equipment storage.'],
            ['slug'=>'incident-reporting','title'=>'Incident Reporting','description'=>'Encourage clear reporting of near misses, incidents and unsafe conditions.','summary'=>['Report unsafe conditions early','Learn from near misses and incidents','Correct issues before work continues'],'detail'=>'Incident reporting supports learning and quick correction. Humelix encourages safety concerns, near misses and incidents to be reported clearly so work can improve.'],
            ['slug'=>'waste-management','title'=>'Waste Management','description'=>'Handle packaging, removed materials and work debris responsibly after service delivery.','summary'=>['Control packaging and removed parts','Keep client sites cleaner','Support safer handover conditions'],'detail'=>'Waste management keeps client sites cleaner and safer. Packaging, old components and work debris should be controlled and removed or handled responsibly after work.'],
            ['slug'=>'client-site-protection','title'=>'Client Site Protection','description'=>'Protect walls, floors, furniture, equipment and occupied areas during installation work.','summary'=>['Protect occupied homes and facilities','Plan clean access routes','Reduce disruption during technical work'],'detail'=>'Client site protection matters because technical work happens in real homes, offices and facilities. The team plans access, cleanliness and protection around occupied spaces.'],
            ['slug'=>'testing-and-commissioning','title'=>'Testing and Commissioning','image'=>'images/generated/safety/safety-testing-commissioning.jpg','description'=>'Check system performance, safety conditions and functional readiness before handover.','summary'=>['Check the completed system before use','Confirm safe operating conditions','Document what was tested and handed over'],'detail'=>'Testing and commissioning confirm that the completed work is ready for use. Humelix checks practical performance, safety conditions and client understanding before handover.'],
            ['slug'=>'safe-handover','title'=>'Safe Handover','image'=>'images/generated/safety/safety-safe-handover.jpg','description'=>'Explain completed work, basic safe use, maintenance needs and next support steps.','summary'=>['Explain completed work clearly','Share safe-use and maintenance guidance','Confirm next support steps'],'detail'=>'Safe handover closes the work responsibly. Clients should understand what was completed, how to use the system safely and what maintenance or support may be needed next.'],
        ];
    }

    public static function safetyProcess(): array
    {
        return ['Site risk review','Team briefing','Isolation and PPE check','Installation/maintenance execution','Testing and commissioning','Handover and documentation'];
    }

    public static function safetyAcrossDivisions(): array
    {
        return [
            ['title'=>'HVAC Work','text'=>'Safe lifting, electrical checks, refrigerant-aware handling, access planning and careful commissioning.'],
            ['title'=>'Solar Mounting & Power Systems','text'=>'Roof access planning, mounting awareness, battery/inverter handling and controlled electrical integration.'],
            ['title'=>'Electrical Installation & Maintenance','text'=>'Isolation, testing, cable routing, panels, earthing and fault-tracing with risk-aware procedures.'],
            ['title'=>'Equipment / Vendor Handling','text'=>'Careful equipment movement, storage, packaging control, component verification and after-sales guidance.'],
            ['title'=>'Home Appliance Installation','text'=>'Mounting safety, cable management, device testing, clean work areas and user handover.'],
        ];
    }
}
