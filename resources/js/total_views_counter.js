const sessionIdCookieName = "PHPSESSID";

document.addEventListener(
    "DOMContentLoaded",
    function () {
        const salt = "wm";
        const storageKey = "wmsid";
        const resourceWPPrefixJson = localStorage.getItem("wmip2cDataBridge");
        const resourceWPPrefix =
            JSON.parse(resourceWPPrefixJson).plugin_api_namespace;

        if (sessionStorage.getItem(storageKey)) {
            return;
        }

        sessionStorage.setItem(storageKey, salt + Date.now());

        let xmlHttpRequest = new XMLHttpRequest();

        xmlHttpRequest.open(
            "POST",
            `${window.location.origin}${resourceWPPrefix}statistics/total-views`
        );
        xmlHttpRequest.send();
    },
    false
);
