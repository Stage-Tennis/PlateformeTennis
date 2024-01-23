const panelsAccess = [
    document.querySelector("a[href='licensed']"),
    document.querySelector("a[href='planning']"),
    document.querySelector("a[href='staff']"),
    document.querySelector("a[href='others']"),
];

const embedDiv = document.querySelector("#subpage-container");

function url(route) {
    return window.location.origin + "/" + route;
}

panelsAccess.forEach((panel) => {
    panel.addEventListener("click", (e) => {
        e.preventDefault();

        panel.classList.toggle("toggled");

        if (panel.classList.contains("toggled")) {
            embedDiv.innerHTML = "<img src='/images/loading_circle.svg' class='justify-center'/>";
            const requestRoute = url(panel.getAttribute("href"));
            const request = fetch(requestRoute, { credentials: "include" });

            request.then((response) => {
                if (response.status === 200) {
                    response.text().then((html) => {
                        embedDiv.innerHTML = html;
                    });
                }
            });
        }

        else {
            embedDiv.innerHTML = "";
        }
    })
});