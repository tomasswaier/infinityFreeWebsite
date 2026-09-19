@extends('layouts.app')
@section('mainContent')
@php
    //Imma be frank here this is vibecoded. I wanted to make it look better and this kinda happened idk I ain't even reading it
    // Shared classes so every control looks the same
    $input = 'w-full rounded-lg border border-project-blue/60 bg-project-white px-3 py-2 shadow-inner '
           . 'focus:outline-none focus:border-project-dark-blue focus:ring-2 focus:ring-project-blue';
    $label = 'block mb-1 font-medium text-project-super-blue';
    // Values that live in `info` (JSON). Everything else is a normal column.
    $columns = ['name', 'rating', 'tldr'];

    $info = isset($subject) ? ($subject['info'] ?? []) : [];
    if (is_string($info)) {                       // in case the model has no 'array' cast
        $info = json_decode($info, true) ?? [];
    }

    // Priority: old input (after a failed validation) > saved value > empty
    $val = fn ($field, $key) => old($field, isset($subject)
        ? (in_array($key, $columns) ? ($subject[$key] ?? '') : ($info[$key] ?? ''))
        : '');

    // name attribute => [label, key on $subject]
    $courseFields = [
        'subjectContent'         => ['Content', 'content','Big topics you covered'],
        'subjectProcedure'       => ['Procedure', 'procedure','How you progressed trought he contents of the subject, first x weeks we did this, then that ...'],
        'subjectPriorKnowledge'  => ['Required / prior knowledge', 'prior_knowledge',"For psip it's good to have prior knowledge of basics of linux"],
        'subjectLectures'        => ['Lectures', 'lectures','Review on lectures good, bad , hard to understand , useless ...'],
        'subjectExercises'       => ['Exercises', 'exercises','We had 3 exercises, two for 5 points one for 10, first two were about....'],
        'subjectGrading'         => ['Examination / grading', 'grading','60% points are from semester and 50% are for exam, overall 110% is possible'],
        'subjectTimeExpenditure' => ['Time expenditure', 'time_expenditure','Projects are hard time wise. For me it was 12*2h(lectures)+12*4h(attendace at seminars is not required)+40h project + 10h of studying for final'],
    ];
    $opinionFields = [
        'subjectTips'       => ['Tips', 'tips',"project takes a long time so start really early, Final exam questions tend to be reused, buy pink wards against evelynn"], // DO NOT CHANGE THIS IT'S VERY FUNNY
        'subjectHighlights' => ['Highlights / praise', 'highlights','Lecturer is very funny and understanding during seminars'],
        'subjectCriticism'  => ['Criticism', 'criticism','Lecturer was very mean during seminars and overall unwelcoming'],
    ];
@endphp

