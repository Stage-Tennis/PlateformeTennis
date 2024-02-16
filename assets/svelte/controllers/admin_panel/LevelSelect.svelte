<script>
    import { createEventDispatcher } from "svelte";
    import Pill from "./Pill.svelte";

    let dispatch = createEventDispatcher();
    const levels_promise = fetch("/api/levels/all").then((response) =>
        response.json(),
    );

    let levels_list;
    function setLevels(levels) {
        levels_list = levels;
    }

    function handleClick(level) {
        dispatch("leveltoggle", { level: level });
    }
</script>

<div class="flex flex-row self-center m-4 flex-wrap justify-center">
    {#await levels_promise then levels}
        {@const levelsWithIndex = levels.map((level, index) => ({
            index,
            level,
        }))}
        {@const a = setLevels(levels)}

        {#each levelsWithIndex as { index, level }}
            <Pill
                toggle
                bgcolor={level.color}
                class="my-0.5"
                on:click={() => handleClick(level)}
            >
                {level.label}
            </Pill>
        {/each}
    {:catch error}
        <p>{error.message}</p>
    {/await}
</div>
