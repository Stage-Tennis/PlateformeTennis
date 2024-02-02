<script>
    import NavBar from "./admin_panel/NavBar.svelte";
    import LicensedPanel from "./admin_panel/LicensedPanel.svelte";
    import PlanningPanel from "./admin_panel/PlanningPanel.svelte";
    import StaffPanel from "./admin_panel/StaffPanel.svelte";
    import OthersPanel from "./admin_panel/OthersPanel.svelte";
    import WelcomeScreen from "./admin_panel/WelcomeScreen.svelte";

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
    <NavBar on:panelChange={changePanel} />

    {#if panel === ""}
        <WelcomeScreen />
    {:else if panel === "licensed"}
        <LicensedPanel />
    {:else if panel === "planning"}
        <PlanningPanel />
    {:else if panel === "staff"}
        <StaffPanel />
    {:else if panel === "others"}
        <OthersPanel />
    {/if}
</div>
