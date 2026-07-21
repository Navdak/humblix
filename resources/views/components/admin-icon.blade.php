@props(['name'])
<svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'aria-hidden' => 'true']) }}>
@switch($name)
@case('dashboard')<path d="M3 12 12 4l9 8M5 10v10h14V10M9 20v-6h6v6"/>@break
@case('articles')<path d="M6 3h9l4 4v14H6zM14 3v5h5M9 12h6m-6 4h6"/>@break
@case('projects')<path d="M4 7h16v13H4zM9 7V4h6v3M4 12h16M10 12v2h4v-2"/>@break
@case('branches')<path d="M4 20V5h9v15M13 9h7v11M7 8h2m-2 4h2m-2 4h2m9-4h1m-1 4h1M2 20h20"/>@break
@case('careers')<path d="M4 7h16v13H4zM9 7V4h6v3M8 13h8M8 17h5"/>@break
@case('equipment')<path d="M4 8 12 4l8 4-8 4-8-4Zm0 4 8 4 8-4M4 16l8 4 8-4"/>@break
@case('videos')<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m10 9 5 3-5 3V9Z"/>@break
@case('team')<path d="M16 20v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.9M16 2.1a4 4 0 0 1 0 7.8"/>@break
@case('enquiries')<path d="M4 5h16v14H4zM4 7l8 6 8-6"/>@break
@case('reviews')<path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9z"/>@break
@case('media')<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m4 17 5-5 3 3 2-2 6 5"/>@break
@case('page-heroes')<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 14l4-4 3 3 3-4 8 8M8 9h.01M7 18h10"/>@break
@case('users')<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M8.5 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM18 8v6m-3-3h6"/>@break
@case('settings')<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2.8 2.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6v.2h-4V21a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1L4.2 17l.1-.1a1.7 1.7 0 0 0 .3-1.9A1.7 1.7 0 0 0 3 14H2.8v-4H3a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9L4.2 7 7 4.2l.1.1A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.6v-.2h4V3a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1L19.8 7l-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.2v4H21a1.7 1.7 0 0 0-1.6 1Z"/>@break
@case('services')<path d="M4 7h16M4 12h16M4 17h16M7 4v6M12 9v6M17 14v6"/>@break
@case('safety')<path d="M12 3 5 6v5c0 4.6 3 8.2 7 10 4-1.8 7-5.4 7-10V6l-7-3Zm-3 9 2 2 4-5"/>@break
@case('seo')<path d="M4 5h16v14H4zM8 9h8M8 13h5M16 17l4 4M15 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>@break
@case('search')<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>@break
@case('bell')<path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>@break
@case('moon')<path d="M21 12.8A8.5 8.5 0 1 1 11.2 3a6.8 6.8 0 0 0 9.8 9.8Z"/>@break
@case('sun')<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>@break
@case('menu')<path d="M4 7h16M4 12h16M4 17h16"/>@break
@case('external')<path d="M14 4h6v6M20 4l-9 9M18 13v6H5V6h6"/>@break
@case('logout')<path d="M10 17l5-5-5-5m5 5H3M14 4h6v16h-6"/>@break
@case('plus')<path d="M12 5v14M5 12h14"/>@break
@case('calendar')<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/>@break
@case('trend')<path d="m3 17 6-6 4 4 8-8M15 7h6v6"/>@break
@case('clock')<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>@break
@default<circle cx="12" cy="12" r="9"/>
@endswitch
</svg>
