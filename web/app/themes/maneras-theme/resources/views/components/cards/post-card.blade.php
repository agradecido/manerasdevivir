{{-- components/cards/post-card.blade.php --}}
<article class="card px-0 pt-0 lg:pb-0 lg:grid lg:grid-cols-5 lg:gap-4">

  <div class="post-feature-image lg:col-span-1 overflow-hidden self-center lg:hidden max-h-[190px] md:max-h-[390px]">
    <a href="{{ $post['permalink'] }}" class="">
      <img
        src="{{ $post['featured'] }}"
        alt="{{ $post['title'] }}"
        class="object-contain"
      />
    </a>
  </div>

  <div class="lg:col-span-5 px-2">
    <header class="px-2">
      <div class="flex flex-wrap justify-between">
        <div class="post-heading flex flex-1 flex-col">
          <h3 class="post-title text-xl pr-2 mt-3">
            <a href="{{ $post['permalink'] }}" class="hover:underline font-normal">
              {{ $post['title'] }}
            </a>
          </h3>
        </div>

        <div class="post-meta flex justify-between sm:justify-start w-full max-w-full min-w-full text-xs mt-2">
          <div class="post-author sm:mr-5">
            <i class="fa fa-solid fa-user-astronaut opacity-75"></i>{{ $post['author'] }}
          </div>
          <div class="post-date">
            <time class="dt-published" datetime="{{ $post['date'] }}">
              <i class="fa fa-calendar-alt opacity-75"></i>{{ $post['date'] }}
            </time>
          </div>
        </div>
      </div>
    </header>

    <div class="post-summary mt-2 flex-1 px-2">
      <p class="mb-0 lg:pb-4">{!! $post['excerpt'] !!}</p>
    </div>
  </div>

</article>
