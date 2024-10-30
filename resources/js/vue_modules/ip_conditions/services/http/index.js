import axios from "axios";
import Interceptors from "./interceptor";

class Http {
    baseUrl = "/";

    constructor() {
        this.axios = axios.create({
            baseURL: this.baseUrl,
        });
        this.ignoredErrors = [];
        this.formData = false;
        this.responseType = "json";

        Interceptors.request(this);
        Interceptors.response(this);
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

    getIgnoredErrors() {
        return this.ignoredErrors;
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
            console.log(e);
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

    ignoreErrorHandler(...errors) {
        if (errors.length) {
            this.ignoredErrors = errors;
        } else {
            this.ignoredErrors = [
                301, 400, 401, 403, 404, 405, 408, 422, 426, 429, 500,
            ];
        }
        return this;
    }

    setHeaders(headers) {
        this.axios.defaults.headers = {
            ...this.axios.defaults.headers,
            ...headers,
        };
        return this;
    }
}

export default Http;