<div class="px-4 py-10 sm:px-6 text-project-dark-blue text-lg">
    <div class="mx-auto w-full max-w-4xl rounded-2xl bg-white shadow-md shadow-neutral-400/50 overflow-hidden">

        {{-- Header band --}}
        <div class="bg-project-super-blue px-6 py-6 sm:px-10">
            <h2 class="text-3xl font-bold text-project-white">
                {{ isset($subject) ? 'Edit subject' : 'Create a subject' }}
            </h2>
            @isset($subject)
                <p class="mt-1 text-base text-project-blue">{{ $subject['name'] }} (ID {{ $subject['id'] }})</p>
            @endisset
        </div>

        <form action="{{ url('admin/subjectCreator') }}" method="post" enctype="multipart/form-data"
              class="px-6 py-8 sm:px-10 space-y-10">
            @csrf
            <input type="hidden" name="school_id" value="{{ $school_id }}">
            @isset($subject)
                <input type="hidden" name="subjectId" value="{{ $subject['id'] }}">
            @endisset

            {{-- Basics --}}
            <fieldset class="space-y-6">
                <legend class="mb-4 text-xl font-bold text-project-super-blue">The basics</legend>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="subjectName" class="{{ $label }}">Name</label>
                        <input id="subjectName" title="Name of the subject" type="text" name="subjectName"
                               value="{{ $val('subjectName', 'name') }}" class="{{ $input }}" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="subjectLanguage" class="{{ $label }}">Language</label>
                        <input id="subjectLanguage" type="text" name="subjectLanguage"
                               value="{{ $val('subjectLanguage', 'language') }}" class="{{ $input }}">
                    </div>
                    <div class="sm:col-span-1">
                        <label for="subjectRating" class="{{ $label }}">Rating</label>
                        <input id="subjectRating"
                               title="This rating is only a placeholder until a user specifically votes for this subject's rating in the subject menu"
                               type="number" name="subjectRating" value="{{ $val('subjectRating', 'rating') }}"
                               step="0.1" max="10" class="{{ $input }}" required>
                    </div>
                </div>

                <!--<div>
                    <label for="subjectDescription" class="{{ $label }}">Description</label>
                    <textarea id="subjectDescription" name="subjectDescription" rows="4"
                              placeholder="In the description, try to mention everything from projects to lectures"
                              class="{{ $input }}" required>{{ $val('subjectDescription', 'description') }}</textarea>
                </div>-->

                <div>
                    <label for="subjectTldr" class="{{ $label }}">TLDR</label>
                    <textarea id="subjectTldr" name="subjectTldr" rows="3"
                              placeholder="The most relevant parts of the subject, like complexity of projects and tests (1024 chars long)"
                              class="{{ $input }}" required>{{ $val('subjectTldr', 'tldr') }}</textarea>
                </div>
            </fieldset>

            {{-- How the subject runs --}}
            <fieldset>
                <legend class="mb-4 text-xl font-bold text-project-super-blue">How the subject works</legend>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    @foreach($courseFields as $name => [$text, $key, $placeholder])
                        <div>
                            <label for="{{ $name }}" class="{{ $label }}">{{ $text }}</label>
                            <textarea id="{{ $name }}" name="{{ $name }}" rows="4"
                                      class="{{ $input }}" placeholder="{{$placeholder}}"
                                      >{{ $val($name, $key) }}</textarea>
                        </div>
                    @endforeach
                </div>
            </fieldset>

            {{-- Student experience --}}
            <fieldset>
                <legend class="mb-4 text-xl font-bold text-project-super-blue">Student experience</legend>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    @foreach($opinionFields as $name => [$text, $key, $placeholder])
                        <div class="{{ $loop->first ? 'md:col-span-2' : '' }}">
                            <label for="{{ $name }}" class="{{ $label }}">{{ $text }}</label>
                            <textarea id="{{ $name }}" name="{{ $name }}" rows="4" placeholder="{{$placeholder}}"
                                      class="{{ $input }}">{{ $val($name, $key) }}</textarea>
                        </div>
                    @endforeach
                </div>
            </fieldset>

            {{-- Tags --}}
            <fieldset>
                <legend class="mb-4 text-xl font-bold text-project-super-blue">Tags</legend>
                <div class="flex flex-wrap gap-3">
                    @foreach($allTags as $tag)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="tag_id_{{ $tag['id'] }}" value="true" class="peer sr-only"
                                   {{ isset($selectedTags) && in_array($tag['id'], $selectedTags) ? 'checked' : '' }}>
                            <span class="inline-block rounded-full border border-project-blue bg-project-white px-4 py-1.5
                                         text-base transition-colors hover:bg-project-light-blue
                                         peer-checked:border-project-dark-blue peer-checked:bg-project-dark-blue peer-checked:text-project-white
                                         peer-focus-visible:ring-2 peer-focus-visible:ring-project-blue peer-focus-visible:ring-offset-2">
                                {{ $tag['name'] }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            {{-- Actions --}}
            <div class="flex justify-end border-t border-project-light-blue pt-6">
                <button type="submit"
                        class="rounded-xl bg-project-dark-blue px-8 py-3 font-semibold text-project-white shadow-sm shadow-neutral-800/40
                               transition-colors hover:bg-project-super-blue
                               focus:outline-none focus-visible:ring-2 focus-visible:ring-project-blue focus-visible:ring-offset-2">
                    Save subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
