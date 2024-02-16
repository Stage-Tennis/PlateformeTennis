<script>
    import RoleSelect from "./RoleSelect.svelte";
    import { createEventDispatcher } from "svelte";
    let roles = [];
    const dispatch = createEventDispatcher();

    let json = null;
    let failed = false;

    async function handleValidation() {
        const payload = {
            name: document.querySelector("input[name='name']").value,
            surname: document.querySelector("input[name='surname']").value,
            email: document.querySelector("input[name='email']").value,
            roles: roles,
        };

        json = await fetch("/api/users/new", {
            method: "POST",
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
    }
</script>

<div class="z-2 bg-black bg-opacity-50 fixed top-0 left-0 w-full h-full"></div>

<div
    class="z-10 bg-slate-200 flex flex-col absolute top-10 left-1/3 p-4 rounded-md"
>
    <div class="flex flex-row justify-between mb-4">
        <h1 class="self-center text-xl">Ajouter un utilisateur</h1>

        <button
            class="self-end hover:text-red-500 transition duration-300 ease-in-out"
            on:click={() => dispatch("close")}>Fermer</button
        >
    </div>
    <span class="italic font-thin self-center"
        >Le reste sera à compléter par l'utilisateur dès sa première connexion</span
    >

    <!-- TODO: Weird error ``Uncaught (in promise) TypeError: ctx[1] is undefined`` -->
    {#if failed}
        {@const violations = json.violations}
        {#each violations as error}
            <p class="text-red-500 self-center">{error.title}</p>
        {/each}
    {/if}
    <div class="flex flex-col">
        <div class="flex flex-row justify-between">
            <div class="flex flex-col m-2">
                <label for="name">Nom</label>
                <input
                    type="text"
                    name="name"
                    class="w-auto h-8 text-lg rounded-md p-2"
                />
            </div>
            <div class="flex flex-col m-2">
                <label for="surname">Prénom</label>
                <input
                    type="text"
                    name="surname"
                    class="w-auto h-8 text-lg rounded-md p-2"
                />
            </div>
        </div>
        <div class="flex flex-col m-2">
            <label for="email">Email</label>
            <input name="email" class="w-auto h-8 text-lg rounded-md p-2" />
        </div>

        <RoleSelect
            on:roletoggle={(evt) => {
                const role = evt.detail.role;

                if (roles.includes(role))
                    roles = roles.filter((r) => r !== role);
                else roles.push(role);
            }}
        />

        <!-- TODO: It's bad to not make a component for this -->
        <button
            on:click={async () => await handleValidation()}
            class="overflow-hidden flex flex-row justify-center bg-[#6941c6]
                    text-[#f9f5ff] rounded-sm p-2 hover:bg-[#5938a8] transition
                    duration-300 ease-in-out"
        >
            <p class="text-center">Créer l'utilisateur</p>
        </button>
    </div>
</div>
