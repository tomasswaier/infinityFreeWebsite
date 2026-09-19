@extends('layouts.app')
@section('mainContent')
<div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 text-project-super-blue">

    {{-- Header --}}
    <header class="flex flex-wrap items-start justify-between gap-4 rounded-2xl bg-project-light-blue px-6 py-7 sm:px-10">
        <div>
            <h1 class="text-3xl font-bold sm:text-4xl">Subjects</h1>
            <p class="mt-1 max-w-prose text-project-dark-blue/80">
                Find out what other students say about each subject, so you know what to expect.
            </p>
        </div>
        <div class="shrink-0">
            <x-anonym-request-popup source="subjectPage" buttonText="Add/Edit Subject" />
        </div>
    </header>

    {{-- Admin --}}
    @if(Auth::user() && supervisesSchool(Auth::user(), $school_id))
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-project-super-blue px-6 py-4 text-project-white">
            <h2 class="text-lg font-bold">Admin panel</h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ url('admin/subjectCreator/'.$school_id) }}"
                   class="rounded-lg bg-project-blue px-4 py-2 font-semibold text-project-super-blue
                          transition-colors hover:bg-project-light-blue
                          focus:outline-none focus-visible:ring-2 focus-visible:ring-project-blue focus-visible:ring-offset-2">
                    Add subject
                </a>
                <a href="{{ url('admin/tagCreator/'.$school_id) }}"
                   class="rounded-lg bg-project-blue px-4 py-2 font-semibold text-project-super-blue
                          transition-colors hover:bg-project-light-blue
                          focus:outline-none focus-visible:ring-2 focus-visible:ring-project-blue focus-visible:ring-offset-2">
                    Create tag
                </a>
            </div>
        </div>
    @endif

    {{-- Filter --}}
    <section class="mt-10">
        <form action="{{ url('subjects/'.$school_id) }}" method="post" enctype="multipart/form-data"
              class="rounded-2xl bg-project-light-blue/60 px-6 py-5">
            @csrf
            <fieldset>
                <legend class="mb-3 text-xl font-bold text-project-dark-blue">Filter by tags</legend>
                <div class="flex flex-wrap gap-3">
                    @foreach($tags as $tag)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="checkbox_{{ $tag['id'] }}" value="{{ $tag['id'] }}" class="peer sr-only">
                            <span class="inline-block rounded-full border border-project-blue bg-white px-4 py-1.5 text-base
                                         transition-colors hover:bg-project-light-blue
                                         peer-checked:border-project-dark-blue peer-checked:bg-project-dark-blue peer-checked:text-project-white
                                         peer-focus-visible:ring-2 peer-focus-visible:ring-project-blue peer-focus-visible:ring-offset-2">
                                {{ $tag['name'] }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </fieldset>
            <button type="submit"
                    class="mt-5 rounded-xl bg-project-dark-blue px-6 py-2.5 font-semibold text-project-white shadow-sm shadow-neutral-800/40
                           transition-colors hover:bg-project-super-blue
                           focus:outline-none focus-visible:ring-2 focus-visible:ring-project-blue focus-visible:ring-offset-2">
                Filter
            </button>
        </form>
    </section>

    {{-- Subject list --}}
    <section class="mt-8">
        <div class="overflow-x-auto rounded-2xl border border-project-blue/60 bg-white shadow-sm">
            <table class="w-full text-left">
                <thead class="bg-project-light-blue/60 text-project-dark-blue">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Subject</th>
                        <th class="w-32 px-6 py-3 font-semibold">Rating</th>
                        @if(Auth::user() && supervisesSchool(Auth::user(), $school_id))
                            <th class="px-6 py-3 font-semibold">Views</th>
                        @endif
                        <th class="px-6 py-3 font-semibold">Tags</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-project-light-blue">
                    @forelse($subjects as $subject)
                        <tr class="transition-colors hover:bg-project-light-blue/40">
                            <td class="px-6 py-3 font-medium">
                                <a href="{{ url('subjects/info/'.$subject['id']) }}"
                                   class="text-project-dark-blue underline decoration-project-blue underline-offset-2 hover:text-project-super-blue">
                                    {{ $subject['name'] }}
                                </a>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3">
                                <span class="font-bold text-project-dark-blue">{{ $subject['rating'] }}</span><span class="text-project-dark-blue/60"> / 10</span>
                            </td>
                            @if(Auth::user() && supervisesSchool(Auth::user(), $school_id))
                                <td class="whitespace-nowrap px-6 py-3">
                                    <span class="font-bold text-project-dark-blue">{{ $subject['views'] }}</span>
                                </td>
                            @endif
                            <td class="px-6 py-3">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($subject['tags'] as $tag)
                                        <span class="rounded-full bg-project-light-blue px-3 py-1 text-sm text-project-dark-blue">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-project-dark-blue/70">
                                No subjects match these tags. Try removing a tag or two.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
