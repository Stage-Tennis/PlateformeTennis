<script>
    import { TinyColor } from "@ctrl/tinycolor";
    import { createEventDispatcher } from "svelte";
    let data;

    export let bgcolor;
    export let fgcolor;
    export let seed;

    export let toggle;
    let isToggled = false;

    // If toggle is set, use it to toggle colors
    function toggleColors() {
        isToggled = !isToggled;

        if (isToggled) {
            bgcolor = new TinyColor(untoggledBgColor).darken(20).toHexString();
            fgcolor = new TinyColor(untoggledFgColor).darken(20).toHexString();
        } else {
            bgcolor = untoggledBgColor;
            fgcolor = untoggledFgColor;
        }
    }

    let dispatch = createEventDispatcher();

    // If seed is set, use it to generate fgcolor and bgcolor
    if (seed) {
        seed = hashString(seed);
        let color = new TinyColor(seed);
        bgcolor = color.toHexString();
    }

    // If fgcolor is not set, generate it from bgcolor
    if (!fgcolor) {
        let color = new TinyColor(bgcolor);
        let wasDark = false;
        if (color.isDark()) {
            color = color.lighten(30);
            bgcolor = color;
            wasDark = true;
        }

        fgcolor = color.darken(wasDark ? 40 : 30).toHexString();
    }

    const untoggledFgColor = fgcolor;
    const untoggledBgColor = bgcolor;

    function hashString(string) {
        var hash = 0,
            i,
            chr;
        if (string.length === 0) return hash;
        for (i = 0; i < string.length; i++) {
            chr = string.charCodeAt(i);
            hash = (hash << 5) - hash + chr;
            hash |= 0; // Convert to 32bit integer
        }
        return hash;
    }
</script>

<!-- svelte-ignore a11y-no-static-element-interactions -->
<!-- svelte-ignore a11y-click-events-have-key-events -->
<div
    class="overflow-hidden flex flex-row align-middle justify-center rounded-xl mx-1 px-2 w-fit h-fit hover:cursor-pointer {$$props.class}"
    style="background-color: {bgcolor}; color: {fgcolor};"
    on:click={() => {
        if (toggle) {
            toggleColors();
        }
        dispatch("click");
    }}
>
    {#if toggle && isToggled}
        <svg
            fill={fgcolor}
            height="800px"
            width="800px"
            version="1.1"
            id="Capa_1"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            viewBox="0 0 490.05 490.05"
            xml:space="preserve"
            class="w-2 h-auto mr-1"
        >
            <g>
                <g>
                    <path
                        d="M418.275,418.275c95.7-95.7,95.7-250.8,0-346.5s-250.8-95.7-346.5,0s-95.7,250.8,0,346.5S322.675,513.975,418.275,418.275
           z M157.175,207.575l55.1,55.1l120.7-120.6l42.7,42.7l-120.6,120.6l-42.8,42.7l-42.7-42.7l-55.1-55.1L157.175,207.575z"
                        fill="#ffffff"
                    />
                </g>
            </g>
        </svg>
    {:else}
        <svg
            viewBox="0 0 100 100"
            xmlns="http://www.w3.org/2000/svg"
            class="w-2 h-auto mr-1"
        >
            <circle cx="50" cy="50" r="50" fill={fgcolor} />
        </svg>
    {/if}
    <span class="select-none" bind:this={data}><slot /></span>
</div>
