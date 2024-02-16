<script>
    import Pill from "./Pill.svelte";
    import AddUserPrompt from "./AddUserPrompt.svelte";
    import EditionDeletionWidget from "./EditionDeletionWidget.svelte";
    import PageSelector from "./PageSelector.svelte";
    import EditUserMenu from "./EditUserMenu.svelte";

    let licensed_data_promise;
    let page = 1;
    let player_count = "?";

    function requestData() {
        licensed_data_promise = fetch(`/api/users/all/${page}`, {
            credentials: "include",
        }).then(async (response) => {
            const json = await response.json();
            player_count = json.count;
            return json;
        });
    }
    requestData();

    let selected = [];
    function toggleSelect(id) {
        if (selected.includes(id)) {
            selected = selected.filter((item) => item !== id);
        } else {
            selected = [...selected, id];
        }
    }

    async function deleteList() {
        const delete_promise = await fetch("/api/users/delete_group", {
            credentials: "include",
            method: "DELETE",
            body: JSON.stringify({
                group: selected,
            }),
        }).then(async (res) => {
            if (res.status === 200) {
                selected = [];
                requestData();
            } else {
                // TODO: Notify user that we failed
            }
        });
    }

    function pageChange(evt) {
        page = evt.detail.page;
        requestData();
    }

    function search() {
        let search = document.querySelector("input[id='search']").value;

        search = search.normalize("NFD").replace(/[\u0300-\u036f]/g, "");

        // If empty search, reset the data
        if (search.length === 0) {
            requestData();
            return;
        }

        licensed_data_promise = fetch(`/api/users/find`, {
            credentials: "include",
            method: "POST",
            body: JSON.stringify({
                name: search,
            }),
        }).then((response) => {
            return response.json();
        });
    }

    let edit_id = null;
    function toggleEditMenu(evt) {
        if (evt.detail.target === null) edit_id = null;
        edit_id = evt.detail.target;
    }

    function closeEditMenu() {
        edit_id = null;
        requestData();
    }

    let add_user_clicked = false;
</script>

<div class="flex flex-col mx-4 flex-grow">
    <div
        class="border-collapse mt-2 shadow overflow-hidden rounded-xl bg-white"
    >
        {#if edit_id != null}
            <EditUserMenu
                user={edit_id}
                on:dataChange={closeEditMenu}
                on:close={closeEditMenu}
            />
        {/if}
        <div class="p-4 flex flex-row mx-auto justify-between">
            <div class="flex flex-row self-center">
                <!-- <span class="font-normal text-xl text-black"
                >Liste des utilisateurs</span
            > -->
                <Pill
                    class="bg-[#f9f5ff] text-[#6941c6] self-center"
                    bgcolor="#f9f5ff"
                    fgcolor="#6941c6">{player_count} Joueurs</Pill
                >
                <input
                    type="text"
                    class="bg-gray-100 rounded-xl p-2 text-xl mx-2"
                    id="search"
                />
                <button
                    on:click={() => search()}
                    class="overflow-hidden flex flex-row bg-[#6941c6]
            text-[#f9f5ff] rounded-md p-2 hover:bg-[#5938a8] transition
            duration-300 ease-in-out mx-2"
                >
                    <p class="self-center">Rechercher</p>
                </button>
            </div>
            <div class="overflow-hidden flex flex-row">
                <!-- TODO: It's bad to not make a component for this -->
                <button
                    on:click={() => deleteList()}
                    class="overflow-hidden flex flex-row bg-[#6941c6]
            text-[#f9f5ff] rounded-sm p-2 hover:bg-[#ff0000] transition
            duration-300 ease-in-out disabled:bg-gray-300 disabled:cursor-not-allowed mx-2"
                    disabled={selected.length == 0}
                >
                    <p class="self-center px-2">
                        Supprimer les utilisateurs ({selected.length})
                    </p>
                </button>
                <button
                    on:click={() => (add_user_clicked = !add_user_clicked)}
                    class="overflow-hidden flex flex-row bg-[#6941c6]
            text-[#f9f5ff] rounded-sm p-2 hover:bg-[#5938a8] transition
            duration-300 ease-in-out mx-2"
                >
                    <img
                        src="/images/add_user.svg"
                        class="w-8 h-8 self-center"
                        alt="Add User"
                    />
                    <p class="self-center pl-2">Nouveau Utilisateur</p>
                </button>
            </div>
        </div>
        {#await licensed_data_promise}
            <img
                src="/images/loading_circle.svg"
                class="h-24 w-auto"
                alt="Loading..."
            />
        {:then licensed_data}
            <table class="w-full">
                <thead class="bg-gray-100 text-gray-600 font-light text-sm">
                    <tr>
                        <th class="text-justify font-normal"></th>
                        <th class="px-4 py-2 text-justify font-normal">Nom</th>
                        <th class="px-4 py-2 text-justify font-normal"
                            >Niveaux</th
                        >
                        <th class="px-4 py-2 text-justify font-normal"
                            >Numéro de licence</th
                        >
                        <th class="px-4 py-2 text-justify font-normal"
                            >Adresse mail</th
                        >
                        <th class="px-4 py-2 text-justify font-normal"
                            >Nombre de jetons</th
                        >
                        <th class="px-4 py-2 text-justify font-normal"
                            >Actions</th
                        >
                    </tr>
                </thead>
                <tbody>
                    {#each licensed_data.all_licensed as licensed}
                        <tr
                            class="bg-white text-gray-700 border border-b-gray-100 border-b-2"
                        >
                            <td class="px-4 py-2">
                                <input
                                    type="checkbox"
                                    on:change={() => toggleSelect(licensed.id)}
                                />
                            </td>
                            <td class="px-4 py-2 flex flex-col">
                                <span class="font-semibold">
                                    {licensed.name}
                                    {licensed.surname}
                                </span>
                                <span
                                    >{licensed.first_connection
                                        ? licensed.phone
                                        : "(Non configuré)"}</span
                                >
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex flex-col">
                                    {#each licensed.level as level}
                                        <Pill
                                            bgcolor={level.color}
                                            class="my-1"
                                        >
                                            {level.label}
                                        </Pill>
                                    {/each}
                                </div>
                            </td>
                            <td class="px-4 py-2"
                                >{licensed.first_connection
                                    ? licensed.license_serial
                                    : "(Non configuré)"}</td
                            >
                            <td class="px-4 py-2">{licensed.email}</td>
                            <td class="px-4 py-2"
                                >{licensed.first_connection
                                    ? licensed.token_amount
                                    : "(Non configuré)"}</td
                            >
                            <td class="px-4 py-2">
                                <EditionDeletionWidget
                                    target={licensed}
                                    on:dataChange={() => requestData()}
                                    on:dataEdit={toggleEditMenu}
                                />
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>

            <PageSelector
                current={page}
                pageCount={licensed_data.page_count}
                on:pageChange={(evt) => pageChange(evt)}
            />

            {#if add_user_clicked == true}
                <AddUserPrompt on:close={() => (add_user_clicked = false)} />
            {/if}
        {:catch error}
            <p>Une erreur est survenue: {error}</p>
        {/await}
    </div>
</div>
