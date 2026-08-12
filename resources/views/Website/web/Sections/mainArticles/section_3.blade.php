<section class="section-3">
    <div class="title">{{ __('article.section3_title') }}</div>
    <div class="cards">
      @php
        $articleCard = __('article.articles.cards2');
        $slugs = collect($articles)->keyBy('id')->map(fn($item) => $item['slug']);
      @endphp
      @foreach ($articleCard as $index => $item)
        <a href="#article-{{ $slugs[$index] ?? $index }}" class="card" data-article="{{ $slugs[$index] ?? $index }}">
          <img src="{{ asset("images/article/{$index}.jpg") }}" alt="{{$item['title']}}">
          <p class="title">{{$item['title']}}</p>
          <p class="text">{{$item['excerpt']}}</p>
          <span class="read-more">{{ __("article.read_more") }} →</span>
        </a>
      @endforeach
    </div>
  </section>