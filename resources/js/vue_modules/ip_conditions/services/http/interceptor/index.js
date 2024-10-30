import ErrorHandler from "../../error-handler/http";

class Interceptor {
    static request(httpInstance) {
        httpInstance.getAxiosInstance().interceptors.request.use(
            async (config) => {
                return config;
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    static response(httpInstance) {
        httpInstance.getAxiosInstance().interceptors.response.use(
            async (response) => {
                // await store.dispatch("preloader/hide");
                return response;
            },
            async (error) => {
                const errorHandler = new ErrorHandler(
                    httpInstance.getIgnoredErrors(),
                    error.response?.request
                );

                try {
                    await errorHandler.handle();
                } catch {
                    await Promise.resolve();
                }

                // await store.dispatch("preloader/hide");

                if (error.response && error.response.status >= 400) {
                    return Promise.reject(error.response);
                }
                return Promise.resolve(error.response);
            }
        );
    }
}

export default Interceptor;
