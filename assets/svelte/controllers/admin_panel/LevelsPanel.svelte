<script>
    import Pill from "./Pill.svelte";
    import LevelEditor from "./LevelEditor.svelte";

    let levels_promise = fetch("/api/levels/all").then((response) => {
        return response.json();
    });

    let level_to_edit = {
        label: "",
        color: "",
    };
    function handleClick(level) {
        if (level_to_edit == level) {
            level_to_edit = {
                label: "",
                color: "",
            };
        } else level_to_edit = level;
    }

    async function editLevel() {
        const label_input = document.querySelector("input[name='label']");
        const color_input = document.querySelector("input[name='color']");
        const new_label = label_input.value;
        const new_color = color_input.value;

        let payload = {};

        if (level_to_edit.label !== new_label) payload.new_label = new_label;
        if (level_to_edit.color !== new_color) payload.new_color = new_color;

        if (payload.new_label || payload.new_color) {
            await fetch(`/api/levels/edit/${level_to_edit.id}`, {
                method: "PATCH",
                body: JSON.stringify(payload),
            }).then((response) => {
                if (response.status === 200) {
                    levels_promise = fetch("/api/levels/all").then(
                        (response) => {
                            return response.json();
                        },
                    );
                }
            });
        }
    }
</script>

<div
    class="border-collapse p-12 h-full shadow overflow-visible rounded-xl bg-white m-auto"
>
    <div class="justify-center items-center">
        <div class="flex flex-row p-4">
            <div class="mb-8">
                {#await levels_promise}
                    <img
                        src="/images/loading_circle.svg"
                        class="h-24 w-auto mx-auto"
                        alt="Loading..."
                    />
                {:then promise}
                    <div class="flex flex-col items-center">
                        {#each promise as level}
                            <Pill
                                bgcolor={level.color}
                                class="my-2"
                                on:click={() => handleClick(level)}
                            >
                                {level.label}
                            </Pill>
                        {/each}
                    </div>
                {/await}
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <!-- FORMULAIRE POUR MODIFIER -->
                <h1 class="text-xl font-semibold mb-4">Modifier le niveau</h1>
                <div class="mb-4">
                    <label
                        for="label"
                        class="block text-gray-700 text-sm font-bold mb-2"
                        >Label</label
                    >
                    <input
                        type="text"
                        name="label"
                        value={level_to_edit.label}
                        disabled={level_to_edit.label === ""}
                        class="w-full border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="color"
                        class="block text-gray-700 text-sm font-bold mb-2"
                        >Couleur</label
                    >
                    <input
                        type="color"
                        name="color"
                        value={level_to_edit.color}
                        disabled={level_to_edit.color === ""}
                        class="w-full border rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    />
                </div>
                <button
                    on:click={async () => editLevel()}
                    class="overflow-hidden flex flex-row bg-[#6941c6] text-[#f9f5ff] rounded-sm p-2 hover:bg-[#5938a8] transition duration-300 ease-in-out"
                >
                    <img
                        src="/images/edit.svg"
                        class="w-8 h-8 self-center"
                        alt="Modifier"
                    />
                    <p class="self-center pl-2">Confirmer les modifications</p>
                </button>
            </div>
        </div>
    </div>
</div>
