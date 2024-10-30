import APIModel from "./APIModel";

export default class FieldsApi extends APIModel {
    constructor() {
        super("/fields");
    }

    async getOperators(fieldId) {
        return await this.http
            .ignoreErrorHandler(500)
            .get(`${this.resourceUrl}/${fieldId}/operators`);
    }

    async getValues(fieldId) {
        return await this.http
            .ignoreErrorHandler(500)
            .get(`${this.resourceUrl}/${fieldId}/values`);
    }
}
