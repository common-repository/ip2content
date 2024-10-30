import APIModel from "./APIModel";

export default class ConditionsApi extends APIModel {
    constructor() {
        super("/conditions");
    }

    async checkCondition(params) {
        return await this.http.post(`${this.resourceUrl}/check`, params);
    }
}
