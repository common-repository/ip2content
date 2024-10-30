import APIModel from "./APIModel";

export default class SettingsApi extends APIModel {
    constructor() {
        super("/statistics");
    }

    async getResults() {
        return await this.http
            // .ignoreErrorHandler(500)
            .get(`${this.resourceUrl}/results`);
    }

    async getLatestDetectedCompanies() {
        return await this.http
            // .ignoreErrorHandler(500)
            .get(`${this.resourceUrl}/latest-detected-companies`);
    }

    async getTopConditions() {
        return await this.http
            // .ignoreErrorHandler(500)
            .get(`${this.resourceUrl}/top-conditions`);
    }
}
