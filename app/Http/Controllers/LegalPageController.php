<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class LegalPageController extends Controller
{
    public function __invoke(string $page): View
    {
        abort_unless(array_key_exists($page, $this->pages()), 404);

        return view('pages.legal', [
            'page' => $page,
            'content' => $this->pages()[$page],
        ]);
    }

    private function pages(): array
    {
        return [
            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'eyebrow' => 'Website governance',
                'intro' => 'This policy explains how HUMELIX LIMITED handles information submitted through this website. It is general website guidance and can be reviewed by legal counsel before launch in each operating market.',
                'sections' => [
                    ['Information we collect', 'We may collect contact details, company details, project location, service interests, uploaded project photos, enquiry messages and communication preferences when you submit a form or chat request.'],
                    ['How we use information', 'We use submitted information to respond to enquiries, prepare consultations or quotations, manage service requests, improve website content and maintain internal records.'],
                    ['Sharing and storage', 'We do not sell enquiry information. Information may be shared internally with authorised Humelix team members or service partners where needed to respond to a request.'],
                    ['Your choices', 'You can contact Humelix to request correction or deletion of information where applicable. Some records may be retained for legitimate operational, safety or legal reasons.'],
                ],
            ],
            'terms' => [
                'title' => 'Terms of Use',
                'eyebrow' => 'Website terms',
                'intro' => 'These terms govern general use of the HUMELIX LIMITED website. They do not replace a signed quotation, service agreement, warranty document or project contract.',
                'sections' => [
                    ['Website content', 'Website content is provided for general information about Humelix services, safety culture, projects and contact pathways. It may be updated without notice.'],
                    ['Service requests', 'Submitting an enquiry does not create a binding contract. Scope, pricing, timing, availability and responsibilities must be confirmed through an official Humelix quotation or agreement.'],
                    ['Acceptable use', 'Do not misuse this website, submit unlawful content, attempt unauthorised access, upload unsafe files or interfere with website availability.'],
                    ['Limitation', 'Humelix aims to keep information accurate, but website content should not be treated as engineering, legal or compliance advice for a specific site without professional review.'],
                ],
            ],
            'cookie-policy' => [
                'title' => 'Cookie Policy',
                'eyebrow' => 'Cookie notice',
                'intro' => 'This cookie policy explains the basic cookie categories that may be used by the HUMELIX LIMITED website.',
                'sections' => [
                    ['Essential cookies', 'Essential cookies may be used for security, form protection, sessions and basic website operation.'],
                    ['Analytics cookies', 'If analytics are enabled later, cookies or similar technologies may help Humelix understand page performance and improve website content.'],
                    ['Third-party services', 'Embedded maps, videos, analytics or communication tools may use their own cookies according to their providers policies.'],
                    ['Managing cookies', 'You can control cookies through your browser settings. Some website features may not work correctly if essential cookies are blocked.'],
                ],
            ],
            'accessibility' => [
                'title' => 'Accessibility Statement',
                'eyebrow' => 'Inclusive access',
                'intro' => 'HUMELIX LIMITED aims to make this website clear, navigable and usable for as many visitors as possible.',
                'sections' => [
                    ['Our approach', 'The website uses semantic HTML, readable contrast, skip links, keyboard-friendly navigation and responsive layouts where practical.'],
                    ['Ongoing improvement', 'Accessibility is reviewed as the website evolves. New media, downloads and interactive modules should be checked before production publication.'],
                    ['Feedback', 'If you experience difficulty using the website, contact Humelix with the page URL, device/browser details and a description of the issue.'],
                    ['Limitations', 'Some third-party embeds or legacy media may not fully meet accessibility expectations. Humelix will review reported issues in good faith.'],
                ],
            ],
        ];
    }
}
