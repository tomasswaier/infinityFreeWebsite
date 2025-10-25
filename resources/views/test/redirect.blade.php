@extends('layouts.app')
@section('mainContent')
<div>
    <h1>Hi! </h1>
    <pre>
If you're here because you wanted to access PPI test then I have sad news for you. This test is unavailable at this moment because it needs to be changed. (If you'd like to help out and get the test running then dm me on dc.)

If you got here from on old link then click on "Main Page" in the top bar, click on your schools dedicated page and continue your business!
    </pre>
</div>


<script src="{{ asset('js/oldLinkRedirect.js') }}"></script>
@endsection
