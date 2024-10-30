import APIModel from "./APIModel";

export default class SettingsApi extends APIModel {
    constructor() {
        super("/settings");
    }

    async clearCache() {
        return await this.http
            // .ignoreErrorHandler(500)
            .get(`${this.resourceUrl}/clear-cache`);
    }
}
