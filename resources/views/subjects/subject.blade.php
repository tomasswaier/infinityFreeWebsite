@extends('layouts.app')
@section('mainContent')
@php
    // info is a JSON column (array cast). Fall back to decoding if the cast is missing.
    $info = $subject['info'] ?? [];
    if (is_string($info)) {
        $info = json_decode($info, true) ?? [];
    }

    // key inside info => heading shown on the page (order = order on the page)
    $sections = [
        'description'      => 'Description',
        'content'          => 'Content',
        'procedure'        => 'Procedure',
        'prior_knowledge'  => 'Required / prior knowledge',
        'lectures'         => 'Lectures',
        'exercises'        => 'Exercises',
        'grading'          => 'Examination / grading',
        'time_expenditure' => 'Time expenditure',
        'tips'             => 'Tips',
    ];
    $opinions = [
        'highlights' => 'Highlights / praise',
        'criticism'  => 'Criticism',
    ];

    $has = fn ($key) => filled($info[$key] ?? '');

    $filledSections = array_filter($sections, fn ($title, $key) => $has($key), ARRAY_FILTER_USE_BOTH);
    $filledOpinions = array_filter($opinions, fn ($title, $key) => $has($key), ARRAY_FILTER_USE_BOTH);
    $missing        = array_diff_key($sections + $opinions, $filledSections + $filledOpinions);
    $toc            = $filledSections + $filledOpinions;

    $rating = (float) ($subject['rating'] ?? 0);
    $ratingPercent = max(0, min(100, $rating * 10));
@endphp

