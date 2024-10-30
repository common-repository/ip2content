import API from "./api/API";

(async () => {
    const ipConditionsContents = document.getElementsByClassName(
        "ip_condition_content"
    );
    const ids = [];

    for (const ipConditionContent of ipConditionsContents) {
        if (ipConditionContent.dataset.id === undefined) {
            continue;
        }

        ids.push(ipConditionContent.dataset.id);
    }

    const idsChain = ids.join(",");
    try {
        const conditionsWithRenderContentId =
            await API.conditions.checkCondition({
                ids: idsChain,
                query: location.search,
            });

        const renderContentsIds = conditionsWithRenderContentId
            .map((item) => item.render_content_id)
            .join(",");
        if (!renderContentsIds) {
            return;
        }
        const contents = await API.conditionsContent.get({
            include: renderContentsIds,
            wmip2c_per_page: 9999,
            _fields: "id,content.rendered",
        });

        conditionsWithRenderContentId.forEach((conditionWithContent) => {
            const renderedContent = contents.find((content) => {
                return (
                    Number(content.id) ===
                    Number(conditionWithContent.render_content_id)
                );
            }).content.rendered;

            for (const ipConditionContent of ipConditionsContents) {
                if (
                    Number(ipConditionContent.dataset.id) ===
                    Number(conditionWithContent.id)
                ) {
                    ipConditionContent.innerHTML = renderedContent;
                }
            }
        });
    } catch (e) {
        console.log(e);
    }
})();
