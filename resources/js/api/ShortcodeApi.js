import APIModel from "./APIModel";

export default class ShortcodeApi extends APIModel {
    constructor() {
        super("/conditions/contents");
        this.useWordpressRouteNamespace();
    }

    async get() {
        return await this.http.get(`${this.resourceUrl}`);
    }
}
