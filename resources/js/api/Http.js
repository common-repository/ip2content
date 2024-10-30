import axios from "axios";

export default class Http {
    baseUrl = "/";

    constructor() {
        this.axios = axios.create({
            baseURL: this.baseUrl,
        });
        this.responseType = "json";
    }

    getAxiosInstance() {
        return this.axios;
    }

    setBaseUrl(baseUrl) {
        this.baseUrl = baseUrl;
    }

    #prepareUrl(url) {
        return `${this.baseUrl}${url}`.replaceAll("//", "/");
    }

    async get(url, params = {}) {
        try {
            const response = await this.axios.get(this.#prepareUrl(url), {
                params,
                responseType: this.responseType,
            });
            return response.data;
        } catch (e) {
            throw e?.data;
        }
    }

    async delete(url, data = {}) {
        try {
            const response = await this.axios.delete(this.#prepareUrl(url), {
                data,
            });
            return response.data;
        } catch (e) {
            throw e?.data;
        }
    }

    async patch(url, data) {
        try {
            const response = await this.axios.patch(
                this.#prepareUrl(url),
                data
            );
            return response.data;
        } catch (e) {
            throw e?.data;
        }
    }

    async post(url, data) {
        try {
            const response = await this.axios.post(this.#prepareUrl(url), data);
            return response.data;
        } catch (e) {
            throw e?.data;
        }
    }

    async put(url, data) {
        try {
            const response = await this.axios.put(this.#prepareUrl(url), data);
            return response.data;
        } catch (e) {
            throw e?.data;
        }
    }

    setResponseType(type) {
        this.responseType = type;
        return this;
    }

    setHeaders(headers) {
        this.axios.defaults.headers = {
            ...this.axios.defaults.headers,
            ...headers,
        };
        return this;
    }

    parseFormData(formData) {
        const data = {};

        for (const dataEntry of formData.entries()) {
            data[dataEntry[0]] = dataEntry[1];
        }

        return data;
    }
}