<div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 text-project-super-blue">

    {{-- Header --}}
    <header class="rounded-2xl bg-project-light-blue px-6 py-7 sm:px-10">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold sm:text-4xl">{{ $subject['name'] }}</h1>
                @if($has('language'))
                    <p class="mt-1 text-sm text-project-dark-blue/70">Taught in {{ $info['language'] }}</p>
                @endif
            </div>
            <div class="shrink-0">
                <x-anonym-request-popup source="subjectPage/{{$subject->id}}" buttonText="Add/Edit Subject" />
            </div>
        </div>

        @if(count($tags))
            <div class="mt-5 flex flex-wrap gap-2">
                @foreach($tags as $tag)
                    <span class="rounded-full bg-white px-3 py-1 text-sm text-project-dark-blue">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
    </header>

    <div class="mt-8 grid grid-cols-1 gap-10 lg:grid-cols-[minmax(0,1fr)_20rem]">

        {{-- Article --}}
        <article class="min-w-0 space-y-10">

            @if(filled($subject['tldr'] ?? ''))
                <div class="rounded-2xl bg-project-light-blue/60 px-6 py-5">
                    <p class="mb-1 text-lg font-bold">TLDR</p>
                    <p class="max-w-prose whitespace-pre-line leading-relaxed">{{ $subject['tldr'] }}</p>
                </div>
            @endif

            @foreach($filledSections as $key => $title)
                <section id="section-{{ $key }}" class="scroll-mt-6">
                    <h2 class="mb-3 border-b border-project-light-blue pb-2 text-2xl font-bold text-project-dark-blue">{{ $title }}</h2>
                    <p class="max-w-prose whitespace-pre-line leading-relaxed">{{ $info[$key] }}</p>
                </section>
            @endforeach

            @if(count($filledOpinions))
                <div class="grid grid-cols-1 gap-6 {{ count($filledOpinions) > 1 ? 'md:grid-cols-2' : '' }}">
                    @foreach($filledOpinions as $key => $title)
                        <section id="section-{{ $key }}"
                                 class="scroll-mt-6 rounded-2xl p-6 {{ $key === 'highlights' ? 'bg-project-light-blue/60' : 'border border-project-blue bg-white' }}">
                            <h2 class="mb-2 text-xl font-bold text-project-dark-blue">{{ $title }}</h2>
                            <p class="whitespace-pre-line leading-relaxed">{{ $info[$key] }}</p>
                        </section>
                    @endforeach
                </div>
            @endif

            @if(count($missing))
                <div class="rounded-2xl border-2 border-dashed border-project-blue px-6 py-5 text-base">
                    <p class="mb-1 font-semibold">Nothing written yet</p>
                    <p>
                        {{ implode(', ', $missing) }}.
                        Know something about {{ count($missing) > 1 ? 'these' : 'this' }}?
                        Use “Add/Edit Subject” at the top of the page to suggest it.
                    </p>
                </div>
            @endif
        </article>

        {{-- Sidebar --}}
        <aside class="space-y-6 lg:sticky lg:top-6 lg:self-start">

            {{-- Rating --}}
            <div class="rounded-2xl border border-project-blue/60 bg-white p-5 shadow-sm">
                <p class="text-4xl font-bold text-project-dark-blue">
                    {{ number_format($rating, 1) }}<span class="text-xl font-normal text-project-dark-blue/60"> / 10</span>
                </p>
                <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-project-light-blue">
                    <div class="h-full rounded-full bg-project-dark-blue" style="width: {{ $ratingPercent }}%"></div>
                </div>
                <p class="mt-2 text-sm text-project-dark-blue/70">Average of the last 20 votes</p>

                @auth
                    <form action="{{ url('subjects/rating/'.$subject['id']) }}" method="post" enctype="multipart/form-data"
                          class="mt-5 border-t border-project-light-blue pt-4">
                        <input type="hidden" name="subject_id" value="{{ $subject['id'] }}">
                        @csrf
                        <label for="userRating" class="mb-2 block font-medium">Your rating</label>
                        <div class="flex items-center gap-2">
                            <input id="userRating" type="number" name="userRating" min="0" max="10"
                                   value="{{ $subject_user_rating ?? 1 }}"
                                   class="w-20 rounded-lg border border-project-blue/60 bg-project-white px-3 py-2 shadow-inner
                                          focus:outline-none focus:border-project-dark-blue focus:ring-2 focus:ring-project-blue">
                            <span class="text-project-dark-blue/70">/ 10</span>
                            <button type="submit"
                                    class="ml-auto rounded-lg bg-project-dark-blue px-4 py-2 text-sm font-semibold text-project-white
                                           transition-colors hover:bg-project-super-blue
                                           focus:outline-none focus-visible:ring-2 focus-visible:ring-project-blue focus-visible:ring-offset-2">
                                Save
                            </button>
                        </div>
                    </form>
                @endauth
                @guest
                    <p class="mt-4 border-t border-project-light-blue pt-4 text-sm text-project-dark-blue/70">Log in to rate this subject.</p>
                @endguest
            </div>

            {{-- Tests --}}
            <div class="rounded-2xl border border-project-blue/60 bg-white p-5 shadow-sm">
                <h3 class="mb-3 text-lg font-bold">Tests for this subject</h3>
                @if(isset($subject_tests) && count($subject_tests))
                    <ul class="-mx-2 space-y-1">
                        @foreach($subject_tests as $test)
                            <li>
                                <a href="{{ url('test/pisisadogshitsubject/'.$test->id.'/30') }}"
                                   class="block rounded-lg px-2 py-1.5 text-project-dark-blue underline decoration-project-blue underline-offset-2
                                          hover:bg-project-light-blue">
                                    {{ $test->test_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-project-dark-blue/70">No tests have been added yet.</p>
                @endif
            </div>

            {{-- On this page --}}
            @if(count($toc))
                <nav class="hidden lg:block" aria-label="On this page">
                    <h3 class="mb-2 px-3 text-lg font-bold">On this page</h3>
                    <ul>
                        @foreach($toc as $key => $title)
                            <li>
                                <a href="#section-{{ $key }}"
                                   class="block rounded-lg px-3 py-1.5 text-project-dark-blue hover:bg-project-light-blue">{{ $title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            {{-- Admin --}}
            @if(Auth::user() && supervisesSchool(Auth::user(), $school_id))
                <div class="rounded-2xl bg-project-super-blue p-5 text-project-white">
                    <h3 class="mb-3 text-lg font-bold">Admin panel</h3>
                    <a href="{{ url('admin/subjectCreator/'.$subject['school_id'].'/'.$subject['id']) }}"
                       class="inline-block rounded-lg bg-project-blue px-4 py-2 font-semibold text-project-super-blue
                              transition-colors hover:bg-project-light-blue
                              focus:outline-none focus-visible:ring-2 focus-visible:ring-project-blue focus-visible:ring-offset-2">
                        Edit subject
                    </a>
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
