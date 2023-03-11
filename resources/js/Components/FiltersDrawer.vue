<script setup>
import { onMounted, ref } from 'vue';
import { Drawer } from 'flowbite';

const drawer = ref();

onMounted(() => {
    // set the drawer menu element
    const $targetEl = document.getElementById('drawer-example');
    const $drawerHideButton = document.getElementById('drawer-hide-button');

    // options with default values
    const options = {
        placement: 'right',
        backdrop: true,
        bodyScrolling: false,
        edge: false,
        edgeOffset: '',
        backdropClasses: 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-30',
        onHide: () => {
            console.log('drawer is hidden');
        },
        onShow: () => {
            console.log('drawer is shown');
        },
        onToggle: () => {
            console.log('drawer has been toggled');
        },
    };

    if ($targetEl) {
        drawer.value = new Drawer($targetEl, options);

        // show the drawer
        drawer.value.show();

        $drawerHideButton.addEventListener('click', () => {
            drawer.value.hide();
        });
    }
});

const toggle = drawer.value.toggle();
</script>

<template>
    <div class="mx-auto max-w-2xl p-4">
        <div
            id="drawer-example"
            class="fixed z-40 h-screen w-80 overflow-y-auto bg-white p-4 dark:bg-gray-800"
            tabindex="-1"
            aria-labelledby="drawer-label"
        >
            <h5
                id="drawer-label"
                class="mb-4 inline-flex items-center text-base font-semibold text-gray-500 dark:text-gray-400"
            >
                <svg
                    class="mr-2 h-5 w-5"
                    aria-hidden="true"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd"
                    ></path>
                </svg>
                Info
            </h5>
            <button
                id="drawer-hide-button"
                type="button"
                aria-controls="drawer-example"
                class="absolute top-2.5 right-2.5 inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
            >
                <svg
                    aria-hidden="true"
                    class="h-5 w-5"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    ></path>
                </svg>
                <span class="sr-only">Close menu</span>
            </button>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                Supercharge your hiring by taking advantage of our
                <a href="#" class="text-blue-600 underline hover:no-underline dark:text-blue-500">limited-time sale</a>
                for Flowbite Docs + Job Board. Unlimited access to over 190K top-ranked candidates and the #1 design job
                board.
            </p>
            <div class="grid grid-cols-2 gap-4">
                <a
                    href="#"
                    class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                    >Learn more</a
                >
                <a
                    href="#"
                    class="inline-flex items-center rounded-lg bg-blue-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >Get access
                    <svg
                        class="ml-2 h-4 w-4"
                        aria-hidden="true"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        ></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</template>
