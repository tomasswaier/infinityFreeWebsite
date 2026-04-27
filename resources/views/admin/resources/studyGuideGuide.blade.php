@extends('layouts.app')
@section('mainContent')
<div class="w-full p-10">
<span>Hi! Here I will try to explain everything about study guides</span><br>
<span id="Don'tUseThem" class="text-xl font-bold">Don't Use Them:</span><br>
<span>Unless the message you want to tell everyone is something similar to the "bc year 2" study guide then don't use it(wasted effort).When I was making them I didn't know about wysiwyg and that's why the "editor" is so bad. Using them for more than displaying text and image is impossible. It would be great if someone implemented wysiwyg however since I'm the only developer I don't see a future in this feature unless someone adds adds the text editor.</span><br><br>
<span id="Creation" class="text-xl font-bold">Study Guide Creation:</span><br>
<span>Study Guides can be created in the admin panel in school page. Be careful however because their DELETION IS IMPOSSIBLE. I tried adding versioning because it sounded cool but my implementation is dumb and useless.</span>
</div>
@endsection
