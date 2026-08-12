<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="google" content="notranslate">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="icon" href="{{asset("images/header/Team-Gym.png")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/public.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/components/header.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/components/footer.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/pages/articles.css")}}">
  @php
    $locale = app()->getLocale();
    $pageTitle = __('article.section1_title');
    $pageDescription = __('article.section1_subtitle');
    $pageImage = asset('images/article/article1.jpg');
    $ogLocale = $locale === 'ar' ? 'ar_AR' : 'en_US';
  @endphp
  <link rel="canonical" href="{{ route('mainArticles') }}">
  <link rel="alternate" hreflang="en" href="{{ route('mainArticles') }}">
  <link rel="alternate" hreflang="ar" href="{{ route('mainArticles') }}">
  <link rel="alternate" hreflang="x-default" href="{{ route('mainArticles') }}">
  <meta name="description" content="{{ $pageDescription }}">
  <meta name="author" content="Team Gym">
  <meta property="og:site_name" content="Team Gym">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Team Gym | {{ $pageTitle }}">
  <meta property="og:description" content="{{ $pageDescription }}">
  <meta property="og:image" content="{{ $pageImage }}">
  <meta property="og:url" content="{{ route('mainArticles') }}">
  <meta property="og:locale" content="{{ $ogLocale }}">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Team Gym | {{ $pageTitle }}">
  <meta name="twitter:description" content="{{ $pageDescription }}">
  <meta name="twitter:image" content="{{ $pageImage }}">
  <script type="application/ld+json" id="blog-jsonld">
    {
      "@@context": "https://schema.org",
      "@type": "Blog",
      "name": "Team Gym Blog",
      "url": "{{ route('mainArticles') }}",
      "description": "{{ $pageDescription }}",
      "publisher": {
        "@type": "Organization",
        "name": "Team Gym",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ asset('images/header/Team-Gym.png') }}"
        }
      },
      "blogPost": [
        @foreach ($articles as $index => $article)
          {
            "@type": "BlogPosting",
            "headline": "{{ $article['title'] }}",
            "image": "{{ $article['image'] }}",
            "url": "{{ $article['url'] }}",
            "datePublished": "{{ $article['datePublished'] }}",
            "inLanguage": "{{ $locale }}",
            "publisher": {
              "@type": "Organization",
              "name": "Team Gym"
            }
          }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
    }
  </script>
  <script type="application/ld+json" id="article-jsonld"></script>
  <title>Team Gym | {{ $pageTitle }}</title>
</head>
<body>
  <div class="main-blur"></div>
  @if (Cookie::has('login_client'))
    <x-web::profile name="{{$client->fname}} {{$client->lname}}" state="{{$client->category}}" documentation="{{$client->documentation}}" img="{{$client->img}}" :lineages="$lineages" :muscles="$muscle" :fats="$fat" :water="$water"/>
  @endif
  <div class="warnings-container">
    @foreach ($errors->all() as $error)
      <x-components::warning :text="$error" />
    @endforeach
  </div>
  <header>
    @include('Website.web.Tires.Articles.navArticles')
  </header>
  @include('Website.web.Sections.mainArticles.section_1')

  @include('Website.web.Sections.mainArticles.section_2')

  @include('Website.web.Sections.mainArticles.section_3')

  @include('Website.web.Sections.mainArticles.section_5')

  @include('Website.web.Sections.mainArticles.section_4')

  @include('Website.web.Sections.footer')
  <script>
    let lableCanava = "{{__('messages.card-profile-lable')}}";
    let cardProfileMonths = [
      "{{ __('messages.card-profile-Jan') }}",
      "{{ __('messages.card-profile-Feb') }}",
      "{{ __('messages.card-profile-Mar') }}",
      "{{ __('messages.card-profile-Apr') }}",
      "{{ __('messages.card-profile-May') }}",
      "{{ __('messages.card-profile-Jun') }}",
      "{{ __('messages.card-profile-Jul') }}",
      "{{ __('messages.card-profile-Aug') }}",
      "{{ __('messages.card-profile-Sep') }}",
      "{{ __('messages.card-profile-Oct') }}",
      "{{ __('messages.card-profile-Nov') }}",
      "{{ __('messages.card-profile-Dec') }}",
    ];
    let articleSEO = {
      defaultTitle: @json('Team Gym | ' . $pageTitle),
      defaultDescription: @json($pageDescription),
      defaultUrl: @json(route('mainArticles')),
      items: @json($seoItems)
    };
  </script>
  <script src="{{asset("js/Website/web/pages/articles.js")}}"></script>
  <script src="{{asset("js/Website/Dashboard/pages/profileCard.js")}}"></script>
</body>
</html>
