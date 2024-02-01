<script>
    const licensed_data_promise = fetch(
        "https://localhost:8000/api/licensed/all",
        {
            credentials: "include",
            headers: {
                // "Access-Control-Allow-Origin": "*",
                credentials: "include",
            },
        },
    ).then((response) => response.json());

    let color = true;

    function alternateColor() {
        let row_color = "";
        if (color) {
            row_color = "bg-slate-100 text-slate-600";
            color = false;
        } else {
            row_color = "bg-slate-200 text-slate-700";
            color = true;
        }
        return row_color;
    }

    function handleLicensedClick(id) {}
</script>

<div class="flex flex-col mx-4 flex-grow">
    {#await licensed_data_promise}
        <img
            src="/images/loading_circle.svg"
            class="h-24 w-auto"
            alt="Loading..."
        />
    {:then licensed_data}
        <table class="border-collapse mt-2 shadow overflow-hidden rounded-xl">
            <thead class="bg-gray-800 text-gray-100">
                <tr>
                    <th class="px-4 py-2">Nom</th>
                    <th class="px-4 py-2">Niveaux</th>
                    <th class="px-4 py-2">Numéro de licence</th>
                    <th class="px-4 py-2">Adresse mail</th>
                    <th class="px-4 py-2">Nombre de jetons</th>
                </tr>
            </thead>
            <tbody>
                {#each licensed_data as licensed}
                    <tr class="bg-white text-gray-700">
                        <td class="px-4 py-2 flex flex-col">
                            <span class="font-semibold">
                                {licensed.name}
                                {licensed.surname}
                            </span>
                            <span>{licensed.phone}</span>
                        </td>
                        <td class="px-4 py-2">
                            {#each licensed.level as level}{level}
                            {/each}
                        </td>
                        <td class="px-4 py-2">{licensed.license_serial}</td>
                        <td class="px-4 py-2">{licensed.email}</td>
                        <td class="px-4 py-2">{licensed.token_amount}</td>
                    </tr>
                {/each}
            </tbody>
        </table>

        <div>
            <p>Actions</p>
            <hr />
            <button>Ajouter</button>
            <button>Supprimer</button>
            <button>Modifier</button>
        </div>
    {:catch error}
        <p>Une erreur est survenue: {error}</p>
    {/await}
</div>
