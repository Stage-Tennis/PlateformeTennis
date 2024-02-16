<script>
    import { createEventDispatcher } from "svelte";
    import RoleSelect from "./RoleSelect.svelte";
    import LevelSelect from "./LevelSelect.svelte";
    export let user;

    const dispatch = createEventDispatcher();
    let roles = [];
    let levels = [];

    let json = "";
    let failed = false;
    async function handleValidation() {
        const tel = document.querySelector("input[name='tel']").value;
        const token_amount = document.querySelector(
            "input[name='token_amount']",
        ).value;
        const mail = document.querySelector("input[name='email']").value;

        const payload = {
            id: user.id,
        };

        if (tel != "") payload.new_phone = tel;
        if (token_amount != "")
            payload.new_token_amount = parseInt(token_amount);
        if (mail != "") payload.new_mail = mail;
        if (roles.length > 0) payload.new_roles = roles.map((r) => r.id);
        if (levels.length > 0) payload.new_levels = levels.map((l) => l.id);

        json = await fetch("/api/users/edit", {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                credentials: "include",
            },
            body: JSON.stringify(payload),
        }).then(async (response) => {
            json = await response.json();
            if (response.status === 200) {
                dispatch("dataChange");
            } else {
                failed = true;
            }
        });
        if (!failed) dispatch("dataChange");
    }
</script>

<div class="z-2 bg-black bg-opacity-50 absolute top-0 left-0 w-screen h-screen">
    <div
        class="flex flex-col z-10 w-1/3 h-auto relative top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 items-center justify-center"
    >
        {#if failed}
            {@const violations = json.violations}
            {#each violations as error}
                <p class="text-red-500 self-center">{error.title}</p>
            {/each}
        {/if}
        <div
            class="flex flex-col bg-slate-200 w-auto m-auto p-4 rounded-md absolute"
        >
            <div class="flex flex-row justify-between mb-4">
                <h1 class="self-center text-xl">Modifier l'utilisateur</h1>

                <button
                    class="self-end hover:text-red-500 transition duration-300 ease-in-out"
                    on:click={dispatch("close")}>Fermer</button
                >
            </div>

            <div class="flex flex-col">
                <div class="flex flex-row justify-between">
                    <div class="flex flex-col m-2">
                        <label for="tel">Téléphone</label>
                        <input
                            type="tel"
                            name="tel"
                            class="w-auto h-8 text-lg rounded-md p-2"
                        />
                    </div>
                    <div class="flex flex-col m-2">
                        <label for="token_amount">Nombre de jetons</label>
                        <input
                            type="number"
                            name="token_amount"
                            class="w-auto h-8 text-lg rounded-md p-2"
                            value={user.token_amount}
                        />
                    </div>
                </div>

                <div class="flex flex-col m-2">
                    <label for="email">Email</label>
                    <input
                        name="email"
                        class="w-auto h-8 text-lg rounded-md p-2"
                    />
                </div>

                <div class="flex flex-col m-2">
                    <span>Rôles</span>
                    <RoleSelect
                        on:roletoggle={(evt) => {
                            const role = evt.detail.role;

                            if (roles.includes(role))
                                roles = roles.filter((r) => r !== role);
                            else roles.push(role);
                        }}
                    />
                </div>

                <div class="flex flex-col m-2">
                    <span>Rôles</span>
                    <LevelSelect
                        on:leveltoggle={(evt) => {
                            const level = evt.detail.level;

                            if (levels.includes(level))
                                levels = levels.filter((l) => l !== level);
                            else levels.push(level);
                        }}
                    />
                </div>

                <!-- TODO: It's bad to not make a component for this -->
                <button
                    on:click={handleValidation}
                    class="overflow-hidden flex flex-row justify-center bg-[#6941c6]
                 text-[#f9f5ff] rounded-sm p-2 hover:bg-[#5938a8] transition
                 duration-300 ease-in-out"
                >
                    <p class="text-center">Valider</p>
                </button>
            </div>
        </div>
    </div>
</div>
