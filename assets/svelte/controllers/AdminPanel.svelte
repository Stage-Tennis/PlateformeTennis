<script>
    import NavBar from "./admin_panel/NavBar.svelte";
    import LicensedPanel from "./admin_panel/LicensedPanel.svelte";
    import WelcomeScreen from "./admin_panel/WelcomeScreen.svelte";
    import LevelsPanel from "./admin_panel/LevelsPanel.svelte";

    let panel = "";
    function changePanel(event) {
        if (event.detail.panel === panel) {
            panel = "";
            return;
        }
        panel = event.detail.panel;
    }
</script>

<div class="flex flex-row">
    <NavBar on:panelChange={(evt) => changePanel(evt)} />

    {#if panel === ""}
        <WelcomeScreen />
    {:else if panel === "licensed"}
        <LicensedPanel />
    {:else if panel === "levels"}
        <LevelsPanel />
    {/if}
</div>
