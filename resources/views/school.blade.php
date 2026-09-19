@extends('layouts.app')
@section('mainContent')
@php
    $select = 'rounded-lg border border-project-blue/60 bg-project-white px-3 py-2 shadow-inner '
            . 'focus:outline-none focus:border-project-dark-blue focus:ring-2 focus:ring-project-blue';
    $secondaryButton = 'rounded-lg border border-project-blue bg-white px-4 py-2 text-sm font-semibold text-project-dark-blue '
                     . 'transition-colors hover:bg-project-light-blue '
                     . 'focus:outline-none focus-visible:ring-2 focus-visible:ring-project-blue focus-visible:ring-offset-2';
@endphp

<div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 text-project-super-blue">

    {{-- Header --}}
    <header class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-project-light-blue px-6 py-7 sm:px-10">
        <h1 class="text-3xl font-bold sm:text-4xl">Welcome to your school page</h1>
        <div class="shrink-0">
            <x-anonym-request-popup source="schoolPage/{{$school_id}}" buttonText="Contact Us" />
        </div>
    </header>

    {{-- Admin --}}
    @if(Auth::user() && supervisesSchool(Auth::user(), $school_id))
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-project-super-blue px-6 py-4 text-project-white">
            <h2 class="text-lg font-bold">Admin panel</h2>
            <a href="{{ url('admin/studyGuide/create/'.$school_id) }}"
               class="rounded-lg bg-project-blue px-4 py-2 font-semibold text-project-super-blue
                      transition-colors hover:bg-project-light-blue
                      focus:outline-none focus-visible:ring-2 focus-visible:ring-project-blue focus-visible:ring-offset-2">
                Create study guide
            </a>
        </div>
    @endif

    {{-- Study guides --}}
    <section class="mt-10">
        <h2 class="mb-4 border-b border-project-light-blue pb-2 text-2xl font-bold text-project-dark-blue">Study guides</h2>

        <form id="main_form" action="" method="post" enctype="multipart/form-data">
            @csrf

            {{-- Filters --}}
            <div class="mb-6 flex flex-wrap items-end gap-6 rounded-2xl bg-project-light-blue/60 px-6 py-5">
                <div>
                    <label for="subject" class="mb-1 block font-medium">Subject</label>
                    <select id="subject" name="subject" onchange="this.form.submit()" class="{{ $select }}">
                        <option value="0">All subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" @if($subject->id == $selected_subject_id) selected @endif>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="order" class="mb-1 block font-medium">Order by</label>
                    <select id="order" name="order" onchange="this.form.submit()" class="{{ $select }}">
                        <option value="lastVersion" @if($order == "lastVersion") selected @endif>Last version</option>
                        <option value="viewCount" @if($order == "viewCount") selected @endif>View count</option>
                        <option value="noOrder" @if($order == "noOrder") selected @endif>No order</option>
                    </select>
                </div>
            </div>

            {{-- List --}}
            <div class="overflow-x-auto rounded-2xl border border-project-blue/60 bg-white shadow-sm">
                <table class="w-full text-left">
                    <thead class="bg-project-light-blue/60 text-project-dark-blue">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Name</th>
                            <th class="w-32 px-6 py-3 font-semibold">Version</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-project-light-blue">
                        @forelse($study_guides as $study_guide)
                            <tr class="transition-colors hover:bg-project-light-blue/40">
                                <td class="px-6 py-3">
                                    <a href="{{ url('studyGuide/'.$study_guide->id) }}"
                                       class="text-project-dark-blue underline decoration-project-blue underline-offset-2 hover:text-project-super-blue">
                                        {{ $study_guide->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="rounded-full bg-project-light-blue px-3 py-1 text-sm text-project-dark-blue">{{ $study_guide->version }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-8 text-center text-project-dark-blue/70">
                                    No study guides match these filters. Try choosing a different subject.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination placeholders (still not wired up, same as before) --}}
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" name="prev" class="{{ $secondaryButton }}">Previous</button>
                <button type="button" name="next" class="{{ $secondaryButton }}">Next</button>
            </div>
        </form>
    </section>
</div>
@endsection
