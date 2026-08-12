<section class="section-4">
    <div class="head">
      <div class="left">
        <h1>{{ __('article.questions_header') }}</h1>
      </div>
    </div>
    <div class="faq">
      @php
        $questionsArticle = __('article.questions_article');
      @endphp
      @foreach ($questionsArticle as $index => $item)
        <div class="item">
          <div class="head">
            <div class="question">{{$item['question']}}</div>
            <div class="toggle">+</div>
          </div>
          <div class="content">
            <p>{{$item['answer']}}</p>
          </div>
        </div>
      @endforeach
    </div>
  </section>