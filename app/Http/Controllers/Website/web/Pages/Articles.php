<?php

namespace App\Http\Controllers\Website\web\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Models\Front\LineageInBody;
use App\Traits\GetLineage;

class Articles extends Controller {
  use GetLineage;

  public function mainArticles(Request $request) {
    $client = Client::where("code", Cookie::get('login_client'))->first();
    $lineages = $this->getlineages();
    $muscle = $this->getArray(LineageInBody::class, "SMM");
    $fat = $this->getArray(LineageInBody::class, "fat_mass");
    $water = $this->getArray(LineageInBody::class, "water");

    $cards = collect(__('article.articles'))->flatMap(fn($group) => $group);
    $slugs = [
      'article1' => 'sports-experience',
      'article2' => 'progress-in-gym',
      'article3' => 'lose-weight',
      'article4' => 'build-muscle',
      'article5' => 'dietary-supplements',
      'article6' => 'healthy-eating-sleep',
    ];
    $published = now()->subDays(7)->toIso8601String();
    $baseUrl = route('mainArticles');

    $articles = $cards->map(function ($card, $id) use ($slugs, $published, $baseUrl) {
      $slug = $slugs[$id] ?? $id;
      return [
        'id' => $id,
        'slug' => $slug,
        'title' => __('article.' . $id . '.title'),
        'excerpt' => $card['excerpt'],
        'content' => __('article.' . $id . '.content'),
        'image' => asset("images/article/{$id}.jpg"),
        'url' => $baseUrl . '#article-' . $slug,
        'datePublished' => $published,
      ];
    })->values();

    $locale = app()->getLocale();
    $seoItems = $articles->mapWithKeys(function ($article) use ($locale) {
      return [$article['slug'] => [
        'title' => 'Team Gym | ' . $article['title'],
        'description' => $article['excerpt'],
        'url' => $article['url'],
        'image' => $article['image'],
        'datePublished' => $article['datePublished'],
        'inLanguage' => $locale,
      ]];
    });

    return view('Website.web.Pages.Articles.mainArticles', compact("client", "lineages", "muscle", "fat", "water", "articles", "seoItems"));
  }
}
