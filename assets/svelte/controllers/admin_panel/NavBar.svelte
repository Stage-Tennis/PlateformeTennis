<script>
    import { createEventDispatcher } from "svelte";

    const dispatch = createEventDispatcher();

    function panelChange(panel) {
        console.log("Panel change: " + panel);
        dispatch("panelChange", {
            panel: panel,
        });
    }
</script>

<nav class="flex flex-col bg-white p-0 px-2 py-4 h-screen">
    <button
        class="my-4 text-xl hover:text-orange-600 transition duration-300 ease-in-out self-start"
        on:click={() => panelChange("licensed")}>Licenciés</button
    >
    <button
        class="my-4 text-xl hover:text-orange-600 transition duration-300 ease-in-out self-start"
        on:click={() => panelChange("planning")}>Planning</button
    >
    <button
        class="my-4 text-xl hover:text-orange-600 transition duration-300 ease-in-out self-start"
        on:click={() => panelChange("staff")}>Staff</button
    >
    <button
        class="my-4 text-xl hover:text-orange-600 transition duration-300 ease-in-out self-start"
        on:click={() => panelChange("others")}>Autres</button
    >
</nav>
