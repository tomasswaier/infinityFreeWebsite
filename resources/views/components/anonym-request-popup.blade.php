@props(['source' => 'notSpecified','buttonText' => 'Ask us'])
<!--code stole from https://flowbite.com/docs/components/modal/-->
<div class="fixed right-1 bottom-1 bg-white border border-black rounded-md">
    <button command="show-modal" commandfor="anonym_request_form"  @click="open = true " class="bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none" type="button">
      {{$buttonText}}
    </button>
</div>

<!-- Main modal -->
<dialog id="anonym_request_form" tabindex="-1" aria-hidden="true" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false" class="overflow-y-auto overflow-x-hidden top-0 right-0 left-0 z-50 justify-center items-center md:inset-0 ">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6">
            <!-- Modal header -->
            <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5">
                <h3 class="text-lg font-medium text-heading">
                    Here you may send us question info, requests for changing subjects or anything else content related!
                </h3>
                <button type="button" @click="open = false" command="close" commandfor="anonym_request_form" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="pt-4 md:pt-6">
                @csrf
                <input id="anonym_request_source" type="hidden" name="source" value="{{$source}}">
                <div class="mb-4">
                    <label for="anonym_request_text" class="block mb-2.5 text-sm font-medium text-heading">What would you like to change:</label>
                    <textarea rows="" cols="" id="anonym_request_text" name="anonym_request_text" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="add question:2+2. options are 2,3,4,5 (correct is 5)" required ></textarea>
                </div>
                <button id="anonym_request_submit_button" type="submit" name="submit" class="bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none w-full mb-3">Submit</button>
                <span class="hidden" id="response_message"></span>
            </form>
        </div>
    </div>
</dialog>
<script src="{{ asset('js/anonymRequest.js') }}"></script>
