<section class="section-2">
    <div class="section-title">
      <h2>{{ __('article.section2_trending') }} <span class="muted">{{ __('article.section2_count') }}</span></h2>
    </div>
    <div class="list">
      @php
        $article = __('article.articles.cards1');
        $articleCard = __('article.articles.cards2');
        $questionsArticle = __('article.questions_article');
        $slugs = collect($articles)->keyBy('id')->map(fn($item) => $item['slug']);
      @endphp
      @foreach ($article as $index => $item)
        <a href="#article-{{ $slugs[$index] ?? $index }}" class="article" data-article="{{ $slugs[$index] ?? $index }}">
          <div class="article__meta">
            <span class="article__tag">
              <svg width="25" height="25" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 13l7-7a4 4 0 0 1 5.656 5.656l-9 9a4 4 0 0 1-5.656-5.656l7-7" stroke="var(--colorParagraph)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <line x1="8.3" y1="17.25" x2="17.7" y2="7.75" stroke="var(--colorParagraph)" stroke-width="2" stroke-linecap="round"/>
              </svg>
              <p>{{ __('article.article_tag') }}</p>
            </span>
            <div class="content">
              <h3 class="title">{{$item['title']}}</h3>
              <p class="excerpt">{{$item['excerpt']}}</p>
              <span class="read-more">{{ __('article.read_more') }} →</span>
            </div>
          </div>
          <img src="{{ asset("images/article/{$index}.jpg") }}" alt="{{$item['title']}}">
        </a>
      @endforeach
    </div>
  </section>