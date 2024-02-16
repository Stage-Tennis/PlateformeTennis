<script>
    import { createEventDispatcher } from "svelte";
    export let current;
    export let pageCount;
    pageCount += 1;

    let dispatch = createEventDispatcher();

    function pageChange(page) {
        dispatch("pageChange", {
            page: page,
        });
    }
</script>

<div class="mx-4 my-2 p-2 bg-[#bc9fe7] rounded-md flex flex-row justify-center">
    {#each { length: pageCount } as _, i}
        {#if i !== 0}
            <!-- bg-[#f9f5ff] text-[#6941c6] --><button
                class="m-1 p-2 bg-none overflow-hidden rounded-full w-8 h-8 flex items-center justify-center"
                on:click={() => pageChange(i)}
                class:font-bold={i === current}
                class:bg-[#533094]={i === current}
                class:bg-[#7751cf]={i !== current}
            >
                <span class=" items-center justify-center">{i}</span>
            </button>
        {/if}
    {/each}
</div>
