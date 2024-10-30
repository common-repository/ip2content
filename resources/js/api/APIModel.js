import Http from "./Http";

export default class APIModel {
    #pluginApiPrefix = "";
    #wpApiPrefix = "";

    constructor(resourceUrl) {
        this.http = new Http();
        this.resourceUrl = resourceUrl;

        const dataBridge = JSON.parse(localStorage.getItem("wmip2cDataBridge"));
        this.#pluginApiPrefix = dataBridge.plugin_api_namespace;
        this.#wpApiPrefix = dataBridge.wp_api_namespace;

        this.http.setBaseUrl(this.#pluginApiPrefix);
    }

    useWordpressRouteNamespace() {
        this.http.setBaseUrl(this.#wpApiPrefix);
    }

    async get(params = {}) {
        return await this.http.get(`${this.resourceUrl}`, params);
    }

    async show(id) {
        return await this.http.get(`${this.resourceUrl}/${id}/show`, {});
    }

    async getForEdit(id) {
        return await this.http.get(`${this.resourceUrl}/${id}/edit`, {});
    }

    async getList(params = {}) {
        return await this.http.get(`${this.resourceUrl}/list`, params);
    }

    async edit(id, payload) {
        return await this.http.get(`${this.resourceUrl}/${id}`, payload);
    }

    async create(payload) {
        return await this.http.post(`${this.resourceUrl}`, payload);
    }

    async update(id, payload) {
        return await this.http.patch(`${this.resourceUrl}/${id}`, payload);
    }

    async delete(params) {
        return await this.http.delete(`${this.resourceUrl}`, params);
    }

    async download(url) {
        return await this.http.setResponseType("blob").get(url, {});
    }
}
