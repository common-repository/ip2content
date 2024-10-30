import APIModel from "./APIModel";

export default class ConditionsApi extends APIModel {
    constructor() {
        super("/conditions");
    }

    async updateStatus(id, status) {
        return await this.http.post(`${this.resourceUrl}/${id}/status`, {
            active: status,
        });
    }

    async update(id, payload) {
        return await this.http.post(
            `${this.resourceUrl}/${id}/update`,
            payload
        );
    }

    async delete(params) {
        return await this.http.post(`${this.resourceUrl}/delete`, params);
    }
}
