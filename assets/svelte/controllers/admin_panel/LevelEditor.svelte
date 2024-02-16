<script>
    import { createEventDispatcher } from "svelte";

    export let levelid;
    export let levellabel;
    export let levelcolor;

    const dispatch = createEventDispatcher();

    function dispatchChange() {
        let payload = {
            id: levelid,
        };
        const new_label = document.querySelector("input[name='label']").value;
        const new_color = document.querySelector("input[name='color']").value;

        if (new_label !== levellabel && new_label !== null) {
            payload.new_label = new_label;
        }

        if (new_color !== levelcolor && new_color !== null) {
            payload.new_color = new_color;
        }
        dispatch("editlevel", { payload: payload });
    }
</script>

<div class="flex flex-col">
    <h1>Modifier le niveau</h1>
    <input type="text" name="label" value={levellabel} />
    <input type="color" name="color" value={levelcolor} />

    <button
        on:click={() => {
            dispatchChange(levelid);
        }}>Valider</button
    >
</div>
