<section class="article-reader" id="article-reader" aria-live="polite" hidden>
    <button type="button" class="reader-close" id="reader-close" aria-label="{{ __('article.reader_back') }}">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <span>{{ __('article.reader_back') }}</span>
    </button>
    @foreach ($articles as $article)
      <article class="reader-article" id="article-{{ $article['slug'] }}" hidden>
        <header class="reader-hero">
          <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}">
          <div class="reader-hero-overlay">
            <span class="badge">{{ __('article.article_tag') }}</span>
            <h1>{{ $article['title'] }}</h1>
            <p>{{ $article['excerpt'] }}</p>
          </div>
        </header>
        <div class="reader-content">
          {!! $article['content'] !!}
        </div>
      </article>
    @endforeach
  </section>
