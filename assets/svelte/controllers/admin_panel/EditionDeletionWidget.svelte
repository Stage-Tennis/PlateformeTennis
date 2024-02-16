<script>
    import { createEventDispatcher } from "svelte";
    import EditUserMenu from "./EditUserMenu.svelte";

    export let target;
    const dispatch = createEventDispatcher();

    let edit_context_opened = false;
    async function deleteUser() {
        const response = await fetch(`/api/users/delete/${target.id}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                credentials: "include",
            },
            body: JSON.stringify({ id: target }),
        }).then((response) => {
            if (response.status === 200) {
                dispatch("dataChange");
            }
        });
    }
</script>

<div class="flex flex-row">
    <button on:click={deleteUser}>
        <img
            src="/images/trash.svg"
            class="w-8 h-8 hover:scale-125 transition duration-300 ease-in-out"
            alt="Supprimer l'utilisateur"
        />
    </button>

    <button on:click={dispatch("dataEdit", { target: target })}>
        <img
            src="/images/edit.svg"
            class="w-8 h-8 hover:scale-125 transition duration-300 ease-in-out"
            alt="Modifier l'utilisateur"
        />
    </button>
</div>
