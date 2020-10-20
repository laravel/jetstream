@props(['message' => session('flash.banner')])

<div class="bg-indigo-500" x-data="{{ json_encode(['show' => true, 'message' => $message]) }}"
            x-show="show && message"
            x-init="
                document.addEventListener('banner-message', event => {
                    message = event.detail.message;
                    show = true;
                });
            ">
    <div class="max-w-screen-xl mx-auto py-2 px-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between flex-wrap">
            <div class="w-0 flex-1 flex items-center">
                <span class="flex p-2 rounded-lg bg-indigo-600">
                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>

                <p class="ml-3 font-medium text-sm text-white truncate">
                    <span class="md:hidden" x-text="message">
                    </span>

                    <span class="hidden md:inline" x-text="message">
                    </span>
                </p>
            </div>

            <div class="flex-shrink-0 sm:ml-3">
                <button
                        type="button"
                        class="-mr-1 flex p-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600 sm:-mr-2 transition ease-in-out duration-150"
                        aria-label="Dismiss"
                        x-on:click="show = false">
                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
