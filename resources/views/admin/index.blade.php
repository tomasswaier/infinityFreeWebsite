@extends('layouts.app')
@section('mainContent')
@php
    $toolLink = 'rounded-lg bg-project-blue px-4 py-2 font-semibold text-project-super-blue '
              . 'transition-colors hover:bg-project-light-blue '
              . 'focus:outline-none focus-visible:ring-2 focus-visible:ring-project-blue focus-visible:ring-offset-2';
    $rowLink = 'text-project-dark-blue underline decoration-project-blue underline-offset-2 hover:text-project-super-blue';
    $pillLink = 'inline-block rounded-lg border border-project-blue px-3 py-1 text-sm font-semibold text-project-dark-blue '
              . 'transition-colors hover:bg-project-light-blue '
              . 'focus:outline-none focus-visible:ring-2 focus-visible:ring-project-blue';
@endphp

<div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 text-project-super-blue">

    {{-- Header --}}
    <header class="rounded-2xl bg-project-light-blue px-6 py-7 sm:px-10">
        <h1 class="text-3xl font-bold sm:text-4xl">Admin dashboard</h1>
        <p class="mt-1 max-w-prose text-project-dark-blue/80">
            Hi! Manage schools, users, requests and tests from here.
        </p>
    </header>

    {{-- Creators --}}
    @if(Auth::user()->authorization == 'admin')
        <div class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-3 rounded-2xl bg-project-super-blue px-6 py-4 text-project-white">
            <h2 class="text-lg font-bold">Creators</h2>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ url('admin/schoolCreator') }}" class="{{ $toolLink }}">School creator</a>
                <a href="{{ url('admin/users/manage') }}" class="{{ $toolLink }}">User manager</a>
                <a href="{{ url('anonymRequest/index') }}" class="{{ $toolLink }}">Anonym request testing</a>
                <x-anonym-request-popup source="adminPage"/>
            </div>
        </div>
    @endif

    <div class="mt-10 grid grid-cols-1 gap-10 xl:grid-cols-2">

        {{-- Anonymous requests --}}
        <section class="min-w-0">
            <h2 class="mb-4 border-b border-project-light-blue pb-2 text-2xl font-bold text-project-dark-blue">Requests</h2>
            <div class="overflow-x-auto rounded-2xl border border-project-blue/60 bg-white shadow-sm">
                <table class="w-full text-left">
                    <thead class="bg-project-light-blue/60 text-project-dark-blue">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Source</th>
                            <th class="px-5 py-3 font-semibold">Text</th>
                            <th class="w-28 px-5 py-3 text-center font-semibold">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-project-light-blue">
                        @forelse($anonymRequests as $anonymRequest)
                            <tr class="align-top transition-colors hover:bg-project-light-blue/40">
                                <td class="whitespace-nowrap px-5 py-3">
                                    <span class="rounded-full bg-project-light-blue px-3 py-1 text-sm text-project-dark-blue">{{ $anonymRequest->source }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <p class="max-w-prose whitespace-pre-line break-words">{{ $anonymRequest->text }}</p>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <a href="{{ url('admin/anonymRequestDelete/'.$anonymRequest->id) }}"
                                       onclick="return confirm('Delete this request?')"
                                       class="inline-block rounded-lg px-3 py-1 text-sm font-semibold text-red-700 transition-colors hover:bg-red-50
                                              focus:outline-none focus-visible:ring-2 focus-visible:ring-red-300">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-8 text-center text-project-dark-blue/70">No requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Tests --}}
        <section class="min-w-0">
            <h2 class="mb-4 border-b border-project-light-blue pb-2 text-2xl font-bold text-project-dark-blue">Tests</h2>
            <div class="overflow-x-auto rounded-2xl border border-project-blue/60 bg-white shadow-sm">
                <table class="w-full text-left">
                    <thead class="bg-project-light-blue/60 text-project-dark-blue">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Test name</th>
                            <th class="px-5 py-3 text-center font-semibold">Submits</th>
                            <th class="px-5 py-3 text-center font-semibold">Edit</th>
                            <th class="px-5 py-3 text-center font-semibold">Questions</th>
                            <th class="px-5 py-3 text-center font-semibold">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-project-light-blue">
                        @forelse($tests as $test)
                            <tr class="transition-colors hover:bg-project-light-blue/40">
                                <td class="px-5 py-3 font-medium">
                                    <a href="{{ url('admin/questionDisplay/'.$test->id) }}" class="{{ $rowLink }}">{{ $test->test_name }}</a>
                                </td>
                                <td class="px-5 py-3 text-center">{{ $test->number_of_submits }}</td>
                                <td class="px-5 py-3 text-center">
                                    <a href="{{ url('admin/questionDisplay/'.$test->id) }}" title="Edit test"
                                       class="inline-block rounded-lg p-1 transition-colors hover:bg-project-light-blue">
                                        <img src="{{ asset('storage/assets/edit_icon.png') }}" class="h-7 w-auto" alt="Edit {{ $test->test_name }}">
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <a href="{{ url('admin/questionCreator/'.$test->id) }}" class="{{ $pillLink }}">+ Add question</a>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <button type="button" disabled title="Not implemented yet"
                                            class="cursor-not-allowed rounded-lg px-3 py-1 text-sm font-semibold text-project-dark-blue/40">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-project-dark-blue/70">No tests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    {{-- Tutorials --}}
    <section class="mt-10">
        <h2 class="mb-4 border-b border-project-light-blue pb-2 text-2xl font-bold text-project-dark-blue">Tutorials</h2>
        <p class="mb-3">Guides for parts of the website:</p>
        <ul class="mb-5 space-y-1">
            <li><a href="{{ url('admin/resources/testGuide/') }}" class="{{ $rowLink }}">Tests guide</a></li>
            <li><a href="{{ url('admin/resources/studyGuideGuide/') }}" class="{{ $rowLink }}">Study guides guide</a></li>
        </ul>
        <p class="max-w-prose text-project-dark-blue/80">
            This project was created to facilitate multiple schools so that's why it's structured as it is and not only for feet
        </p>
    </section>

    {{-- Developer notes --}}
    <section class="mt-10 rounded-2xl border-2 border-dashed border-project-blue px-6 py-5">
        <h2 class="mb-2 text-xl font-bold text-project-dark-blue">Developer notes</h2>
        <p class="max-w-prose whitespace-pre-line">Hi Valeriia a prob ppl whom I forgot to delete accounts!!! I wanted to add a few things to the websiet and since ppl complained before that it looks like shit I decided to push forward this ai slop as my prob last commit(and the change to subjects so it looks more like vowi.fsinf.at).</p><br>
        <p class="max-w-prose whitespace-pre-line">creating tags really doesnt work as well as it should so it would be great to redo it(I have not tested it yet but i think it'll kill everything you wrote into ur subject)</p>
    </section>
</div>
@endsection
