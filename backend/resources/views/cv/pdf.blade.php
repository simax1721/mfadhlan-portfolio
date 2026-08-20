<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $profile['name'] }} — CV</title>
<style>
    @page { margin: 30px 40px; }

    * { box-sizing: border-box; }

    body {
        font-family: "DejaVu Sans", sans-serif;
        font-size: 11pt;
        line-height: 1.45;
        color: #1f2937;
    }

    .header { display: table; width: 100%; margin-bottom: 12px; }
    .header .photo-cell { display: table-cell; width: 122px; vertical-align: top; }
    .header .info-cell { display: table-cell; vertical-align: top; padding-left: 18px; }

    .photo-box {
        width: 110px;
        background: #0e7490;
        border-radius: 6px;
        text-align: center;
    }
    .photo-box img { width: 110px; height: auto; border-radius: 6px; }

    h1.name { font-size: 25pt; margin: 0 0 2px; color: #0f172a; font-weight: 700; }
    .role { font-size: 13.5pt; color: #0e7490; font-weight: 700; margin: 0 0 8px; letter-spacing: 0.4px; }

    .contact-line { font-size: 9.5pt; color: #475569; margin-bottom: 2px; }
    .contact-line span { margin-right: 14px; }

    .rule { border-top: 1.5px solid #0e7490; margin: 4px 0 12px; }

    .section-title {
        font-size: 12pt;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #0e7490;
        font-weight: 700;
        border-bottom: 1px solid #cbd5e1;
        padding-bottom: 3px;
        margin: 14px 0 8px;
    }

    .summary { font-size: 11pt; color: #334155; text-align: justify; }

    .item { margin-bottom: 9px; }
    .item-header { display: table; width: 100%; }
    .item-title-cell { display: table-cell; }
    .item-date-cell { display: table-cell; text-align: right; font-size: 9.5pt; color: #64748b; white-space: nowrap; vertical-align: top; }
    .item-title { font-size: 12pt; font-weight: 700; color: #0f172a; }
    .item-subtitle { font-size: 11pt; color: #0e7490; font-weight: 600; margin: 1px 0 3px; }
    .tech-line { font-size: 9pt; color: #64748b; margin-bottom: 3px; }

    ul.bullets { margin: 3px 0 0; padding-left: 13px; }
    ul.bullets li { font-size: 10pt; color: #334155; margin-bottom: 2px; }

    .skills-table { display: table; width: 100%; }
    .skills-col { display: table-cell; width: 20%; vertical-align: top; padding-right: 8px; }
    .skills-col-title {
        font-size: 9pt;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }
    .skills-col ul { margin: 0; padding-left: 11px; }
    .skills-col li { font-size: 9.5pt; color: #334155; margin-bottom: 2px; }

    .edu-org-table { display: table; width: 100%; }
    .edu-org-col { display: table-cell; width: 50%; vertical-align: top; padding-right: 12px; }
    .edu-org-item { margin-bottom: 7px; }
    .edu-org-title { font-weight: 700; font-size: 11pt; color: #0f172a; }
    .edu-org-sub { font-size: 10pt; color: #475569; }
    .edu-org-period { font-size: 9pt; color: #64748b; }
</style>
</head>
<body>

    <div class="header">
        @if($photoPath)
            <div class="photo-cell">
                <div class="photo-box"><img src="{{ $photoPath }}" alt=""></div>
            </div>
        @endif
        <div class="info-cell">
            <h1 class="name">{{ $profile['name'] }}</h1>
            <div class="role">{{ strtoupper($profile['role']) }}</div>
            <div class="contact-line">
                <span>{{ $profile['email'] }}</span>
                @if($profile['phone'])<span>{{ $profile['phone'] }}</span>@endif
                <span>{{ $profile['location'] }}</span>
            </div>
            @if($profile['github_url'] || $profile['linkedin_url'])
                <div class="contact-line">
                    @if($profile['github_url'])<span>{{ $profile['github_url'] }}</span>@endif
                    @if($profile['linkedin_url'])<span>{{ $profile['linkedin_url'] }}</span>@endif
                </div>
            @endif
        </div>
    </div>
    <div class="rule"></div>

    <div class="section-title">
        {{ $locale === 'id' ? 'Ringkasan Profesional' : 'Professional Summary' }}
    </div>
    <p class="summary">{{ $profile['summary'] }}</p>

    @if(count($experiences))
        <div class="section-title">
            {{ $locale === 'id' ? 'Pengalaman Kerja' : 'Work Experience' }}
        </div>
        @foreach($experiences as $exp)
            <div class="item">
                <div class="item-header">
                    <div class="item-title-cell"><span class="item-title">{{ $exp['title'] }}</span></div>
                    <div class="item-date-cell">{{ $exp['period'] }}</div>
                </div>
                <div class="item-subtitle">{{ $exp['company'] }}</div>
                @if(count($exp['tech_stack']))
                    <div class="tech-line">{{ implode(' • ', $exp['tech_stack']) }}</div>
                @endif
                @if(count($exp['bullets']))
                    <ul class="bullets">
                        @foreach($exp['bullets'] as $bullet)
                            <li>{{ $bullet }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    @endif

    @if(count($projects))
        <div class="section-title">
            {{ $locale === 'id' ? 'Proyek Pilihan' : 'Selected Projects' }}
        </div>
        @foreach($projects as $project)
            <div class="item">
                <div class="item-header">
                    <div class="item-title-cell"><span class="item-title">{{ $project['title'] }}</span></div>
                </div>
                @if($project['subtitle'])
                    <div class="item-subtitle">{{ $project['subtitle'] }}</div>
                @endif
                @if(count($project['tech_stack']))
                    <div class="tech-line">{{ implode(' • ', $project['tech_stack']) }}</div>
                @endif
                @if(count($project['bullets']))
                    <ul class="bullets">
                        @foreach($project['bullets'] as $bullet)
                            <li>{{ $bullet }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    @endif

    @if($skillsByCategory->isNotEmpty())
        <div class="section-title">
            {{ $locale === 'id' ? 'Keahlian Teknis' : 'Technical Skills' }}
        </div>
        <div class="skills-table">
            @foreach($skillsByCategory as $category => $items)
                <div class="skills-col">
                    <div class="skills-col-title">{{ $category }}</div>
                    <ul>
                        @foreach($items as $skill)
                            <li>{{ $skill->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endif

    @if(count($education) || count($organizations))
        <div class="section-title">
            {{ $locale === 'id' ? 'Pendidikan & Organisasi' : 'Education & Organization' }}
        </div>
        <div class="edu-org-table">
            <div class="edu-org-col">
                @foreach($education as $edu)
                    <div class="edu-org-item">
                        <div class="edu-org-title">{{ $edu['degree'] }}</div>
                        <div class="edu-org-sub">{{ $edu['institution'] }}</div>
                        <div class="edu-org-period">{{ $edu['period'] }}</div>
                    </div>
                @endforeach
            </div>
            <div class="edu-org-col">
                @foreach($organizations as $org)
                    <div class="edu-org-item">
                        <div class="edu-org-title">{{ $org['role'] }}</div>
                        <div class="edu-org-sub">{{ $org['organization'] }} &middot; {{ $org['year'] }}</div>
                        @if($org['description'])
                            <div class="edu-org-period">{{ $org['description'] }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</body>
</html>
