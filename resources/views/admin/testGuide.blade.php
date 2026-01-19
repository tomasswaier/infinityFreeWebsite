@extends('layouts.app')
@section('mainContent')
<div class="w-full p-10">
<span>Hi! Here I will try to explain everything bcs everyone who was</span><br>
<span id="TestCreation" class="text-xl font-bold">Test Creation:</span><br>
<span>Tests can be created in admin panel when you enter a TestPage of any school. Tests can NOT be deleted (only directly trough db) so please don't create them for no reason(button for test deletion has been disabled for social reasons)</span><br>

<span id="QuestionCreationSection" class="text-xl font-bold">Question Creation:</span><br>
<span>To create question enter the Admin Page. In Tests table find the test into which you'd like to add the question and click the Big Bold plush sign in the "add question" column.</span><br>
<img  src="{{asset('storage/assets/blankQuestion.png')}}" alt="question creator screenshot"><br>
<ul class="list-disc">
   <li>First input field of the form is the Question Text input field. It should display question itself which should be answered in the option fields.</li>
   <li>Right below the Question input is the Image input field. After clicking the button Browse a menu will open up where you may select any file on your computer. When the question is displayed the image will then be displayed on the website right below the Question text.</li>
   <li>Under the Image input field is a select element with all available question types.
   <ol class="list-decimal pl-5">
     <li>Boolean choice-simple input field where the user has to choose if the answer is true or false. To the right of the input's is a text field where you may insert a statement such as "Cats are blue". If the statement is True you select the left input field, oterwise select the right input field</li>
     <li>Boolean choice one correct- Similar to boolean choice, in this question type the user has to select one answer from list of presented options. By clicking the + button in bottom left corner you may add more options and afterwards select select one of them as the correcet answer by clicking selecting the radio button next to them</li>
     <li>Write in- Very simple option type which contains only expected answer and aftertext. Aftertext is there only for stylistic purposes and does not need to be filled.(In case two answers can be correct you can devide them by a semicolumn)</li>
     <li>Multiple choice- This is a special input field specifically created for grouping large numbers of options("select the correct group for each of these 10 elements"). When first loaded it loads a puls button and two white input fields. Input fields are columns and you may add more columns by clicking the plus button. After you added desired number of columns(groups) click the plus button at the bottom left corner. Now you can see a new row with radio buttons for each column but also a text field on the left. Into the text field you may add desired text and aftewards choose the correct group by selecting radio button in a column </li>
     <li>One from Many- Option type which uses the HTML select element to choose the correct answer. One row contains radio button which marks if the option is correct and a text field which should contain the text displayed in the select element. Adding more options is possible trough the plus button in the bottom left corner and as you fill out your options they are dynamically displayed in the select element right about your options.</li>
    <li>Open answer- This is a option field used for questions which you can not check. They are most useful in combination with the Explanation field where after the user writes their answer and submits the Test, the Explanation field will display your text which should be the correct answer. Example Question:Describe PWA ;Example Explanation: *text containing all features and advantages*. This way the user may better themselves at writing longer descriptions or explanations. </li>
    <li>Fill in table- This option is a uique one where you can define dimensions of your table trough plus buttons(first by defining columns and then rows). Afterwards you may choose if the desired field is an answer field or a test field by selecting(answer) the checkbox field to the right of the answer or leaving it empty(simple text)</li>
    <li>Drag Group field- To be done. PracujemSPrilbou started working on it but it seems that he hasn't finished. </li>
   </ol>
   </li>
   <li>Each question type has preceding text where you may write anything for stylistic purposes. To the right of the input field is a blue button which may be used to delete this option field</li>
   <li>Each option type may be extended in some way by the + button in the left corner of the light blue square.</li>
   <li>By pressing the white + button under the light blue square you may add more option fields.Currently it may only add another option field of the same type. DB and backend have been built with the idea of different answer types in one question in mind but I just didn't get to it.</li>
   <li>Last Field is the Explanation. Text written into this field is displayed after the test is submitted. You may add here an explanation or sources for you answer. </li>
</ul>
<span>After you finish your question and want to send it to the database just click the submit button.</span><br>
<span class="text-xs">Hosting service has sometimes problems and returns random SQL errors. In case this happens press mouse button 5 which should load the page again with all of your answer and submit it again.</span><br>


<span id="QuestionEditingSection" class="text-xl font-bold">Question Editing:</span><br>
<span>Editing questions is VERY SIMPLE but here are the steps for those who aren't sure!</span><br>
<span>In admin panel find the Test into which the question belongs. By clicking the edit icon(in the edit column) or the name of the test you will enter a page with ALL questions. Here you need to find which your question either by question text or id if you know it. After locating your question and clicking the edit icon you will see form which should look like this:</span>
<img  src="{{asset('storage/assets/filledQuestion.png')}}" alt="question creator screenshot"><br>
<span>I belive the UI to be intuitive enough for you to do what you need. Changes are only commited if you press the submit button</span><br>

<span id="QuestionDeletionSection" class="text-xl font-bold">Question Deletion:</span><br>
<span>You may delete a question by clicking the X button in the Table of questions</span><br>
</div>
@endsection
