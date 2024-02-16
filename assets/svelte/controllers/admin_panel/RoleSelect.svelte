<script>
    import { createEventDispatcher } from "svelte";
    import Pill from "./Pill.svelte";

    let dispatch = createEventDispatcher();
    const roles_promise = fetch("/api/roles/all").then((response) =>
        response.json(),
    );

    let roles_list;
    function setRoles(roles) {
        roles_list = roles;
    }

    function handleClick(role) {
        dispatch("roletoggle", { role: role });
    }
</script>

<div class="flex flex-row self-center m-4">
    {#await roles_promise then roles}
        {@const rolesWithIndex = roles.map((role, index) => ({ index, role }))}
        {@const a = setRoles(roles)}

        {#each rolesWithIndex as { index, role }}
            <Pill
                toggle
                seed={role.display_name}
                on:click={() => handleClick(role)}
            >
                {role.display_name}
            </Pill>
        {/each}
    {:catch error}
        <p>{error.message}</p>
    {/await}
</div>
