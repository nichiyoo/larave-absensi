<div class="cursor-pointer text-zinc-100 dark:text-zinc-500" x-on:click="dark = !dark">
    <i class="w-5 h-5" data-lucide="moon" x-show="dark === false"></i>
    <i class="w-5 h-5" data-lucide="sun" x-show="dark === true"></i>
    <span class="sr-only">Toggle theme</span>
</div>
